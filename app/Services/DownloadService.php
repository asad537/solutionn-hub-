<?php

namespace App\Services;

use App\Models\MediaDownload;
use Illuminate\Support\Facades\Log;

/**
 * DownloadService — Multi-threaded download orchestrator.
 *
 * Uses aria2c for maximum download speed:
 *   -x 16 (16 connections per server)
 *   -s 16 (16 splits per file)
 *   -k 1M  (minimum split size)
 *
 * Falls back to cURL if aria2c is not available.
 */
class DownloadService
{
    /** @var string */
    private $aria2cPath;

    /** @var array */
    private $config;

    /** @var string */
    private $downloadDir;

    public function __construct()
    {
        $this->aria2cPath  = config('downloader.aria2c_path', '/usr/local/bin/aria2c');
        $this->config      = config('downloader.aria2c', []);
        $this->downloadDir = config('downloader.download_dir', storage_path('app/downloads'));

        if (!is_dir($this->downloadDir)) {
            mkdir($this->downloadDir, 0755, true);
        }
    }

    /**
     * Download a file from URL.
     *
     * @param string      $url      Direct CDN URL
     * @param string      $filename Desired output filename
     * @param string|null $referer  Referer header for CDN auth
     * @param string|null $platform Platform name for logging
     * @return array ['success' => bool, 'path' => string, 'size' => int, 'error' => string|null]
     */
    public function download($url, $filename, $referer = null, $platform = null)
    {
        // Sanitize filename
        $safeFilename = $this->sanitizeFilename($filename);
        $outputPath   = $this->downloadDir . '/' . $safeFilename;

        // Ensure no path traversal
        if (strpos(realpath($this->downloadDir), storage_path()) !== 0 && is_dir($this->downloadDir)) {
            return ['success' => false, 'path' => null, 'size' => 0, 'error' => 'Invalid download directory'];
        }

        $proxySession = null;
        if (preg_match('/[?&]proxy_session=([a-zA-Z0-9]+)/', $url, $matches)) {
            $proxySession = $matches[1];
            $url = preg_replace('/([?&])proxy_session=[a-zA-Z0-9]+&?/', '$1', $url);
            $url = rtrim($url, '?&');
        }

        $proxy = null;
        $isYouTube = (strpos($url, 'googlevideo.com') !== false || strpos($url, 'youtube') !== false || strpos($url, 'youtu.be') !== false);
        if ($isYouTube) {
            $configuredProxy = config('downloader.ytdlp_proxy');
            if ($configuredProxy) {
                $proxy = \App\Services\MediaExtractorService::getStickyProxy($configuredProxy, $proxySession);
            }
        }

        // Try aria2c first (fastest), fallback to cURL
        if ($this->hasAria2c()) {
            $result = $this->downloadWithAria2c($url, $safeFilename, $referer, $proxy);
        } else {
            $result = $this->downloadWithCurl($url, $outputPath, $referer, $proxy);
        }

        return $result;
    }

    /**
     * Download using aria2c (16 connections, ultra-fast).
     */
    private function downloadWithAria2c($url, $filename, $referer = null, $proxy = null)
    {
        $connections   = $this->config['connections'] ?? 16;
        $splits        = $this->config['splits'] ?? 16;
        $minSplitSize  = $this->config['min_split_size'] ?? '1M';
        $timeout       = $this->config['timeout'] ?? 120;
        $maxRetries    = $this->config['max_retries'] ?? 5;
        $retryWait     = $this->config['retry_wait'] ?? 3;

        $ua = config('downloader.extraction.user_agent', 'Mozilla/5.0');

        $cmd = escapeshellarg($this->aria2cPath)
            . ' -x ' . (int) $connections
            . ' -s ' . (int) $splits
            . ' -k ' . escapeshellarg($minSplitSize)
            . ' --timeout=' . (int) $timeout
            . ' --max-tries=' . (int) $maxRetries
            . ' --retry-wait=' . (int) $retryWait
            . ' --check-certificate=false'
            . ' --file-allocation=none'
            . ' --console-log-level=error'
            . ' --summary-interval=0'
            . ' --download-result=hide'
            . ' -d ' . escapeshellarg($this->downloadDir)
            . ' -o ' . escapeshellarg($filename)
            . ' --user-agent=' . escapeshellarg($ua);

        if ($referer) {
            $cmd .= ' --referer=' . escapeshellarg($referer);
        }

        if ($proxy) {
            $cmd .= ' --all-proxy=' . escapeshellarg($proxy);
        }

        $cmd .= ' ' . escapeshellarg($url);
        $cmd .= ' 2>&1';

        $startTime = microtime(true);
        exec($cmd, $output, $exitCode);
        $elapsed = (int) ((microtime(true) - $startTime) * 1000);

        $outputPath = $this->downloadDir . '/' . $filename;

        if ($exitCode === 0 && file_exists($outputPath)) {
            $fileSize = filesize($outputPath);
            Log::info("DownloadService: aria2c completed in {$elapsed}ms, {$fileSize} bytes", [
                'file' => $filename,
            ]);
            return [
                'success' => true,
                'path'    => $outputPath,
                'size'    => $fileSize,
                'error'   => null,
                'ms'      => $elapsed,
            ];
        }

        $errorMsg = implode("\n", array_slice($output, -5));
        Log::warning('DownloadService: aria2c failed', [
            'exit_code' => $exitCode,
            'error'     => $errorMsg,
        ]);

        return [
            'success' => false,
            'path'    => null,
            'size'    => 0,
            'error'   => 'aria2c failed (exit ' . $exitCode . '): ' . $errorMsg,
        ];
    }

    /**
     * Fallback: download with cURL.
     */
    private function downloadWithCurl($url, $outputPath, $referer = null, $proxy = null)
    {
        $startTime = microtime(true);
        $fp = fopen($outputPath, 'wb');
        if (!$fp) {
            return ['success' => false, 'path' => null, 'size' => 0, 'error' => 'Cannot open output file'];
        }

        $ch = curl_init($url);
        $options = [
            CURLOPT_FILE           => $fp,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_TIMEOUT        => $this->config['timeout'] ?? 120,
            CURLOPT_CONNECTTIMEOUT => 20,
            CURLOPT_BUFFERSIZE     => 131072,
            CURLOPT_USERAGENT      => config('downloader.extraction.user_agent', 'Mozilla/5.0'),
        ];

        if ($referer) {
            $options[CURLOPT_REFERER] = $referer;
        }

        if ($proxy) {
            $options[CURLOPT_PROXY] = $proxy;
        }

        curl_setopt_array($ch, $options);

        $success = curl_exec($ch);
        $httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);
        fclose($fp);

        $elapsed = (int) ((microtime(true) - $startTime) * 1000);

        if ($success && $httpCode >= 200 && $httpCode < 400 && file_exists($outputPath)) {
            $fileSize = filesize($outputPath);
            if ($fileSize > 0) {
                return ['success' => true, 'path' => $outputPath, 'size' => $fileSize, 'error' => null, 'ms' => $elapsed];
            }
        }

        // Cleanup failed download
        if (file_exists($outputPath)) {
            @unlink($outputPath);
        }

        return ['success' => false, 'path' => null, 'size' => 0, 'error' => $error ?: "HTTP {$httpCode}"];
    }

    /**
     * Check if aria2c is available.
     */
    private function hasAria2c()
    {
        if (file_exists($this->aria2cPath)) return true;
        $which = trim(shell_exec('which aria2c 2>/dev/null') ?: '');
        if ($which) {
            $this->aria2cPath = $which;
            return true;
        }
        return false;
    }

    /**
     * Sanitize a filename for safe storage.
     */
    private function sanitizeFilename($filename)
    {
        // Remove path separators and dangerous chars
        $safe = preg_replace('/[^A-Za-z0-9._\-]/', '_', $filename);
        // Prevent empty or hidden files
        $safe = ltrim($safe, '.');
        if (!$safe) $safe = 'download_' . time();
        // Limit length
        return substr($safe, 0, 200);
    }

    /**
     * Get the configured download directory path.
     */
    public function getDownloadDir()
    {
        return $this->downloadDir;
    }

    /**
     * Clean up old downloaded files.
     *
     * @param int $maxAgeHours
     * @return int Number of files deleted
     */
    public function cleanupOldFiles($maxAgeHours = 24)
    {
        $deleted = 0;
        $cutoff  = time() - ($maxAgeHours * 3600);

        foreach (glob($this->downloadDir . '/*') as $file) {
            if (is_file($file) && filemtime($file) < $cutoff) {
                if (@unlink($file)) $deleted++;
            }
        }

        // Also cleanup HLS directory
        $hlsDir = config('downloader.hls_dir', storage_path('app/hls'));
        if (is_dir($hlsDir)) {
            foreach (glob($hlsDir . '/*', GLOB_ONLYDIR) as $dir) {
                if (filemtime($dir) < $cutoff) {
                    $this->removeDirectory($dir);
                    $deleted++;
                }
            }
        }

        return $deleted;
    }

    /**
     * Recursively remove a directory.
     */
    private function removeDirectory($dir)
    {
        if (!is_dir($dir)) return;
        foreach (glob($dir . '/{,.}*', GLOB_BRACE) as $file) {
            $base = basename($file);
            if ($base === '.' || $base === '..') continue;
            if (is_dir($file)) {
                $this->removeDirectory($file);
            } else {
                @unlink($file);
            }
        }
        @rmdir($dir);
    }
}

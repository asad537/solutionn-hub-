<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use App\Services\PlatformDetector;

class MediaExtractorService
{
    protected $config;
    protected $ytdlpPath;

    public function __construct()
    {
        $this->config = config('downloader');
        $this->ytdlpPath = $this->config['ytdlp_path'] ?? base_path('venv/bin/yt-dlp');
    }

    /**
     * Extract media info from a URL.
     */
    public function extract($url)
    {
        $startTime = microtime(true);
        $platform = PlatformDetector::detect($url);

        // Platforms where RapidAPI is PRIMARY (yt-dlp needs auth or proxy blocks them)
        // RapidAPI Primary check
        $rapidApiPrimary = ['LinkedIn', 'Snapchat', 'Instagram', 'TikTok', 'Likee', 'Twitter'];
        // Platforms where RapidAPI is FALLBACK if yt-dlp fails (YouTube removed to strictly enforce yt-dlp)
        $rapidApiFallback = ['TikTok', 'Instagram', 'Facebook', 'LinkedIn', 'Snapchat', 'Reddit', 'Likee', 'Twitter'];

        $result = null;

        // Skip yt-dlp for platforms where it always fails (auth / proxy block)
        if (!in_array($platform['platform'], $rapidApiPrimary)) {
            $result = $this->extractViaCli($url);
        }

        // Fallback (or primary for LinkedIn/Snapchat) to RapidAPI
        if (!$result && in_array($platform['platform'], $rapidApiFallback)) {
            $result = $this->extractViaRapidApi($url);
        }

        if (!$result) {
            throw new \RuntimeException('Extraction failed: no output from extractor');
        }

        // Post-process: separate, deduplicate, and sort formats
        if (!empty($result['medias'])) {
            $videos = [];
            $audios = [];

            foreach ($result['medias'] as $media) {
                if (($media['type'] ?? 'video') === 'audio') {
                    $audios[] = $media;
                } else {
                    $videos[] = $media;
                }
            }

            // Deduplicate Videos by height (resolution)
            $dedupedVideos = [];
            foreach ($videos as $v) {
                $height = (int) ($v['height'] ?? 0);
                $key = $height ?: ($v['quality'] ?? 'HD');
                
                if (!isset($dedupedVideos[$key])) {
                    $dedupedVideos[$key] = $v;
                } else {
                    $existing = $dedupedVideos[$key];
                    $preferNew = false;
                    
                    // 1. Prefer progressive formats (has_audio = true) so they download instantly without merging
                    if (!empty($v['has_audio']) && empty($existing['has_audio'])) {
                        $preferNew = true;
                    } elseif (empty($v['has_audio']) && !empty($existing['has_audio'])) {
                        $preferNew = false;
                    } else {
                        // 2. Otherwise, prefer larger file size
                        $preferNew = (($v['raw_size'] ?? 0) > ($existing['raw_size'] ?? 0));
                    }

                    if ($preferNew) {
                        $dedupedVideos[$key] = $v;
                    }
                }
            }

            // Deduplicate Audios by quality/bitrate
            $dedupedAudios = [];
            foreach ($audios as $a) {
                $bitrate = (int) ($a['bitrate'] ?? 0);
                $key = $bitrate ?: ($a['quality'] ?? '128k');
                
                if (!isset($dedupedAudios[$key])) {
                    $dedupedAudios[$key] = $a;
                } else {
                    if (($a['raw_size'] ?? 0) > ($dedupedAudios[$key]['raw_size'] ?? 0)) {
                        $dedupedAudios[$key] = $a;
                    }
                }
            }

            // Sort videos descending by height/resolution
            $videoList = array_values($dedupedVideos);
            usort($videoList, function($a, $b) {
                $hA = (int) ($a['height'] ?? 0);
                $hB = (int) ($b['height'] ?? 0);
                return $hB <=> $hA;
            });

            // Sort audios descending by bitrate
            $audioList = array_values($dedupedAudios);
            usort($audioList, function($a, $b) {
                $bA = (int) ($a['bitrate'] ?? 0);
                $bB = (int) ($b['bitrate'] ?? 0);
                return $bB <=> $bA;
            });

            // Merge them back into result
            $result['medias'] = array_merge($videoList, $audioList);
        }

        $result['extraction_ms'] = (int) ((microtime(true) - $startTime) * 1000);
        return $result;
    }

    /**
     * Extract via yt-dlp CLI.
     */
    private function extractViaCli($url)
    {
        $binary = $this->findBinary();
        if (!$binary) return null;

        $platform = PlatformDetector::detect($url);
        $isYouTube = ($platform['platform'] === 'YouTube');
        
        // Build base command
        $baseCmd = escapeshellarg($binary)
            . ' --dump-single-json'
            . ' --no-warnings'
            . ' --no-playlist'
            . ' --no-check-certificate'
            . ' --geo-bypass'
            . ' --format-sort "vcodec:h264,res,acodec:m4a"'
            . ' --socket-timeout 30'
            . ' --retries 2';

        // Cookies support
        $cookiesPath = storage_path('app/cookies.txt');
        if (file_exists($cookiesPath) && !in_array($platform['platform'], ['Dailymotion', 'Facebook'])) {
            $baseCmd .= ' --cookies ' . escapeshellarg($cookiesPath);
        }

        // Platform-specific optimizations
        if ($isYouTube) {
            $extArgs = $this->config['extraction']['extractor_args']['youtube'] ?? null;
            if ($extArgs) {
                $baseCmd .= ' --extractor-args ' . escapeshellarg($extArgs);
            }
        } elseif ($platform['platform'] === 'Facebook') {
            $baseCmd .= ' --extractor-args "facebook:max_video_size=1080"';
        }

        // User agent and Impersonation (Bypasses bot protections like FB checkpoint instantly)
        $ua = $this->config['extraction']['user_agent'] ?? 'Mozilla/5.0';
        $baseCmd .= ' --user-agent ' . escapeshellarg($ua) . ' --impersonate "chrome"';

        // Define execution options
        // YouTube: go proxy-first (Hetzner datacenter IP is always bot-checked by YouTube directly)
        // Non-YouTube: direct only
        $attempts = [];
        $proxyRequired = in_array($platform['platform'], ['YouTube', 'TikTok', 'Instagram', 'Facebook', 'Snapchat', 'LinkedIn', 'Reddit']);
        if ($proxyRequired) {
            $attempts[] = [
                'use_proxy' => true,
                'session_id' => substr(md5(uniqid(microtime(), true)), 0, 8)
            ];
            // If proxy fails, try without proxy as fallback
            $attempts[] = [
                'use_proxy' => false,
                'session_id' => null
            ];
        } else {
            $attempts[] = [
                'use_proxy' => false,
                'session_id' => null
            ];
        }

        $timeout = 60;

        foreach ($attempts as $index => $attempt) {
            $cmd = $baseCmd;
            $sessionId = $attempt['session_id'];

            if ($attempt['use_proxy']) {
                $httpProxy = $this->config['ytdlp_proxy'] ?? null;
                if ($httpProxy && $sessionId) {
                    $proxyWithSession = self::getStickyProxy($httpProxy, $sessionId);
                    $cmd .= ' --proxy ' . escapeshellarg($proxyWithSession);
                }
            }

            // URL
            $cmd .= ' ' . escapeshellarg($url) . ' 2>&1';
            
            Log::debug('MediaExtractor: Executing CLI (Attempt ' . ($index + 1) . ', Proxy: ' . ($attempt['use_proxy'] ? 'Yes' : 'No') . ') | URL: ' . $url);

            $output = $this->execWithTimeout($cmd, $timeout);

            if ($output) {
                file_put_contents(storage_path('logs/ytdlp_debug_' . ($index+1) . '.log'), $output);
                $parsed = $this->parseYtdlpJson($output, $url, $sessionId);
                if ($parsed) {
                    return $parsed;
                }
                
                Log::warning("MediaExtractor: CLI Attempt " . ($index + 1) . " parsing failed or returned error. Output: " . substr($output, 0, 300));
            } else {
                Log::warning("MediaExtractor: CLI Attempt " . ($index + 1) . " execution returned empty output.");
            }
        }
        
        return null;
    }

    /**
     * EXTRACT VIA RAPIDAPI: Social Download All-In-One.
     */
    private function extractViaRapidApi($url)
    {
        $config = config('downloader.rapidapi');
        if (empty($config['key'])) return null;

        $client = new Client([
            'base_uri' => $config['base_url'],
            'timeout'  => 25.0,
            'verify'   => false
        ]);

        try {
            $response = $client->post($config['path'], [
                'headers' => [
                    'X-RapidAPI-Key'  => $config['key'],
                    'X-RapidAPI-Host' => $config['host'],
                    'Content-Type'    => 'application/json',
                ],
                'json' => ['url' => $url]
            ]);

            $data = json_decode($response->getBody(), true);
            if (!$data) return null;

            $apiData = isset($data['data']) && is_array($data['data']) ? $data['data'] : $data;
            if (empty($apiData['medias']) && empty($apiData['title'])) return null;

            $result = [
                'title'          => $apiData['title'] ?? 'Video',
                'thumbnail'      => $apiData['thumbnail'] ?? '',
                'source'         => $apiData['source'] ?? 'RapidAPI',
                'duration'       => $this->formatDuration($apiData['duration'] ?? 0),
                'duration_raw'   => $apiData['duration'] ?? 0,
                'uploader'       => $apiData['author'] ?? null,
                'best_audio_url' => '',
                'medias'         => [],
                'is_rapidapi'    => true,
            ];

            foreach ($apiData['medias'] ?? [] as $m) {
                $type = $m['type'] ?? 'video';
                $isAudio = ($type === 'audio');
                
                $ext = strtolower($m['extension'] ?? ($isAudio ? 'MP3' : 'MP4'));
                if ($type === 'video' && $ext === 'webm') {
                    continue;
                }
                if ($type === 'audio' && in_array($ext, ['webm', 'opus'])) {
                    continue;
                }

                $result['medias'][] = [
                    'url'        => $m['url'],
                    'quality'    => $m['quality'] ?? ($isAudio ? ($m['bitrate'] ?? '128k') : 'HD'),
                    'extension'  => strtoupper($m['extension'] ?? ($isAudio ? 'MP3' : 'MP4')),
                    'size'       => (is_string($m['size'] ?? '') && preg_match('/[a-z]/i', (string)($m['size'] ?? ''))) ? $m['size'] : $this->formatSize($m['size'] ?? 0),
                    'raw_size'   => (float) ($m['size'] ?? 0),
                    'type'       => $type,
                    'has_audio'  => $type === 'video',
                    'height'     => (int) ($m['height'] ?? 0),
                    'width'      => (int) ($m['width'] ?? 0),
                    'bitrate'    => (int) ($m['bitrate'] ?? 0),
                    'user_agent' => '',
                    'referer'    => '',
                ];
            }

            return $result;
        } catch (\Exception $e) {
            Log::error('MediaExtractor: RapidAPI Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Parse raw yt-dlp --dump-single-json output.
     */
    private function parseYtdlpJson($jsonString, $url, $sessionId = null)
    {
        // Extract valid JSON from output if yt-dlp prints errors/warnings due to 2>&1
        $startPos = strpos($jsonString, '{');
        $endPos = strrpos($jsonString, '}');
        if ($startPos !== false && $endPos !== false && $endPos > $startPos) {
            $jsonString = substr($jsonString, $startPos, $endPos - $startPos + 1);
        }

        $info = json_decode($jsonString, true);
        if (!$info || json_last_error() !== JSON_ERROR_NONE) return null;

        if (!empty($info['_type']) && $info['_type'] === 'playlist' && !empty($info['entries'])) {
            $info = $info['entries'][0];
        }

        $result = [
            'id'           => $info['id'] ?? '',
            'title'        => $info['title'] ?? 'Video',
            'thumbnail'    => $info['thumbnail'] ?? '',
            'source'       => $info['extractor_key'] ?? 'Direct',
            'duration'     => $this->formatDuration($info['duration'] ?? 0),
            'duration_raw' => $info['duration'] ?? 0,
            'uploader'     => $info['uploader'] ?? null,
            'medias'       => [],
        ];

        $source = strtolower($info['extractor_key'] ?? '');
        $isYouTube = (strpos($source, 'youtube') !== false);

        // For non-YouTube platforms, pre-scan to see if there are any combined (video+audio) formats.
        // Only filter out video-only formats if combined ones exist (Instagram DASH case).
        // If ALL formats are "video-only" (Snapchat, TikTok, LinkedIn), show them all.
        $hasCombinedVideoFormat = false;
        if (!$isYouTube) {
            foreach ($info['formats'] ?? [] as $f) {
                if (empty($f['url'])) continue;
                $fIsAudio = (!empty($f['vcodec']) && $f['vcodec'] === 'none');
                $fHasAudio = (!empty($f['acodec']) && $f['acodec'] !== 'none');
                if (!$fIsAudio && $fHasAudio) {
                    $hasCombinedVideoFormat = true;
                    break;
                }
            }
        }

        foreach ($info['formats'] ?? [] as $f) {
            if (empty($f['url'])) continue;

            if ($source === 'pinterest' && strpos($f['url'], '.m3u8') !== false) {
                // Rewrite Pinterest m3u8 to playable mp4
                $f['url'] = preg_replace('/hls\/(.*?)_\w+\.m3u8$/', 'expMp4/$1_720w.mp4', $f['url']);
                $f['ext'] = 'mp4';
                $f['vcodec'] = 'h264'; // ensure it passes filters
            } else {
                // Skip HLS/DASH manifest formats (manifest.googlevideo.com, .mpd, .m3u8)
                if (!in_array($source, ['dailymotion', 'twitch', 'twitchvod', 'twitchstream']) && (strpos($f['url'], 'manifest.googlevideo.com') !== false || strpos($f['url'], 'hls_playlist') !== false || strpos($f['url'], '.mpd') !== false || strpos($f['url'], '.m3u8') !== false)) {
                    continue;
                }
            }

            // Skip storyboard and mhtml preview formats
            if (isset($f['format_id']) && (strpos($f['format_id'], 'sb') === 0 || strpos($f['format_id'], 'storyboard') !== false)) {
                continue;
            }
            if (isset($f['ext']) && strtolower($f['ext']) === 'mhtml') {
                continue;
            }

            $ext = strtolower($f['ext'] ?? '');
            $isAudio = (!empty($f['vcodec']) && $f['vcodec'] === 'none');
            $type = $isAudio ? 'audio' : 'video';
            $hasAudio = (!empty($f['acodec']) && $f['acodec'] !== 'none');

            $isDashAllowed = $isYouTube || strpos($source, 'facebook') !== false || strpos($source, 'reddit') !== false;
            // For non-YouTube platforms, skip video-only (DASH) formats ONLY IF combined formats exist.
            // (Prevents Instagram DASH fragments, but keeps Snapchat/TikTok/LinkedIn single streams)
            if (!$isDashAllowed && $hasCombinedVideoFormat && $type === 'video' && !$hasAudio) {
                continue;
            }

            // Skip AV1 and VP9 video codecs for resolutions ≤ 1080p (not natively supported by Apple QuickTime/macOS/iOS)
            // We allow AV1/VP9 only for >1080p (2K/4K) where H264 does not exist.
            $vcodec = strtolower($f['vcodec'] ?? '');
            $isAv1 = strpos($vcodec, 'av01') !== false || strpos($vcodec, 'av1') !== false;
            $isVp9 = strpos($vcodec, 'vp9') !== false || strpos($vcodec, 'vp09') !== false;
            $height = (int) ($f['height'] ?? 0);

            if ($type === 'video' && $height <= 1080 && ($isAv1 || $isVp9)) {
                continue;
            }

            // Skip WEBM video formats for ≤1080p (H264 available as better alternative)
            // BUT allow WebM/VP9 for 4K+ (1440p/2160p) since YouTube has no H264 above 1080p
            if ($type === 'video' && $ext === 'webm' && $height <= 1080) {
                continue;
            }
            // Skip WEBM/Opus audio formats
            if ($type === 'audio' && in_array($ext, ['webm', 'opus'])) {
                continue;
            }

            $mediaUrl = $f['url'];
            if ($sessionId) {
                $mediaUrl .= (strpos($mediaUrl, '?') !== false ? '&' : '?') . 'proxy_session=' . $sessionId;
            }

            // For 4K+ WebM video-only streams: show MP4 badge since FFmpeg merge outputs MP4
            $displayExt = strtoupper($f['ext'] ?? 'MP4');
            if ($type === 'video' && $ext === 'webm' && $height > 1080) {
                $displayExt = 'MP4'; // FFmpeg merge will remux WebM→MP4 container
            }

            $bitrate = (int) ($f['abr'] ?? ($f['tbr'] ?? 0));

            $rawSize = (float) ($f['filesize'] ?? ($f['filesize_approx'] ?? 0));
            if ($rawSize <= 0 && !empty($f['tbr']) && !empty($info['duration'])) {
                // For VP9/AV1 streams (1440p/2160p) that will be transcoded to H.264:
                // VP9/AV1 bitrate is much higher than the equivalent H.264 CRF28 output.
                // Use a realistic H.264 estimate: ~60% of VP9 bitrate, capped at:
                //   4K: ~8000 kbps, 1440p: ~5000 kbps (matches vidsave ~500MB for 4K)
                $isVp9OrAv1 = strpos($vcodec, 'vp9') !== false || strpos($vcodec, 'vp09') !== false
                           || strpos($vcodec, 'av01') !== false;
                if ($isVp9OrAv1 && $height > 1080) {
                    // Estimate H.264 CRF28 output bitrate (much smaller than VP9 source)
                    $maxKbps = $height >= 2160 ? 8000 : ($height >= 1440 ? 5000 : 3500);
                    $estimatedKbps = min((float)$f['tbr'] * 0.55, $maxKbps);
                    $rawSize = ($estimatedKbps * 1000 * $info['duration']) / 8;
                } else {
                    $rawSize = ($f['tbr'] * 1000 * $info['duration']) / 8;
                }
            }

            // Fallback: Fetch exact file size via fast HTTP HEAD request if size is still missing
            if ($rawSize <= 0 && !empty($mediaUrl) && strpos($mediaUrl, '.m3u8') === false && strpos($mediaUrl, '.mpd') === false) {
                $ch = curl_init($mediaUrl);
                curl_setopt($ch, CURLOPT_NOBODY, true);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HEADER, true);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($ch, CURLOPT_MAXREDIRS, 2);
                curl_setopt($ch, CURLOPT_TIMEOUT, 2);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/114.0.0.0');
                curl_exec($ch);
                $cl = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
                curl_close($ch);
                if ($cl > 0) {
                    $rawSize = (float)$cl;
                }
            }

            $result['medias'][] = [
                'format_id'  => $f['format_id'] ?? '',
                'url'        => $mediaUrl,
                'quality'    => $f['format_note'] ?? ($height ? $height.'p' : 'HD'),
                'extension'  => $displayExt,
                'size'       => $this->formatSize($rawSize),
                'raw_size'   => $rawSize,
                'type'       => $type,
                // For non-YouTube: if no combined format exists and this IS a video,
                // it's a single progressive stream (TikTok/Snapchat) — treat as has_audio = true
                'has_audio'  => (!empty($f['acodec']) && $f['acodec'] !== 'none')
                               || (!$isYouTube && !$hasCombinedVideoFormat && $type === 'video'),
                'height'     => $height,
                'bitrate'    => $bitrate,
                'vcodec'     => strtolower($f['vcodec'] ?? ''), // e.g. 'vp9', 'av01.0.12M.08', 'avc1.640028'
                'user_agent' => $f['http_headers']['User-Agent'] ?? $f['http_headers']['user-agent'] ?? '',
                'referer'    => $f['http_headers']['Referer'] ?? $f['http_headers']['referer'] ?? '',
                'cookies'    => self::cleanCookieString($f['cookies'] ?? ''),
            ];
        }

        return $result;
    }

    /**
     * Jugar: Try to refresh cookies automatically.
     */
    public function refreshCookies()
    {
        $binary = $this->findBinary();
        if (!$binary) return false;

        $cookiesPath = storage_path('app/cookies.txt');
        $testUrl = 'https://www.youtube.com/watch?v=dQw4w9WgXcQ';
        
        $cmd = escapeshellarg($binary)
            . ' --cookies ' . escapeshellarg($cookiesPath)
            . ' --user-agent ' . escapeshellarg($this->config['extraction']['user_agent'] ?? 'Mozilla/5.0')
            . ' --extractor-args "youtube:player_client=android"'
            . ' --no-warnings --quiet --dump-single-json ' . escapeshellarg($testUrl) . ' > /dev/null 2>&1';
        
        @exec($cmd);
        return file_exists($cookiesPath);
    }

    private function formatDuration($seconds)
    {
        if (!$seconds) return '00:00';
        $d = (int) $seconds;
        $h = intdiv($d, 3600);
        $m = intdiv($d % 3600, 60);
        $s = $d % 60;
        return $h > 0 ? sprintf('%02d:%02d:%02d', $h, $m, $s) : sprintf('%02d:%02d', $m, $s);
    }

    private function formatSize($bytes)
    {
        if (!$bytes) return '';
        $displaySz = (float) $bytes;
        foreach (['B', 'KB', 'MB', 'GB'] as $unit) {
            if ($displaySz < 1024.0) return sprintf('%.1f %s', $displaySz, $unit);
            $displaySz /= 1024.0;
        }
        return '';
    }

    private function findBinary()
    {
        if (file_exists($this->ytdlpPath)) return $this->ytdlpPath;
        $system = trim(shell_exec('which yt-dlp 2>/dev/null') ?: '');
        if ($system && file_exists($system)) return $system;
        return null;
    }

    private function execWithTimeout($cmd, $timeoutSeconds)
    {
        $descriptorspec = [
            0 => ["pipe", "r"],
            1 => ["pipe", "w"],
            2 => ["pipe", "w"]
        ];

        $env = [
            'PATH' => getenv('PATH') ?: '/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin',
            'HOME' => getenv('HOME') ?: '/var/www',
            'LANG' => 'en_US.UTF-8',
            'LC_ALL' => 'en_US.UTF-8',
            'PYTHONIOENCODING' => 'utf-8'
        ];

        $process = proc_open($cmd, $descriptorspec, $pipes, null, $env);
        if (!is_resource($process)) return null;

        fclose($pipes[0]); // Close stdin to prevent hanging
        stream_set_blocking($pipes[1], false);
        $output  = '';
        $start   = time();

        while (true) {
            $chunk = fread($pipes[1], 65536);
            if ($chunk !== false && $chunk !== '') {
                $output .= $chunk;
            }

            $status = proc_get_status($process);
            if (!$status['running']) {
                stream_set_blocking($pipes[1], true);
                $output .= stream_get_contents($pipes[1]);
                break;
            }

            if ((time() - $start) > $timeoutSeconds) {
                proc_terminate($process, 9);
                fclose($pipes[1]);
                fclose($pipes[2]);
                proc_close($process);
                return null;
            }
            usleep(10000);
        }

        $remaining = stream_get_contents($pipes[1]);
        if ($remaining) $output .= $remaining;

        fclose($pipes[1]);
        fclose($pipes[2]);
        proc_close($process);

        return $output ?: null;
    }

    public static function getStickyProxy($proxyUrl, &$sessionId = null)
    {
        if (!$proxyUrl) return null;
        
        $parsed = parse_url($proxyUrl);
        if (!$parsed || empty($parsed['pass'])) return $proxyUrl;
        
        // Handle DataImpulse sticky sessions (uses login__sessid.ID:password format)
        if (strpos($parsed['host'] ?? '', 'dataimpulse.com') !== false) {
            if (!$sessionId) {
                $sessionId = substr(md5(uniqid(microtime(), true)), 0, 8);
            }
            
            $user = $parsed['user'] ?? '';
            // If the username already contains a session, extract it
            if (strpos($user, ';sessid.') !== false) {
                if (preg_match('/;sessid\.([a-zA-Z0-9]+)/', $user, $matches)) {
                    $sessionId = $matches[1];
                }
                return $proxyUrl;
            }
            
            // Append sessid to the username
            $newUser = $user . ';sessid.' . $sessionId;
            
            $scheme = isset($parsed['scheme']) ? $parsed['scheme'] . '://' : 'http://';
            $pass = $parsed['pass'];
            $host = $parsed['host'] ?? '';
            $port = isset($parsed['port']) ? ':' . $parsed['port'] : '';
            
            return $scheme . $newUser . ':' . $pass . '@' . $host . $port;
        }
        
        if (strpos($parsed['pass'], '_session-') !== false) {
            if (preg_match('/_session-([a-zA-Z0-9]+)/', $parsed['pass'], $matches)) {
                $sessionId = $matches[1];
            }
            return $proxyUrl;
        }
        
        if (!$sessionId) {
            $sessionId = substr(md5(uniqid(microtime(), true)), 0, 8);
        }
        
        $newPass = $parsed['pass'] . '_session-' . $sessionId . '_lifetime-15m';
        
        $scheme = isset($parsed['scheme']) ? $parsed['scheme'] . '://' : 'http://';
        $user = isset($parsed['user']) ? $parsed['user'] : '';
        $host = isset($parsed['host']) ? $parsed['host'] : '';
        $port = isset($parsed['port']) ? ':' . $parsed['port'] : '';
        
        return $scheme . $user . ':' . $newPass . '@' . $host . $port;
    }

    public static function cleanCookieString($rawCookies)
    {
        if (empty($rawCookies)) {
            return '';
        }
        
        $parts = explode(';', $rawCookies);
        $cleanParts = [];
        $ignoredKeys = ['domain', 'path', 'expires', 'max-age', 'samesite', 'secure', 'httponly'];
        
        foreach ($parts as $part) {
            $part = trim($part);
            if ($part === '') continue;
            
            $eqPos = strpos($part, '=');
            if ($eqPos === false) {
                continue;
            }
            
            $key = strtolower(substr($part, 0, $eqPos));
            if (in_array($key, $ignoredKeys)) {
                continue;
            }
            
            $cleanParts[] = $part;
        }
        
        return implode('; ', $cleanParts);
    }
}

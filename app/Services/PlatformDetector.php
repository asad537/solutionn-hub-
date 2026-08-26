<?php

namespace App\Services;

/**
 * PlatformDetector — Identifies the media platform from a URL.
 *
 * Extracts:
 *   - Platform name (YouTube, Instagram, TikTok...)
 *   - Unique media ID from the URL
 *   - Referer header for CDN auth
 */
class PlatformDetector
{
    /**
     * Platform detection rules.
     * Each entry: [host_pattern => [platform_name, id_regex (optional)]]
     */
    private static $platforms = [
        'youtube.com'    => ['YouTube',     '/(?:v=|\/embed\/|\/shorts\/)([A-Za-z0-9_\-]{11})/'],
        'youtu.be'       => ['YouTube',     '/youtu\.be\/([A-Za-z0-9_\-]{11})/'],
        'instagram.com'  => ['Instagram',   '/(?:\/p\/|\/reel\/|\/tv\/)([A-Za-z0-9_\-]+)/'],
        'tiktok.com'     => ['TikTok',      '/\/video\/(\d+)/'],
        'vt.tiktok.com'  => ['TikTok',      null],
        'facebook.com'   => ['Facebook',    '/(?:\/videos\/|\/watch\/\?v=|\/reel\/)(\d+)/'],
        'fb.watch'       => ['Facebook',    null],
        'fb.com'         => ['Facebook',    null],
        'twitter.com'    => ['Twitter',     '/\/status\/(\d+)/'],
        'x.com'          => ['Twitter',     '/\/status\/(\d+)/'],
        'linkedin.com'   => ['LinkedIn',    null],
        'snapchat.com'   => ['Snapchat',    null],
        'vimeo.com'      => ['Vimeo',       '/vimeo\.com\/(\d+)/'],
        'dailymotion.com'=> ['Dailymotion', '/(?:video\/)([a-z0-9]+)/i'],
        'dai.ly'         => ['Dailymotion', '/dai\.ly\/([a-z0-9]+)/i'],
        'reddit.com'     => ['Reddit',      '/\/comments\/([a-z0-9]+)/i'],
        'twitch.tv'      => ['Twitch',      '/\/videos\/(\d+)/'],
        'soundcloud.com' => ['SoundCloud',  null],
        'pinterest.com'  => ['Pinterest',   '/\/pin\/(\d+)/'],
    ];

    /**
     * Detect the platform from a URL.
     *
     * @param string $url
     * @return array ['platform' => string, 'id' => string|null, 'referer' => string]
     */
    public static function detect($url)
    {
        $host = strtolower(parse_url($url, PHP_URL_HOST) ?: '');
        // Strip www.
        $host = preg_replace('/^www\./', '', $host);

        foreach (self::$platforms as $pattern => $config) {
            if (strpos($host, $pattern) !== false) {
                $platformName = $config[0];
                $idRegex      = $config[1];
                $mediaId      = null;

                if ($idRegex && preg_match($idRegex, $url, $matches)) {
                    $mediaId = $matches[1];
                }

                // Fallback: hash the URL as ID if no regex match
                if (!$mediaId) {
                    $mediaId = substr(hash('sha256', $url), 0, 16);
                }

                $referer = config('downloader.referers.' . $platformName, 'https://www.google.com/');

                return [
                    'platform' => $platformName,
                    'id'       => $mediaId,
                    'referer'  => $referer,
                ];
            }
        }

        // CDN host fallback detection
        if (strpos($host, 'cdninstagram') !== false || strpos($host, 'fbcdn') !== false) {
            return ['platform' => 'Instagram', 'id' => substr(hash('sha256', $url), 0, 16), 'referer' => 'https://www.instagram.com/'];
        }
        if (strpos($host, 'tiktokcdn') !== false) {
            return ['platform' => 'TikTok', 'id' => substr(hash('sha256', $url), 0, 16), 'referer' => 'https://www.tiktok.com/'];
        }

        return [
            'platform' => 'Other',
            'id'       => substr(hash('sha256', $url), 0, 16),
            'referer'  => 'https://www.google.com/',
        ];
    }

    /**
     * Get just the platform name.
     */
    public static function platformName($url)
    {
        return self::detect($url)['platform'];
    }

    /**
     * Get just the media ID.
     */
    public static function mediaId($url)
    {
        return self::detect($url)['id'];
    }

    /**
     * Check if URL is from YouTube.
     */
    public static function isYouTube($url)
    {
        $host = strtolower(parse_url($url, PHP_URL_HOST) ?: '');
        return strpos($host, 'youtube') !== false || strpos($host, 'youtu.be') !== false;
    }

    /**
     * Validate that a URL is a supported media URL.
     */
    public static function isSupported($url)
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) return false;
        $detected = self::detect($url);
        return $detected['platform'] !== 'Other';
    }
}

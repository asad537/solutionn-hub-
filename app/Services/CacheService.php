<?php

namespace App\Services;

use App\Models\Media;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/**
 * CacheService — Dual-layer cache (Redis + MySQL).
 *
 * FLOW:
 *   1. Check Redis first (instant, <1ms)
 *   2. If Redis miss, check MySQL (fast, ~5ms)
 *   3. If MySQL hit, warm Redis from MySQL
 *   4. If both miss, return null (caller must extract)
 *
 * WRITE:
 *   1. Write to Redis with TTL
 *   2. Write/update MySQL (permanent)
 */
class CacheService
{
    /**
     * Get cached media info.
     *
     * @param string $platform
     * @param string $mediaId
     * @return array|null  The media data array, or null if not cached.
     */
    public function get($platform, $mediaId)
    {
        $cacheKey = $this->buildKey($platform, $mediaId);

        // ── Layer 1: Redis ──────────────────────────────────────────────
        $cached = Cache::get($cacheKey);
        if ($cached) {
            // Increment hit count in background (don't block response)
            $this->incrementHits($platform, $mediaId);
            return $cached;
        }

        // ── Layer 2: MySQL ──────────────────────────────────────────────
        if (config('downloader.cache.db_persist', true)) {
            $media = Media::byPlatformId($platform, $mediaId)->first();
            if ($media && !$media->isStale(config('downloader.cache.ttl_minutes', 30))) {
                $data = $this->mediaToArray($media);
                // Warm Redis from MySQL
                $this->putRedis($cacheKey, $data);
                $media->incrementHits();
                return $data;
            }
        }

        return null;
    }

    /**
     * Store media info in both Redis and MySQL.
     *
     * @param string $platform
     * @param string $mediaId
     * @param string $url        Original URL
     * @param array  $data       The full media data array
     * @return Media|null        The created/updated Media model
     */
    public function put($platform, $mediaId, $url, array $data)
    {
        $cacheKey = $this->buildKey($platform, $mediaId);

        // ── Layer 1: Redis ──────────────────────────────────────────────
        $this->putRedis($cacheKey, $data);

        // ── Layer 2: MySQL ──────────────────────────────────────────────
        if (config('downloader.cache.db_persist', true)) {
            try {
                return Media::updateOrCreate(
                    ['platform' => $platform, 'platform_id' => $mediaId],
                    [
                        'url'               => $url,
                        'title'             => $data['title'] ?? null,
                        'thumbnail'         => $data['thumbnail'] ?? null,
                        'uploader'          => $data['uploader'] ?? null,
                        'view_count'        => $data['view_count'] ?? null,
                        'duration'          => $data['duration_raw'] ?? null,
                        'duration_string'   => $data['duration'] ?? null,
                        'formats_json'      => $data['medias'] ?? [],
                        'metadata_json'     => [
                            'source'         => $data['source'] ?? null,
                            'best_audio_url' => $data['best_audio_url'] ?? null,
                        ],
                        'best_audio_url'    => $data['best_audio_url'] ?? null,
                        'extraction_ms'     => $data['extraction_ms'] ?? null,
                        'last_extracted_at' => now(),
                    ]
                );
            } catch (\Throwable $e) {
                Log::warning('CacheService: MySQL write failed', ['error' => $e->getMessage()]);
            }
        }

        return null;
    }

    /**
     * Invalidate cache for a specific media.
     */
    public function forget($platform, $mediaId)
    {
        Cache::forget($this->buildKey($platform, $mediaId));
    }

    /**
     * Build the Redis cache key.
     */
    private function buildKey($platform, $mediaId)
    {
        $prefix = config('downloader.cache.prefix', 'media:');
        return $prefix . strtolower($platform) . ':' . $mediaId;
    }

    /**
     * Write to Redis with configured TTL.
     */
    private function putRedis($key, array $data)
    {
        $ttl = config('downloader.cache.ttl_minutes', 30);
        Cache::put($key, $data, now()->addMinutes($ttl));
    }

    /**
     * Increment hit count without blocking.
     */
    private function incrementHits($platform, $mediaId)
    {
        try {
            Media::byPlatformId($platform, $mediaId)->increment('hit_count');
        } catch (\Throwable $e) {
            // Non-critical, don't crash
        }
    }

    /**
     * Convert a Media model to the standard response array.
     */
    private function mediaToArray(Media $media)
    {
        $metadata = $media->metadata_json ?: [];
        return [
            'title'          => $media->title,
            'thumbnail'      => $media->thumbnail,
            'source'         => $metadata['source'] ?? $media->platform,
            'duration'       => $media->duration_string,
            'duration_raw'   => $media->duration,
            'uploader'       => $media->uploader,
            'view_count'     => $media->view_count,
            'best_audio_url' => $media->best_audio_url,
            'medias'         => $media->formats_json ?: [],
            'extraction_ms'  => $media->extraction_ms,
            'cached'         => true,
        ];
    }
}

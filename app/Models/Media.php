<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    protected $table = 'media';

    protected $fillable = [
        'platform',
        'platform_id',
        'url',
        'title',
        'description',
        'thumbnail',
        'uploader',
        'view_count',
        'duration',
        'duration_string',
        'formats_json',
        'metadata_json',
        'best_audio_url',
        'extraction_ms',
        'hit_count',
        'last_extracted_at',
    ];

    protected $casts = [
        'formats_json'       => 'array',
        'metadata_json'      => 'array',
        'duration'           => 'integer',
        'view_count'         => 'integer',
        'hit_count'          => 'integer',
        'extraction_ms'      => 'integer',
        'last_extracted_at'  => 'datetime',
    ];

    /* ── Relations ────────────────────────────────────── */

    public function cachedFormats()
    {
        return $this->hasMany(CachedFormat::class, 'media_id');
    }

    public function downloads()
    {
        return $this->hasMany(MediaDownload::class, 'media_id');
    }

    /* ── Scopes ───────────────────────────────────────── */

    public function scopeByPlatformId($query, $platform, $platformId)
    {
        return $query->where('platform', $platform)->where('platform_id', $platformId);
    }

    public function scopePopular($query, $limit = 20)
    {
        return $query->orderByDesc('hit_count')->limit($limit);
    }

    /* ── Helpers ──────────────────────────────────────── */

    public function incrementHits()
    {
        $this->increment('hit_count');
    }

    public function isStale($minutes = 30)
    {
        if (!$this->last_extracted_at) return true;
        return $this->last_extracted_at->diffInMinutes(now()) > $minutes;
    }
}

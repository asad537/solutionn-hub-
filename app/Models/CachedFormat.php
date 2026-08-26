<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CachedFormat extends Model
{
    protected $table = 'cached_formats';

    protected $fillable = [
        'media_id', 'format_id', 'type', 'quality', 'extension',
        'vcodec', 'acodec', 'filesize', 'filesize_human',
        'width', 'height', 'fps', 'abr', 'url',
        'has_audio', 'has_video', 'url_expires_at',
    ];

    protected $casts = [
        'filesize'       => 'integer',
        'width'          => 'integer',
        'height'         => 'integer',
        'fps'            => 'integer',
        'abr'            => 'integer',
        'has_audio'      => 'boolean',
        'has_video'      => 'boolean',
        'url_expires_at' => 'datetime',
    ];

    public function media()
    {
        return $this->belongsTo(Media::class, 'media_id');
    }

    public function isExpired()
    {
        if (!$this->url_expires_at) return false;
        return $this->url_expires_at->isPast();
    }

    public function scopeVideos($query)
    {
        return $query->where('type', 'video');
    }

    public function scopeAudios($query)
    {
        return $query->where('type', 'audio');
    }
}

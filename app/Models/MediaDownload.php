<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MediaDownload extends Model
{
    protected $table = 'media_downloads';

    protected $fillable = [
        'media_id',
        'url',
        'platform',
        'format',
        'quality',
        'status',
        'progress',
        'file_path',
        'file_size',
        'ip_address',
        'title',
        'error_message',
        'processing_ms',
    ];

    protected $casts = [
        'progress'      => 'integer',
        'file_size'     => 'integer',
        'processing_ms' => 'integer',
    ];

    /* ── Status constants ─────────────────────────────── */
    const STATUS_PENDING     = 'pending';
    const STATUS_DOWNLOADING = 'downloading';
    const STATUS_MERGING     = 'merging';
    const STATUS_COMPLETED   = 'completed';
    const STATUS_FAILED      = 'failed';

    /* ── Relations ────────────────────────────────────── */

    public function media()
    {
        return $this->belongsTo(Media::class, 'media_id');
    }

    /* ── Scopes ───────────────────────────────────────── */

    public function scopeActive($query)
    {
        return $query->whereIn('status', [self::STATUS_PENDING, self::STATUS_DOWNLOADING, self::STATUS_MERGING]);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    public function scopeByIp($query, $ip)
    {
        return $query->where('ip_address', $ip);
    }

    /* ── Helpers ──────────────────────────────────────── */

    public function markDownloading()
    {
        $this->update([
            'status'        => self::STATUS_DOWNLOADING,
            'error_message' => null,
        ]);
    }

    public function markMerging()
    {
        $this->update([
            'status'        => self::STATUS_MERGING,
            'error_message' => null,
        ]);
    }

    public function markCompleted($filePath, $fileSize = null)
    {
        $this->update([
            'status'        => self::STATUS_COMPLETED,
            'file_path'     => $filePath,
            'file_size'     => $fileSize,
            'progress'      => 100,
            'error_message' => null,
        ]);
    }

    public function markFailed($error)
    {
        $this->update([
            'status'        => self::STATUS_FAILED,
            'error_message' => substr($error, 0, 1000),
        ]);
    }
}

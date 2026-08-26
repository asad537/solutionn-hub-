<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProcessingJob extends Model
{
    protected $table = 'processing_jobs';

    protected $fillable = [
        'job_type', 'job_id', 'media_id', 'download_id',
        'status', 'progress', 'payload', 'error_message',
        'attempts', 'processing_ms', 'worker_id',
        'started_at', 'completed_at',
    ];

    protected $casts = [
        'payload'      => 'array',
        'progress'     => 'integer',
        'attempts'     => 'integer',
        'processing_ms'=> 'integer',
        'started_at'   => 'datetime',
        'completed_at' => 'datetime',
    ];

    const STATUS_QUEUED     = 'queued';
    const STATUS_PROCESSING = 'processing';
    const STATUS_COMPLETED  = 'completed';
    const STATUS_FAILED     = 'failed';

    const TYPE_EXTRACT  = 'extract';
    const TYPE_DOWNLOAD = 'download';
    const TYPE_MERGE    = 'merge';
    const TYPE_HLS      = 'hls';
    const TYPE_CLEANUP  = 'cleanup';

    public function media()
    {
        return $this->belongsTo(Media::class, 'media_id');
    }

    public function download()
    {
        return $this->belongsTo(MediaDownload::class, 'download_id');
    }

    public function markProcessing()
    {
        $this->update([
            'status'     => self::STATUS_PROCESSING,
            'started_at' => now(),
            'attempts'   => $this->attempts + 1,
        ]);
    }

    public function markCompleted($processingMs = null)
    {
        $this->update([
            'status'        => self::STATUS_COMPLETED,
            'progress'      => 100,
            'completed_at'  => now(),
            'processing_ms' => $processingMs,
        ]);
    }

    public function markFailed($error)
    {
        $this->update([
            'status'        => self::STATUS_FAILED,
            'error_message' => substr($error, 0, 5000),
            'completed_at'  => now(),
        ]);
    }
}

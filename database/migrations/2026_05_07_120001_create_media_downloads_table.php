<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMediaDownloadsTable extends Migration
{
    public function up()
    {
        Schema::create('media_downloads', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('media_id')->nullable()->index();
            $table->string('url', 2048);                    // original URL
            $table->string('platform', 50)->index();
            $table->string('format', 20)->default('mp4');    // mp4, webm, mp3, m4a
            $table->string('quality', 50)->nullable();       // 1080p, 720p, 128kbps
            $table->string('status', 20)->default('pending'); // pending, downloading, merging, completed, failed
            $table->unsignedInteger('progress')->default(0); // 0-100
            $table->string('file_path', 500)->nullable();    // storage path
            $table->unsignedBigInteger('file_size')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('title', 500)->nullable();
            $table->string('error_message', 1000)->nullable();
            $table->unsignedInteger('processing_ms')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('created_at');
            $table->index(['ip_address', 'created_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('media_downloads');
    }
}

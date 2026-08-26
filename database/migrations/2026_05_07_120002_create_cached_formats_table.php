<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCachedFormatsTable extends Migration
{
    public function up()
    {
        Schema::create('cached_formats', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('media_id')->index();
            $table->string('format_id', 100);           // yt-dlp format_id
            $table->string('type', 20);                  // video, audio
            $table->string('quality', 100)->nullable();  // 1080p, 720p, 128kbps
            $table->string('extension', 10);             // mp4, webm, m4a
            $table->string('vcodec', 50)->nullable();    // h264, vp9, av1
            $table->string('acodec', 50)->nullable();    // aac, opus, mp3
            $table->unsignedBigInteger('filesize')->nullable();
            $table->string('filesize_human', 20)->nullable(); // "15.3 MB"
            $table->unsignedInteger('width')->nullable();
            $table->unsignedInteger('height')->nullable();
            $table->unsignedSmallInteger('fps')->nullable();
            $table->unsignedSmallInteger('abr')->nullable(); // audio bitrate kbps
            $table->string('url', 4096)->nullable();      // direct CDN URL
            $table->boolean('has_audio')->default(false);
            $table->boolean('has_video')->default(true);
            $table->timestamp('url_expires_at')->nullable();
            $table->timestamps();

            $table->index(['media_id', 'type']);
            $table->unique(['media_id', 'format_id'], 'cf_media_format_unique');

            $table->foreign('media_id')->references('id')->on('media')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('cached_formats');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDownloadLogsTable extends Migration
{
    public function up()
    {
        Schema::create('download_logs', function (Blueprint $table) {
            $table->id();
            $table->string('platform')->default('Unknown');      // YouTube, Instagram, etc.
            $table->string('format')->default('MP4');            // MP4, MP3, etc.
            $table->string('quality')->default('—');             // 1080p, 720p, Audio, etc.
            $table->string('ip_address', 45)->nullable();        // IPv4 or IPv6
            $table->string('type')->default('extraction');       // 'extraction' or 'download'
            $table->boolean('status')->default(true);            // true=success, false=fail
            $table->string('title', 255)->nullable();            // Video title
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('download_logs');
    }
}

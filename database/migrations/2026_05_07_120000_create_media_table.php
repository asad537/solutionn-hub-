<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMediaTable extends Migration
{
    public function up()
    {
        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->string('platform', 50)->index();           // YouTube, Instagram, TikTok...
            $table->string('platform_id', 255)->index();       // video/media ID on platform
            $table->string('url', 2048);                       // original URL
            $table->string('title', 500)->nullable();
            $table->text('description')->nullable();
            $table->string('thumbnail', 2048)->nullable();
            $table->string('uploader', 255)->nullable();
            $table->unsignedBigInteger('view_count')->nullable();
            $table->unsignedInteger('duration')->nullable();    // seconds
            $table->string('duration_string', 20)->nullable();  // "05:32"
            $table->json('formats_json')->nullable();           // full yt-dlp format data
            $table->json('metadata_json')->nullable();          // extra metadata
            $table->string('best_audio_url', 2048)->nullable();
            $table->unsignedInteger('extraction_ms')->nullable(); // extraction time tracking
            $table->unsignedBigInteger('hit_count')->default(0);  // popularity tracking
            $table->timestamp('last_extracted_at')->nullable();
            $table->timestamps();

            // Composite index for fast lookups
            $table->unique(['platform', 'platform_id'], 'media_platform_id_unique');
            $table->index('created_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('media');
    }
}

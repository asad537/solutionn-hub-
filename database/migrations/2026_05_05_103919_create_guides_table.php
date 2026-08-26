<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGuidesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('guides', function (Blueprint $table) {
            $table->id();
            $table->integer('author_id')->unsigned()->nullable();
            $table->mediumText('title')->nullable();
            $table->longText('description')->nullable();
            $table->mediumText('content')->nullable();
            $table->string('slug')->nullable();
            $table->string('featured_image')->nullable();
            $table->string('author_name', 100)->nullable();
            $table->string('tags', 300)->nullable();
            $table->string('video_url', 200)->nullable();
            $table->tinyInteger('status')->default(1);
            $table->longText('meta_description')->nullable();
            $table->longText('meta_keywords')->nullable();
            $table->string('image_alt')->nullable();
            $table->longText('tags_cloud')->nullable();
            $table->string('reading_time')->nullable();
            $table->mediumText('related_posts')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('guides');
    }
}

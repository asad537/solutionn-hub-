<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHomepageSettingsTable extends Migration
{
    public function up()
    {
        Schema::create('homepage_settings', function (Blueprint $table) {
            $table->id();
            $table->string('hero_heading')->default('HD Video & Music Downloader for Seamless Downloads');
            $table->string('hero_button_text')->default('Download Video Saver');
            $table->string('hero_button_url')->default('#');
            $table->longText('hero_description')->nullable();
            $table->timestamps();
        });

        // Insert default row
        DB::table('homepage_settings')->insert([
            'hero_heading'     => 'HD Video & Music Downloader for Seamless Downloads',
            'hero_button_text' => 'Download Video Saver',
            'hero_button_url'  => '#',
            'hero_description' => '',
            'created_at'       => now(),
            'updated_at'       => now(),
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('homepage_settings');
    }
}

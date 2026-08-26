<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('page_seo_settings', function (Blueprint $table) {
            $table->string('meta_robots')->default('index, follow')->nullable();
        });
        Schema::table('blogs', function (Blueprint $table) {
            $table->string('meta_robots')->default('index, follow')->nullable();
        });
        Schema::table('guides', function (Blueprint $table) {
            $table->string('meta_robots')->default('index, follow')->nullable();
        });
        Schema::table('platforms', function (Blueprint $table) {
            $table->string('meta_robots')->default('index, follow')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('page_seo_settings', function (Blueprint $table) {
            $table->dropColumn('meta_robots');
        });
        Schema::table('blogs', function (Blueprint $table) {
            $table->dropColumn('meta_robots');
        });
        Schema::table('guides', function (Blueprint $table) {
            $table->dropColumn('meta_robots');
        });
        Schema::table('platforms', function (Blueprint $table) {
            $table->dropColumn('meta_robots');
        });
    }
};

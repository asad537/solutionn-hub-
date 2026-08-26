<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFaqSeoToHomepageSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('homepage_settings', function (Blueprint $table) {
            $table->string('faq_meta_title')->nullable();
            $table->text('faq_meta_description')->nullable();
            $table->text('faq_meta_keywords')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('homepage_settings', function (Blueprint $table) {
            $table->dropColumn(['faq_meta_title', 'faq_meta_description', 'faq_meta_keywords']);
        });
    }
}

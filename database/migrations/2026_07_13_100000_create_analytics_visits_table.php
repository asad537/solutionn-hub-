<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnalyticsVisitsTable extends Migration
{
    public function up()
    {
        Schema::create('analytics_visits', function (Blueprint $table) {
            $table->id();
            $table->string('session_id', 120)->nullable()->index();
            $table->string('ip_address', 45)->nullable()->index();
            $table->string('country_code', 8)->nullable()->index();
            $table->string('country', 100)->nullable();
            $table->string('city', 120)->nullable();
            $table->string('path', 500)->nullable();
            $table->string('referrer', 1000)->nullable();
            $table->string('source', 80)->default('Direct')->index();
            $table->string('device', 30)->default('Desktop');
            $table->string('user_agent', 1000)->nullable();
            $table->timestamp('last_seen_at')->nullable()->index();
            $table->timestamps();
            $table->index(['created_at', 'ip_address']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('analytics_visits');
    }
}

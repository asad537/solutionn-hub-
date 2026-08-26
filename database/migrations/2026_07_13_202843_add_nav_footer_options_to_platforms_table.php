<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNavFooterOptionsToPlatformsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('platforms', function (Blueprint $table) {
            $table->boolean('show_in_navbar')->default(0)->after('status');
            $table->boolean('show_in_footer')->default(0)->after('show_in_navbar');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('platforms', function (Blueprint $table) {
            $table->dropColumn(['show_in_navbar', 'show_in_footer']);
        });
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProcessingJobsTable extends Migration
{
    public function up()
    {
        Schema::create('processing_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('job_type', 50)->index();     // extract, download, merge, hls, cleanup
            $table->string('job_id', 100)->unique();     // unique job identifier
            $table->unsignedBigInteger('media_id')->nullable()->index();
            $table->unsignedBigInteger('download_id')->nullable()->index();
            $table->string('status', 20)->default('queued'); // queued, processing, completed, failed
            $table->unsignedInteger('progress')->default(0);
            $table->json('payload')->nullable();         // job parameters
            $table->text('error_message')->nullable();
            $table->unsignedInteger('attempts')->default(0);
            $table->unsignedInteger('processing_ms')->nullable();
            $table->string('worker_id', 100)->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index(['job_type', 'status']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('processing_jobs');
    }
}

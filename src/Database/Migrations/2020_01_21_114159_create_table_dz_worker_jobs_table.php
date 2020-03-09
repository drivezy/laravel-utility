<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableDzWorkerJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up ()
    {
        Schema::create('dz_worker_jobs', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('hostname')->nullable();
            $table->integer('pid')->nullable();

            $table->char('job_identifier', 36)->nullable();
            $table->string('job_name')->nullable();

            $table->dateTime('start_time')->nullable();
            $table->dateTime('end_time')->nullable();
            $table->dateTime('failed_at')->nullable();

            $table->timestamps();

            $table->index(['hostname', 'pid']);
            $table->index('job_identifier');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down ()
    {
        Schema::dropIfExists('dz_worker_jobs');
    }
}

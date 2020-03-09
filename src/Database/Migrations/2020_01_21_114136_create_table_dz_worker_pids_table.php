<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableDzWorkerPidsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up ()
    {
        Schema::create('dz_worker_processes', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('hostname')->nullable();
            $table->integer('pid')->nullable();

            $table->string('job_name')->nullable();
            $table->dateTime('last_ping_time')->nullable();

            $table->boolean('active')->default(true);

            $table->timestamps();

            $table->index(['hostname', 'pid']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down ()
    {
        Schema::dropIfExists('dz_worker_processes');
    }
}

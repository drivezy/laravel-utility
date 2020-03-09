<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableDzWorkerStatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up ()
    {
        Schema::create('dz_worker_stats', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('hostname');

            $table->integer('minute');
            $table->integer('counter');

            $table->string('type')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down ()
    {
        Schema::dropIfExists('dz_worker_stats');
    }
}

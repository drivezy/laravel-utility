<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDzWorkerJobsChannelIndexTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up ()
    {
        Schema::table('dz_worker_jobs', function (Blueprint $table) {
            $table->index('channel');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down ()
    {
        Schema::table('dz_worker_jobs', function (Blueprint $table) {
            $table->dropIndex('dz_worker_jobs_channel_index');
        });
    }
}

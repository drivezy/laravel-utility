<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDzEventQueuesPickLatencyColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up ()
    {
        Schema::table('dz_event_queues', function (Blueprint $table) {
            $table->integer('pick_latency')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down ()
    {
        Schema::table('dz_event_queues', function (Blueprint $table) {
            $table->dropColumn('pick_latency');
        });
    }
}

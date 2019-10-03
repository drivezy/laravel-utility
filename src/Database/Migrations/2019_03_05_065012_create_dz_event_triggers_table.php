<?php

use Drivezy\LaravelUtility\LaravelUtility;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDzEventTriggersTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up () {
        Schema::create('dz_event_triggers', function (Blueprint $table) {
            $userTable = LaravelUtility::getUserTable();

            $table->bigIncrements('id');
            $table->unsignedBigInteger('event_queue_id')->nullable();

            $table->string('identifier')->nullable();

            $table->dateTime('start_time')->nullable();
            $table->dateTime('end_time')->nullable();

            $table->string('log_file')->nullable();
            $table->unsignedBigInteger('total_latency')->nullable();

            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();

            $table->foreign('event_queue_id')->references('id')->on('dz_event_queues');

            $table->foreign('created_by')->references('id')->on($userTable);
            $table->foreign('updated_by')->references('id')->on($userTable);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down () {
        Schema::dropIfExists('dz_event_triggers');
    }
}

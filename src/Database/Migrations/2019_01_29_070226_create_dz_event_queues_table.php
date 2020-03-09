<?php

use Drivezy\LaravelUtility\LaravelUtility;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDzEventQueuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up ()
    {
        Schema::create('dz_event_queues', function (Blueprint $table) {
            $userTable = LaravelUtility::getUserTable();

            $table->increments('id');
            $table->unsignedInteger('event_id')->nullable();

            $table->string('event_name');
            $table->text('object_value')->nullable();

            $table->dateTime('start_time')->nullable();
            $table->dateTime('end_time')->nullable();

            $table->dateTime('scheduled_start_time')->nullable();

            $table->string('source_type')->nullable();
            $table->unsignedInteger('source_id')->nullable();

            $table->string('target_type')->nullable();
            $table->unsignedInteger('target_id')->nullable();

            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();

            $table->foreign('created_by')->references('id')->on($userTable);
            $table->foreign('updated_by')->references('id')->on($userTable);

            $table->timestamps();
            $table->softDeletes();

            $table->index(['source_type', 'source_id']);
            $table->index(['target_type', 'target_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down ()
    {
        Schema::dropIfExists('dz_event_queues');
    }
}

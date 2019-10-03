<?php

use Drivezy\LaravelUtility\LaravelUtility;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDzScheduledJobsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up () {
        Schema::create('dz_scheduled_jobs', function (Blueprint $table) {
            $userTable = LaravelUtility::getUserTable();

            $table->bigIncrements('id');
            $table->string('name');
            $table->string('description')->nullable();

            $table->unsignedBigInteger('event_id')->nullable();

            $table->string('parameter')->nullable();
            $table->string('timing')->nullable();

            $table->dateTime('start_time')->nullable();
            $table->dateTime('end_time')->nullable();
            $table->dateTime('last_scheduled_time')->nullable();

            $table->boolean('active')->default(true);

            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();

            $table->foreign('event_id')->references('id')->on('dz_event_details');

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
        Schema::dropIfExists('dz_scheduled_jobs');
    }
}

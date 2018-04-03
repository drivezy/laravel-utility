<?php

use App\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHmLookupValuesTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up () {
        Schema::create('hm_lookup_values', function (Blueprint $table) {
            $userTable = ( new User() )->getTable();

            $table->increments('id');
            $table->unsignedInteger('lookup_type_id')->nullable();

            $table->string('name');
            $table->text('value');
            $table->string('description')->nullable();

            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();

            $table->foreign('lookup_type_id')->references('id')->on('hm_lookup_types');

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
        Schema::dropIfExists('hm_lookup_values');
    }
}

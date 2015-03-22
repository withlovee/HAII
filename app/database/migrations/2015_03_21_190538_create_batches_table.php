<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBatchesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('batches', function (Blueprint $table) {
            $table->increments('id');
            $table->string('data_type');
            $table->string('problem_type');
            $table->string('stations')->nullable();;
            $table->boolean('all_station');
            $table->dateTime('start_datetime');
            $table->dateTime('end_datetime');
            $table->dateTime('add_datetime')->nullable();
            $table->dateTime('finish_datetime')->nullable();
            $table->string('status')->default('waiting');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop('batches');
    }
}

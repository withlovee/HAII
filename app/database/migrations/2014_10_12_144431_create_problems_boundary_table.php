<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProblemsBoundaryTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('problems_boundary', function (Blueprint $table) {
            $table->string('station_code');
            $table->dateTime('latest_datetime');
            $table->string('data_type', 40);
            $table->string('problem_type', 40);

            $table->primary(array('station_code', 'data_type', 'problem_type'));
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop('problems_boundary');
    }
}

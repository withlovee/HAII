<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProblemsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('problems', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('station_code');
			$table->string('type', 40);
			$table->dateTime('start_datetime');
			$table->dateTime('end_datetime');
			$table->integer('num');
			$table->string('status');
			$table->timestamps();

			$table->index('station_code');
			$table->index('start_datetime');
			$table->index('type');
			$table->index('status');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('problems');
	}

}

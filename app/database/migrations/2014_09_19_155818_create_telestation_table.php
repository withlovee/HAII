<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTelestationTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('telestation', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('code', 10);
			$table->integer('sim_id');
			$table->string('geocode', 8);
			$table->string('geo_code_basin', 4);
			$table->string('name', 50);
			$table->decimal('lat');
			$table->decimal('lng');
			$table->string();
			$table->string();
			$table->string();
			$table->string();
			$table->string();
			$table->string();
			$table->string();
			$table->timestamps();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('telestation');
	}

}

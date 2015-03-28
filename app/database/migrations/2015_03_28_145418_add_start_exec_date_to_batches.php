<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStartExecDateToBatches extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('batches', function(Blueprint $table)
		{
				$table->dateTime('begin_exec_datetime')->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('batches', function(Blueprint $table)
		{
			$table->dropColumn('begin_datetime');
		});
	}

}

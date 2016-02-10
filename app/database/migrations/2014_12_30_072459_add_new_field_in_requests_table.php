<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewFieldInRequestsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('requests', function(Blueprint $table)
		{
			$table->string('weekday_call')->nullable()->after('meetingtimezonetext');
			$table->string('weekday_call_time')->nullable()->after('meetingtimezonetext');	
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('requests', function(Blueprint $table)
		{
			$table->dropColumn('weekday_call');
			$table->dropColumn('weekday_call_time');
		});
	}

}

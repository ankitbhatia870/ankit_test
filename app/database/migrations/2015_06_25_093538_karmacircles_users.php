<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class KarmacirclesUsers extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//                      		Schema::create('karmacircles_users', function(Blueprint $table)
		{
			$table->increments('id')->initial(100);
			$table->integer('user_id')->unsigned();
			$table->foreign('user_id')->references('id')->on('users');
			$table->text('givers')->nullable();
			$table->text('takers')->nullable();
			$table->text('givers_takers')->nullable();
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
		//
		Schema::drop('karmacircles_users');
	}

}

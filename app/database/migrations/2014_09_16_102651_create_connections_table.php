<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConnectionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('connections', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('networktype');
			$table->string('networkid')->unique();
			$table->string('fname')->nullable();
			$table->string('lname')->nullable();
			$table->text('industry')->nullable();
			$table->text('headline')->nullable();
			$table->text('location')->nullable();
			$table->text('linkedinurl')->nullable();
			$table->string('piclink')->nullable();
			$table->integer('user_id')->unique()->nullable();
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
		Schema::drop('connections');
	}

}

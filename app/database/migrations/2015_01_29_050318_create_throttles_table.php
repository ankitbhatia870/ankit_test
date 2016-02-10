<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateThrottlesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('throttles', function(Blueprint $table)
		{
			$table->increments('id')->initial(100);
			$table->integer('user_id')->initial(100);  
			$table->integer('totalMessageCount')->default('0');
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
		Schema::drop('throttles');  
	}

}

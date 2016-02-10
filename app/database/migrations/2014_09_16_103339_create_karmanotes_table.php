<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKarmanotesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('karmanotes', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('req_id')->unique()->unsigned()->onDelete('cascade');
			$table->integer('user_idreceiver')->unsigned()->nullable();
			$table->integer('user_idgiver')->unsigned()->nullable();
			$table->integer('connection_idgiver')->unsigned()->nullable();
			$table->integer('dollarsdonated')->nullable();
			$table->text('details')->nullable();
			$table->text('skills')->nullable();
			$table->enum('statusgiver', array('hidden', 'visible'))->default('visible');
			$table->enum('statusreceiver', array('hidden', 'visible'))->default('visible');
			$table->integer('viewstatus')->dafault(0);
			$table->integer('share_onlinkedin')->dafault(0); 
			$table->timestamps(); 
			$table->foreign('req_id')->references('id')->on('requests');
			/*$table->foreign('user_idgiver')->references('id')->on('users');
			$table->foreign('connection_idgiver')->references('id')->on('connections');*/
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('karmanotes');
	}

}


<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function(Blueprint $table)
		{
			$table->increments('id')->initial(100);
			$table->string('email')->unique();
			$table->enum('role', array('admin', 'member'))->default('member');
			$table->string('linkedinid')->unique();
			$table->string('fname');
			$table->string('lname')->nullable();			
			$table->string('piclink',255)->nullable();
			$table->text('headline')->nullable();
			$table->text('industry')->nullable();
			$table->text('summary')->nullable();
			$table->text('linkedinurl')->nullable();
			$table->string('location',255)->nullable();
			$table->string('causesupported',255)->nullable();
			$table->string('urlcause',255)->nullable();
			$table->string('donationtypeforcause',255)->nullable();
			$table->text('comments')->nullable();
			$table->integer('karmascore')->default(10);
			$table->integer('noofmeetingspm')->nullable();
			$table->integer('dollarsdonated')->nullable();
			$table->integer('dollarsraised')->nullable();
			$table->string('token',255);
			$table->enum('termsofuse', array('0', '1'))->default(0);
			$table->enum('userstatus', array('pending','TOS not accepted','fetching connection','ready for approval','approved','hidden'))->default('pending');
			$table->integer('totalConnectionCount')->nullable();
			$table->string('remember_token',255);
			$table->dateTime('profileupdatedate');
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
		Schema::drop('users');
	}

}

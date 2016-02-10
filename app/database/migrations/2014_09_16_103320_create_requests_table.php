<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequestsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('requests', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id_introducer')->nullable();
			$table->integer('user_id_receiver')->unsigned()->onDelete('cascade');
			$table->integer('user_id_giver')->nullable()->onDelete('cascade');
			$table->integer('connection_id_giver')->nullable();
			$table->integer('event_id')->nullable();
			$table->string('subject')->nullable();
			$table->text('notes')->nullable();
			$table->integer('payitforward')->dafault(0);
			$table->integer('sendKarmaNote')->dafault(0);
			$table->integer('buyyoucoffee')->dafault(0);
			$table->text('reply')->nullable();
			$table->text('meetingduration')->nullable();
			$table->dateTime('meetingdatetime');
			$table->string('meetingtimezone');
			$table->string('meetingslot');
			$table->enum('meetingtype', array('inperson', 'skype','phone','google'));
			$table->string('meetinglocation')->nullable();
			$table->enum('status', array('pending', 'archived','accepted','completed'));
			$table->dateTime('req_createdate');
			$table->dateTime('req_updatedate');
			$table->integer('replyviewstatus')->dafault(0);
			$table->integer('requestviewstatus')->dafault(0);
			$table->integer('cronjobflag')->dafault(0);
			$table->timestamps();
			//$table->foreign('user_idintroducer')->references('id')->on('users');
			$table->foreign('user_id_receiver')->references('id')->on('users');
			//$table->foreign('user_idgiver')->references('id')->on('users');
			//$table->foreign('connection_idgiver')->references('id')->on('connections');
			//$table->foreign('event_id')->references('id')->on('events');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('requests');
	}

}


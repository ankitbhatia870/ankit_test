<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class Karmafeed extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users_karmafeeds';
    protected $fillable = array('receiver_id','giver_id','message_type','id_type');
   
   	

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	

   
    
}

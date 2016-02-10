<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class Usersconnection extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users_connections';
	//protected $fillable = array('name');
	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	/*public function Tag()
    {
    	return $this->belongsTo('Tag');
    }

	public function User()
    {
        return $this->belongsToMany('User');
    }
*/
    /*public function test() {
	//return $this->belongsTo('User');

	  return $this->hasMany('Connection','id','connection_id');
	  return $this->hasManyThrough('')

    }*/
	
}

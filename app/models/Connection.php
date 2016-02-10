<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class Connection extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'connections';
	protected $fillable = array('networktype','networkid','fname','lname','industry','headline','linkedinurl','piclink','location');
	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */

	public function UsersConnection()
    {
        return $this->belongsToMany('Userconnection');
    }
    
    
}

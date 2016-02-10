<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class Group extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'groups';
	protected $fillable = array('name','description');
	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */

	/*public function Userstag()
    {
        return $this->hasOne('Userstag');
    }*/

    public function Users()
    {
        return $this->belongsToMany('User','users_groups');
    }

    public function Usersgroup()
    {
       return $this->hasMany('Usersgroup');
    }

}

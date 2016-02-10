<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class Tag extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'tags';
	protected $fillable = array('name');
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
        return $this->belongsToMany('User','users_tags');
    }

    public function Userstag()
    {
       return $this->hasMany('Userstag');
    }

}

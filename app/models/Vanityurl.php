<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class Vanityurl extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'vanityurls';
	protected $fillable = array('name','redirecturl');
	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */

	/*public function Userstag()
    {
        return $this->hasOne('Userstag');
    }*/


}

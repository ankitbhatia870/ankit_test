<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class Message extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'requests_messages';
	protected $fillable = array('messageText');
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

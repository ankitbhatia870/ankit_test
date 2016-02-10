<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class Question extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'questions';
	protected $fillable = array('subject','description','user_id','skills','queryStatus','access');
	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */


	public function GiversHelp()
    {
         return $this->hasMany('Questionwillingtohelp', 'question_id', 'id');
    }
    public function Groupquestion()
    {
         return $this->hasMany('Groupquestion', 'question_id', 'id');
    }

	
}

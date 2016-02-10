<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class Meetingrequest extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'requests';
	//protected $fillable = array('name');
	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	public $errors;

	public static $rules = array(
        'notes'            => 'required'     
        
    );
     /*Server Side Validation for Meeting Request*/
    public function isValid($data){
        $validation = Validator::make($data, static::$rules);

        if($validation->passes()){
            return true;
        }
        else{
            $this->errors = $validation->messages();
            return false;
        }
    }

	public function user()
    {
        return $this->belongsToMany('User');
    }

	
}

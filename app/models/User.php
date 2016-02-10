<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('remember_token');

    public $errors;

    /*Rules for Validation*/
    public static $rules = array(
        'urlcause' =>'url',
        'termsofuse'             => 'required'
    );
    /*Server Side Validation for Registration Page*/
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
      /*Model Relation for Getting All users Tags*/
   public function Tags(){
        return $this->belongsToMany('Tag','users_tags');
    }
    /*Model Relation for Getting All users Connections*/
	public function Connections(){
       return $this->belongsToMany('Connection','users_connections');
       // return $this->hasMany('Connection');
    }
    public function Groups(){
       return $this->belongsToMany('Group','users_groups');
       // return $this->hasMany('Connection');
    }     
    /*Model Relation for Getting All users Meeting Requests in which he was a giver*/
    public function Giver(){
        return $this->hasMany('Meetingrequest', 'user_id_giver', 'id');
    }
    /*Model Relation for Getting All users Meeting Requests in which he was a giver and non-karma user*/
    public function NonKarmaGiver(){
        return $this->hasMany('Meetingrequest', 'connection_id_giver', 'id');
    }
    /*Model Relation for Getting All users Meeting Requests in which he was a receiver*/
    public function Receiver(){
        return $this->hasMany('Meetingrequest', 'user_id_receiver', 'id');
    }
    /*Model Relation for Getting All users Meeting Requests in which he was a introducer*/
    public function Introducer()
    {
        return $this->hasMany('Meetingrequest', 'user_id_introducer', 'id');
    }
     /*Model Relation for Getting All users KarmaNotes in which he was a receiver*/
    public function KarmanoteGiver()
    {
    	return $this->hasManyThrough('Karmanote', 'Meetingrequest','user_id_receiver','req_id');
    }
     /*Model Relation for Getting All users KarmaNotes in which he was a giver*/
    public function KarmanoteReceiver()
    {
    	return $this->hasManyThrough('Karmanote', 'Meetingrequest','user_id_giver','req_id');
    }
    /*Model Relation for Getting All users Meeting Requests in which he was a giver*/
    public function Questions(){
        return $this->hasMany('Question', 'user_id', 'id');
    }
    
}

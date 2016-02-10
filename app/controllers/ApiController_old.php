<?php

class ApiController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
		return 'Hello, API';
	}


	/**
	 * Function to save user information.
	 *
	 * @return Response
	 */
	
	public function saveuserInfo() {
		$result=Request::all();
        $rules = Validator::make(Request::all(), [
                    'fname' => 'required',
                    'lname' => 'required',
                    'email' => 'required',
                    'linkedinid' => 'required',
                    'token' => 'required',
                    'linkedinurl' => 'required'
                    
                    
        ]);
        if ($rules->fails()) {
            $this->status = 'failure';
            $this->message = 'There is something missing So user cant be registered';
            return Response::json(array('status'=>$this->status,
    									'message'=>$this->message,
    									
    			));
        } else {
        	 $linkedinid=Request::get('linkedinid');
        	 $email=Request::get('email');
           		$user = User::where('linkedinid', '=', $linkedinid) ->orWhere('email', '=', $email)->first();
				$randNumber=ApiController::getGUID();
				if(!empty($user)){
					//This code will execute when user will already register.
					$user_id=$user->id;
					$user_data			= $user->toArray();
					$user_id 			= 	$user_data['id'];
					$user_info 			= User::find($user_id);
					$user = User::find($user_id);
					$user->token 		=  $result['token'];
					$user->site_token   =  $randNumber;
					$user->save();
					$CurrentDate = KarmaHelper::currentDate();
					$profileupdatedate = $user_info->profileupdatedate;
					$diffDate = KarmaHelper::dateDiff($CurrentDate,$profileupdatedate);
					$diffDate = $diffDate->days * 24 + $diffDate->h; 
					$refreshTime = Adminoption::where('option_name','=','connection refresh time')->first();
					if(!empty($refreshTime)){
						$refreshTime = $refreshTime->toArray();
						$refreshTime = $refreshTime['option_value'];
					}
					else{
						$refreshTime = '360';
					}
					if($diffDate >= $refreshTime){
						$date = Carbon::now()->addMinutes(5);
						Queue::push('UpdateUser', array('id' => $user_id,'result' => $result));
						
					}
					
					$this->status='Success';
					$this->message = 'You are already registered';
					$userStatus = User::where('linkedinid', '=', $linkedinid) ->orWhere('email', '=', $email)->select('userstatus')->first();
					$userStatus=$userStatus->userstatus;
				}
				else{
					//This code will execute when new user will register.
					$user = new User;
					$user->fname 				= $result['fname'];
					$user->lname 				= @$result['lname'];
					$user->email 				= $result['email'];
					$user->piclink 				= @$result['piclink'];
					$user->linkedinid 			= $result['linkedinid']; 
					$user->summary		 		= @$result['summary'];
					$user->location 			= @$result['location'];
					$user->industry 			= @$result['industry'];
					$user->headline 			= @$result['headline'];
					$user->linkedinurl 			= @$result['linkedinurl'];
					$user->token 				= @$result['token'];
					$user->termsofuse			= 1;
					$user->userstatus           = 'ready for approval';
					$user->noofmeetingspm		= 2;		
					$user->profileupdatedate 	= date('Y-m-d H:i:s');
					$user->site_token			= $randNumber;
					$user->save();

					$user 		= $user;
					$user_id 	= $user->id;
					Queue::push('MessageSender@newUserEmail',array('user_id'=> $user_id));
					//funtion to save data on connections table.
					$InsConnection = KarmaHelper::insertUserConnection($user);
					$this->status = 'Success';
					$this->message = 'User successfully registered';
					$userStatus = User::where('linkedinid', '=', $result['linkedinid']) ->orWhere('email', '=', $result['email'])->select('userstatus')->first();
					$userStatus=$userStatus->userstatus;
				}

				return Response::json(array('status'=>$this->status,
    									'message'=>$this->message,
    									'UserId'=>$user_id,
    									'UserAccesstoken'=>$user->site_token,
    									'UserStatus'=>$userStatus
    			));
        
    		}

    		
    }

    /**
	 * Function to get all country list with their country code.
	 *
	 * @return In Response we will send status{success or failure},countrylist.
	 */

	public function getCountryList() {
    	$getCountryList = DB::table('countries')->select('country','country_code')->distinct('country')->get();
    	if(!empty($getCountryList)){
    		$this->status='success';
    		$this->message='Get all country list';
    	}else{
    		$this->status='failure';
    		$this->message='There is no country list available in the database';
    	}
    	return Response::json(array('status'=>$this->status,
    									'message'=>$this->message,'CountryList'=>$getCountryList
    									
    	));

    }

	/**
	 * Function to generate random unique 6 dight otp for new registered user.
	 *
	 * @return In Response we will send status{success or failure}.
	 */
    public function generateOTP() {
        #validation
        $validator = Validator::make(Request::all(), [
                    'phone_number' => 'required',
                    'device_uid' => 'required',
                    'country_code'=> 'required'
        ]);
        if ($validator->fails()) {
            #display error if validation fails                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                       
            $this->status = 'Validation fails';
            $this->message = 'arguments missing';
        } else {
        	
            #If validation suceeded
            #get inputs
			$phone_number = Request::get('phone_number');
			$country_code = Request::get('country_code');
            $device_uid = Request::get('device_uid');
            $getuser = User::where('id', '=', $device_uid)->first();
            #Check if phone is registered
            if (!empty($getuser)) {
            	//$string = rand(100000, 999999);
                //$otpNumber = strtoupper($string);
            	$otpNumber=123456;
            	DB::table('users')->where('id','=',$device_uid)
            	->update(array('country_code' => $country_code, 'phone_number' => $phone_number,'user_activation_key' => $otpNumber));
				$this->status = 'success';
                $this->message = "OTPNumber is generated";
            } 
        }
        return Response::json(array('status'=>$this->status,
    									'message'=>$this->message,
    									
    	));
        
    }

    /**
	 * Function to generate random unique access token.
	 *
	 * @return we will send unique access token.
	 */
    public function getGUID(){
	    if (function_exists('com_create_guid')){
	        return com_create_guid();
	    }else{
	        mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
	        $charid = strtoupper(md5(uniqid(rand(), true)));
	        $hyphen = chr(45);// "-"
	        $uuid = // "{"
	            substr($charid, 0, 8).$hyphen
	            .substr($charid, 8, 4).$hyphen
	            .substr($charid,12, 4).$hyphen
	            .substr($charid,16, 4).$hyphen
	            .substr($charid,20,12);
	            // "}"
	        return $uuid;
	    }
	}

    
    /*
	 * Function to verify that otp which enter is correct or not.
	 *
	 * @return In Response we will send status{success or failure}.
	 */
    public function otpVerification() {
    	$validator = Validator::make(Request::all(), [
                    'otp_number' => 'required',
                    'userId' => 'required'
        ]);
        if ($validator->fails()) {
            #display error if validation fails                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                       
            $this->status = 'Validation fails';
            $this->message = 'arguments missing';

        } else {
        	$userId = Request::get('userId');
        	$getOTP = Request::get('otp_number');
        	$getOTPNumber=User::where('id', '=', $userId)->where('user_activation_key','=',$getOTP)->count();
        	
        	if ($getOTPNumber >0) {
        		$this->status = 'success';
            	$this->message = 'OTP matched';
            	DB::table('users')->where('id', '=', $userId)->update(array('userstatus' => 'approved'));
        	}else{
        		$this->status = 'failure';
            	$this->message = 'The OTP does not match. Please enter again.';
        	}
        }
        return Response::json(array('status'=>$this->status,
    									'message'=>$this->message,
    									
    	));

	}
   
   /**
	 * Function to update profile of particular user.
	 *
	 * @return In Response we will send status{success or failure}.
	 */
	public function profileShow() {
	 	$validator = Validator::make(Request::all(), [
                    'accessToken' => 'required',
                    'userId' => 'required'
        ]);
        if ($validator->fails()) {
            #display error if validation fails                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                       
            $this->status = 'Validation fails';
            $this->message = 'arguments missing';
        } else {
        	$accesstoken = Request::get('accessToken');
            $user_id = Request::get('userId');
            $profileUserDetail 	= User::find($user_id);
            $getuser = User::where('id', '=', $user_id)->where('site_token','=',$accesstoken)->select('fname','lname','piclink','headline','industry','summary','location','karmascore','comments','noofmeetingspm','causesupported','urlcause','donationtypeforcause')->first();
            if(!empty($getuser)){
            	$profileUserSkills 	= $profileUserDetail->Tags;
            	$users_group = $profileUserDetail->Groups;
            	$this->status = 'success';
            	//$this->message = '';
            	return Response::json(
            		array('status'=>$this->status,
    					  'UserData'=>$getuser,
    					  'SkillData'=>$profileUserSkills,
    					  'UserGroup'=>$users_group
    												
    			));
            }else{
            	$this->status = 'failure';
            	$this->message= 'You are not a login user.';
            }

	 	}
	 	return Response::json(array(
	 		'status'=>$this->status,
	 		'message'=>$this->message
	 	));
	 }

	/**
	 * Function to update profile "I can help with" of particular user.
	 *
	 * @return In Response we will send status{success or failure}.
	 */
	 public function saveAdviceInfo() {
	 	$validator = Validator::make(Request::all(), [
                    'accessToken' => 'required',
                    'userId' => 'required',
                    'numberofMeeting' => 'required',
                    'comments' => 'required'
        ]);
        if ($validator->fails()) {
            #display error if validation fails                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                       
            $this->status = 'Validation fails';
            $this->message = 'arguments missing';
        } else {
        	$accesstoken = Request::get('accessToken');
            $user_id = Request::get('userId');
            $numberofMeeting = Request::get('numberofMeeting');
            $comments = Request::get('comments');
            $user = User::find($user_id);
           	if($accesstoken==$user->site_token){
	           	if(!empty($user)){
	           		$user->noofmeetingspm = $numberofMeeting;
					$user->comments = $comments;
					$user->save();
	        		$this->status = 'success';
	        		$this->message= 'successfully edited';
	            }else{
	            	$this->status = 'failure';
	            	$this->message= 'You are not a login user1.';
	            }
	        }else{
	        	$this->status = 'failure';
	        	$this->message= 'You are not a login user.';
	        }

	 	}
	 	return Response::json(array(
	 		'status'=>$this->status,
	 		'message'=>$this->message
    	));
	}

	 /**
	 * Function to update profile "Cause I support" of particular user.
	 *
	 * @return In Response we will send status{success or failure}.
	 */
	public function saveCauseInfo(){
		
		$validator = Validator::make(Request::all(), [
                    'accessToken' => 'required',
                    'userId' => 'required',
                    'causeSupported' => 'required',
                    'donationTypeForCause' => 'required',
                    'urlCause' => 'required'
        ]);
        if ($validator->fails()) {
            #display error if validation fails                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                       
            $this->status = 'Validation fails';
            $this->message= 'Argument missing';
        } else {
        	$user_id = Request::get('userId');
	    	$accesstoken = Request::get('accessToken');
	    	$causesupported = Request::get('causeSupported');
	    	$urlcause = Request::get('urlCause');
	    	$donationtypeforcause = Request::get('donationTypeForCause');
	    	$user =  User::find($user_id);
	    	if($accesstoken==$user->site_token){
		    	if(!empty($user)){
		    		$user->causesupported = strip_tags($causesupported);
					$user->urlcause = strip_tags($urlcause);
					$user->donationtypeforcause = strip_tags($donationtypeforcause);
					$user->save();
					$this->status = 'Success';
					$this->message= 'successfully edited.';
				}else{
					$this->status = 'Failure';
					$this->message= 'You are not a login user.';
		    	}
		    }else{
		    	$this->status = 'Failure';
		    	$this->message= 'You are not a login user.';
		    }
        }
        return Response::json(array(
        	'status'=>$this->status,
        	'message'=>$this->message
    	));

	}
	/**
	 * Function to update Basic profile of particular user.
	 *
	 * @return In Response we will send status{success or failure}.
	 */

	public function saveUserBasicInfo(){
		
		$validator = Validator::make(Request::all(), [
                    'accessToken' => 'required',
                    'userId' => 'required',
                    'headline' => 'required',
                    'industry' => 'required',
                    'location' => 'required'
                    
        ]);
        if ($validator->fails()) {
            #display error if validation fails                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                       
            $this->status = 'Validation fails';
            $this->message= 'Argument missing';
        } else {
        	$user_id = Request::get('userId');
	    	$accesstoken = Request::get('accessToken');
	    	$headline = Request::get('headline');
	    	$industry = Request::get('industry');
	    	$location = Request::get('location');
	    	$user =  User::find($user_id);
	    	if($accesstoken==$user->site_token){
	    		if(!empty($user)){
		    		$user->headline = $headline;
					$user->industry = $industry;
					$user->location = $location;
					$user->save();
					$this->status = 'Success';
					$this->message= 'successfully edited';
				}else{
					$this->status = 'Failure';
					$this->message= 'Please login to edit';
		    	}
		    }else{
		    	$this->status = 'Failure';
		    	$this->message= 'You are not a login user.';
		    }
        }
        return Response::json(array(
        	'status'=>$this->status,
        	'message'=>$this->message
    	));

	}

	 /**
	 * Function to update profile "User Group" of particular user.
	 *
	 * @return In Response we will send status{success or failure}.
	 */

    public function joinLeaveGroup(){
		
		$validator = Validator::make(Request::all(), [
                    'accessToken' => 'required',
                    'userId' => 'required',
                    'groupId' => 'required',
                    'groupAction' => 'required'
        ]);
        if ($validator->fails()) {
            #display error if validation fails                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                       
            $this->status = 'Validation fails';
            $this->message = 'arguments missing';
        } else {
        	$userId = Request::get('userId');
	    	$accesstoken = Request::get('accessToken');
	    	$groupId = Request::get('groupId');
	    	$action= Request::get('groupAction');
			$usersgroupCount = Usersgroup::where('user_id','=',$userId)->count();
			$user =  User::find($userId);
			$checkGroupId=Group::find($groupId);
			if(!empty($checkGroupId)){
				if($accesstoken==$user->site_token){
					if($action == 'leave'){   	
						if($usersgroupCount > 1){
							$usersgroupCount = Usersgroup::where('user_id','=',$userId)->where('group_id','=',$groupId)->delete();
							$this->status = 'Success';
							$this->message= 'You are successfully leave a group';
						}
						else{
							$this->status = 'Failure';
							$this->message= 'There is something missing';
						}
						return Response::json(array(
							'status'=>$this->status
	    				));
					}
					if($action == 'join'){	
						$usersgroupCount = Usersgroup::where('user_id','=',$userId)->where('group_id','=',$groupId)->delete();
						$usergroup = new Usersgroup;
						$usergroup->user_id = $userId;
						$usergroup->group_id = $groupId;
						$usergroup->save();
						$this->status = 'Success';
						$this->message= 'You are successfully join a group';
					}else{
							$this->status = 'Failure';
							$this->message= 'There is something missing';
					}
				}else{
					$this->status = 'Failure';
					$this->message= 'You are not a login user';

				}
			}else{
				$this->status = 'Failure';
				$this->message= 'Group cant exist';
			}
			
		}
		return Response::json(array(
			'status'=>$this->status,
			'message'=>$this->message
    	));
	}

	/**
	 * Function to auto scroll skill.
	 *
	 * @return In Response we will send status{success or failure} and searchSkillData.
	 */
	public function getSkillData(){
		$validator = Validator::make(Request::all(), [
                    'accessToken' => 'required',
                    'userId' => 'required',
                    'searchKeyword' => 'required'
        ]);
        if ($validator->fails()) {
            #display error if validation fails                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                       
            $this->status = 'Validation fails';
        } else {
        	$user_id = Request::get('userId');
        	$accesstoken = Request::get('accessToken');
	    	$search = Request::get('searchKeyword');
	    	$searchquery = DB::select(DB::raw("select id,name from tags where name LIKE '%".$search."%'And id Not IN (select tag_id from users_tags where user_id=".$user_id.")"));	
        	$user =  User::find($user_id);
	    	if($accesstoken==$user->site_token){
	        	if(!empty($searchquery)){
	        		$this->status = 'Success';
	        		return Response::json(array('status'=>$this->status,
	        									'searchSkillData'=>$searchquery
	    			));
	        	}else{
	        		$this->status = 'Success';
	        		$this->message = 'There is no data available';
	        		return Response::json(array('status'=>$this->status,
	        									'message'=>$this->message,
	        									'searchSkillData'=>$searchquery
	    			));
	        	}
	        }else{
	        	$this->status = 'Failure';
	        }
        }
        return Response::json(array('status'=>$this->status
    	));
	}
	
	/**
	 * Function to update profile "Skills" of particular user.
	 *
	 * @return In Response we will send status{success or failure}.
	 */
	public function updateSkill(){
		$validator = Validator::make(Request::all(), [
                    'accessToken' => 'required',
                    'userId' => 'required',
                    //'skillTags' => 'required'
        ]);
        if ($validator->fails()) {
            #display error if validation fails                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                       
            $this->status = 'Validation fails';
            $this->message = 'arguments missing';
        } else {
        	$result=Request::all();
        	$userId = $result['userId'];
	    	$userSkill = $result['skillTags'];
	    	$accesstoken = $result['accessToken'];
	    	$user =  User::find($userId);
	    	if($accesstoken==$user->site_token){
		    	
		    	if(empty($userSkill)){
		    		$this->status = 'Failure';
			    	$this->message= 'There is no skill selected';
		    	}else{
		    		foreach ($userSkill as $value) {
		    			$value=$value['id'];
		    			if(!empty($value)){
		    				$checkSkill=DB::table('users_tags')->where('user_id','=',$userId)->where('tag_id','=',$value)->count();
			    				$userData = new Userstag;
					    		$userData->tag_id=$value;
					    		$userData->user_id=$userId;
					    		$userData->save();
					    }
					}
					$this->status = 'Success';
				    $this->message= 'Your skill has updated';
				}
		    }else{
		    	$this->status = 'Failure';
			    $this->message= 'You are not login user.Please login first.';
		    }
		}
	    return Response::json(array(
	    	'status'=>$this->status,
	    	'message'=>$this->message
    	));
    } 

    /**
	 * Function to update profile "groups" of particular user.Here we will fetch all the groups
	 *
	 * @return In Response we will send status{success or failure} and group list.
	 */
    public function getGroupList() {
    	$validator = Validator::make(Request::all(), [
                    'accessToken' => 'required',
                    'userId' => 'required',
                    //'skillTags' => 'required'
        ]);
        if ($validator->fails()) {
            #display error if validation fails                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                       
            $this->status = 'Validation fails';
            $this->message = 'arguments missing';
        } else {
	        	$accessToken=Request::get('accessToken');
	        	$userId=Request::get('userId');
	        	$user =  User::find($userId);
	        	if($accessToken==$user->site_token){

	        		$usersgroupData = DB::select(DB::raw("select distinct(group_id) from users_groups where group_id Not IN(select group_id from `users_groups` where `user_id` =".$userId.")"));	
			    	$user_group='';
					foreach ($usersgroupData as $value) {
						$user_group[]=$value->group_id;
					}
					if(!empty($user_group)){
						$user_group=array_filter($user_group);
						//$user_group=implode(",", $user_group);
					}
					//print_r($user_group);die;
					$getGroupList =DB::table('groups')->select('id','name','url','description')->distinct('id')->whereIn('id',$user_group)->get();
					 
			    	if(!empty($getGroupList)){
			    		$this->status='success';
			    		$this->message='Get all Group list';
			    	}else{
			    		$this->status='Success';
			    		$this->message='There is no Group list available in the database';
			    	}
			    	return Response::json(array('status'=>$this->status,
	    									'message'=>$this->message,'GroupList'=>$getGroupList
	    									
	    			));
			    }else{
			    	$this->status='failure';
			    	$this->message='You are not a login user.';
			    }
	    	
	    }
	    return Response::json(array(
	    	'status'=>$this->status,
	    	'message'=>$this->message
    	));

    }



}

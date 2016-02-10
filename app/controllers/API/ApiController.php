<?php
namespace API;
use Validator;
use Request;
use Response;
use NotificationHelper;use Twilio;use User;use Userstag;use Usersgroup;use Question;use Tag;Use KarmaHelper;use Karmafeed;use Group;use Karmanote;use Questionwillingtohelp;use Queue;use Adminoption;use Carbon;use Connection; //Models
use Illuminate\Support\Facades\DB; //To queries directly
class ApiController extends \BaseController {
//Index function
	
	public function index()
	{
		//
		//return 'Hello, API';
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
                    'linkedinurl' => 'required',
                    'headline'=>'required'
                    
                    
        ]);
        if ($rules->fails()) {
            $this->status = 'failure';
            $this->message = "There is something missing so user can't be registered";
            return Response::json(array('status'=>$this->status,
    									'message'=>$this->message,
    									
    			));
        } else {
        	 $linkedinid=Request::get('linkedinid');
        	 $location=Request::get('location');
        	 $email=Request::get('email');
           		$user = User::where('email', '=', $email)->first();
				$randNumber=ApiController::getGUID();
				if(!empty($user)){
					//This code will execute when user is already registered.
					$user_id=$user->id;
					$user_data			= $user->toArray();
					$user_id 			= 	$user_data['id'];
					/************* Change it **********/
					$user = User::find($user_id);
					/************* Change it **********/
					$user->token 		=  $result['token'];
                    $user->site_token   	=  $randNumber;
                    $user->industry 			= $result['industry'];
					$user->headline 			= $result['headline'];
					$user->linkedinurl 			= @$result['linkedinurl'];
                    $user->location   			=  $location;
					$user->piclink 				= $result['piclink'];
					$user->save();
					$userStatus=$user->userstatus;
            		$userActivationKey=$user->user_activation_key;
					$userPhonenumber=$user->phone_number;
            		if($userStatus=='approved'){
            			$CurrentDate = KarmaHelper::currentDate();
						$profileupdatedate = $user->profileupdatedate;
						$diffDate = KarmaHelper::dateDiff($CurrentDate,$profileupdatedate);
						$diffDate = $diffDate->days * 24 + $diffDate->h; 
						$refreshTime = Adminoption::where('option_name','=','connection refresh time')->first();
						//$resultData = json_decode($linkedinService->request('/people/~:(id,first-name,last-name,skills,headline,summary,industry,member-url-resources,picture-urls::(original),location,public-profile-url,email-address,site-standard-profile-request)?format=json'), true);
						if(!empty($refreshTime)){
							$refreshTime = $refreshTime->toArray();
							$refreshTime = $refreshTime['option_value'];
						}else{
							$refreshTime = '360';
						}
						if($diffDate >= $refreshTime){
							$date = Carbon::now()->addMinutes(5);
							Queue::push('UpdateUser', array('id' => $user_id,'result' => $result));
							
						}
						//$InsConnection = KarmaHelper::updateUserProfile($user,$result);
						//$checkPhoneNumber=DB::select(DB::raw("SELECT IF(phone_number IS NULL or phone_number = '', '0', phone_number) as phone_number from users where id=".$user_id." limit 1"));
						if($userActivationKey=='' && $userActivationKey=='null'){
							$this->status='Success';
							$this->userstatus='NotRegisteredWithApp';
							$this->message='This user is not registered with app.';
						}else if($userActivationKey=='0'){
							$this->status='Success';
							$this->userstatus='approvedWithPhone';
							$this->message='This user is approved with app.';
						}else{
							$this->status='Success';
							$this->userstatus='approvedWithoutPhone';
							$this->message='This user is approved from web but not from app.';
						}
						
					}else if($userStatus=='ready for approval'){
						$this->status='Success';
						$this->userstatus='ready for approval';
						$this->message = 'User is in ready for approval State';
					}else if($userStatus=='TOS not accepted'){
						$user->termsofuse			= 1;
						$user->userstatus           = 'ready for approval';
						$user->save();
						$groupId=1;
            			$usersgroupDelete = Usersgroup::where('user_id','=',$user_id)->where('group_id','=',$groupId)->delete();
                		$addUserGroup = new Usersgroup;
                		$addUserGroup->user_id=$user_id;
                		$addUserGroup->group_id=1;
                		$addUserGroup->save();
                		$user_id_giver='null';
						$feedType='KarmaGroup';
						KarmaHelper::storeKarmacirclesfeed($user_id_giver,$user_id,$feedType,$groupId);
						$this->status='Success';
						$this->userstatus='Tos State';
						$this->message = 'User is in TOS State';
					}
					
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
					$user->userstatus           = 'ready for approval';
					$user->termsofuse			= 1;
					$user->noofmeetingspm		= 2;		
					$user->profileupdatedate 	= date('Y-m-d H:i:s');
					$user->site_token			= $randNumber;
					$user->save();

					$user 		= $user;
					$user_id 	= $user->id;
					$this->userstatus=$user->userstatus;
                    Queue::push('MessageSender@newUserEmail',array('user_id'=> $user_id));
					//funtion to save data on connections table.
					//$InsConnection = KarmaHelper::insertUserConnection($user);
					$this->userstatus='ready for approval';
					$this->status = 'Success';
					$this->message = 'User successfully registered';
					Queue::push('MessageSender@newUserEmail',array('user_id'=> $user_id));
					/************* Change it **********/
					//$userStatus = User::where('linkedinid', '=', $result['linkedinid']) ->orWhere('email', '=', $result['email'])->select('userstatus')->first();
					//$userStatus=$userStatus->userstatus;
					/************* Change it **********/
				}

				return Response::json(array('status'=>$this->status,
    									'message'=>$this->message,
    									'UserId'=>$user_id,
    									'UserAccesstoken'=>$user->site_token,
    									'UserStatus'=>$this->userstatus
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
	 * Function to generate random unique 6 dight Verification code for new registered user.
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
            $this->message = 'Arguments missing';
        } else {
        	
            #If validation suceeded
            #get inputs
			$phone_number = Request::get('phone_number');
			$country_code = Request::get('country_code');
            $device_uid = Request::get('device_uid');
            $checkUserPhoneNumber = User::where('phone_number', '=', $phone_number)->where('id','!=',$device_uid)->first();
            #Check if phone is registered
            if (empty($checkUserPhoneNumber)) {
            	$CurrentDate=Carbon::now();
            	$userData=User::where('id','=',$device_uid)->select('updated_at','user_activation_key')->first();
            	$diffDate = KarmaHelper::dateDiff($CurrentDate,$userData->updated_at);
            	$number='+'.$country_code.''.$phone_number;
            	//echo '<pre>';print_r($userData->user_activation_key);die;
            	if($userData->user_activation_key !='0' && $userData->user_activation_key !='NULL' && $userData->user_activation_key !=''){
            		if($diffDate->i >10){
	            		$string = rand(1000, 9999);
	                	$otpNumber = strtoupper($string);	
	            	}else{
	            		$otpNumber = $userData->user_activation_key;
	            	}	
            	}else{
            		$string = rand(1000, 9999);
	                $otpNumber = strtoupper($string);
            	}
            	$result=Twilio::to($number)->message('Welcome to karmacircles.com.Your verification code number is '.$otpNumber.'.Please enter your verification code number for complete your registration process');
            	DB::table('users')->where('id','=',$device_uid)
            	->update(array('country_code' => $country_code, 'phone_number' => $phone_number,'user_activation_key' => $otpNumber,'updated_at'=>Carbon::now()));
        		 $numberWithcountrycode=$country_code.''.$phone_number;
        		 $userData=User::where('id','=',$device_uid)->first();
        		 if(empty($userData->totalConnectionCount)){
					$userData->totalConnectionCount = 0;
					$userData->save();
				 }
        		 $connectionDataCount = Connection::where('phone_number','LIKE', '%'.$numberWithcountrycode)->orWhere('phone_number','LIKE', '%'.$phone_number)->orWhere('networkid','=',$userData->linkedinid)->count();
        		 if($connectionDataCount > 1){
        		 	$connectionDataResult = Connection::where('phone_number','LIKE', '%'.$numberWithcountrycode)->orWhere('phone_number','LIKE', '%'.$phone_number)->orWhere('networkid','=',$userData->linkedinid)->get();
        		 	foreach ($connectionDataResult as $key => $value) {
        		 		DB::table('users_mykarma')->where('connection_id','=',$value->id)->update(array('user_id' => $device_uid,'connection_id' => 'NULL'));
        		 		DB::table('users_karmafeeds')->where('karmafeed_connection_id','=',$value->id)->update(array('giver_id' => $device_uid,'karmafeed_connection_id' => 'NULL'));
        		 		DB::table('requests')->where('connection_id_giver','=',$value->id)->update(array('connection_id_giver' => 'NULL','user_id_giver' => $device_uid));
        		 		DB::table('karmanotes')->where('connection_idgiver','=',$value->id)->update(array('connection_idgiver' => 'NULL','user_idgiver' => $device_uid));
        		 		if($value->networkid != $userData->linkedinid){
        		 			$userDelete = Connection::where('id','=',$value->id)->delete();
        		 		}
        		 	}
        		 }
        		 $connectionData = Connection::where('phone_number','LIKE', '%'.$numberWithcountrycode)->orWhere('phone_number','LIKE', '%'.$phone_number)->orWhere('networkid','=',$userData->linkedinid)->first();
        		if(!empty($connectionData)){
        		 	$networkId=$connectionData->networkid;
        		 	$phoneNumber=$connectionData->phone_number;
        		 	$connectionData->fname 			= @$userData->fname;
					$connectionData->lname 			= @$userData->lname;  
					$connectionData->phone_number 	= @$phone_number;
					$connectionData->country_code 	= @$country_code;
					$connectionData->headline 		= @$userData->headline;
					$connectionData->industry 		= @$userData->industry;
					$connectionData->location 		= @$userData->location;
					$connectionData->piclink 		= @$userData->pictureUrl; 
					$connectionData->linkedinurl 	= @$userData->linkedinurl;
					if(empty($networkId)){
						$connectionData->networkid 		= @$userData->linkedinid;	
					}
					$connectionData->user_id 		= @$userData->id;
					$connectionData->save();
        		}else{
        			$saveConnectionData=new Connection;
        			$saveConnectionData->fname 			= @$userData->fname;
					$saveConnectionData->lname 			= @$userData->lname;
					$saveConnectionData->phone_number 	= @$phone_number;
					$saveConnectionData->country_code 	= @$country_code;
        			$saveConnectionData->headline 		= @$userData->headline;
					$saveConnectionData->industry 		= @$userData->industry;
					$saveConnectionData->location 		= @$userData->location;
					$saveConnectionData->piclink 		= @$userData->pictureUrl; 
					$saveConnectionData->linkedinurl 	= @$userData->linkedinurl;
					$saveConnectionData->networkid 		= @$userData->linkedinid;
					$saveConnectionData->user_id 		= @$userData->id;
					$saveConnectionData->save();
				}
        		
            	    	
				$this->status = 'success';
                $this->message = "Verification Code is generated";
            }else{
            	$this->status = 'Failure';
                $this->message = "This phone number is already exist";
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
	 * Function to verify that Verification code which enter is correct or not.
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
            $this->message = 'Arguments missing';

        } else {
        	$userId = Request::get('userId');
        	$getOTP = Request::get('otp_number');
        	$getOTPNumber=User::where('id', '=', $userId)->where('user_activation_key','=',$getOTP)->count();
        	if ($getOTPNumber >0) {
        		$this->status = 'success';
            	$this->message = 'Verification code matched';
            	$groupId=1;
            	$usersgroupDelete = Usersgroup::where('user_id','=',$userId)->where('group_id','=',$groupId)->delete();
                $addUserGroup = new Usersgroup;
                $addUserGroup->user_id=$userId;
                $addUserGroup->group_id=1;
                $addUserGroup->save();
                $user_id_giver='null';
				$feedType='KarmaGroup';
				KarmaHelper::storeKarmacirclesfeed($user_id_giver,$userId,$feedType,$groupId);
            	DB::table('users')->where('id', '=', $userId)->update(array('userstatus' => 'approved','termsofuse' => '1','user_activation_key'=>'0'));
            	$connectionData=Connection::where('user_id','=',$userId)->first();
            	if(!empty($connectionData)){
            		DB::table('users_mykarma')->where('connection_id', '=', $connectionData->id)->where('users_role','=','Giver')->update(array('user_id' => $userId,'fname' =>$connectionData->fname,'lname' =>$connectionData->lname,'piclink' =>$connectionData->piclink));	
            	}
            }else{
        		$this->status = 'failure';
            	$this->message = 'The verification code does not match. Please enter again.';
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
            /********* Need to be checked **********/
            $profileUserDetail 	= User::find($user_id);
           	$getuser = DB::table('users')->where('id', '=', $user_id)->where('site_token','=',$accesstoken)->select('fname','lname','piclink','headline','industry','summary','location','karmascore','comments','noofmeetingspm','causesupported','urlcause','donationtypeforcause')->first();
            /********* Need to be checked **********/
            if(!empty($getuser)){
            	$profileUserSkills 	= $profileUserDetail->Tags;
            	$usersGroup = $profileUserDetail->Groups;
            	$this->status = 'success';
            	//$this->message = '';
            	return Response::json(
            		array('status'=>$this->status,
    					  'UserData'=>$getuser,
    					  'SkillData'=>$profileUserSkills,
    					  'UserGroup'=>$usersGroup
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
	            	$this->message= 'You are not a login user.';
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
							/****** Change variable name *******/
							$usersgroupDelete = Usersgroup::where('user_id','=',$userId)->where('group_id','=',$groupId)->delete();
							$this->status = 'Success';
							$this->message= 'You are successfully leave a group';
						}
						else{
							$this->status = 'Failure';
							$this->message= 'You are not a member of a group or only one group joined';
						}
						return Response::json(array(
							'status'=>$this->status,
							'message'=>$this->message
	    				));
					}
					if($action == 'join'){
					    /****** Change variable name *******/	
						$usersgroupDelete = Usersgroup::where('user_id','=',$userId)->where('group_id','=',$groupId)->delete();
						$usergroup = new Usersgroup;
						$usergroup->user_id = $userId;
						$usergroup->group_id = $groupId;
						$usergroup->save();
						$user_id_giver='null';
						$feedType='KarmaGroup';
						KarmaHelper::storeKarmacirclesfeed($user_id_giver,$userId,$feedType,$groupId);
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
        	/********* change this approach ************/
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
		    				    //$checkSkill=DB::table('users_tags')->where('user_id','=',$userId)->where('tag_id','=',$value)->count();
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
        } 
        else {
	        	$accessToken=Request::get('accessToken');
	        	$userId=Request::get('userId');
	        	$user =  User::where('site_token','=',$accessToken)->count();
	        	if($user > 0){

	        		//unjoined group list
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

					//Joined group list
					$usersjoingroupData = DB::select(DB::raw("select distinct(group_id) from users_groups where group_id IN(select group_id from `users_groups` where `user_id` =".$userId.")"));
	        		$user_join_group='';
					foreach ($usersjoingroupData as $value) {
						$user_join_group[]=$value->group_id;
					}
					if(!empty($user_join_group)){
						$user_join_group=array_filter($user_join_group);
						//$user_group=implode(",", $user_group);
					}
					//print_r($user_group);die;
					$getJoinGroupList =DB::table('groups')->select('id','name','url','description')->distinct('id')->whereIn('id',$user_join_group)->get();
					 
			    	if(!empty($getGroupList)){
			    		$this->status='success';
			    		$this->message='Get all Group list';
			    	}else{
			    		$this->status='Success';
			    		$this->message='There is no Group list available in the database';
			    	}
			    	return Response::json(array('status'=>$this->status,
	    									'message'=>$this->message,'GroupJoinedList'=>$getJoinGroupList,'GroupUnjoinedList'=>$getGroupList
	    									
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

     /**
     * Function to check status of particular user.
     *
     * @return In Response we will send status{success or failure}.
     */

     public function checkUserStatus() {
        $validator = Validator::make(Request::all(), [
                    'linkedinId'=> 'required',
                    'email'=>'required'
        ]);
        if ($validator->fails()) {
            #display error if validation fails                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                       
            $this->status = 'Validation fails';
            $this->message = 'arguments missing';
        } else {
            $firstName =Request::get('firstName');
            $lastName=Request::get('lastName');
            $email=Request::get('email');
            $linkedinId=Request::get('linkedinId');
            $checkUserStatus =DB::table('users')->where('linkedinid','=',$linkedinId)->where('email','=',$email)->where('termsofuse','=','1')->select('fname','lname')->first();
            if(!empty($checkUserStatus)){
                $this->status='Verified';
                $this->message="User is already exist";
                $firstName=$checkUserStatus->fname;
                $lastName=$checkUserStatus->lname;
                return Response::json(array(
                    'status'=>$this->status,
                    'message'=>$this->message,
                    'firstName'=>$firstName,
                    'lastName'=>$lastName,
                ));        
            }else{
                $this->status='Not Verified';
                $this->message="This is a new user";
            }
        }
        return Response::json(array(
            'status'=>$this->status,
            'message'=>$this->message
        ));
    }

    public function pushNotification() {
    	$validator = Validator::make(Request::all(), [
                    'userId'=> 'required',
                    'accessToken'=>'required',
                    'senderId'=>'required',
                    'token'=>'required',
                    'deviceType'=>'required'
        ]);
        if ($validator->fails()) {
            #display error if validation fails                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                       
            $this->status = 'Validation fails';
            $this->message = 'arguments missing';
        } else {
            $userId =Request::get('userId');
            $accessToken=Request::get('accessToken');
            $senderId=Request::get('senderId');
            $token=Request::get('token');
            $deviceType=Request::get('deviceType');
            $checkUserStatus =User::where('id','=',$userId)->first();
            if(!empty($checkUserStatus)){
            	$userData=User::find($userId);
            	$userData->deviceToken=$token;
            	$userData->deviceType=$deviceType;
            	$userData->save();
            	// if( $deviceType=='Android'){
            	// 	$setMessage='You have a new activity on KarmaCircles.';
            	// 	$pushNotificationStatus=NotificationHelper::androidPushNotification($token,$setMessage);
            	// 	$this->status = $pushNotificationStatus;
            	// 	$this->message = 'Push Notification Message';
            	// }else{
            	// 	$pushNotificationStatus=NotificationHelper::androidPushNotification($token,$setMessage);
            	// 	$this->status = $pushNotificationStatus;
            	// 	$this->message = 'Push Notification Message';
            	// }
            	$this->status = 'Success';
            	$this->message = 'Token has saved successfully.';
    		}else{
            	$this->status = 'Failure';
            	$this->message = 'There is no such user available';
            }
        }
        return Response::json(array(
            'status'=>$this->status,
            'message'=>$this->message
        ));
   	}
   	//save notification setting
   	public function saveNotificationSettings() {
    	$validator = Validator::make(Request::all(), [
                    'userId'=> 'required',
                    'accessToken'=>'required',
                    'notificationFor'=>'required'
                    
        ]);
        if ($validator->fails()) {
            #display error if validation fails                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                       
            $this->status = 'Validation fails';
            $this->message = 'arguments missing';
        } else {
            $userId =Request::get('userId');
            $accessToken=Request::get('accessToken');
            $notificationFor=Request::get('notificationFor');
            $getUser = User::where('id', '=', $userId)->where('site_token','=',$accessToken)->first();
            if(!empty($getUser)){
            	if($notificationFor=='1' || $notificationFor=='2' || $notificationFor=='3'){
            		$this->status = 'Success';
            		$this->message = 'Setting has been saved, you will receive notifications accordingly.';	
            	}else{
            		$this->status = 'Failure';
            		$this->message = 'Please enter right notification type.';
            	}
            }else{
            	$this->status = 'Failure';
            	$this->message = 'There is no such user available';
            }
            	
            	
    	}
        return Response::json(array(
            'status'=>$this->status,
            'message'=>$this->message
        ));
   	}

   



}

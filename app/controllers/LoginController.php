<?php

class LoginController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	
	/*Linkedin Login Using OAuth*/
	public function loginWithLinkedin(){
        // get data from input
        $code = Input::get( 'code' );
        $linkedinService = OAuth::consumer('Linkedin');
        
        if( !empty( $code ) )
        { 
            // This was a callback request from linkedin, get the token
            $token = $linkedinService->requestAccessToken( $code );
            //$token="7ee85959-1809-4899-a10d-626740702f5d";
            // Send a request with it. Please note that XML is the default format.
            $result = json_decode($linkedinService->request('/people/~:(id,first-name,last-name,skills,headline,summary,industry,member-url-resources,picture-urls::(original),location,public-profile-url,email-address,site-standard-profile-request)?format=json'), true);
 			
 			$linkedinid = $result['id'];
			
 			//echo "<pre>";print_r($token->getAccessToken());echo "</pre>";
			//echo $token->accessToken;die;
			//die();
			if(!empty($token)){      
				//$user = User::where('linkedinid', '=', $linkedinid)->first();
				$user = User::where('linkedinid', '=', $linkedinid)->first();
				if(!empty($user)){

					$user_data			= $user->toArray();
					$user_id 			= 	$user_data['id'];
					$user_info 			= User::find($user_id);
					$user = User::find($user_id);
					$user->token 		=  $token->getAccessToken();
					$user->save();
					$CurrentDate = KarmaHelper::currentDate();
					$profileupdatedate = $user_info->profileupdatedate;
					$diffDate = KarmaHelper::dateDiff($CurrentDate,$profileupdatedate);
					$diffDate = $diffDate->days * 24 + $diffDate->h; 
					$refreshTime = Adminoption::where('option_name','=','connection refresh time')->first();
					//echo "<pre>";print_r($refreshTime);echo "</pre>";die();
					$InsConnection = KarmaHelper::updateUserProfile($user,$result);
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
					//echo "<pre>";print_r($user);echo "</pre>";die();
					if (Auth::loginUsingId($user_id)){
						if($user['registrationstatus']=='0'){
							return Redirect::to('register');
						}
						else{
							
							return Redirect::to('dashboard');
						}	                 
					}
					else{
						return Redirect::route('/');
					}
				}
				else{
				    if(!isset($result['publicProfileUrl']) || ($result['publicProfileUrl'] == '')){
						$publicProfileUrl = $result['siteStandardProfileRequest']['url'];
					}
					else{
						$publicProfileUrl = $result['publicProfileUrl'];
					}
					$user = new User;
					$user->fname 				= $result['firstName'];
					$user->lname 				= @$result['lastName'];
					$user->email 				= $result['emailAddress'];
					$user->piclink 				= @$result['pictureUrls']['values'][0];
					$user->linkedinid 			= $result['id']; 
					$user->summary		 		= @$result['summary'];
					$user->location 			= @$result['location']['name'];
					$user->industry 			= @$result['industry'];
					$user->headline 			= @$result['headline'];
					$user->linkedinurl 			= @$publicProfileUrl;
					$user->token 				= $token->getAccessToken();
					$user->profileupdatedate 	= date('Y-m-d H:i:s');
					$user->save();

					$user 		= $user;
					$user_id 	= $user->id;
					$InsTag 	=    KarmaHelper::insertUsertag($user,$result); 
					Queue::push('MessageSender@newUserEmail',array('user_id'=> $user_id));
					$InsConnection = KarmaHelper::insertUserConnection($user);
					if (Auth::loginUsingId($user_id)){
						return Redirect::to('dashboard');
					}
					else{
						return Redirect::route('/');
					}
				}

			}
		}// if not ask for permission first
        else{
            // get linkedinService authorization
            $url = $linkedinService->getAuthorizationUri(array('state'=>'DCEEFWF45453sdffef424'));
            // return to linkedin login url
            return Redirect::to( (string)$url );
        }
    }

 	/*Function for checking the user current status*/
    public function statusCheck(){
    	$CurrentUser = Auth::User();
			$userstatus = auth::user()->userstatus;
			if( $userstatus == 'pending' || $userstatus == 'fetching connection' || $userstatus == 'ready for approval' ){	
				return View::make('error/pending',array('CurrentUser' => $CurrentUser));				
			}
			elseif($userstatus == 'hidden' ){
				return View::make('error/hidden',array('CurrentUser' => $CurrentUser));
			}
			else{
				return Redirect::to('/dashboard');
			}
    }

    /*Function for Login register page*/
    public function register(){
		$CurrentUser= Auth::user();
		/*Process for filtering the name*/
			$fname = $CurrentUser->fname;
			$lname = $CurrentUser->lname;
			$fname_without = preg_replace('/[^a-z\d ]/i', '',$fname);
			$lname_without = preg_replace('/[^a-z\d ]/i', '',$lname);
			$fname_final = explode(' ',trim($fname_without));
			$lname_final = explode(' ',trim($lname_without));
			$fname_final = $fname_final[0];
			$lname_final = $lname_final[0];
			//echo"<pre>";print_r($CurrentUser);echo"</pre>";
			if($CurrentUser->userstatus == 'pending'){
				Queue::push('UpdateUser@UpdateUserInfo', array('id' => $CurrentUser->id));
				$CurrentUser->userstatus = 'fetching connection';
				$CurrentUser->save();			 	
			}	  
		return View::make('register',array('CurrentUser' => $CurrentUser,'fname_final'=>$fname_final,'lname_final'=>$lname_final));
	}
	
	/*User logout function*/
	public function logout(){
		Auth::logout();
		return Redirect::to('/');
	}	
}

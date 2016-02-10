<?php

class UserController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */

	public function __construct(User $user){
		$this->user = $user;
	}
	
	/*Inserting User registration details into database*/
	public function saveRegisterInfo(){
		if (!$this->user->isValid(Input::all())) {
		// redirect our user back to the form with the errors from the validator
		return Redirect::back()->withInput()->withErrors($this->user->errors);

	} else {
		$shareOnLinkedin = '';
		if(!empty(Input::get('shareOnLinkedin')))	$shareOnLinkedin = Input::get('shareOnLinkedin');
		$id = Auth::User()->id;
		$termsofuse = Auth::User()->termsofuse;
		$userstatus = Auth::User()->userstatus;
		$linkedinid = Auth::User()->linkedinid;
		$fname = Input::get('fname');
		$lname = Input::get('lname'); 
		if(!empty($fname)){
			$connection = Connection::where('networkid','=',$linkedinid)->first();
			$connection->fname = strip_tags($fname);
			$connection->lname = strip_tags($lname);
			$connection->save();
		}
		$user = $this->user->find($id);
		if(!empty($fname)){
			$user->fname = strip_tags($fname);
			$user->lname = strip_tags($lname);
		}
		$user->causesupported = strip_tags(Input::get('causesupported'));
		$user->urlcause = strip_tags(Input::get('urlcause'));
		$user->donationtypeforcause = strip_tags(Input::get('donationtypeforcause'));
		$user->noofmeetingspm = 2;
		//$user->comments = strip_tags(Input::get('comments'));
		 if($shareOnLinkedin == '1' ){
				Queue::push('MessageSender@shareNewRegisterOnLinkedin', array('type' =>'9','id' => $id)); 
			} 

		//die($termsofuse);  
 
		if($termsofuse != '1')		{
			$user->termsofuse = '1';	
			$user->userstatus  = 'ready for approval'; 
			/* // CURL request to update newly registered user on EMAIL TOOL
			$ch = curl_init(); //create curl resource
			curl_setopt($ch, CURLOPT_URL, "http://54.200.33.219/add_user/".$user->email);  
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_exec($ch); // pass url in browser 
			curl_close($ch); // close curl */  
		}		
 
		if($user->save()){
			$usergroup = new Usersgroup;
			$usergroup->user_id = $id;
			$usergroup->group_id = '1';
			$usergroup->save();
		}
		if($termsofuse == '1'){
			return Redirect::to('profile/'.strtolower(Auth::User()->fname.'-'.Auth::User()->lname).'/'.$id);
		}
		else{
			return Redirect::to('/dashboard');
		}
		
	}
	}
	public function saveCauseInfo(){
		$id = Auth::User()->id;
		$user =  User::find($id);
		$user->causesupported = strip_tags(Input::get('causesupported'));
		$user->urlcause = strip_tags(Input::get('urlcause'));
		$user->donationtypeforcause = strip_tags(Input::get('donationtypeforcause'));
		$user->save();
		return Redirect::to('profile/'.strtolower(Auth::User()->fname.'-'.Auth::User()->lname).'/'.$id);
			
	}
	public function saveAdviceInfo(){
		//echo "<pre>";print_r($_POST);echo "</pre>";die();
		$id = Auth::User()->id;
		$user = User::find($id);
		$user->noofmeetingspm = strip_tags(Input::get('noofmeetingspm'));
		$user->comments = strip_tags(Input::get('comments'));
		$user->save();
		return Redirect::to('profile/'.strtolower(Auth::User()->fname.'-'.Auth::User()->lname).'/'.$id);
	}
	public function profile($profilename,$id){
		$profileSelf = 0;
		$CurrentUserDetail = ""; 
		$profileUserDetail 	= User::find($id);
		$profileUserDetailCount = DB::table('users') ->count();
		//echo '<pre>';print_r($profileUserDetailCount);die;
		$first_name=$profileUserDetail->fname;
		$last_name=$profileUserDetail->lname;
		$name=$first_name.' '.$last_name;
		$userId = Auth::User();
		if(!empty($userId)){
			$Receiver=$userId->id;	
		}
		if(Auth::check()){
		    $checkMeetingStatus = DB::select(DB::raw("select user_id_receiver,user_id_giver,id,status from requests where (user_id_receiver=".$Receiver." OR user_id_receiver=".$id.") AND (user_id_giver=".$Receiver." OR user_id_giver=".$id." ) AND status NOT IN ('completed','archived','cancelled') order by created_at DESC limit 1"));  
		}
		if(empty($profileUserDetail)){
			if(Auth::check()) return Redirect::to('404');
			else return Redirect::to('/');
		}
		$profileNameCheck = $profileUserDetail->fname.'-'.$profileUserDetail->lname;  
		if($profileUserDetail == '' || strtolower($profilename) != strtolower($profileNameCheck) || $profileUserDetail->termsofuse == '0' || $profileUserDetail->userstatus != 'approved'){
			if(Auth::check()) return Redirect::to('404');
			else return Redirect::to('/');
		}
		
		$profileUserSkills 	= $profileUserDetail->Tags;
		if(Auth::check()){
			$CurrentUserDetail 	= Auth::User();
			$CurrentUserId = $CurrentUserDetail->id;
			
			if($CurrentUserId == $profileUserDetail['id']){
				$profileSelf = "1";
			}
			else{
				$profileSelf = "0";	
			}
		}
		$users_group = User::find($id)->Groups;
		//Karma notes block
		$karmaTrailUser = $karmaReceivedUser = $karmaSentUser  = '';
		$start =0; $perpage=15;  
		$karmaTrail = KarmaHelper::getKarmaTrail($id,$start,$perpage); 
		//echo "<pre>";print_r($users_group->toArray());echo "</pre>";die;
		/*$karmaReceived = KarmaHelper::getReceivedRequestKarmaNotes($id);
		$karmaSent = KarmaHelper::getSentKarmaNotes($id);*/
		if(count($karmaTrail) > 0){
			foreach ($karmaTrail as $trail) {
				$userSkills = "";
				$karma['user_id_receiver'] = User::find($trail->user_id_receiver)->toArray();
				if(!empty($trail->user_id_giver)){
					$karma['user_id_giver'] = User::find($trail->user_id_giver)->toArray();
				}
				else{
					//echo '<pre>';print_r($trail->connection_id_giver);die;
					$karma['user_id_giver'] = Connection::find($trail->connection_id_giver)->toArray();
				}
				if($id == $trail->user_id_receiver){
					$karma['status'] = $trail->statusreceiver;	
				}else{
					$karma['status'] = $trail->statusgiver;	
				}

				$karma['piclink'] = $trail->piclink;
				$karma['karmaNotes'] = $trail->details;
				
				if(!empty($trail->skills)){
					$userSkills = KarmaHelper::getSkillsname($trail->skills);
				}
				$karma['skills'] = $userSkills;
				$karma['req_id'] = $trail->req_id;
				$karma['meetingdatetime'] = date('F d, Y', strtotime($trail->meetingdatetime));
				$karma['created_at'] = date('F d, Y', strtotime($trail->created_at));
				$karmaTrailUser[] = $karma;
			}
		}
//echo "<pre>";print_r($karmaTrailUser);echo "</pre>";die;

		$karmaReceived = $profileUserDetail->KarmanoteReceiver()
							->orderBy('karmanotes.created_at','desc')
							->skip($start)->take($perpage)
							->get();
		//echo "<pre>";print_r($karmaReceived->toArray());echo "</pre>";die;
		$karmaSent  = $profileUserDetail->KarmanoteGiver()
						->orderBy('karmanotes.created_at','desc')
						->skip($start)->take($perpage)
						->get();
		if(!empty($karmaReceived)){
			foreach ($karmaReceived->toArray() as $received) {
				$userReceivedSkills = "";
				$karmareceived['user_id_receiver'] = User::find($received['user_idreceiver'])->toArray();
				if(!empty($received['user_id_giver']))
					$karmareceived['user_id_giver'] = User::find($received['user_idgiver'])->toArray();
				else
					$karmareceived['user_id_giver'] = Connection::find($received['connection_idgiver'])->toArray();
				
				$karmareceived['karmaNotes'] = $received['details'];
				if(!empty($received['skills'])){
					$userReceivedSkills = KarmaHelper::getSkillsname($received['skills']);
				}
				$karmareceived['skills'] = $userReceivedSkills;
				$karmareceived['status'] = $received['statusgiver'];
				$karmareceived['req_id'] = $received['req_id'];
				$karmareceived['req_detail'] = Meetingrequest::find($received['req_id']);
				$karmareceived['created_at'] = date('F d, Y', strtotime($received['created_at']));
				$karmaReceivedUser[] = $karmareceived;
			}
		}
		//echo "<pre>";print_r($karmaSent->toArray());echo "</pre>";die;
		if(!empty($karmaSent)){
			foreach ($karmaSent->toArray() as $sent) {
				$userSentSkills = "";
				$karmasent['user_id_receiver'] = User::find($sent['user_idreceiver'])->toArray();
				if(!empty($sent['user_idgiver']))
					$karmasent['user_id_giver'] = User::find($sent['user_idgiver'])->toArray();
				else
					$karmasent['user_id_giver'] = Connection::find($sent['connection_idgiver'])->toArray();
				
				$karmasent['karmaNotes'] = $sent['details'];
				if(!empty($sent['skills'])){
					
					$userSentSkills = KarmaHelper::getSkillsname($sent['skills']);
				}
				$karmasent['skills'] = $userSentSkills;
				$karmasent['req_id'] = $sent['req_id'];
				$karmasent['req_detail'] = Meetingrequest::find($sent['req_id'])->toArray();
				$karmasent['status'] = $sent['statusreceiver'];
				
				$karmasent['created_at'] = date('F d, Y', strtotime($sent['created_at']));
				$karmaSentUser[] = $karmasent;
			}
		}
		//function to check how much meeting are happend in one week.
		// if(!empty($Receiver)){
		// 	$MeetingRequestPending = KarmaHelper::karmaMeetingPendingCount($Receiver,$id);	
		// }else{
			$MeetingRequestPending=0;
		//}

		
		if(!empty($userId)){
			$checkMeetingStatus = KarmaHelper::getMeetingStatusForWeb($Receiver,$id);
		}else{
			$checkMeetingStatus=array();
			$receiverData=array();
			$giverData=array();
		}
		//echo '<pre>';print_r($checkMeetingStatus);die;
		//latest karma Query
		if(!empty($checkMeetingStatus)){
			$receiverData=User::find($checkMeetingStatus['receiverId']);
			$giverData=User::find($checkMeetingStatus['giverId']);
		}
		$getKarmaQuery = DB::table('questions')->select('questions.question_url','questions.subject','questions.id')->where('questions.user_id',$id)->where('questions.queryStatus','=','open')->orderBy('questions.created_at','desc')->first();
		//echo '<pre>';print_r($getKarmaQuery);die;
		return View::make 
				('profile',array('meetingStatus'=>$checkMeetingStatus,'receiverData'=>$receiverData,'giverData'=>$giverData, 'checkMeetingStatus'=>$checkMeetingStatus,'getKarmaQuery' => $getKarmaQuery, 'pageTitle' => $name.' Profile | KarmaCircles','pageDescription'=>$name.' is on KarmaCircles. Join KarmaCircles to request a meeting from '.$name.'  for free. KarmaCircles makes it easy for people to give and receive help for free.', 'id'=>$id,'CurrentUser' => $CurrentUserDetail,'MeetingRequestPending' => $MeetingRequestPending,'ProfileUserSkills'=>$profileUserSkills,
						'profileUserDetail' => $profileUserDetail,'profileSelf'=>$profileSelf, 'karmaTrail'=>$karmaTrailUser,
						 'karmaReceived'=>$karmaReceivedUser, 'karmaSent'=>$karmaSentUser, 'countTrail'=>0, 'countReceived'=>0,
						  'countSent'=>0,'profielURL'=> '','karmascore'=>'','ProfileUserGroup'=>$users_group))
				->with('title', "Welcome to profile")
				->with('description', "Profile Description")
				->with('desc', "Profile Description");

	}

	public function loadmoreProfile(){
		//Karma notes block
		$karmaTrailUser = $karmaReceivedUser = $karmaSentUser  = '';
		$profileSelf = 0;
		$CurrentUserDetail = "";
		$CurrentUserId =0; 
		
		if(!empty($_REQUEST))
		{
			$start=$_REQUEST['hitcount']+1; 
			$action=$_REQUEST['action']; 
			$id=$_REQUEST['userProfile'];  
			$perpage = 10;  
			$profileUserDetail 	= User::find($id);
			if(Auth::check()){
				$CurrentUserDetail 	= Auth::User();
				$CurrentUserId = $CurrentUserDetail->id;
				
				if($CurrentUserId == $profileUserDetail['id'])
				{
					$profileSelf = "1";
				} 
				else{
					$profileSelf = "0";	
				}
			}
		
			if($action == 'KarmaTrail'){
				$karmaTrail = KarmaHelper::getKarmaTrail($id,$start,$perpage);
				if(count($karmaTrail) > 0){
					foreach ($karmaTrail as $trail) {
						$userSkills = "";
						$karma['user_id_receiver'] = User::find($trail->user_id_receiver)->toArray();
						if(!empty($trail->user_id_giver)){
							$karma['user_id_giver'] = User::find($trail->user_id_giver)->toArray();
						}
						else{
							$karma['user_id_giver'] = Connection::find($trail->connection_id_giver)->toArray();
						}
						if($id == $trail->user_id_receiver){
							$karma['status'] = $trail->statusreceiver;	
						}else{
							$karma['status'] = $trail->statusgiver;	
						}

						$karma['piclink'] = $trail->piclink;
						$karma['karmaNotes'] = $trail->details;
						
						if(!empty($trail->skills)){
							$userSkills = KarmaHelper::getSkillsname($trail->skills);
						}
						$karma['skills'] = $userSkills;
						$karma['req_id'] = $trail->req_id;
						$karma['meetingdatetime'] = date('F d, Y', strtotime($trail->meetingdatetime));
						$karma['created_at'] = date('F d, Y', strtotime($trail->created_at));
						$karmaTrailUser[] = $karma;
					}	
				}		
			}
	//echo "<pre>";print_r($karmaTrailUser);echo "</pre>";die;
			if($action == 'KarmaNotesReceived'){
				$karmaReceived = $profileUserDetail->KarmanoteReceiver()
								->orderBy('karmanotes.created_at','desc')
								->skip($start)->take($perpage)
								->get();
				if(!empty($karmaReceived)){
					foreach ($karmaReceived->toArray() as $received) {
						$userReceivedSkills = "";
						$karmareceived['user_id_receiver'] = User::find($received['user_idreceiver'])->toArray();
						if(!empty($received['user_id_giver']))
							$karmareceived['user_id_giver'] = User::find($received['user_idgiver'])->toArray();
						else
							$karmareceived['user_id_giver'] = Connection::find($received['connection_idgiver'])->toArray();
						
						$karmareceived['karmaNotes'] = $received['details'];
						if(!empty($received['skills'])){
							$userReceivedSkills = KarmaHelper::getSkillsname($received['skills']);
						}
						$karmareceived['skills'] = $userReceivedSkills;
						$karmareceived['status'] = $received['statusgiver'];
						$karmareceived['req_id'] = $received['req_id'];
						$karmareceived['req_detail'] = Meetingrequest::find($received['req_id']);
						$karmareceived['created_at'] = date('F d, Y', strtotime($received['created_at']));
						$karmaReceivedUser[] = $karmareceived;
					}
				}

			}
			//echo "<pre>";print_r($karmaReceived->toArray());echo "</pre>";die;
			if($action == 'KarmaNotesSent'){ 
				$karmaSent  = $profileUserDetail->KarmanoteGiver()
								->orderBy('karmanotes.created_at','desc')
								->skip($start)->take($perpage)
								->get();
				
				//echo "<pre>";print_r($karmaSent->toArray());echo "</pre>";die;
				if(!empty($karmaSent)){
					foreach ($karmaSent->toArray() as $sent) {
						$userSentSkills = "";
						$karmasent['user_id_receiver'] = User::find($sent['user_idreceiver'])->toArray();
						if(!empty($sent['user_idgiver']))
							$karmasent['user_id_giver'] = User::find($sent['user_idgiver'])->toArray();
						else
							$karmasent['user_id_giver'] = Connection::find($sent['connection_idgiver'])->toArray();
						
						$karmasent['karmaNotes'] = $sent['details'];
						if(!empty($sent['skills'])){
							
							$userSentSkills = KarmaHelper::getSkillsname($sent['skills']);
						}
						$karmasent['skills'] = $userSentSkills;
						$karmasent['req_id'] = $sent['req_id'];
						$karmasent['req_detail'] = Meetingrequest::find($sent['req_id'])->toArray();
						$karmasent['status'] = $sent['statusreceiver'];
						
						$karmasent['created_at'] = date('F d, Y', strtotime($sent['created_at']));
						$karmaSentUser[] = $karmasent;
					}
				}
			}

			return View::make('ajax_loadmoreProfile',array('id'=>$id,'CurrentUserDetail'=>$CurrentUserDetail,
						'profileSelf'=>$profileSelf, 'karmaTrail'=>$karmaTrailUser,
						'profileUserDetail'=>$profileUserDetail,'countTrail'=>0, 'countReceived'=>0,
						  'countSent'=>0,'profielURL'=> '','karmascore'=>'',
						 'karmaReceived'=>$karmaReceivedUser, 'karmaSent'=>$karmaSentUser));
		}

	}

	public function savegroupsetting(){
	//echo "<pre>";print_r($_POST);echo "</pre>";die;
	$Samegroup = '0';
	$group_ids 			=	Input::get('Groups');
	/*$meeting_setting 	= Input::get('meeting_setting');
	if(empty($meeting_setting)){
		$meeting_setting = "accept from all";
	}
	else{
		$meeting_setting = "accept from group only";
	}*/
	//echo "<pre>";print_r($group_ids);echo "</pre>";die;
	$user_id = Auth::User()->id;
    $UsersgroupCount = Usersgroup::where('user_id','=',$user_id)->count();
    if($UsersgroupCount > '0'){   	 
    	    $UsersgroupCount = Usersgroup::where('user_id','=',$user_id)->delete();
    }
    	if(!empty($group_ids)){
		 	 foreach ($group_ids as $key => $value) {
	    	 	$usergroup = new Usersgroup;
		    	$usergroup->user_id = $user_id;
		    	$usergroup->group_id = $value;
		    	$usergroup->save();
	    	 }
	    	/*$user = User::find($user_id);
			$user->meeting_setting = $meeting_setting;
			$user->save();*/ 
    	 }
    	 /*else{
    	 	$user = User::find($user_id);
			$user->meeting_setting = "accept from all";
			$user->save();
    	 }*/
		return Redirect::to('profile/'.strtolower(Auth::User()->fname.'-'.Auth::User()->lname).'/'.$user_id);
    /*if($Usersgroup == '0'){
    	if($group_id != '-1'){
	    	$usergroup = new Usersgroup;
	    	$usergroup->user_id = $user_id;
	    	$usergroup->group_id = $group_id;
	    	$usergroup->save();
	    	$user = User::find($user_id);
	    	$user->userstatus = 'ready for approval';
	    	$user->meeting_setting = $meeting_setting;
	    	$user->save();
	    	return Redirect::to('/dashboard');
    	}
    }
    elseif($Usersgroup == '1'){
    	if($group_id == '-1'){
    		$Usersgroup = Usersgroup::where('user_id','=',$user_id)->first();
	    	$Usersgroup = Usersgroup::find($Usersgroup->id)->delete();
	    	$user = User::find($user_id);
	    	$user->meeting_setting = "accept from all";
	    	$user->save();
    	}else{
    		$Usersgroup = Usersgroup::where('user_id','=',$user_id)->first();
    		if($Usersgroup->group_id == $group_id){
    			$Samegroup = 1; 
    		}
	    	$Usersgroup = Usersgroup::find($Usersgroup->id);
	    	$Usersgroup->group_id = $group_id;
	    	$Usersgroup->save();
	    	if($Samegroup != '1'){
		    	$user = User::find($user_id);
		    	$user->userstatus = 'ready for approval';
		    	$user->meeting_setting = $meeting_setting;
		    	$user->save();
	    	}
	    	else{
	    		$user = User::find($user_id);
				$user->meeting_setting = $meeting_setting;
				$user->save();
	    	}

	    	return Redirect::to('/dashboard');
    	}
    	
    }*/ 
    return Redirect::to('profile/'.strtolower(Auth::User()->fname.'-'.Auth::User()->lname).'/'.$user_id);
}


	public function updateCause(){
		$CurrentUser= Auth::user();
		return View::make('updateCause',array('CurrentUser' => $CurrentUser));
	}
	public function updateAdvice(){
		$CurrentUser= Auth::user();
		return View::make('updateAdvice',array('CurrentUser' => $CurrentUser));
	}
	public function updateGroup(){
		$CurrentUser= Auth::user();
		$UsersgroupCount = Auth::user()->Groups()->count();
		$Usersgroups = Auth::user()->Groups;
		//	echo "<pre>";print_r($Usersgroup[0]->name);echo "</pre>";die();
		$groups = DB::select(DB::raw('SELECT * FROM `groups`'));
		return View::make('updateGroup',array('CurrentUser' => $CurrentUser,'groups'=>$groups,'Usersgroups'=>$Usersgroups,'UsersgroupCount'=>$UsersgroupCount));
	}
	public function groupError(){
		$CurrentUser= Auth::user();
		return View::make('error.group',array('CurrentUser' => $CurrentUser));
	}
	public function pendingArchivedError($giver_id,$meetingId){
		$CurrentUser= Auth::user();
		$giverdetail = User::find($giver_id);
		$url = '/meeting/'.$CurrentUser->fname.'-'.$CurrentUser->lname.'-'.$giverdetail->fname.'-'.$giverdetail->lname.'/'.$meetingId;
		$name = $giverdetail->fname.' '.$giverdetail->lname;
		return View::make('error.request-pendingArchived',array('CurrentUser' => $CurrentUser,'url'=>$url,'name'=>$name));
	}
	public function acceptedError($giver_id,$meetingId){ 
		$CurrentUser= Auth::user();
		$giverdetail = User::find($giver_id);
		$url = '/meeting/'.$CurrentUser->fname.'-'.$CurrentUser->lname.'-'.$giverdetail->fname.'-'.$giverdetail->lname.'/'.$meetingId;
		$name = $giverdetail->fname.' '.$giverdetail->lname;
		return View::make('error.request-accepted',array('CurrentUser' => $CurrentUser,'url'=>$url,'name'=>$name));
	}
	public function messageOnLinkedin(){
		$CurrentUser= Auth::user();
		return View::make('error.linkedin-limitreach',array('CurrentUser' => $CurrentUser));
	}
	public function UpdateExistingUserGroup(){
		$users = DB::table('users')->select('id')->get();

		$groups_query = DB::select(DB::raw("select id from groups" ));
		
		$group=array();
		foreach ($groups_query as $group_value) {
			$group[]=$group_value->id;
		}

		$userid=0;
		$group_set= array();
		foreach($users as $val){
			$searchquery = DB::select(DB::raw("select user_id,group_id  from users_groups where users_groups.user_id= ".$val->id));	

			$usergroup_id=array();
			//echo"<pre>";print_r($searchquery);echo"</pre>";
			foreach($searchquery as $vals)
			{
				$usergroup_id[]=$vals->group_id;
			}

				$set =in_array(1, $usergroup_id);
				if($set == ""){
					$usergroup = new Usersgroup;
			    	$usergroup->user_id = $val->id;
			    	$usergroup->group_id = 1;
			    	$usergroup->save();
				}
				//echo"<pre>==========="; print_r($set);
	}


 
}
	/*public function SendInvitetoNonKC(){ 
		$CurrentUser= Auth::user();
		

		$giver_email = '';		
		if(!empty(Input::get('giver_email'))) $giver_email = Input::get('giver_email');
		$user_id_receiver 	= Input::get('user_id_receiver');
		$user_id_giver 		= Input::get('user_id_giver');
		$subject 			= strip_tags(Input::get('subject'));
		$notes 				= strip_tags(Input::get('notes'));
		$checkLimit = KarmaHelper::CheckUserLinkedMgsLimit();
		if($checkLimit == 1){
			Queue::push('MessageSender@InvitationToNonKc', array('type' =>'17','user_id_giver' => $user_id_giver,'user_id_receiver' => $user_id_receiver,'giver_email'=>$giver_email,'subject'=>$subject,'notes'=>$notes));
			return Redirect::to('/dashboard');
		}
		else{
			return Redirect::to('meesageOnLinkedin/limitreached'); 
		}

	}*/ 

	public function SendInvitationKC(){
		$CurrentUser= Auth::user();
		$giver_email = ''; 
		if(!empty(Input::get('giver_email'))) $giver_email = Input::get('giver_email');
		$user_id_receiver 	= Input::get('user_id_receiver');
		$user_id_giver 		= Input::get('user_id_giver');
		$notes 				= strip_tags(Input::get('notes'));
		$type =17;
		MessageHelper::InvitationToNonKcUser($type,$user_id_giver,$user_id_receiver,$notes,$giver_email);
		return Redirect::to('/dashboard');
	}

	public function sitemap(){
		$UserData = DB::table('users')->select('id','fname','lname')->get();
		$QueryData = DB::table('questions')->get();
		$GroupData = DB::table('groups')->select('id','name')->get();
		$site_url=URL::to('/');
		$static_pages[]=$site_url.'/FAQs';
		$static_pages[]=$site_url.'/how-it-works';
		$static_pages[]=$site_url.'/about';
		$static_pages[]=$site_url.'/terms';
		$static_pages[]=$site_url.'/groupsAll';
		$letters = range('a', 'z');
		foreach ($letters as $value_letter) {
			$site_url=URL::to('/');
			$static_pages[]=$site_url.'/directory/skills-'.$value_letter;
		}
		sort($static_pages);
		foreach ($QueryData as $value_query) {
			$site_url=URL::to('/');
			$id=$value_query->id;
			$query_subject=$value_query->question_url;
			$dynamic_name=$query_subject.'/'.$id;
			$public_query_url[]=$site_url.'/question/'.$dynamic_name;
		}
		sort($public_query_url);
		foreach ($UserData as $value_user) {
			$site_url=URL::to('/');
			$id=$value_user->id;
			$fname=$value_user->fname;
			$lname=$value_user->lname;
			$dynamic_name=$fname.'-'.$lname.'/'.$id;
			$public_profile_url[]=$site_url.'/profile/'.$dynamic_name;
		}
		sort($public_profile_url);
		foreach ($GroupData as $value_group) {
			$site_url=URL::to('/');
			$id=$value_group->id;
			$group_name=strtolower(trim(str_replace(' ', '-', $value_group->name)));
			$group_dynamic_name=$group_name.'/'.$id;
			$public_group_url[]=$site_url.'/groups/'.$group_dynamic_name;
		}
		sort($public_group_url);
		$KarmaData = DB::table('karmanotes')->select('req_id','user_idreceiver','user_idgiver')->get();
		foreach ($KarmaData as $value_karma) {
			$site_url=URL::to('/');
			$id=$value_karma->req_id;
		 	$user_idreceiver=$value_karma->user_idreceiver;
		 	$user_idgiver=$value_karma->user_idgiver;
		 	if(!empty($user_idreceiver)){
		 		$KarmaName_receiver = DB::table('users')->where('id','=',$user_idreceiver)->select('fname','lname')->first();
		 	
		 	  $fname=$KarmaName_receiver->fname;
		 	  $lname=$KarmaName_receiver->lname;
		 	  $karmaNote_receiver_name=$fname.'-'.$lname;
		 	}
		 	if(!empty($user_idgiver)){
		 		$KarmaName_giver = DB::table('users')->where('id','=',$user_idgiver)->select('fname','lname')->first();
				$fname_giver=$KarmaName_giver->fname;
		 	 	$lname_giver=$KarmaName_giver->lname;
		 	  	$karmaNote_giver_name=$fname_giver.'-'.$lname_giver;// echo '<pre>';echo 
		 	  //echo '<pre>';print_r($karmaNote_giver_name);exit;
		 	  	if(!empty($user_idreceiver)){
		 	  		$dynamic_meeting=$karmaNote_receiver_name.'-'.$karmaNote_giver_name.'/'.$id;
		 	  	}
				$meeting_url[]=$site_url.'/meeting/'.$dynamic_meeting;
				sort($meeting_url);
			}
			
		}
			$getSitemapUrl= array_merge($static_pages,$public_profile_url,$public_group_url,$meeting_url,$public_query_url);
			foreach ($getSitemapUrl as $key => $value) {
			 	$getSitemapUrlresult[]=htmlspecialchars("<sitemap><url>".$value."</url></sitemap>", ENT_QUOTES);
			}
			$getSitemapUrlresult=implode("\n", $getSitemapUrlresult);
			$getSitemapUrlresult=htmlspecialchars_decode('<sitemapset>'.$getSitemapUrlresult.'</sitemapset>');

			$file = public_path(). "/test.xml";  // <- Replace with the path to your .xml file
     			$options = array('ftp' => array('overwrite' => true)); 
				$stream = stream_context_create($options); 
				file_put_contents($file, $getSitemapUrlresult, 0, $stream);
    }

    public function updateUser(){
    	$CurrentUserDetail 	= Auth::User();
		$CurrentUserId = $CurrentUserDetail->id;
    	$userSkill = Input::get('skillTags');
    	//echo '<pre>';print_r($userSkill);die;
    	if(!empty($userSkill)){
    		foreach ($userSkill as $value) {
	    		$userData = new Userstag;
	    		$userData->tag_id=$value;
	    		$userData->user_id=$CurrentUserId;
	    		$userData->save();
	    	}	
    	}
    	
    	return Redirect::to('profile/'.strtolower(Auth::User()->fname.'-'.Auth::User()->lname).'/'.$CurrentUserId);
		
	} 
	public static function storeKarmacirclesRecord(){
		$user_info = Auth::User();
		if(!empty($user_info))
		$user_circle_giver = DB::table('karmanotes')->select('user_idgiver')->where('karmanotes.user_idreceiver','=',$user_info->id)->get();
		$user_circle_receiver = DB::table('karmanotes')->select('user_idreceiver')->where('karmanotes.user_idgiver','=',$user_info->id)->get();
		echo '<pre>';print_r($user_circle_giver);die;
		$karmaCircle = new Karmacircle;
		if(!empty($user_circle_giver)){
			$karmaCircle ->givers 						= implode(',', $user_circle_giver);
		}
		if(!empty($user_circle_receiver)){
			$karmaCircle ->takers 						= implode(',', $user_circle_receiver);
		}
		
		
		 
	}
	//Function to make karmaTrail of particular user.
	public static function otherProfileTrail(){
		$karmacircleFeed = Karmafeed::select('message_type As type','id_type','receiver_id','giver_id','updated_at')->get();
		foreach ($karmacircleFeed as $karmaFeed) {
			$receiverId=$karmaFeed->receiver_id;
			$giverId=$karmaFeed->giver_id;
			$karmaFeedMessage=$karmaFeed->type;
			$karmaFeedId=$karmaFeed->id_type;
			$karmaFeedDate=$karmaFeed->updated_at;
			if(!empty($receiverId)){
				$receiverData=User::where('id','=',$receiverId)->select('fname As receiverFirstName','lname As receiverLastName','piclink As receiverPic')->first();	
			}
			if(!empty($giverId)){
				$giverData=User::where('id','=',$giverId)->select('fname As giverFirstName','lname As giverLastName','piclink As giverPic')->first();	
				
			}
			if($karmaFeedMessage=='KarmaNote'){
				$getKarmaNoteDetails = Karmanote::where('id','=',$karmaFeedId)
							->select(array('karmanotes.details As description'))
				            ->first();
				$karmaNoteFeed[] = array_merge($karmaFeed->toArray(),$getKarmaNoteDetails->toArray(), $giverData->toArray(),$receiverData->toArray());
			}else{
				$karmacircleFeedCount = DB::table('users_karmafeeds')->where('message_type','=','KarmaNote')->count();
				if($karmacircleFeedCount < 1){
					$karmaNoteFeed=array();	
				}
			}
				
			if($karmaFeedMessage=='Group'){
				$getGroupDetails = Group::where('id','=',$karmaFeedId)
							->select(array('groups.name As name','groups.description As description'))
				            ->first();
				$groupFeed[] = array_merge($karmaFeed->toArray(),$getGroupDetails->toArray(),$receiverData->toArray());
			}else{
				$karmacircleGroupCount = DB::table('users_karmafeeds')->where('message_type','=','Group')->count();
				if($karmacircleGroupCount < 1){
					$groupFeed=array();	
				}
			}
			if($karmaFeedMessage=='KarmaQuery'){
				$getQueryDetails = Question::where('id','=',$karmaFeedId)
							->select(array('questions.queryStatus As queryStatus','questions.description As description'))
				            ->first();
				 
				$queryFeed[] = array_merge($karmaFeed->toArray(),$getQueryDetails->toArray(),$receiverData->toArray());
			}else{
				$karmacircleQueryCount = DB::table('users_karmafeeds')->where('message_type','=','Question')->count();
				if($karmacircleQueryCount < 1){
					$queryFeed=array();	
				}
			}
			if($karmaFeedMessage=='OfferHelpTo'){
							
							$getOfferHelpDetails = Question::where('id','=',$karmaFeedId)
										->select(array('questions.queryStatus As queryStatus','questions.description As description'))
							            ->first();
							  	$getOfferHelpCount=Questionwillingtohelp::has('id')->where('user_id','=',$receiverId)->where('question_id','=',$karmaFeedId)->count();
								$offerHelpToFeed[] = array_merge($karmaFeed->toArray(),$getOfferHelpDetails->toArray(),$receiverData->toArray(),$giverData->toArray());
							
			}else{
				$offerHelpToFeedCount = DB::table('users_karmafeeds')->where('message_type','=','OfferHelpTo')->count();
				if($offerHelpToFeedCount < 1){
					$offerHelpToFeed=array();	
				}
			}
			
		}
		$feed=array_merge($karmaNoteFeed,$groupFeed,$queryFeed);
		$sort = array();
			foreach($feed as $k=>$v) {
    			$sort['updated_at'][$k] = $v['updated_at'];
			}
			array_multisort($sort['updated_at'], SORT_DESC, $feed);
			echo "<pre>";
			print_r($arr);
			die;
		echo '<pre>';print_r($feed);die;
	}


}

<?php

class HomeController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Default Home Controllers
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'HomeController@showWelcome');
	|
	*/

	public function index(){	
		$getKcuser = array();
		$getKcuser = KarmaHelper::getKcuser(0);
        //echo '<pre>';print_r($getKcuser);die;
		return View::make('index', array('pageTitle' => 'Welcome to KarmaCircles - Log In, Sign Up or Learn More',
					'pageDescription' => 'KarmaCircles is a platform for finding skilled people, request them for an online/phone/in-person meeting and then thank them for the time & help given by them through KarmaNote.',
					'getKcuserTwo'=>$getKcuser[2],
					'getKcuserOne'=>$getKcuser[1],
					'getKcuser'=>$getKcuser[0]));
	}
	public function dashboard($idget = null){

		$user_info = Auth::user();
		$totalNotesPending = 0;
		$Receiver = $user_info->id;
		$ReceiverDetail = User::find($Receiver); 
		$MeetingRequestPending = $ReceiverDetail->Giver()->Where('status','=','pending')->count();
		//echo $MeetingRequestPending;exit;
		// Calculate number of connection user have on KC platform
		//echo $MeetingRequestPending;
		$user_id = 0; $location =  '';
		
		if(!empty($user_info)){
			$user_id = $user_info->id;
			$location = $user_info->location;
		}
		/*Dashboard section*/
		// fetch user connections on KC
		$getUserConnection = KarmaHelper::getUserConnection($user_id,$location);
	 	$user_connection_onkc = 0; 
        if(!empty($getUserConnection)){
            foreach ($getUserConnection as $key => $value) {
                if(isset($value->con_user_id)) $user_connection_onkc++;
            }
         
        } 

		// fetch pending karmanote requests
		$PendingRequest = array();
		$totalPendingRequest =0; 
		$PendingRequest = KarmaHelper::getPendingKarmaNotes($user_info->id);
		if(!empty($PendingRequest))
		$totalPendingRequest = count($PendingRequest);

		//fetch pending KM requests only received no read no unread
		$totalReceivedRequest = 0;
		$GiverInMeeting = Auth::User()->Giver()->where('status', 'pending')->orderBy('updated_at', 'DESC')->get();
		if(!empty($GiverInMeeting))
		$totalReceivedRequest = count($GiverInMeeting);

		//fetch pending karma intros
		$totalintroductionInitiated=0;
		$IntroducerInitiated = Auth::User()->Introducer;
		if(!empty($IntroducerInitiated)){
			foreach ($IntroducerInitiated as $key => $value) {
				if(!empty($value['user_id_receiver']))
				$value['user_id_receiver'] = User::find($value['user_id_receiver'])->toArray();
				if(!empty($value['user_id_giver'])){
					$value['user_id_giver'] = User::find($value['user_id_giver'])->toArray();
				}
				else{
					$value['user_id_giver'] = Connection::find($value['connection_id_giver'])->toArray();
				}
				if($value->status == 'pending') $totalintroductionInitiated++;
			}
		}
		
		// fetch queries that have been posted in the last 7 days within common groups including both public & private.
		$Usergroup 	= KarmaHelper::getuserGroup();
		$All_groups = '';
		$group_question = 0;
		$totagroupquestion=0;
		$yesterday = Carbon::now()->subDays(1);
		$one_week_ago = Carbon::now()->subWeeks(1);
			 $group_question = DB::table('questions')
							->select(array('*'))
				            ->where('questions.user_id','!=',$user_info->id)
				            ->where('questions.queryStatus','open')
				            ->where('questions.created_at', '>=', $one_week_ago)
	       					->where('questions.created_at', '<=', $yesterday)
				            ->orderBy('questions.created_at','DESC')
				            ->groupBy('id')
				            ->get();
				   
			if(!empty($group_question)){
				$totagroupquestion = count($group_question); 
			}
		// fetch a 3 random users on KC platform with a common group of logged in user
        $getKcuser = array();
        $getKcuser = KarmaHelper::getKcuser(0);

		if(empty($getKcuser)){
			// fetch a user connection either KC or NON KC
			$getsuggestion = KarmaHelper::getUserConnection($user_id,$location);
			//get test users id		
			$getUser = KarmaHelper::getTestUsers();
				if(!empty($getsuggestion)){
					foreach ($getsuggestion as $key => $value) {
						$test_match = in_array($value->con_user_id,$getUser);
						if($value->con_user_id !="" &&  $test_match != 1)
						{
							$getKc = DB::table('users as u')
							->select(array('u.userstatus','u.id','u.fname','u.lname','u.linkedinurl','u.piclink','u.headline','u.email','u.karmascore','u.location'))
							->where('u.id','=',$value->con_user_id)
							->where('u.userstatus','=','approved')
							->get();  
							if(!empty($getKc))
								$value->networkid = $getKc;
						}
					} 
					$getsuggestion = $getsuggestion[array_rand($getsuggestion)];
				}

				// fetch a user connection only nON kc
				$getinvites =  "";	
				$getinvites = KarmaHelper::getUserNonKcConnection($user_id,$location);	
				if(!empty($getinvites))
				$getinvites = $getinvites[array_rand($getinvites)];

		}	
		$checkMeetingStatus = KarmaHelper::getMeetingStatusForWeb($user_id,$getKcuser[0]->id);
		$checkMeetingStatusOne = KarmaHelper::getMeetingStatusForWeb($user_id,$getKcuser[1]->id);
		$checkMeetingStatusTwo = KarmaHelper::getMeetingStatusForWeb($user_id,$getKcuser[2]->id);
					
		//fetch random 5 unique notes
		$getKarmanote =""; 
	    $getKarmanote = KarmaHelper::getKarmanote();
	   	foreach ($getKcuser as $key =>$value) {
	    	$giver_id=$value->id;
		    $MeetingRequestPending = KarmaHelper::karmaMeetingPendingCount($Receiver,$giver_id);
			$MeetingRequestPendingArray[]=$MeetingRequestPending;
			    
	    }
	    return View::make('dashboard',
			array(	'pageTitle' => 'Dashboard | Karmacircles',
					'pageDescription' => 'KarmaCircles is an online peer-to-peer knowledge sharing platform.',
					'MeetingRequestPending' => $MeetingRequestPendingArray[0],
					'MeetingRequestPendingOne' => $MeetingRequestPendingArray[1],
					'MeetingRequestPendingTwo' => $MeetingRequestPendingArray[2],
					'totagroupquestion'=>$totagroupquestion,
					'getKarmanote'=>$getKarmanote,
					//'getinvites'=>$getinvites,
					'getKcuserTwo'=>$getKcuser[2],
					'getKcuserOne'=>$getKcuser[1],
					'getKcuser'=>$getKcuser[0],
					'checkMeetingStatus'=>$checkMeetingStatus,'checkMeetingStatusOne'=>$checkMeetingStatusOne,'checkMeetingStatusTwo'=>$checkMeetingStatusTwo,
					//'getsuggestion'=>$getsuggestion, 
					'totalintroductionInitiated'=>$totalintroductionInitiated,
					'totalReceivedRequest'=>$totalReceivedRequest,
					'totalPendingRequest' => $totalPendingRequest,
					'user_connection_onkc'=>$user_connection_onkc,
					'CurrentUser' => $user_info,
					'PendingMeetingRequest'=>$MeetingRequestPending,
					'NotesPendingRequest'=>$totalNotesPending,
					
			  	)
			);
	} 

	public function ajaxhomeSuggestion(){
		$getKcuser = array();
        $getKcuser = KarmaHelper::getKcuser(0);
        return View::make('ajaxhome_suggestion', array(
					'pageTitle' => 'KarmaCircles',
					'getKcuser'=>$getKcuser[0],
					'getKcuserOne'=>$getKcuser[1],
					'getKcuserTwo'=>$getKcuser[2]));
	}


	public function ajaxdashboardSuggestion(){
		$getinvites =  "";
		$getsuggestion =array();
		$user_info = Auth::user();
 		$user_id = 0; $location =  '';
 		if(!empty($user_info)){
            $user_id = $user_info->id;
            $location = $user_info->location;
            $ReceiverDetail = User::find($user_id);
        }

		$user_info = Auth::user();
		$getKcuser = KarmaHelper::getKcuser(0);
		$skipcount='';
				
				//if(!empty($_REQUEST['skipcount'])) 
					$skipcount = $_REQUEST['skipcount'];
			if(empty($getKcuser)){
				$getsuggestion = KarmaHelper::getUserConnection($user_id,$location);
					if(!empty($getsuggestion)){
						foreach ($getsuggestion as $key => $value) {
							if($value->con_user_id != ""){
								$getKcuser = DB::table('users as u')
								->select(array('u.userstatus','u.id','u.fname','u.lname','u.linkedinurl','u.piclink','u.headline','u.email','u.karmascore','u.location'))
								->where('u.id','=',$value->con_user_id)
								->where('u.userstatus','=','approved')
								->get();
								if(!empty($getKcuser))
									$value->networkid = $getKcuser;
							}
						} 
						$getsuggestion = $getsuggestion[array_rand($getsuggestion)];
						//$skipcount ++;
					}
					// fetch a user connection only nON kc
						
					$getinvites = KarmaHelper::getUserNonKcConnection($user_id,$location);	
					if(!empty($getinvites)) 
					$getinvites = $getinvites[array_rand($getinvites)];
			}		
			$skipcountreq = $skipcount+1;
					$skipcountintro=$skipcount+2;
					$skipcountinvite=$skipcount+3;
					$checkMeetingStatus = KarmaHelper::getMeetingStatusForWeb($user_id,$getKcuser[0]->id);
				   	$checkMeetingStatusOne = KarmaHelper::getMeetingStatusForWeb($user_id,$getKcuser[1]->id);
				    $checkMeetingStatusTwo = KarmaHelper::getMeetingStatusForWeb($user_id,$getKcuser[2]->id);
					
			foreach ($getKcuser as $key =>$value) {
				    $giver_id=$value->id;
				    //$MeetingRequestPending = KarmaHelper::karmaMeetingPendingCount($user_id,$giver_id);
				    $MeetingRequestPending='0';
				    $MeetingRequestPendingArray[]=$MeetingRequestPending;
			}
			return View::make('ajaxdashboard_suggestion',array('CurrentUser'=>$user_info, 'checkMeetingStatus'=>$checkMeetingStatus,'checkMeetingStatusOne'=>$checkMeetingStatusOne,'checkMeetingStatusTwo'=>$checkMeetingStatusTwo,'MeetingRequestPending' => $MeetingRequestPendingArray[0],
					'MeetingRequestPendingOne' => $MeetingRequestPendingArray[1],
					'MeetingRequestPendingTwo' => $MeetingRequestPendingArray[2],'pageTitle' => 'Dashboard','getinvites'=>$getinvites,'getsuggestion'=>$getsuggestion,'getKcuser' => $getKcuser[0],'getKcuserOne' => $getKcuser[1],'getKcuserTwo' => $getKcuser[2],'skipcountreq'=>$skipcountreq,'skipcountintro'=>$skipcountintro,'skipcountinvite'=>$skipcountinvite));
	}


	public function howitworks(){
			$user_info = Auth::user();
			return View::make('footer.howitworks',array('pageTitle' => 'How KarmaCircles Works | KarmaCircles','pageDescription'=>'Welcome to KarmaCircles: Request help by searching skills, Receive help through KarmaMeeting, and thank KarmaGivers by sending KarmaNotes.', 'CurrentUser' => $user_info));
	}
	public function faqs($category=null,$question=null){
			$user_info = Auth::user();
			return View::make('footer.faqs',array('pageTitle' => 'FAQs | KarmaCircles','pageDescription'=>'Find answers to all your KarmaCircles-related questions.','CurrentUser' => $user_info,'category'=>$category,'question'=>$question));
	}
	public function terms(){
			$user_info = Auth::user();
			return View::make('footer.terms',array('pageTitle' => 'Terms of Service & Privacy Policy | KarmaCircles','pageDescription'=> 'Welcome to KarmaCircles.com! KarmaCircles provides an online venue and services that connect users providing advice and consultations with users seeking consultations and advice, which Services are accessible at KarmaCircles.com. These terms govern your access to and use of the Site, application and services and all Collective Content (as defined below) and constitute a binding legal agreement between you and KarmaCircles. Please read these terms carefully, and contact us if you have any questions.', 'CurrentUser' => $user_info));
	}
	public function mobileterms(){
			$user_info = Auth::user();
			return View::make('footer.mobileterms',array('pageTitle' => 'Terms of Service & Privacy Policy | KarmaCircles','pageDescription'=> 'Welcome to KarmaCircles.com! KarmaCircles provides an online venue and services that connect users providing advice and consultations with users seeking consultations and advice, which Services are accessible at KarmaCircles.com. These terms govern your access to and use of the Site, application and services and all Collective Content (as defined below) and constitute a binding legal agreement between you and KarmaCircles. Please read these terms carefully, and contact us if you have any questions.', 'CurrentUser' => $user_info));
	}
	public function about(){ 
			$user_info = Auth::user();
			$team = $advisor = "";
			$team = DB::table('users')
						->select(array('users.*'))
						->where('users.userstatus','=','approved')   
						->whereIn('users.id',array(105)) 
						->orderBy('created_at','DESC') 
						->get();       
			
  			
  			$advisor = DB::table('users')
						->select(array('users.*'))
						->where('users.userstatus','=','approved')   
						->whereIn('users.id',array(276,162,130))
						->orderBy('created_at','DESC')  
						->get();       
			
			return View::make('footer.about',array('pageTitle' => 'About Us | KarmaCircles','pageDescription'=>'KarmaCircles is an online peer-to-peer knowledge sharing platform.', 'CurrentUser' => $user_info,'team'=>$team[0],'advisor'=>$advisor));
	}

	public function inviteOnkc($giver_id){ 
		$user_info = Auth::user();
		$giver_info = Connection::find($giver_id);
		$receiver_vanity_url = KarmaHelper::getVanityURL($user_info->id);
		$checkMsgLimit = KarmaHelper::CheckUserLinkedMgsLimit();
		return View::make('invite_onkc',array('pageTitle' => 'KarmaCircles Invite','checkMsgLimit' => $checkMsgLimit,'CurrentUser' => $user_info,'GiverInfo' => $giver_info,'receiver_vanity_url'=>$receiver_vanity_url));
	}   

	
	public function searchUsers(){

		//echo "<pre>";print_r($_REQUEST);echo "</pre>"; 

		$user_info = Auth::user(); 
		$location = '';
		
		if(isset($user_info)){
			$user_id = $user_info->id;
			$location = $user_info->location;	
		}else{
			$user_id = 0;
			$location = '';
		}
		/*echo "string ".$user_id.'--'.$location;
		die;*/
		//$user_id = $user_info->id;
		$searchresult = array();
		$start =0; $perpage=10;
		
		
		if(!empty($_GET)) 
		{
			$search = Input::get('searchUser');
			$searchOption = Input::get('searchOption'); 
			if($searchOption == 'All'){
         	   $searchquery = DB::select(DB::raw( 'SELECT * from (select `users`.`fname`, `users`.`lname`, `users`.`piclink`, `users`.`linkedinid`,
												 `users`.`linkedinurl`, `users`.`headline`, `users`.`location`, `users`.`id` from `users` 
												where concat(fname," ",lname) LIKE "%'.$search.'%" and `users`.`id` != '.$user_id.' and `users`.`userstatus` = "approved" 
												union SELECT `connections`.`fname`, `connections`.`lname`, `connections`.`piclink`, `connections`.`networkid`, 
												`connections`.`linkedinurl`, `connections`.`headline`, `connections`.`location`, `users_connections`.`connection_id` 
												from `connections` inner join `users_connections` on `connections`.`id` = `users_connections`.`connection_id` 
												where concat(fname," ",lname) LIKE "%'.$search.'%" and `users_connections`.`user_id` = '.$user_id.'
												UNION
												SELECT `users`.`fname`, `users`.`lname`, `users`.`piclink`, `users`.`linkedinid`,
												`users`.`linkedinurl`, `users`.`headline`, `users`.`location`, `users`.`id` from `users` where industry 
												LIKE "%'.$search.'%" and `users`.`id` != '.$user_id.' and `users`.`userstatus` = "approved" union SELECT `connections`.`fname`, 
												`connections`.`lname`, `connections`.`piclink`, `connections`.`networkid`, `connections`.`linkedinurl`, 
												`connections`.`headline`, `connections`.`location`, `users_connections`.`connection_id` from `connections` 
												inner join `users_connections` on `connections`.`id` = `users_connections`.`connection_id` where industry 
												LIKE "%'.$search.'%" and `users_connections`.`user_id` = '.$user_id.'
												UNION
												SELECT `users`.`fname`, `users`.`lname`, `users`.`piclink`, `users`.`linkedinid`,
												`users`.`linkedinurl`, `users`.`headline`, `users`.`location`, `users`.`id` from `users` where location 
												LIKE "%'.$search.'%" and `users`.`id` != '.$user_id.' and `users`.`userstatus` = "approved" union SELECT `connections`.`fname`, 
												`connections`.`lname`, `connections`.`piclink`, `connections`.`networkid`, `connections`.`linkedinurl`, 
												`connections`.`headline`, `connections`.`location`, `users_connections`.`connection_id` from `connections` 
												inner join `users_connections` on `connections`.`id` = `users_connections`.`connection_id` where location 
												LIKE "%'.$search.'%" and `users_connections`.`user_id` = '.$user_id.'
												UNION
												SELECT `users`.`fname`, `users`.`lname`,`users`.`piclink`,`users`.`linkedinid`,
											 `users`.`linkedinurl`, `users`.`headline`, `users`.`location`, `users`.`id`
											  from `tags` inner join `users_tags` on `tags`.`id` = `users_tags`.`tag_id`
											 inner join `users` on `users`.`id` = `users_tags`.`user_id` where `name` LIKE "%'.$search.'%" and
											 `users`.`userstatus` = "approved" and `users`.`id` != '.$user_id.' group by `users_tags`.`user_id`) as alldata group by alldata.linkedinid limit 10 offset '.$start ));
         	   foreach ($searchquery as $key => $value) {
         	  		$searchresult[$key]['fname'] = $value->fname;
         	  		$searchresult[$key]['lname'] = $value->lname;
         	  		$searchresult[$key]['linkedinurl'] = $value->linkedinurl;
         	  		$searchresult[$key]['headline'] = $value->headline;
         	  		$searchresult[$key]['location'] = $value->location;
         	  		$searchresult[$key]['linkedinid'] = $value->linkedinid;
         	  		$searchresult[$key]['piclink'] = $value->piclink;
         	  		$searchresult[$key]['id'] = $value->id;

         	  		$linkedinid = $value->linkedinid;
         	  		$linkedinUserData = DB::table('users')->where('linkedinid', '=', $linkedinid)->first();
         	  		
         	  		if(!empty($linkedinUserData)){
	         	  		$tags = User::find($linkedinUserData->id)->Tags()->orderBy(DB::raw('RAND()'))->take(10)->get();
	         	  		$searchresult[$key]['UserData'] = array();
	         	  		$searchresult[$key]['Tags'] = array();
	         	  		$searchresult[$key]['UserData'] = $linkedinUserData;
	         	  		$searchresult[$key]['Tags'] = $tags;
         	  		}	
         	  	}
			}
			elseif($searchOption == 'People'){
			    $searchquery = DB::select(DB::raw( 'select * from (select `users`.`fname`, `users`.`lname`, `users`.`piclink`, `users`.`linkedinid`,
										 `users`.`linkedinurl`, `users`.`headline`, `users`.`location`, `users`.`id` from 
										`users` where concat(fname," ",lname) LIKE "%'.$search.'%" and `users`.`id` != '.$user_id.' and
										 `users`.`userstatus` = "approved" union select `connections`.`fname`,
										 `connections`.`lname`, `connections`.`piclink`, `connections`.`networkid`,
										 `connections`.`linkedinurl`, `connections`.`headline`, `connections`.`location`,
										 `users_connections`.`connection_id` from `connections`
										 inner join `users_connections` on `connections`.`id` = `users_connections`.`connection_id`
										 where concat(fname," ",lname) LIKE "%'.$search.'%" and `users_connections`.`user_id` = '.$user_id.') as 
										result group by result.linkedinid order by result.location = "'.$location.'" desc limit 10 offset '.$start  ));
         	  	foreach ($searchquery as $key => $value) {
         	  		$searchresult[$key]['fname'] = $value->fname;
         	  		$searchresult[$key]['lname'] = $value->lname;
         	  		$searchresult[$key]['linkedinurl'] = $value->linkedinurl;
         	  		$searchresult[$key]['headline'] = $value->headline;
         	  		$searchresult[$key]['location'] = $value->location;
         	  		$searchresult[$key]['linkedinid'] = $value->linkedinid;
         	  		$searchresult[$key]['piclink'] = $value->piclink;
         	  		$searchresult[$key]['id'] = $value->id;

         	  		$linkedinid = $value->linkedinid;
         	  		$linkedinUserData = DB::table('users')->where('linkedinid', '=', $linkedinid)->first();
         	  		if(!empty($linkedinUserData)){
	         	  		$tags = User::find($linkedinUserData->id)->Tags()->orderBy(DB::raw('RAND()'))->take(10)->get();
	         	  		$searchresult[$key]['UserData'] = array();
	         	  		$searchresult[$key]['Tags'] = array();
	         	  		$searchresult[$key]['UserData'] = $linkedinUserData;
	         	  		$searchresult[$key]['Tags'] = $tags;
         	  		}	
         	  	}
			}
			elseif($searchOption == 'Skills'){
				$skillTag = array(); 
         	  	$searchquery = DB::table('tags')
			            ->join('users_tags', 'tags.id', '=', 'users_tags.tag_id')
			            ->join('users', 'users.id', '=', 'users_tags.user_id')
			            ->where('name', 'LIKE', $search)
			            ->where('users.userstatus', '=', 'approved')
			            ->where('users.id', '!=', $user_id)			            
			            ->groupBy('users_tags.user_id')
			            ->orderBy('users.location', '=', $location)
			            ->select('tags.name', 'tags.id', 'users_tags.user_id', 'users.fname', 'users.lname', 'users.email', 'users.piclink', 'users.linkedinid', 'users.linkedinurl', 'users.location', 'users.headline')
			            ->skip($start)->take($perpage)
			            ->get();
			    
         	  	foreach ($searchquery as $key => $value) {
         	  		$searchresult[$key]['fname'] = $value->fname;
         	  		$searchresult[$key]['lname'] = $value->lname;
         	  		$searchresult[$key]['linkedinurl'] = $value->linkedinurl;
         	  		$searchresult[$key]['headline'] = $value->headline;
         	  		$searchresult[$key]['location'] = $value->location;
         	  		$searchresult[$key]['email'] = $value->email;
         	  		$searchresult[$key]['linkedinid'] = $value->linkedinid;
         	  		$searchresult[$key]['piclink'] = $value->piclink;


         	  		$linkedinid = $value->linkedinid;
         	  		$linkedinUserData = DB::table('users')->where('linkedinid', '=', $linkedinid)->first();
         	  		if(!empty($value->user_id)){
	         	  		//$tags = User::find($value->user_id)->Tags()->orderBy('name', '=', $search)->take(10)->get()->toArray();
	         	  		/*$tags = DB::table('tags')
	         	  				->join('users_tags', 'tags.id', '=', 'users_tags.tag_id')
	         	  				->where('user_id', '=', $user_id)
	         	  				->orderBy('tags.name', '=', $search)
	         	  				->select('name')->get();*/
	         	  		$tags = DB::select(DB::raw( "select `name` from `tags` inner join `users_tags` on `tags`.`id` = `users_tags`.`tag_id` where `user_id` = $linkedinUserData->id order by `tags`.`name` = '$search' desc" ));			

	         	  		
	         	  		foreach ($tags as $skillkey => $skillvalue) {
	         	  			$skillTag[$skillkey]['name'] = $skillvalue->name;
	         	  		}
						
	         	  		$searchresult[$key]['UserData'] = array();
	         	  		$searchresult[$key]['Tags'] = array();
	         	  		$searchresult[$key]['UserData'] = $linkedinUserData;
	         	  		$searchresult[$key]['Tags'] = $skillTag;
	         	  		//echo "<pre>";print_r($searchresult);echo "</pre>";die;
         	  		}	
         	  		
         	  		
         	  	}
			}
			elseif($searchOption == 'Industry'){
			    $searchquery = DB::select(DB::raw( 'select * from (select `users`.`fname`, `users`.`lname`, `users`.`piclink`, `users`.`linkedinid`,
										 `users`.`linkedinurl`, `users`.`headline`, `users`.`location`, `users`.`id` from 
										`users` where industry LIKE "%'.$search.'%" and `users`.`id` != '.$user_id.' and
										 `users`.`userstatus` = "approved" union select `connections`.`fname`,
										 `connections`.`lname`, `connections`.`piclink`, `connections`.`networkid`,
										 `connections`.`linkedinurl`, `connections`.`headline`, `connections`.`location`,
										 `users_connections`.`connection_id` from `connections`
										 inner join `users_connections` on `connections`.`id` = `users_connections`.`connection_id`
										 where industry LIKE "%'.$search.'%" and `users_connections`.`user_id` = '.$user_id.') as 
										result group by result.linkedinid order by result.location = "'.$location.'" desc limit 10 offset '.$start ));        	
			    //echo "<pre>";print_r($searchresult);echo "</pre>";die;
         	  	foreach ($searchquery as $key => $value) {
         	  		$searchresult[$key]['fname'] = $value->fname;
         	  		$searchresult[$key]['lname'] = $value->lname;
         	  		$searchresult[$key]['linkedinurl'] = $value->linkedinurl;
         	  		$searchresult[$key]['headline'] = $value->headline;
         	  		$searchresult[$key]['location'] = $value->location;
         	  		$searchresult[$key]['linkedinid'] = $value->linkedinid;
         	  		$searchresult[$key]['piclink'] = $value->piclink;
         	  		$searchresult[$key]['id'] = $value->id;

         	  		$linkedinid = $value->linkedinid;
         	  		$linkedinUserData = DB::table('users')->where('linkedinid', '=', $linkedinid)->first();
         	  		
         	  		if(!empty($linkedinUserData)){
	         	  		$tags = User::find($linkedinUserData->id)->Tags()->orderBy(DB::raw('RAND()'))->take(10)->get();
	         	  		$searchresult[$key]['UserData'] = array();
	         	  		$searchresult[$key]['Tags'] = array();
	         	  		$searchresult[$key]['UserData'] = $linkedinUserData;
	         	  		$searchresult[$key]['Tags'] = $tags;
         	  		}	
         	  	}
			}
			elseif($searchOption == 'Location'){
				$searchquery = DB::select(DB::raw( 'select * from (select `users`.`fname`, `users`.`lname`, `users`.`piclink`, `users`.`linkedinid`,
										 `users`.`linkedinurl`, `users`.`headline`, `users`.`location`, `users`.`id` from 
										`users` where location LIKE "%'.$search.'%" and `users`.`id` != '.$user_id.' and
										 `users`.`userstatus` = "approved" union select `connections`.`fname`,
										 `connections`.`lname`, `connections`.`piclink`, `connections`.`networkid`,
										 `connections`.`linkedinurl`, `connections`.`headline`, `connections`.`location`,
										 `users_connections`.`connection_id` from `connections`
										 inner join `users_connections` on `connections`.`id` = `users_connections`.`connection_id`
										 where location LIKE "%'.$search.'%" and `users_connections`.`user_id` = '.$user_id.') as 
										result group by result.linkedinid order by result.location limit 10 offset '.$start ));        	
			    
         	  	foreach ($searchquery as $key => $value) {
         	  		$searchresult[$key]['fname'] = $value->fname;
         	  		$searchresult[$key]['lname'] = $value->lname;
         	  		$searchresult[$key]['linkedinurl'] = $value->linkedinurl;
         	  		$searchresult[$key]['headline'] = $value->headline;
         	  		$searchresult[$key]['location'] = $value->location;
         	  		$searchresult[$key]['linkedinid'] = $value->linkedinid;
         	  		$searchresult[$key]['piclink'] = $value->piclink;
         	  		$searchresult[$key]['id'] = $value->id;

         	  		$linkedinid = $value->linkedinid;
         	  		$linkedinUserData = DB::table('users')->where('linkedinid', '=', $linkedinid)->first();
         	  		
         	  		if(!empty($linkedinUserData)){
	         	  		$tags = User::find($linkedinUserData->id)->Tags()->orderBy(DB::raw('RAND()'))->take(10)->get();
	         	  		$searchresult[$key]['UserData'] = array();
	         	  		$searchresult[$key]['Tags'] = array();
	         	  		$searchresult[$key]['UserData'] = $linkedinUserData;
	         	  		$searchresult[$key]['Tags'] = $tags;
         	  		}	
         	  	}
			}
				elseif($searchOption == 'Groups'){				
					$searchquery = DB::table('groups')
			            ->join('users_groups', 'groups.id', '=', 'users_groups.group_id')
			            ->join('users', 'users.id', '=', 'users_groups.user_id')
			            ->where('name', '=', $search)
			            ->where('users.userstatus', '=', 'approved')	
			            ->where('users.id', '!=', $user_id)				            		            
			            ->groupBy('users_groups.user_id')
			            ->orderBy('users.location', '=', $location)
			            ->select('groups.name', 'groups.id', 'users_groups.user_id', 'users.fname', 'users.lname', 'users.email', 'users.piclink', 'users.linkedinid', 'users.linkedinurl', 'users.location', 'users.headline')
			           	->skip($start)->take($perpage)
			            ->get();
			          // echo "<pre>";print_r($searchquery);echo "</pre>";die;
         	  	foreach ($searchquery as $key => $value) {
         	  		$searchresult[$key]['fname'] = $value->fname;
         	  		$searchresult[$key]['lname'] = $value->lname;
         	  		$searchresult[$key]['linkedinurl'] = $value->linkedinurl;
         	  		$searchresult[$key]['headline'] = $value->headline;
         	  		$searchresult[$key]['location'] = $value->location;
         	  		$searchresult[$key]['linkedinid'] = $value->linkedinid;
         	  		$searchresult[$key]['piclink'] = $value->piclink;
         	  		$searchresult[$key]['id'] = $value->id;
         	  		$linkedinid = $value->linkedinid;
         	  		$linkedinUserData = DB::table('users')->where('linkedinid', '=', $linkedinid)->first();
         	  		
         	  		if(!empty($linkedinUserData)){
	         	  		$tags = User::find($linkedinUserData->id)->Tags()->orderBy(DB::raw('RAND()'))->take(10)->get();
	         	  		$searchresult[$key]['UserData'] = array();
	         	  		$searchresult[$key]['Tags'] = array();
	         	  		$searchresult[$key]['UserData'] = $linkedinUserData;
	         	  		$searchresult[$key]['Tags'] = $tags;
         	  		}	
         	  	}
			}

			elseif($searchOption == 'Tags'){
				$searchquery = DB::select(DB::raw( 'select * from (select `users`.`fname`, `users`.`lname`, `users`.`piclink`, `users`.`linkedinid`,
										 `users`.`linkedinurl`, `users`.`headline`, `users`.`location`, `users`.`id` from 
										`users` where headline LIKE "%'.$search.'%" and `users`.`id` != '.$user_id.' and
										 `users`.`userstatus` = "approved" union select `connections`.`fname`,
										 `connections`.`lname`, `connections`.`piclink`, `connections`.`networkid`,
										 `connections`.`linkedinurl`, `connections`.`headline`, `connections`.`location`,
										 `users_connections`.`connection_id` from `connections`
										 inner join `users_connections` on `connections`.`id` = `users_connections`.`connection_id`
										 where headline LIKE "%'.$search.'%" and `users_connections`.`user_id` = '.$user_id.') as 
										result group by result.linkedinid order by result.location = "'.$location.'" desc limit 10 offset'.$start ));         	
			    //echo "<pre>";print_r($searchresult);echo "</pre>";die;
         	  	foreach ($searchquery as $key => $value) {
         	  		$searchresult[$key]['fname'] = $value->fname;
         	  		$searchresult[$key]['lname'] = $value->lname;
         	  		$searchresult[$key]['linkedinurl'] = $value->linkedinurl;
         	  		$searchresult[$key]['headline'] = $value->headline;
         	  		$searchresult[$key]['location'] = $value->location;
         	  		$searchresult[$key]['linkedinid'] = $value->linkedinid;
         	  		$searchresult[$key]['piclink'] = $value->piclink;
         	  		$searchresult[$key]['id'] = $value->id;

         	  		$linkedinid = $value->linkedinid;
         	  		$linkedinUserData = DB::table('users')->where('linkedinid', '=', $linkedinid)->first();
         	  		
         	  		if(!empty($linkedinUserData)){
	         	  		$tags = User::find($linkedinUserData->id)->Tags()->orderBy(DB::raw('RAND()'))->take(10)->get();
	         	  		$searchresult[$key]['UserData'] = array();
	         	  		$searchresult[$key]['Tags'] = array();
	         	  		$searchresult[$key]['UserData'] = $linkedinUserData;
	         	  		$searchresult[$key]['Tags'] = $tags;
         	  		}	
         	  	}
			}
			$totalCount = 0; 
			if(!empty($searchresult)) $totalCount = count($searchresult);
			//echo "<pre>"; print_r( $searchresult);echo "</pre>"; 
			$checkMsgLimit = KarmaHelper::CheckUserLinkedMgsLimit();
			
			return View::make('search_result',array('checkMsgLimit' => $checkMsgLimit,'CurrentUser' => $user_info,'searchresult'=> $searchresult, 'totalResult' => $totalCount, 'searchFor'=> $search, 'searchCat'=>$searchOption));
			
		}
		
	}

	public function LoadmoresearchResult(){
		
		$user_info = Auth::user(); 
		$location = '';
		if(isset($user_info)){
			$user_id = $user_info->id;
			$location = $user_info->location;	
		}else{
			$user_id = 0;
			$location = '';
		}
		$perpage=10;
		if(isset($_REQUEST['hitcount']))
			$start = $_REQUEST['hitcount']+1;
		$searchresult = array();
		
		
		if(!empty($_REQUEST)) 
		{
			$search = $_REQUEST['searchUser'];
			$searchOption =  $_REQUEST['searchOption'];

			if($searchOption == 'All'){
         	   $searchquery = DB::select(DB::raw( 'SELECT * from (select `users`.`fname`, `users`.`lname`, `users`.`piclink`, `users`.`linkedinid`,
												 `users`.`linkedinurl`, `users`.`headline`, `users`.`location`, `users`.`id` from `users` 
												where concat(fname," ",lname) LIKE "%'.$search.'%" and `users`.`id` != '.$user_id.' and `users`.`userstatus` = "approved" 
												union SELECT `connections`.`fname`, `connections`.`lname`, `connections`.`piclink`, `connections`.`networkid`, 
												`connections`.`linkedinurl`, `connections`.`headline`, `connections`.`location`, `users_connections`.`connection_id` 
												from `connections` inner join `users_connections` on `connections`.`id` = `users_connections`.`connection_id` 
												where concat(fname," ",lname) LIKE "%'.$search.'%" and `users_connections`.`user_id` = '.$user_id.'
												UNION
												SELECT `users`.`fname`, `users`.`lname`, `users`.`piclink`, `users`.`linkedinid`,
												`users`.`linkedinurl`, `users`.`headline`, `users`.`location`, `users`.`id` from `users` where industry 
												LIKE "%'.$search.'%" and `users`.`id` != '.$user_id.' and `users`.`userstatus` = "approved" union SELECT `connections`.`fname`, 
												`connections`.`lname`, `connections`.`piclink`, `connections`.`networkid`, `connections`.`linkedinurl`, 
												`connections`.`headline`, `connections`.`location`, `users_connections`.`connection_id` from `connections` 
												inner join `users_connections` on `connections`.`id` = `users_connections`.`connection_id` where industry 
												LIKE "%'.$search.'%" and `users_connections`.`user_id` = '.$user_id.'
												UNION
												SELECT `users`.`fname`, `users`.`lname`, `users`.`piclink`, `users`.`linkedinid`,
												`users`.`linkedinurl`, `users`.`headline`, `users`.`location`, `users`.`id` from `users` where location 
												LIKE "%'.$search.'%" and `users`.`id` != '.$user_id.' and `users`.`userstatus` = "approved" union SELECT `connections`.`fname`, 
												`connections`.`lname`, `connections`.`piclink`, `connections`.`networkid`, `connections`.`linkedinurl`, 
												`connections`.`headline`, `connections`.`location`, `users_connections`.`connection_id` from `connections` 
												inner join `users_connections` on `connections`.`id` = `users_connections`.`connection_id` where location 
												LIKE "%'.$search.'%" and `users_connections`.`user_id` = '.$user_id.'
												UNION
												SELECT `users`.`fname`, `users`.`lname`,`users`.`piclink`,`users`.`linkedinid`,
											 `users`.`linkedinurl`, `users`.`headline`, `users`.`location`, `users`.`id`
											  from `tags` inner join `users_tags` on `tags`.`id` = `users_tags`.`tag_id`
											 inner join `users` on `users`.`id` = `users_tags`.`user_id` where `name` LIKE "%'.$search.'%" and
											 `users`.`userstatus` = "approved" and `users`.`id` != '.$user_id.' group by `users_tags`.`user_id`) as alldata group by alldata.linkedinid limit 10 offset '.$start ));
         	   foreach ($searchquery as $key => $value) {
         	  		$searchresult[$key]['fname'] = $value->fname;
         	  		$searchresult[$key]['lname'] = $value->lname;
         	  		$searchresult[$key]['linkedinurl'] = $value->linkedinurl;
         	  		$searchresult[$key]['headline'] = $value->headline;
         	  		$searchresult[$key]['location'] = $value->location;
         	  		$searchresult[$key]['linkedinid'] = $value->linkedinid;
         	  		$searchresult[$key]['piclink'] = $value->piclink;
         	  		$searchresult[$key]['id'] = $value->id;

         	  		$linkedinid = $value->linkedinid;
         	  		$linkedinUserData = DB::table('users')->where('linkedinid', '=', $linkedinid)->first();
         	  		
         	  		if(!empty($linkedinUserData)){
	         	  		$tags = User::find($linkedinUserData->id)->Tags()->orderBy(DB::raw('RAND()'))->take(10)->get();
	         	  		$searchresult[$key]['UserData'] = array();
	         	  		$searchresult[$key]['Tags'] = array();
	         	  		$searchresult[$key]['UserData'] = $linkedinUserData;
	         	  		$searchresult[$key]['Tags'] = $tags;
         	  		}	
         	  	}
			}
			elseif($searchOption == 'People'){
			    $searchquery = DB::select(DB::raw( 'select * from (select `users`.`fname`, `users`.`lname`, `users`.`piclink`, `users`.`linkedinid`,
										 `users`.`linkedinurl`, `users`.`headline`, `users`.`location`, `users`.`id` from 
										`users` where concat(fname," ",lname) LIKE "%'.$search.'%" and `users`.`id` != '.$user_id.' and
										 `users`.`userstatus` = "approved" union select `connections`.`fname`,
										 `connections`.`lname`, `connections`.`piclink`, `connections`.`networkid`,
										 `connections`.`linkedinurl`, `connections`.`headline`, `connections`.`location`,
										 `users_connections`.`connection_id` from `connections`
										 inner join `users_connections` on `connections`.`id` = `users_connections`.`connection_id`
										 where concat(fname," ",lname) LIKE "%'.$search.'%" and `users_connections`.`user_id` = '.$user_id.') as 
										result group by result.linkedinid order by result.location = "'.$location.'" desc limit 10 offset '.$start  ));
         	  	foreach ($searchquery as $key => $value) {
         	  		$searchresult[$key]['fname'] = $value->fname;
         	  		$searchresult[$key]['lname'] = $value->lname;
         	  		$searchresult[$key]['linkedinurl'] = $value->linkedinurl;
         	  		$searchresult[$key]['headline'] = $value->headline;
         	  		$searchresult[$key]['location'] = $value->location;
         	  		$searchresult[$key]['linkedinid'] = $value->linkedinid;
         	  		$searchresult[$key]['piclink'] = $value->piclink;
         	  		$searchresult[$key]['id'] = $value->id;

         	  		$linkedinid = $value->linkedinid;
         	  		$linkedinUserData = DB::table('users')->where('linkedinid', '=', $linkedinid)->first();
         	  		if(!empty($linkedinUserData)){
	         	  		$tags = User::find($linkedinUserData->id)->Tags()->orderBy(DB::raw('RAND()'))->take(10)->get();
	         	  		$searchresult[$key]['UserData'] = array();
	         	  		$searchresult[$key]['Tags'] = array();
	         	  		$searchresult[$key]['UserData'] = $linkedinUserData;
	         	  		$searchresult[$key]['Tags'] = $tags;
         	  		}	
         	  	}
			}
			elseif($searchOption == 'Skills'){
				$skillTag = array(); 
         	  	$searchquery = DB::table('tags')
			            ->join('users_tags', 'tags.id', '=', 'users_tags.tag_id')
			            ->join('users', 'users.id', '=', 'users_tags.user_id')
			            ->where('name', 'LIKE', $search)
			            ->where('users.userstatus', '=', 'approved')
			            ->where('users.id', '!=', $user_id)			            
			            ->groupBy('users_tags.user_id')
			            ->orderBy('users.location', '=', $location)
			            ->select('tags.name', 'tags.id', 'users_tags.user_id', 'users.fname', 'users.lname', 'users.email', 'users.piclink', 'users.linkedinid', 'users.linkedinurl', 'users.location', 'users.headline')
			            ->skip($start)->take($perpage)
			            ->get();
			    
         	  	foreach ($searchquery as $key => $value) {
         	  		$searchresult[$key]['fname'] = $value->fname;
         	  		$searchresult[$key]['lname'] = $value->lname;
         	  		$searchresult[$key]['linkedinurl'] = $value->linkedinurl;
         	  		$searchresult[$key]['headline'] = $value->headline;
         	  		$searchresult[$key]['location'] = $value->location;
         	  		$searchresult[$key]['email'] = $value->email;
         	  		$searchresult[$key]['linkedinid'] = $value->linkedinid;
         	  		$searchresult[$key]['piclink'] = $value->piclink;


         	  		$linkedinid = $value->linkedinid;
         	  		$linkedinUserData = DB::table('users')->where('linkedinid', '=', $linkedinid)->first();
         	  		if(!empty($value->user_id)){
	         	  		//$tags = User::find($value->user_id)->Tags()->orderBy('name', '=', $search)->take(10)->get()->toArray();
	         	  		/*$tags = DB::table('tags')
	         	  				->join('users_tags', 'tags.id', '=', 'users_tags.tag_id')
	         	  				->where('user_id', '=', $user_id)
	         	  				->orderBy('tags.name', '=', $search)
	         	  				->select('name')->get();*/
	         	  		$tags = DB::select(DB::raw( "select `name` from `tags` inner join `users_tags` on `tags`.`id` = `users_tags`.`tag_id` where `user_id` = $linkedinUserData->id order by `tags`.`name` = '$search' desc" ));			

	         	  		
	         	  		foreach ($tags as $skillkey => $skillvalue) {
	         	  			$skillTag[$skillkey]['name'] = $skillvalue->name;
	         	  		}
						
	         	  		$searchresult[$key]['UserData'] = array();
	         	  		$searchresult[$key]['Tags'] = array();
	         	  		$searchresult[$key]['UserData'] = $linkedinUserData;
	         	  		$searchresult[$key]['Tags'] = $skillTag;
	         	  		//echo "<pre>";print_r($searchresult);echo "</pre>";die;
         	  		}	
         	  		
         	  		
         	  	}
			}
			elseif($searchOption == 'Industry'){
			    $searchquery = DB::select(DB::raw( 'select * from (select `users`.`fname`, `users`.`lname`, `users`.`piclink`, `users`.`linkedinid`,
										 `users`.`linkedinurl`, `users`.`headline`, `users`.`location`, `users`.`id` from 
										`users` where industry LIKE "%'.$search.'%" and `users`.`id` != '.$user_id.' and
										 `users`.`userstatus` = "approved" union select `connections`.`fname`,
										 `connections`.`lname`, `connections`.`piclink`, `connections`.`networkid`,
										 `connections`.`linkedinurl`, `connections`.`headline`, `connections`.`location`,
										 `users_connections`.`connection_id` from `connections`
										 inner join `users_connections` on `connections`.`id` = `users_connections`.`connection_id`
										 where industry LIKE "%'.$search.'%" and `users_connections`.`user_id` = '.$user_id.') as 
										result group by result.linkedinid order by result.location = "'.$location.'" desc limit 10 offset '.$start ));        	
			    //echo "<pre>";print_r($searchresult);echo "</pre>";die;
         	  	foreach ($searchquery as $key => $value) {
         	  		$searchresult[$key]['fname'] = $value->fname;
         	  		$searchresult[$key]['lname'] = $value->lname;
         	  		$searchresult[$key]['linkedinurl'] = $value->linkedinurl;
         	  		$searchresult[$key]['headline'] = $value->headline;
         	  		$searchresult[$key]['location'] = $value->location;
         	  		$searchresult[$key]['linkedinid'] = $value->linkedinid;
         	  		$searchresult[$key]['piclink'] = $value->piclink;
         	  		$searchresult[$key]['id'] = $value->id;

         	  		$linkedinid = $value->linkedinid;
         	  		$linkedinUserData = DB::table('users')->where('linkedinid', '=', $linkedinid)->first();
         	  		
         	  		if(!empty($linkedinUserData)){
	         	  		$tags = User::find($linkedinUserData->id)->Tags()->orderBy(DB::raw('RAND()'))->take(10)->get();
	         	  		$searchresult[$key]['UserData'] = array();
	         	  		$searchresult[$key]['Tags'] = array();
	         	  		$searchresult[$key]['UserData'] = $linkedinUserData;
	         	  		$searchresult[$key]['Tags'] = $tags;
         	  		}	
         	  	}
			}
			elseif($searchOption == 'Location'){
				$searchquery = DB::select(DB::raw( 'select * from (select `users`.`fname`, `users`.`lname`, `users`.`piclink`, `users`.`linkedinid`,
										 `users`.`linkedinurl`, `users`.`headline`, `users`.`location`, `users`.`id` from 
										`users` where location LIKE "%'.$search.'%" and `users`.`id` != '.$user_id.' and
										 `users`.`userstatus` = "approved" union select `connections`.`fname`,
										 `connections`.`lname`, `connections`.`piclink`, `connections`.`networkid`,
										 `connections`.`linkedinurl`, `connections`.`headline`, `connections`.`location`,
										 `users_connections`.`connection_id` from `connections`
										 inner join `users_connections` on `connections`.`id` = `users_connections`.`connection_id`
										 where location LIKE "%'.$search.'%" and `users_connections`.`user_id` = '.$user_id.') as 
										result group by result.linkedinid order by result.location limit 10 offset '.$start ));        	
			    
         	  	foreach ($searchquery as $key => $value) {
         	  		$searchresult[$key]['fname'] = $value->fname;
         	  		$searchresult[$key]['lname'] = $value->lname;
         	  		$searchresult[$key]['linkedinurl'] = $value->linkedinurl;
         	  		$searchresult[$key]['headline'] = $value->headline;
         	  		$searchresult[$key]['location'] = $value->location;
         	  		$searchresult[$key]['linkedinid'] = $value->linkedinid;
         	  		$searchresult[$key]['piclink'] = $value->piclink;
         	  		$searchresult[$key]['id'] = $value->id;

         	  		$linkedinid = $value->linkedinid;
         	  		$linkedinUserData = DB::table('users')->where('linkedinid', '=', $linkedinid)->first();
         	  		
         	  		if(!empty($linkedinUserData)){
	         	  		$tags = User::find($linkedinUserData->id)->Tags()->orderBy(DB::raw('RAND()'))->take(10)->get();
	         	  		$searchresult[$key]['UserData'] = array();
	         	  		$searchresult[$key]['Tags'] = array();
	         	  		$searchresult[$key]['UserData'] = $linkedinUserData;
	         	  		$searchresult[$key]['Tags'] = $tags;
         	  		}	
         	  	}
			}
				elseif($searchOption == 'Groups'){				
					$searchquery = DB::table('groups')
			            ->join('users_groups', 'groups.id', '=', 'users_groups.group_id')
			            ->join('users', 'users.id', '=', 'users_groups.user_id')
			            ->where('name', '=', $search)
			            ->where('users.userstatus', '=', 'approved')	
			            ->where('users.id', '!=', $user_id)				            		            
			            ->groupBy('users_groups.user_id')
			            ->orderBy('users.location', '=', $location)
			            ->select('groups.name', 'groups.id', 'users_groups.user_id', 'users.fname', 'users.lname', 'users.email', 'users.piclink', 'users.linkedinid', 'users.linkedinurl', 'users.location', 'users.headline')
			           	->skip($start)->take($perpage)
			            ->get();
			          // echo "<pre>";print_r($searchquery);echo "</pre>";die;
         	  	foreach ($searchquery as $key => $value) {
         	  		$searchresult[$key]['fname'] = $value->fname;
         	  		$searchresult[$key]['lname'] = $value->lname;
         	  		$searchresult[$key]['linkedinurl'] = $value->linkedinurl;
         	  		$searchresult[$key]['headline'] = $value->headline;
         	  		$searchresult[$key]['location'] = $value->location;
         	  		$searchresult[$key]['linkedinid'] = $value->linkedinid;
         	  		$searchresult[$key]['piclink'] = $value->piclink;
         	  		$searchresult[$key]['id'] = $value->id;
         	  		$linkedinid = $value->linkedinid;
         	  		$linkedinUserData = DB::table('users')->where('linkedinid', '=', $linkedinid)->first();
         	  		
         	  		if(!empty($linkedinUserData)){
	         	  		$tags = User::find($linkedinUserData->id)->Tags()->orderBy(DB::raw('RAND()'))->take(10)->get();
	         	  		$searchresult[$key]['UserData'] = array();
	         	  		$searchresult[$key]['Tags'] = array();
	         	  		$searchresult[$key]['UserData'] = $linkedinUserData;
	         	  		$searchresult[$key]['Tags'] = $tags;
         	  		}	
         	  	}
			}

			elseif($searchOption == 'Tags'){
				$searchquery = DB::select(DB::raw( 'select * from (select `users`.`fname`, `users`.`lname`, `users`.`piclink`, `users`.`linkedinid`,
										 `users`.`linkedinurl`, `users`.`headline`, `users`.`location`, `users`.`id` from 
										`users` where headline LIKE "%'.$search.'%" and `users`.`id` != '.$user_id.' and
										 `users`.`userstatus` = "approved" union select `connections`.`fname`,
										 `connections`.`lname`, `connections`.`piclink`, `connections`.`networkid`,
										 `connections`.`linkedinurl`, `connections`.`headline`, `connections`.`location`,
										 `users_connections`.`connection_id` from `connections`
										 inner join `users_connections` on `connections`.`id` = `users_connections`.`connection_id`
										 where headline LIKE "%'.$search.'%" and `users_connections`.`user_id` = '.$user_id.') as 
										result group by result.linkedinid order by result.location = "'.$location.'" desc limit 10 offset'.$start ));         	
			    //echo "<pre>";print_r($searchresult);echo "</pre>";die;
         	  	foreach ($searchquery as $key => $value) {
         	  		$searchresult[$key]['fname'] = $value->fname;
         	  		$searchresult[$key]['lname'] = $value->lname;
         	  		$searchresult[$key]['linkedinurl'] = $value->linkedinurl;
         	  		$searchresult[$key]['headline'] = $value->headline;
         	  		$searchresult[$key]['location'] = $value->location;
         	  		$searchresult[$key]['linkedinid'] = $value->linkedinid;
         	  		$searchresult[$key]['piclink'] = $value->piclink;
         	  		$searchresult[$key]['id'] = $value->id;

         	  		$linkedinid = $value->linkedinid;
         	  		$linkedinUserData = DB::table('users')->where('linkedinid', '=', $linkedinid)->first();
         	  		
         	  		if(!empty($linkedinUserData)){
	         	  		$tags = User::find($linkedinUserData->id)->Tags()->orderBy(DB::raw('RAND()'))->take(10)->get();
	         	  		$searchresult[$key]['UserData'] = array();
	         	  		$searchresult[$key]['Tags'] = array();
	         	  		$searchresult[$key]['UserData'] = $linkedinUserData;
	         	  		$searchresult[$key]['Tags'] = $tags;
         	  		}	
         	  	}
			}
			$totalCount = 0; 
			if(!empty($searchresult)) $totalCount = count($searchresult);
			//echo "<pre>"; print_r( $searchresult);echo "</pre>"; 
			$checkMsgLimit = KarmaHelper::CheckUserLinkedMgsLimit(); 
				
			
		return View::make('ajax_loadsearchresult',array('checkMsgLimit' => $checkMsgLimit,'CurrentUser' => $user_info,'searchresult'=> $searchresult, 'totalResult' => $totalCount, 'searchFor'=> $search, 'searchCat'=>$searchOption));
			
		}
			
			
	}

	/*search userconnection first page load*/
	public function searchConnections(){
		//check if user has reached the message limit
		$checkMsgLimit = KarmaHelper::CheckUserLinkedMgsLimit();
		$user_info = Auth::user();  
		$location = '';
		if(isset($user_info)){
			$user_id = $user_info->id;
			$location = $user_info->location;	
		}else{
			$user_id = 0;
			$location = '';
		}

		$searchresult = array();
		if(empty($_GET))
		{
			$start=0;
			$perpage = 30;     
			$hit = 1; 
			$getUserConnection = KarmaHelper::getLIMITEDUserConnection($user_id,$location,$start,$perpage);

			//echo "<pre>";print_r($getUserConnection);echo"</pre>";die;

	 	  	foreach ($getUserConnection as $key => $value) { 
				$linkedinid = $value->networkid;
	 	  		$linkedinUserData = DB::table('users')->where('linkedinid', '=', $linkedinid)->first();
				//if($linkedinUserData->userstatus != 'ready for approval'){ 
					$searchresult[$key]['fname'] = $value->fname;
					$searchresult[$key]['lname'] = $value->lname;
					$searchresult[$key]['linkedinurl'] = $value->linkedinurl;
					$searchresult[$key]['headline'] = $value->headline;
					$searchresult[$key]['location'] = $value->location;
					$searchresult[$key]['linkedinid'] = $value->networkid;
					$searchresult[$key]['piclink'] = $value->piclink;
					$searchresult[$key]['id'] = $value->id;

					if(!empty($linkedinUserData)){
						$tags = User::find($linkedinUserData->id)->Tags()->orderBy(DB::raw('RAND()'))->take(10)->get();
						$searchresult[$key]['UserData'] = array();
						$searchresult[$key]['Tags'] = array();
						if($linkedinUserData->userstatus != 'ready for approval')
						$searchresult[$key]['UserData'] = $linkedinUserData;
						else
						$searchresult[$key]['UserData'] = "";
						$searchresult[$key]['Tags'] = $tags; 
					}	
				//} 
	 	  	}  
			
			//echo "<pre>";print_r($searchresult);echo"</pre>";die;   
			
	 	  	$searchresult = array_values(array_sort($searchresult, function($value)
				{
					if(!empty($value['UserData']))
					{
						return $value['UserData']->karmascore;  
						//echo "yes".$value['UserData']->karmascore; 
					}   
					
				}));
	 	  	$searchresult = array_reverse($searchresult);  
	 	  	 
	     	$totalCount = 0; 
			//echo "<pre>";print_r($searchresult);echo"</pre>";   
			if(!empty($searchresult)) $totalCount = count($searchresult); 
			return View::make('user_connection',array('checkMsgLimit'=>$checkMsgLimit,'CurrentUser' => $user_info,'searchresult'=> $searchresult, 'totalResult' => $totalCount));
		}
	}


	/*search userconnection first page load*/
	public function LoadmoreuserConnections(){ 
		$checkMsgLimit = KarmaHelper::CheckUserLinkedMgsLimit();
		$user_info = Auth::user(); 
		$location = '';
		if(isset($user_info)){
			$user_id = $user_info->id;
			$location = $user_info->location;	
		}else{
			$user_id = 0;
			$location = '';
		}

		$searchresult = "";
		if(!empty($_REQUEST))
		{ 
			$start=$_REQUEST['hitcount']+1; 
			$perpage = 10;   
			$getUserConnection = KarmaHelper::getLIMITEDUserConnection($user_id,$location,$start,$perpage);

	 	  	foreach ($getUserConnection as $key => $value) {
	 	  		$searchresult[$key]['fname'] = $value->fname;
	 	  		$searchresult[$key]['lname'] = $value->lname;
	 	  		$searchresult[$key]['linkedinurl'] = $value->linkedinurl;
	 	  		$searchresult[$key]['headline'] = $value->headline;
	 	  		$searchresult[$key]['location'] = $value->location;
	 	  		$searchresult[$key]['linkedinid'] = $value->networkid;
	 	  		$searchresult[$key]['piclink'] = $value->piclink;
	 	  		$searchresult[$key]['id'] = $value->id;

	 	  		$linkedinid = $value->networkid;
	 	  		$linkedinUserData = DB::table('users')->where('linkedinid', '=', $linkedinid)->first();
	 	  		if(!empty($linkedinUserData)){
	     	  		$tags = User::find($linkedinUserData->id)->Tags()->orderBy(DB::raw('RAND()'))->take(10)->get();
	     	  		$searchresult[$key]['UserData'] = array();
	     	  		$searchresult[$key]['Tags'] = array();
	     	  		$searchresult[$key]['UserData'] = $linkedinUserData;
	     	  		$searchresult[$key]['Tags'] = $tags;
	 	  		}	
	 	  	}
	 	  	$searchresult = array_values(array_sort($searchresult, function($value)
				{
					if(!empty($value['UserData']))
					return $value['UserData']->karmascore; 
				}));
	 	  	$searchresult = array_reverse($searchresult); 
	     	$totalCount = 0;   
			if(!empty($searchresult)) $totalCount = count($searchresult); 
			return View::make('ajax_loadmoreuserConnections',array('checkMsgLimit'=>$checkMsgLimit,'perpage'=>$perpage,'CurrentUser' => $user_info,'searchresult'=> $searchresult, 'totalResult' => $totalCount));
		}
	}
	
	public function cropImage(){
		//checking if paramateres and file exists
		    $path = $_GET["path"];

		    //getting extension type (jpg, png, etc)
		    $type = explode(".", $path);
		    $ext = strtolower($type[sizeof($type)-1]);
		    $ext = (!in_array($ext, array("jpeg","png","gif"))) ? "jpeg" : $ext;

		    //get image size
		    $size = getimagesize($path);
		    $width = $size[0];
		    $height = $size[1];

		    //get source image
		    $func = "imagecreatefrom".$ext;
		    $source = $func($path);

		    //setting default values

		    $new_width = $width;
		    $new_height = $height;
		    $k_w = 1;
		    $k_h = 1;
		    $dst_x =0;
		    $dst_y =0;
		    $src_x =0;
		    $src_y =0;

		    //selecting width and height
		    if(!isset ($_GET["width"]) && !isset ($_GET["height"]))
		    {
		        $new_height = $height;
		        $new_width = $width;
		    }
		    else if(!isset ($_GET["width"]))
		    {
		        $new_height = $_GET["height"];
		        $new_width = ($width*$_GET["height"])/$height;
		    }
		    else if(!isset ($_GET["height"]))
		    {
		        $new_height = ($height*$_GET["width"])/$width;
		        $new_width = $_GET["width"];
		    }
		    else
		    {
		        $new_width = $_GET["width"];
		        $new_height = $_GET["height"];
		    }

		    //secelcting_offsets

		        if($new_width>$width )//by width
		        {
		            $dst_x = ($new_width-$width)/2;
		        }
		        if($new_height>$height)//by height
		        {
		            $dst_y = ($new_height-$height)/2; 
		        }
		        if( $new_width<$width || $new_height<$height )
		        {
		            $k_w = $new_width/$width;
		            $k_h = $new_height/$height;

		            if($new_height>$height)
		            {
		                $src_x  = ($width-$new_width)/2;
		            }
		            else if ($new_width>$width)
		            {
		                    $src_y  = ($height-$new_height)/2;
		            }
		            else
		            {
		                if($k_h>$k_w)
		                {
		                    $src_x = round(($width-($new_width/$k_h))/2);
		                }
		                else
		                {
		                    $src_y = round(($height-($new_height/$k_w))/2);
		                }
		            }
		        }
		    $output = imagecreatetruecolor( $new_width, $new_height);
		    //to preserve PNG transparency
		    if($ext == "png")
		    {
		        //saving all full alpha channel information
		        imagesavealpha($output, true);
		        //setting completely transparent color
		        $transparent = imagecolorallocatealpha($output, 0, 0, 0, 127);
		        //filling created image with transparent color
		        imagefill($output, 0, 0, $transparent);
		    }
		    imagecopyresampled( $output, $source,  $dst_x, $dst_y, $src_x, $src_y, 
		                        $new_width-2*$dst_x, $new_height-2*$dst_y, 
		                        $width-2*$src_x, $height-2*$src_y);
		    //free resources
		    ImageDestroy($source);

		    //output image
		    header('Content-Type: image/'.$ext);
		    $func = "image".$ext;
		    $func($output); 

		    //free resources
		    ImageDestroy($output);
	}

	public function searchConnectionData(){
		$user_info = Auth::user(); 
		$user_id = 0; $location = $search = $totalCount =$searchCat = '';
		$searchresult = array();
		if(!empty($_REQUEST['searchUsers'])) $search = $_REQUEST['searchUsers'];
		if(!empty($_REQUEST['searchCat'])) $searchCat = $_REQUEST['searchCat'];
		if($searchCat == 'People'){
			if(!empty($user_info)){
			$user_id = $user_info->id;
			$location = $user_info->location;
		}		
		$searchquery = 	DB::select(DB::raw('SELECT * from (SELECT `connections`.`fname`,
						`connections`.`lname`, `connections`.`piclink`, `connections`.`networkid`,
						`connections`.`linkedinurl`, `connections`.`headline`, `connections`.`location`,
						`connections`.`id`,`connections`.`user_id`,`users`.`userstatus` from `connections`
						inner join `users_connections` on `connections`.`id` = `users_connections`.`connection_id`
						LEFT JOIN `users` on (`users`.`id` = `connections`.`user_id`)
						WHERE concat(connections.fname," ",connections.lname) LIKE "%'.$search.'%" and `users_connections`.`user_id` = '.$user_id.' 
													UNION
						SELECT `connections`.`fname`,`connections`.`lname`, `connections`.`piclink`, `connections`.`networkid`,
						`connections`.`linkedinurl`, `connections`.`headline`, `connections`.`location`,
						`connections`.`id`,`connections`.`user_id`,`users`.`userstatus` from `connections` 
						inner join `users` on (`connections`.`user_id` = `users`.`id`)
						WHERE concat(connections.fname," ",connections.lname) LIKE "%'.$search.'%" and `connections`.`user_id` != "" 
						and `connections`.`user_id` != '.$user_id.' and `users`.`userstatus` = "approved" ) as 
						result group by result.networkid order by result.user_id desc,result.location = "'.$location.'" desc
						'));
	  	foreach ($searchquery as $key => $value) {
	  		$searchresult[$key]['fname'] = $value->fname;
	  		$searchresult[$key]['lname'] = $value->lname;
	  		$searchresult[$key]['linkedinurl'] = $value->linkedinurl;
	  		$searchresult[$key]['headline'] = $value->headline;
	  		$searchresult[$key]['location'] = $value->location;
	  		$searchresult[$key]['linkedinid'] = $value->networkid;
	  		$searchresult[$key]['piclink'] = $value->piclink;
	  		$searchresult[$key]['id'] = $value->user_id;
	  		//$searchresult[$key]['userstatus'] = $value->userstatus;
	  		$searchresult[$key]['unique_id'] = $key;
			//$checkKarmaUser = KarmaHelper::checkKarmaUser($value->networkid);
	  		if($value->user_id != '' && ($value->userstatus == 'approved' || $value->userstatus == ''))
	  			$karmaProfileLink = 'profile/'.strtolower($value->fname.'-'.$value->lname).'/'.$value->user_id;
	  		else
	  			$karmaProfileLink = '';
	  		$searchresult[$key]['karmaProfileLink'] = $karmaProfileLink;
	  	}
        $totalCount = 0;
		if(!empty($searchresult)) $totalCount = count($searchresult);
		//echo "<pre>";print_r($searchresult);echo "</pre>";
		return View::make('ajaxsearch_result',array('CurrentUser' => $user_info,'searchresult'=> $searchresult, 'totalResult' => $totalCount, 'searchFor'=> $search)); 	  	
		}
		elseif($searchCat == 'Groups'){
			$searchquery = 	DB::select(DB::raw('SELECT * FROM `groups` Where `groups`.`name` LIKE "%'.$search.'%"'));
			if(!empty($searchquery)){
			foreach ($searchquery as $key => $value) {
		  		$searchresult[$key]['id'] = $value->id;
		  		$searchresult[$key]['name'] = $value->name;
		  		$searchresult[$key]['description'] = $value->description;
	  		}
	  	}
			
			if(!empty($searchresult)) $totalCount = count($searchresult);
			//echo "<pre>";print_r($searchquery);echo "</pre>";die();
			return View::make('ajaxresult_groupSkills_group',array('CurrentUser' => $user_info,'searchresult'=> $searchresult, 'totalResult' => $totalCount, 'searchFor'=> $search,'searchCat'=> $searchCat)); 	  	

		}
		elseif($searchCat == 'Skills'){
			$searchquery = 	DB::select(DB::raw('SELECT * FROM `tags` Where `tags`.`name` LIKE "%'.$search.'%"'));
			if(!empty($searchquery)){
				foreach ($searchquery as $key => $value) {
			  		$searchresult[$key]['id'] = $value->id;
			  		$searchresult[$key]['name'] = $value->name; 
		  		}
			}
			if(!empty($searchresult)) $totalCount = count($searchresult);
			//echo "<pre>";print_r($searchquery);echo "</pre>";die();
			return View::make('ajaxresult_groupSkills',array('CurrentUser' => $user_info,'searchresult'=> $searchresult, 'totalResult' => $totalCount, 'searchFor'=> $search,'searchCat'=> $searchCat)); 	  	

		}
		
	}
	public function searchConnectionDataIntroGiver(){
		$user_info = Auth::user(); 
		$user_id = 0; $location = $search = $totalCount =$searchCat = '';
		$searchresult = array();
		if(!empty($_REQUEST['searchUsers'])) $search = $_REQUEST['searchUsers'];
		if(!empty($_REQUEST['searchCat'])) $searchCat = $_REQUEST['searchCat'];
		if($searchCat == 'People'){
			if(!empty($user_info)){
			$user_id = $user_info->id;
			$location = $user_info->location;
		}		
		$searchquery = 	DB::select(DB::raw('SELECT * from (SELECT `connections`.`fname`,
						`connections`.`lname`, `connections`.`piclink`, `connections`.`networkid`,
						`connections`.`linkedinurl`, `connections`.`headline`, `connections`.`location`,
						`connections`.`id`,`connections`.`user_id`,`users`.`userstatus` from `connections`
						inner join `users_connections` on `connections`.`id` = `users_connections`.`connection_id`
						LEFT JOIN `users` on (`users`.`id` = `connections`.`user_id`)
						WHERE concat(connections.fname," ",connections.lname) LIKE "%'.$search.'%"  and `connections`.`user_id` != "" and `users_connections`.`user_id` = '.$user_id.' 
													UNION
						SELECT `connections`.`fname`,`connections`.`lname`, `connections`.`piclink`, `connections`.`networkid`,
						`connections`.`linkedinurl`, `connections`.`headline`, `connections`.`location`,
						`connections`.`id`,`connections`.`user_id`,`users`.`userstatus` from `connections` 
						inner join `users` on (`connections`.`user_id` = `users`.`id`)
						WHERE concat(connections.fname," ",connections.lname) LIKE "%'.$search.'%" and `connections`.`user_id` != "" 
						and `connections`.`user_id` != '.$user_id.' and `users`.`userstatus` = "approved" ) as 
						result group by result.networkid order by result.user_id desc,result.location = "'.$location.'" desc
						'));
	  	foreach ($searchquery as $key => $value) {
	  		$searchresult[$key]['fname'] = $value->fname;
	  		$searchresult[$key]['lname'] = $value->lname;
	  		$searchresult[$key]['linkedinurl'] = $value->linkedinurl;
	  		$searchresult[$key]['headline'] = $value->headline;
	  		$searchresult[$key]['location'] = $value->location;
	  		$searchresult[$key]['linkedinid'] = $value->networkid;
	  		$searchresult[$key]['piclink'] = $value->piclink;
	  		$searchresult[$key]['id'] = $value->user_id;
	  		$searchresult[$key]['connection_id'] = $value->id;
	  		//$searchresult[$key]['userstatus'] = $value->userstatus;
	  		$searchresult[$key]['unique_id'] = $key;
			//$checkKarmaUser = KarmaHelper::checkKarmaUser($value->networkid);
	  		if($value->user_id != '' && ($value->userstatus == 'approved' || $value->userstatus == ''))
	  			$karmaProfileLink = 'profile/'.strtolower($value->fname.'-'.$value->lname).'/'.$value->user_id;
	  		else
	  			$karmaProfileLink = '';
	  		$searchresult[$key]['karmaProfileLink'] = $karmaProfileLink;
	  	}
        $totalCount = 0;
		if(!empty($searchresult)) $totalCount = count($searchresult);
		//echo "<pre>";print_r($searchresult);echo "</pre>";
		//check if user messaging limit is reached for thr day 
		$checkMsgLimit = KarmaHelper::CheckUserLinkedMgsLimit(); 
		return View::make('ajaxsearch_resultIntroGiver',array('checkMsgLimit' => $checkMsgLimit,'CurrentUser' => $user_info,'searchresult'=> $searchresult, 'totalResult' => $totalCount, 'searchFor'=> $search)); 	  	
		}
		
		
	}
	public function searchConnectionDataIntroReceiver(){
		$user_info = Auth::user(); 
		$user_id = 0; $location = $search = $totalCount =$searchCat = '';
		$searchresult = array();
		if(!empty($_REQUEST['searchUsers'])) $search = $_REQUEST['searchUsers'];
		if(!empty($_REQUEST['searchCat'])) $searchCat = $_REQUEST['searchCat'];
		if($searchCat == 'People'){
			if(!empty($user_info)){
			$user_id = $user_info->id;
			$location = $user_info->location;
		}		
		$searchquery = 	DB::select(DB::raw('SELECT * from (SELECT `connections`.`fname`,
						`connections`.`lname`, `connections`.`piclink`, `connections`.`networkid`,
						`connections`.`linkedinurl`, `connections`.`headline`, `connections`.`location`,
						`connections`.`id`,`connections`.`user_id`,`users`.`userstatus` from `connections`
						inner join `users_connections` on `connections`.`id` = `users_connections`.`connection_id`
						LEFT JOIN `users` on (`users`.`id` = `connections`.`user_id`)
						WHERE concat(connections.fname," ",connections.lname) LIKE "%'.$search.'%"  and `connections`.`user_id` != ""  
													UNION
						SELECT `connections`.`fname`,`connections`.`lname`, `connections`.`piclink`, `connections`.`networkid`,
						`connections`.`linkedinurl`, `connections`.`headline`, `connections`.`location`,
						`connections`.`id`,`connections`.`user_id`,`users`.`userstatus` from `connections` 
						inner join `users` on (`connections`.`user_id` = `users`.`id`)
						WHERE concat(connections.fname," ",connections.lname) LIKE "%'.$search.'%" and `connections`.`user_id` != "" 
						and `connections`.`user_id` != '.$user_id.' and `users`.`userstatus` = "approved" ) as 
						result group by result.networkid order by result.user_id desc,result.location = "'.$location.'" desc
						'));
	  	foreach ($searchquery as $key => $value) {
	  		$searchresult[$key]['fname'] = $value->fname;
	  		$searchresult[$key]['lname'] = $value->lname;
	  		$searchresult[$key]['linkedinurl'] = $value->linkedinurl;
	  		$searchresult[$key]['headline'] = $value->headline;
	  		$searchresult[$key]['location'] = $value->location;
	  		$searchresult[$key]['linkedinid'] = $value->networkid;
	  		$searchresult[$key]['piclink'] = $value->piclink;
	  		$searchresult[$key]['id'] = $value->user_id;
	  		$searchresult[$key]['connection_id'] = $value->id;
	  		//$searchresult[$key]['userstatus'] = $value->userstatus;
	  		$searchresult[$key]['unique_id'] = $key;
			//$checkKarmaUser = KarmaHelper::checkKarmaUser($value->networkid);
	  		if($value->user_id != '' && ($value->userstatus == 'approved' || $value->userstatus == ''))
	  			$karmaProfileLink = 'profile/'.strtolower($value->fname.'-'.$value->lname).'/'.$value->user_id;
	  		else
	  			$karmaProfileLink = '';
	  		$searchresult[$key]['karmaProfileLink'] = $karmaProfileLink;
	  	}
        $totalCount = 0;
		if(!empty($searchresult)) $totalCount = count($searchresult);
		//echo "<pre>";print_r($searchresult);echo "</pre>";
		return View::make('ajaxsearch_resultIntroReceiver',array('CurrentUser' => $user_info,'searchresult'=> $searchresult, 'totalResult' => $totalCount, 'searchFor'=> $search)); 	  	
		}
		
	}
	public function searchforskillonquery(){
		$user_info = Auth::user(); 
		if(!empty($_REQUEST['searchskill'])) $search = $_REQUEST['searchskill'];
		$searchquery = 	DB::select(DB::raw('SELECT * FROM `tags` Where `tags`.`name` LIKE "%'.$search.'%"'));
			if(!empty($searchquery)){
				foreach ($searchquery as $key => $value) {
			  		$searchresult[$key]['id'] = $value->id;
			  		$searchresult[$key]['name'] = $value->name;
		  		}
			}
			if(!empty($searchresult)) $totalCount = count($searchresult);
			//echo "<pre>";print_r($searchresult);echo "</pre>";die();
			return View::make('ajaxsearch_resultQuerySkills',array('CurrentUser' => $user_info,'searchresult'=> $searchresult, 'totalResult' => $totalCount, 'searchFor'=> $search)); 	  	
			//return View::make('ajaxresult_groupSkills',array('CurrentUser' => $user_info,'searchresult'=> $searchresult, 'totalResult' => $totalCount, 'searchFor'=> $search)); 	  	
		
	}
	public function searchforskillonqueryforprofile(){
		$user_info = Auth::user(); 
		if(!empty($user_info)){
			$user_id=$user_info->id;
		if(!empty($_REQUEST['searchskill'])) $search = $_REQUEST['searchskill'];
		//$searchquery = 	DB::select(DB::raw('SELECT * FROM `tags` Where `tags`.`name` LIKE "%'.$search.'%"And where id Not IN (select tag_id from users_tags where user_id='.$user_id.')'));
			$searchquery = DB::select(DB::raw("select * from tags where name LIKE '%".$search."%'And id Not IN (select tag_id from users_tags where user_id=".$user_id.")"));	
			//echo '<pre>';print_r($usersTagId);die;
			if(!empty($searchquery)){
				foreach ($searchquery as $key => $value) {
			  		$searchresult[$key]['id'] = $value->id;
			  		$searchresult[$key]['name'] = $value->name;
		  		}
			}
		}
			if(!empty($searchresult)) $totalCount = count($searchresult);
			//echo "<pre>";print_r($searchresult);echo "</pre>";die();
			return View::make('ajaxsearch_resultQuerySkills',array('CurrentUser' => $user_info,'searchresult'=> $searchresult, 'totalResult' => $totalCount, 'searchFor'=> $search)); 	  	
			//return View::make('ajaxresult_groupSkills',array('CurrentUser' => $user_info,'searchresult'=> $searchresult, 'totalResult' => $totalCount, 'searchFor'=> $search)); 	  	
		
	}
	public function errorshow(){
		   return Response::view('error.404');
	}

	/*Vanity URLs*/
	public function vanityredirect($name){
		$Vanityurl = Vanityurl::Where('vanityurl','=',$name)->first();
		if(!empty($Vanityurl)){
		//	echo "<pre>";print_r($Vanityurl);echo "</pre>";die();
			return Redirect::to($Vanityurl->redirecturl);
		}
		else{
			return Redirect::to('404');
		}
	}
	public static function storeKarmacirclesRecord(){
		$users = DB::table('users')->get();
		if(!empty($users)){
			foreach ($users as $user_info){
				$UserskarmaCircle = Karmacircle::where('user_id','=',$user_info->id)->delete();
				$user_circle_giver = DB::table('karmanotes')->select('user_idgiver')->distinct()->where('karmanotes.user_idreceiver','=',$user_info->id)->get();
				$user_circle_receiver = DB::table('karmanotes')->select('user_idreceiver')->distinct()->where('karmanotes.user_idgiver','=',$user_info->id)->get();
				$karmaCircle = new Karmacircle;
				if(!empty($user_info)){
					$karmaCircle->user_id=$user_info->id;
				}
				//code to save givers id.
				if(!empty($user_circle_giver)){
					$user_circle_giver_data='';
					foreach ($user_circle_giver as $key => $value) {
						$user_circle_giver_data[]=$value->user_idgiver;
					}
					if(!empty($user_circle_giver_data)){
						$user_circle_giver_data=array_filter($user_circle_giver_data);
						$karmaCircle ->givers = implode(',', $user_circle_giver_data);
					}
				}
				
				//code to save recivers id.
				if(!empty($user_circle_receiver)){
					$user_circle_receiver_data='';
					foreach ($user_circle_receiver as $key => $value) {
						$user_circle_receiver_data[]=$value->user_idreceiver;
					}
					if(!empty($user_circle_receiver_data)){
						$user_circle_receiver_data=array_filter($user_circle_receiver_data);
						$karmaCircle ->receivers 						= implode(',', $user_circle_receiver_data);
					}
				}
				//code to save receiver's receiver id.
				if(!empty($user_circle_receiver_data)){
					$users_receivers = DB::table('karmanotes')->select('user_idreceiver')->distinct()->whereIn('user_idgiver', $user_circle_receiver_data)->get();	
					$user_circle_receiver_receiver_data='';
					foreach ($users_receivers as $key => $value_receiver) {
						$user_circle_receiver_receiver_data[]=$value_receiver->user_idreceiver;
					}
				}	
				if(!empty($user_circle_receiver_receiver_data)){
					$user_circle_receiver_receiver_data=array_filter($user_circle_receiver_receiver_data);
					$karmaCircle ->receivers_receivers = implode(',', $user_circle_receiver_receiver_data);
				}
				$karmaCircle->save();
			}
				return Redirect::to('dashboard');
			
		}
	}

	public static function storeKarmacirclesRelationCron(){
		$giver=1288;
		$receiver=1395;
		$currentDate=Carbon::now();
		$giverReceiverData= DB::select(DB::raw("SELECT DISTINCT user_idgiver As giver,user_idreceiver As receiver FROM karmanotes WHERE created_at > DATE_SUB(NOW(), INTERVAL 2 DAY)"));
		echo '<pre>';print_r($giverReceiverData);die;
		//All the karmanotes sends by the users.
		if(!empty($giverReceiverData)){
			$giversGiver= DB::select(DB::raw("select peer_id as Id from user_karmacircles where user_id=".$giver." AND giver='1' AND Id NOT IN (select peer_id from user_karmacircles where user_id=".$receiver." AND givers_giver='1')"));	
			//Fetch the list of all Peers where User is A and Giver flag is set and not in (all Peers where User is B and GiversGiver flag is is set)
				//echo '<pre>';print_r($giversGiver);die;
				foreach ($giversGiver as $key => $value) {
					$value=$value->Id;
					$checkGiverData= DB::select(DB::raw("select count(id) as rowCount from user_karmacircles where user_id=".$receiver." AND peer_id=".$value));
					$checkGiverData=$checkGiverData[0]->rowCount;
					//Insert a row with B as User, P as Peer and GiversGiver flag = True all other flags zero with GG_Date updated
					if($checkGiverData < 1){
						$peerId=$value;
						$karmaCircle= new Userskarmacircle;
						$karmaCircle->user_id=$receiver;
						$karmaCircle->peer_id=$peerId;
						$karmaCircle->givers_giver=1;
						$karmaCircle->givers_giver_date=Carbon::now();
						$karmaCircle->save();
					}else{
						$checkGiversFlag= DB::select(DB::raw("select count(id) as rowCount from user_karmacircles where user_id=".$receiver." AND peer_id=".$value." AND givers_giver=1"));
						$checkGiversFlag=$checkGiversFlag[0]->rowCount;
						//set givers_giver flag.
						if($checkGiversFlag < 1){
							$result=DB::table('user_karmacircles')->where('user_id','=',$receiver)->where('peer_id','=',$value)->update(array('givers_giver' => '1','givers_giver_date' => $currentDate));	
						}
						
					}

					$checkReceiverData= DB::select(DB::raw("select count(*) as rowCount from user_karmacircles where user_id=".$value." AND peer_id=".$receiver));
					$checkReceiverData=$checkReceiverData[0]->rowCount;
					if($checkReceiverData < 1){
						$peerId=$value;
						$karmaCircle= new Userskarmacircle;
						$karmaCircle->user_id=$peerId;
						$karmaCircle->peer_id=$receiver;
						$karmaCircle->receivers_receiver=1;
						$karmaCircle->receivers_receiver_date=Carbon::now();
						$karmaCircle->save();
					}else{
						$checkReceiversFlag= DB::select(DB::raw("select count(*) as rowCount from user_karmacircles where user_id=".$value." AND peer_id=".$receiver." And receivers_receiver=1"));
						$checkReceiversFlag=$checkReceiversFlag[0]->rowCount;
						if($checkReceiversFlag < 1){
							$result=DB::table('user_karmacircles')->where('peer_id','=',$receiver)->where('user_id','=',$value)->update(array('receivers_receiver' => '1','receivers_receiver_date' => $currentDate));	
						}
					}
						
				}
		}
		
		$receiversReceiver= DB::select(DB::raw("select peer_id as Id from user_karmacircles where user_id=".$receiver." AND receiver='1' AND Id NOT IN (select peer_id from user_karmacircles where user_id=".$giver." AND receivers_receiver='1')")); 	
		foreach ($receiversReceiver as $key => $value) {
			$value=$value->Id;
			$checkReceiverData= DB::select(DB::raw("select count(*) as rowCount from user_karmacircles where user_id=".$value." AND peer_id=".$giver));
			$checkReceiverData=$checkReceiverData[0]->rowCount;
			if($checkReceiverData < 1){
				$peerId=$value;
				$karmaCircle= new Userskarmacircle;
				$karmaCircle->user_id=$peerId;
				$karmaCircle->peer_id=$giver;
				$karmaCircle->givers_giver=1;
				$karmaCircle->givers_giver_date=Carbon::now();
				$karmaCircle->save();
			}else{
				$checkReceiversCount= DB::select(DB::raw("select count(id) as rowCount from user_karmacircles where user_id=".$value." AND peer_id=".$giver." And givers_giver=1"));
				$checkReceiversCount=$checkReceiversCount[0]->rowCount;
				if($checkReceiversCount < 1){
					$result=DB::table('user_karmacircles')->where('user_id','=',$value->Id)->where('peer_id','=',$giver)->update(array('givers_giver' => '1','givers_giver_date' => $currentDate));	
				}
			}

			$checkGiverData= DB::select(DB::raw("select count(*) as rowCount from user_karmacircles where user_id=".$giver." AND peer_id=".$value));
			$checkGiverData=$checkGiverData[0]->rowCount;
			if($checkGiverData < 1){
				$peerId=$value;
				$karmaCircle= new Userskarmacircle;
				$karmaCircle->user_id=$giver;
				$karmaCircle->peer_id=$peerId;
				$karmaCircle->receivers_receiver=1;
				$karmaCircle->receivers_receiver_date=Carbon::now();
				$karmaCircle->save();
			}else{
				$checkGiversCount= DB::select(DB::raw("select count(id) as rowCount from user_karmacircles where user_id=".$giver." AND peer_id=".$value." And receivers_receiver=1"));
				$checkGiversCount=$checkGiversCount[0]->rowCount;
				if($checkGiversCount < 1){
					$result=DB::table('user_karmacircles')->where('peer_id','=',$value)->where('user_id','=',$giver)->update(array('receivers_receiver' => '1','receivers_receiver_date' => $currentDate));	
				}
			}
					
		}
	}

	public static function commonConnection($userId,$otherUserId){
		$usersFirstCommonConnectionGiver=Karmanote::where('user_idreceiver','=',$userId)->where('user_idgiver','<>','null')->select('user_idgiver')->distinct('user_idgiver')->get();
		$usersFirstCommonConnectionReceiver=Karmanote::where('user_idgiver','=',$userId)->where('user_idreceiver','<>','null')->select('user_idreceiver')->distinct('user_idreceiver')->get();
		$userFirstData=array_merge($usersFirstCommonConnectionGiver->toArray(),$usersFirstCommonConnectionReceiver->toArray());
		foreach ($userFirstData as $key => $value) {
			if(isset($value['user_idreceiver'])){
				$userFirstCommonId[]=$value['user_idreceiver'];	
			}
			if(isset($value['user_idgiver'])){
				$userFirstCommonId[]=$value['user_idgiver'];	
			}
		}
		$userFirstCommonIdResult = array_unique($userFirstCommonId);
		$usersSecondCommonConnectionGiver=Karmanote::where('user_idreceiver','=',$otherUserId)->where('user_idgiver','<>','null')->select('user_idgiver')->distinct('user_idgiver')->get();
		$usersSecondCommonConnectionReceiver=Karmanote::where('user_idgiver','=',$otherUserId)->where('user_idreceiver','<>','null')->select('user_idreceiver')->distinct('user_idreceiver')->get();
		$userSecondData=array_merge($usersSecondCommonConnectionGiver->toArray(),$usersSecondCommonConnectionReceiver->toArray());
		foreach ($userSecondData as $key => $value) {
			if(isset($value['user_idreceiver'])){
				$userSecondCommonId[]=$value['user_idreceiver'];	
			}
			if(isset($value['user_idgiver'])){
				$userSecondCommonId[]=$value['user_idgiver'];	
			}
		}
		$userSecondCommonIdResult = array_unique($userSecondCommonId);
		$commonUser = array_intersect($userFirstCommonIdResult,$userSecondCommonIdResult);
		$countCommonConnection=count($commonUser);
		
		return $countCommonConnection;	
	}
	//script for saving my karma data for old meeting.
	public function scriptForMyKarma(){	
		$getMeetingData= DB::table('requests')->where('user_id_receiver','=','605')->orWhere('user_id_giver','=','605')->get();
		foreach ($getMeetingData as $key => $value) {
			$receiverId=$value->user_id_receiver;
			$updatedDate=$value->updated_at;
			//for complete meeting

			if($value->status=='completed'){
				if($value->user_id_giver !='' && $value->user_id_giver !='NULL'){
					$giverId=$value->user_id_giver;
					$connectionGiverId='null';
				}else{
					$giverId='null';
					$connectionGiverId=$value->connection_id_giver;
				}
				$meetingStatus=$value->status;
				$meetingId=$value->id;
				$saveDataInMyKarma=KarmaHelper::saveDataInMyKarma($receiverId,$giverId,$meetingId,$meetingStatus,$connectionGiverId,$updatedDate);	
			}else if($value->user_id_giver !='' && $value->user_id_giver !='NULL'){
				$giverId=$value->user_id_giver;
				$connectionGiverId='null';
				$meetingStatus=$value->status;
				$meetingId=$value->id;
				$saveDataInMyKarma=KarmaHelper::saveDataInMyKarma($receiverId,$giverId,$meetingId,$meetingStatus,$connectionGiverId,$updatedDate);
			}
			
			//else //for uncomplete meeting status.
			
		}
		echo 'success';exit;
		
	}
	//script for saving my karma data for old meeting.
	public function scriptForDeletingIndividualMyKarma(){
		$userId=114;
		$mykarmaData=Mykarma::where('user_id','=',$userId)->select('entry_id')->get();
		foreach ($mykarmaData as $key => $value) {
			$id=$value->entry_id;
			$deleteMykarma=Mykarma::where('entry_id','=',$id)->delete();
		}
		echo 'success';
	}	
	

	
	
}

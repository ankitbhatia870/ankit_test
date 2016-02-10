<?php 

class KarmaHelper {
	public static function dateDiff($start,$end=false){
		$return = array();
		try {
			$start = new DateTime($start);
			$end = new DateTime($end);
			$form = $start->diff($end);
			} 
		catch (Exception $e){
			return $e->getMessage();
		}
		return $form;
		/*$display = array('y'=>'year',
						'm'=>'month',
						'd'=>'day',
						'h'=>'hour',
						'i'=>'minute',
						's'=>'second');
		foreach($display as $key => $value){
			if($form->$key > 0){
				$return[] = $form->$key.' '.($form->$key > 1 ? $value.'s' : $value);
			}
		}
		return implode($return, ', ');*/
	}

	/*Get Current time in format*/
	public static function currentDate(){
		return date('Y-m-d H:i:s');
	}

	/*Insert Tags and Users Tag in Database*/
	public static function insertUsertag($user,$result){ 
		$user_id = $user->id;
		$token = $user->token;
			
		if(!isset($result) || $result != ''){
			$result = json_decode(file_get_contents("https://api.linkedin.com/v1/people/~:(id,first-name,last-name,skills,headline,summary,industry,member-url-resources,picture-urls::(original),location,public-profile-url,email-address)?format=json&oauth2_access_token=$token"));	
			if(!empty($result->skills->values)){
				Userstag::where('user_id', '=',$user_id)->delete();	
				foreach ($result->skills->values as $key => $item){
					$tag = new Tag;
					$Tag = tag::firstOrCreate(array('name' =>  $item->skill->name));
					$tag_id = $Tag['id'];
					$users_tag = new Userstag;
					$users_tag->user_id = $user_id;
					$users_tag->tag_id = $tag_id;
					$users_tag->save();
				}
				return true;
			}
		}
		else{
			if(!empty($result['skills']['values'])){
				foreach ($result['skills']['values'] as $key => $item){
					$tag = new Tag;
					$Tag = tag::firstOrCreate(array('name' =>  $item['skill']['name']));
					$tag_id = $Tag['id'];
					$users_tag = new Userstag;
					$users_tag->user_id = $user_id;
					$users_tag->tag_id = $tag_id;
					$users_tag->save();
				}
				return true;
			}
		}		
		return true;//echo "<pre>"; print_r($result); echo "</pre>";die();
		
	}

	/*Insert Connections and User Connection in Database*/
	public static function insertUserConnection($user){
		$user_id = $user->id;
		$token = $user->token;
		
		$user_connection_data = "https://api.linkedin.com/v1/people/~/connections?id,first-name,last-name,headline,summary,industry,member-url-resources,picture-urls::(original),location,public-profile-url,site-standard-profile-request&format=json&oauth2_access_token=$token";
		//echo $user_connection_data;exit;
		$ch = curl_init($user_connection_data);
  		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
  		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
  		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
  		$data = curl_exec($ch);
  		curl_close($ch);
  		$user_connection=json_decode($data);
  		Log::alert('User connection fetch');  

		 

		/* for($i=0;$i<count($user_connection->person);$i++)
		{
			print_r($user_connection->person[$i]->{'first-name'}); 
		} */ 
		//echo "<pre>========="; print_r($user_connection); echo "</pre>========"; die;
		
		if(!empty($user_connection->values)){
			$group = new Usersgroup;
			$group->user_id=$user_id;
			$group->group_id=1;
			$group->save();
			Usersconnection::where('user_id', '=',$user_id)->delete();
			foreach ($user_connection->values as $key => $value) {
				if(!empty($value->id) && ($value->id != 'private')){		
					if(!isset($value->publicProfileUrl) || ($value->publicProfileUrl == '')){
						$publicProfileUrl = $value->siteStandardProfileRequest->url;
					}
					else{
						$publicProfileUrl = $value->publicProfileUrl;
					}
					$connection = new Connection;
					$connection = Connection::firstOrCreate(array('networkid' => $value->id));
					if($connection['user_id'] != ''){
						$Connuser = User::find($connection['user_id']);
				       /* $Connuser->fname 				= @$value->firstName;
						$Connuser->lname 				= @$value->lastName;*/
						$Connuser->location 			= @$value->location->name;
						$Connuser->industry 			= @$value->industry;
						$Connuser->piclink 				= @$value->pictureUrl;
						$Connuser->linkedinurl 			= @$publicProfileUrl;
						$Connuser->headline 			= @$value->headline;
						$Connuser->save();
					}
					$connection_id = $connection['id'];
					$connection = connection::find($connection_id);
					$connection->networktype 	= 'linkedin';
					if($connection->user_id == ''){
						$connection->fname 			= @$value->firstName;
						$connection->lname 			= @$value->lastName;
					}
					$connection->headline 		= @$value->headline;
					$connection->industry 		= @$value->industry;
					$connection->location 		= @$value->location->name;
					$connection->piclink 		= @$value->pictureUrl; 
					$connection->linkedinurl 	= @$publicProfileUrl;
					$connection->save();
					
					$users_connections = new Usersconnection;
					$users_connections->user_id = $user_id;
					$users_connections->connection_id = $connection_id;
					$users_connections->save();
					$userDetail = User::find($user_id);
					$userDetail->totalConnectionCount = $user_connection->_total;
					$userDetail->save();	
				}
			}
		}
		$userDetail = User::find($user_id);
		if(empty($userDetail->totalConnectionCount)){
			$userDetail->totalConnectionCount = 0;
			$userDetail->save();
		}
		
		$connection = new Connection;
		$connection = Connection::firstOrCreate(array('networkid' => $user['linkedinid']));
		$connection_id = $connection['id'];
		$connection = Connection::find($connection_id);
		$connection->networktype 	= 'linkedin';
		if($connection->user_id == ''){
			$connection->fname 			= @$user['fname'];
			$connection->lname 			= @$user['lname'];
		}
		$connection->headline 		= @$user['headline'];
		$connection->industry 		= @$user['industry'];
		$connection->location 		= @$user['location'];
		$connection->piclink 		= @$user['piclink'];
		$connection->linkedinurl 	= @$user['linkedinurl'];
		$connection->user_id 		= $user_id	;
		$connection->save();
		return true;
		
		
	}
	
	
	/*For Updating user profile after 15 days using queue*/
	public static function updateUserProfile($user_id,$result){
		//echo '<pre>';print_r($result);die;
		$imageurl = "";
		$userId=$user_id->id;
		$user = User::find($userId);
		if(isset($result['emailAddress'])){
			$user->email 				= $result['emailAddress'];
		}
       	if(isset($result['pictureUrls']['values'][0])){
			$user->piclink 				= $result['pictureUrls']['values'][0];
		}	
		$user->location 			= @$result['location']['name'];
		
		$user->headline 			= $result['headline'];
		$user->linkedinurl 			= @$result['publicProfileUrl'];
        $user->profileupdatedate 	= date('Y-m-d H:i:s');
		$user->save();
		return true;
	}

/*Function for updating Request table and karma note table when user 1st login*/
	public static function updateRequestAndKarmaNote($user_data){
		$user_id = $user_data->id;
		
		$connection_info = DB::table('connections')->where('user_id', '=', $user_id)->get();
		$connection_id = $connection_info[0]->id;
		$updateUsersMyKarmaTable = DB::table('users_mykarma')->where('user_id', '=', $connection_id)->get();
		if(!empty($updateUsersMyKarmaTable)){
			foreach ($updateUsersMyKarmaTable as $key => $value) {
				$myKarmaData = MyKarma::find($value->id);
				$myKarmaData->user_id = $user_id;
				$myKarmaData->save();
			}	
		}
		$meetingRequestData = DB::table('requests')->where('connection_id_giver', '=', $connection_id)->get();
		foreach ($meetingRequestData as $key => $value) {
			$Meetingrequest = Meetingrequest::find($value->id);
			$Meetingrequest->user_id_giver = $user_id;
			$Meetingrequest->save();
		}
		$karmaNoteData = DB::table('karmanotes')->where('connection_idgiver', '=', $connection_id)->get();
		foreach ($karmaNoteData as $key => $value) {
			$Karmanote = Karmanote::find($value->id);
			$Karmanote->user_idgiver = $user_id;
			$Karmanote->save();
			KarmaHelper::updateKarmaScore($Karmanote->user_idgiver,$Karmanote->user_idreceiver);
		}
		return true;
	}

/*Function for checking connection is karma user or not*/
	public static function checkKarmaUser($linkedinId){
		if($linkedinId){
			$userDetail = DB::table('users')
			            ->where('linkedinid', '=', $linkedinId)
			            ->select('id')
			            ->get();
			if(!empty($userDetail))            
				return true;
			else
				return false;
		}else{
			return false;
		}
	}

	/* Function for get Pending request of karmanotes of user */
	public static function getPendingKarmaNotes($user_id){
		if(!empty($user_id)){
			$pendingRequest = DB::table('requests')
			            ->join('users', 'users.id', '=', 'requests.user_id_giver')
			            ->where('requests.status', '=', 'accepted')
			            ->where('requests.user_id_receiver', '=', $user_id)
			            ->select('requests.id','requests.notes', 'requests.meetingdatetime', 'users.id as user_id','users.fname', 'users.lname', 'users.email', 'users.piclink', 'users.linkedinid', 'users.linkedinurl', 'users.location', 'users.headline', 'users.karmascore','requests.updated_at')
			            ->get();
			return $pendingRequest;			            
		}else{
			return false;
		}		            

	}
	/* Function for fetch Karma note requests for a user */
	public static function getReceivedRequestKarmaNotes($user_id){
		if(!empty($user_id)){
			$requestNotes = DB::table('karmanotes')
						->join('requests', 'requests.id', '=', 'karmanotes.req_id')
			            ->join('users', 'users.id', '=', 'requests.user_id_receiver')
			            ->where('karmanotes.user_idgiver', '=', $user_id)
			            ->select('karmanotes.id', 'karmanotes.details', 'karmanotes.statusgiver', 'karmanotes.skills', 'karmanotes.req_id', 'karmanotes.created_at', 'users.id', 'users.fname', 'users.lname', 'users.email', 'users.piclink', 'users.linkedinid', 'users.linkedinurl', 'users.location', 'users.headline', 'requests.user_id_receiver', 'users.karmascore', 'requests.user_id_giver')
			            ->get();
			return $requestNotes;
		}else return false;
	}
	
	/* Function for fetch Karma notes sent by a user */
	public static function getSentKarmaNotes($user_id){
		if(!empty($user_id)){
			$requestNotes = DB::table('karmanotes')
			            ->join('users', 'users.id', '=', 'karmanotes.user_idgiver')
			            ->join('requests', 'requests.id', '=', 'karmanotes.req_id')
			            ->where('requests.user_id_receiver', '=', $user_id)
			            ->select('karmanotes.details', 'karmanotes.statusgiver', 'karmanotes.skills', 'karmanotes.req_id', 'karmanotes.created_at',  'users.id', 'users.fname', 'users.lname', 'users.email', 'users.piclink', 'users.linkedinid', 'users.linkedinurl', 'users.location', 'users.headline', 'requests.user_id_receiver', 'requests.user_id_giver', 'users.karmascore')
			            ->get();
			return $requestNotes;
		}else return false;
	}

	/* Function for fetch Karma trail of user */
	public static function getKarmaTrail($user_id,$start,$perpage){
		if(!empty($user_id)){
			$karmaTrail = DB::table('karmanotes')
						->join('requests', 'requests.id', '=', 'karmanotes.req_id')
			            ->join('users', 'users.id', '=', 'requests.user_id_receiver')
			            ->where('requests.user_id_receiver', '=', $user_id)     
			            ->orwhere('requests.user_id_giver', '=', $user_id)
			            ->orderBy('karmanotes.created_at','desc')
			            ->select('karmanotes.details', 'karmanotes.statusgiver', 'karmanotes.statusreceiver', 'karmanotes.skills', 'karmanotes.req_id', 'karmanotes.created_at', 'users.id', 'users.fname', 'users.lname', 'users.email', 'users.piclink', 'users.linkedinid', 'users.linkedinurl', 'users.location', 'users.headline' , 'requests.user_id_receiver', 'requests.user_id_giver','requests.connection_id_giver','requests.status', 'requests.connection_id_giver','requests.meetingdatetime', 'users.karmascore')
			            ->skip($start)->take($perpage) 
			            ->get();
			return $karmaTrail;
		}else return false;
	}

/*Function for updating user karma score */
	public static function updateKarmaScore($giver_id,$receiver_id){
		$user_giver = User::find($giver_id);
		$user_giver->karmascore = $user_giver->karmascore + 10;
		$user_giver->save();
		$user_receiver = User::find($receiver_id);
		$user_receiver->karmascore = $user_receiver->karmascore + 2;
		$user_receiver->save();
		return true;		
	}
/*Function for cutting the string with respect to length given*/
	public static function stringCut($string,$length){
		if (strlen($string) > $length){
	        $stringCut = substr($string, 0, $length);
	        // make sure it ends in a word so assassinate doesn't become ass...
	        $string = substr($stringCut, 0, strrpos($stringCut, ' ')).'...'; 
    	}
    	return $string;
	}
	public static function generateURL($meetingId,$receiverDetail,$giverDetail){ 
		$url =  URL::to('')."/meeting/".$receiverDetail->fname.'-'.$receiverDetail->lname.'-'.$giverDetail->fname.'-'.$giverDetail->lname."/".$meetingId;
		return $url;
	}
	/*function for checking receiver connection with giver*/
	public static function CheckConnection($receiverDetail,$giverDetail){
		$giverConnectionId = Connection::where('networkid','=',$giverDetail->linkedinid)->first()->id;
		$receiverConnection = User::find($receiverDetail->id)->Connections()->where('connection_id','=',$giverConnectionId)->get()->toArray();
		//echo "<pre>"; print_r($giverConnectionId);echo "</pre>";die();
		if(empty($receiverConnection)){
			return false;
		}
		else{
			return true;
		}
	}
/* Function for fetching tags by multiple tag id*/
	public static function getSkillsname($tagIds){
		if(!empty($tagIds)){
			//$tags = str_split($tagIds);
			$tags = explode(',', $tagIds);
			$skills = Tag::whereIn('tags.id', $tags)->get();
			return $skills;
		}else{
			return false;
		}
	}
	/* Function to get time slot with half an hour intervals */
	public static function halfHourTimes() {
		  $formatter = function ($time) {
		    if ($time % 3600 == 0) {
		      return date('h:i a', $time);
		    } else {
		      return date('h:i a', $time);
		    }
		  };
		  $halfHourSteps = range(0, 47*1800, 1800);
		  return array_map($formatter, $halfHourSteps);
	}
	/* get meeting detail by meeting Id */
	public static function getMeetingDetail($meetingId){
		$meetingDetail = Meetingrequest::find($meetingId)->toArray();
		return $meetingDetail;
	}
	/*function for getting count of the Unread MeetingRequest*/
	public static function UnreadMeetingRequest(){
		if (Auth::check()) {
			$user_info = Auth::User();
			$Receiver = $user_info->id;
	        $ReceiverDetail = User::find($Receiver); 
	        $MeetingRequestPending = $ReceiverDetail->Giver()->Where('status','=','pending')->count();
			$MeetingRequestUnreadRequest = $ReceiverDetail->Giver()->Where('requestviewstatus','=','0')->Where('status','=','pending')->count();
			$MeetingRequestUnreadReply = $ReceiverDetail->Receiver()->Where('replyviewstatus','=','0')->Where('status','=','accepted')->count();
			$totalUnreadRequest = $MeetingRequestUnreadRequest+$MeetingRequestUnreadReply;
			return $totalUnreadRequest;
		}
		
	}
	/*function for getting count of the unread karmanote*/
	public static function UnreadKarmaNote(){
		if (Auth::check()) {
			$user_info = Auth::User();
			$Receiver = $user_info->id;
	        $ReceiverDetail = User::find($Receiver); 
			$totalUnreadkarmanote = $ReceiverDetail->KarmanoteReceiver()->Where('viewstatus','=','0')->count();
			return 	$totalUnreadkarmanote;	
		}		
	}
	/*function for changing the read status of request and karmannote*/
	public static function ChangeReadStatus($Detail,$StatusName){		
		if($StatusName == 'replyviewstatus'){
			$MeetingRequest = Meetingrequest::find($Detail->id);
			$MeetingRequest->replyviewstatus = '1';
			$MeetingRequest->save();
		}
		elseif($StatusName == 'requestviewstatus'){
			$MeetingRequest = Meetingrequest::find($Detail->id);
			$MeetingRequest->requestviewstatus = '1';
			$MeetingRequest->save();
		}
		elseif($StatusName == 'KarmaNoteStatus'){
			$KarmanoteDetail = Karmanote::find($Detail->id);
			$KarmanoteDetail->viewstatus = '1';
			$KarmanoteDetail->save();
		}
		return true;
	}
	/*function for calculating time according to the time zone*/
	public static function calculateTime($meetingtimeZone){
		$minutes = $hours = "";
		$hours = floor(abs($meetingtimeZone));
		$minutes = round(60*(abs($meetingtimeZone)-$hours));
		if($meetingtimeZone < 0){
			$timestring = "- ".$hours." hours ".$minutes." minutes";
			if(empty($minutes)){
				$timestring = "- ".$hours." hours";
			}
			$currentTimeWithZone= date("Y-m-d H:i:s", strtotime(KarmaHelper::currentDate().$timestring));
		}
		else{
			$timestring = "+ ".$hours." hours ".$minutes." minutes";
			if(empty($minutes)){
				$timestring = "+ ".$hours." hours";
			}			
			$currentTimeWithZone= date("Y-m-d H:i:s", strtotime(KarmaHelper::currentDate().$timestring));
		}
		return $currentTimeWithZone;
	}

	/*Function for updating user karma score to 5 for introducer */
	public static function updateIntroducerKarmaScore($introducer_id){
		$introducer = User::find($introducer_id);
		$introducer->karmascore = $introducer->karmascore + 5;
		$introducer->save();
		return true;		
	}

	/*Function to get user connections of a user KC and non KC both*/
	public static function getUserConnection($user_id,$location){
		$searchquery = DB::table('users_connections')
					->join('connections', 'users_connections.connection_id', '=', 'connections.id')
					//->join('users', 'connections.user_id', '=', 'users.id')
					->select(array('connections.user_id as con_user_id','connections.networkid',
						'connections.fname','connections.lname','connections.piclink','connections.linkedinurl',
						'connections.headline','connections.location','connections.id',
						/*'users_connections.*','users.lname as user_lname','users.fname as user_fname'
						,'users.userstatus','users.karmascore',*/
						))
		            ->where('users_connections.user_id','=',$user_id)
		            ->orderBy('con_user_id','DESC')
		            ->get();
		return $searchquery;
	}

	/*Function to get user connections of a user KC and non KC both*/
	public static function getConnectionData($user_id,$location){
		$searchquery = DB::table('connections')
						->select(array('connections.user_id as con_user_id','connections.networkid',
						'connections.fname','connections.lname','connections.piclink','connections.linkedinurl',
						'connections.headline','connections.location','connections.id',
						/*'users_connections.*','users.lname as user_lname','users.fname as user_fname'
						,'users.userstatus','users.karmascore',*/
						))
		            ->where('connections.user_id','=',$user_id)
		            ->get();
		return $searchquery;
	}

	/*Function to get user connections of a user only non KC both*/
	public static function getUserNonKcConnection($user_id,$location){
		$searchquery = DB::table('users_connections')
					->join('connections', 'users_connections.connection_id', '=', 'connections.id')
					//->join('users', 'connections.user_id', '=', 'users.id')
					->select(array('connections.user_id as con_user_id','connections.networkid',
						'connections.fname','connections.lname','connections.piclink','connections.linkedinurl',
						'connections.headline','connections.location','connections.id',
						/*'users_connections.*','users.lname as user_lname','users.fname as user_fname'
						,'users.userstatus','users.karmascore',*/
						))
		            ->where('users_connections.user_id','=',$user_id)
		            ->where('connections.user_id','=',NULL)
		            ->orderBy('con_user_id','DESC')
		            ->get();
		return $searchquery;
	}

	// fetch a random users on KC platform with a common group of logged in user
	public static function getKcuser($user_id){
		$getUser = array();
		$getUser = KarmaHelper::getTestUsers();
		$user_info = Auth::User();
		if(!empty($user_info)){
			$Receiver = $user_info->id;
		}

		if(empty($Receiver)){
			if($user_id > 0){
				$Usergroup 	= KarmaHelper::getuserGroup();
				$All_groups = '';
				if(!$Usergroup->isEmpty()){ 
					foreach ($Usergroup as $key => $value) {
						$All_groups[] = $value->id;
					}	
					$getKcuser = DB::table('users as u')
							->join('users_groups', 'u.id', '=', 'users_groups.user_id')
							->select(array('u.userstatus','u.id','u.fname','u.lname','u.linkedinurl','u.piclink','u.headline','u.email','u.karmascore','u.location'))
				            ->whereIn('users_groups.group_id',$All_groups)
				            ->WhereNotIn('u.id',$getUser)
				            //->Where('u.id','!=',$Receiver)
				            ->Where('u.id','!=',$user_id)  
				            ->distinct()
				            ->where('u.userstatus','=','approved')
				            ->orderByRaw("RANd()")->take(3)  
				            ->get();
					if(empty($getKcuser))
					{
						$getKcuser = DB::table('users as u')
							->join('users_groups', 'u.id', '=', 'users_groups.user_id')
							->select(array('u.userstatus','u.id','u.fname','u.lname','u.linkedinurl','u.piclink','u.headline','u.email','u.karmascore','u.location')) 
				            //->whereIn('users_groups.group_id',$All_groups)
				            ->WhereNotIn('u.id',$getUser)
				            //->Where('u.id','!=',$Receiver)
				            ->Where('u.id','!=',$user_id)  
				            ->distinct()
				            ->where('u.userstatus','=','approved')
				            ->orderByRaw("RANd()")->take(3)   
				            ->get(); 
					}
				  
				    return $getKcuser;        
				}
			}
			else{
				$kscore = 12;
				$kscore =Adminoption::Where('option_name','=','Set Kscore Value')->select("option_value")->first();
				if(!empty($kscore))
				$kscore = $kscore->option_value;
				$getKcuser = DB::table('users as u')
							->join('users_groups', 'u.id', '=', 'users_groups.user_id')
							->select(array('u.userstatus','u.id','u.fname','u.lname','u.linkedinurl','u.piclink','u.headline','u.email','u.karmascore','u.location'))
				            ->where('u.userstatus','=','approved')
				            ->WhereNotIn('u.id',$getUser)
				           // ->Where('u.id','!=',$Receiver)
				            ->Where('u.id','!=',$user_id) 
				            ->distinct()
				            ->Where('u.karmascore','>',$kscore) 
				            ->orderByRaw("RANd()")->take(3) 
				            ->get(); 
				return $getKcuser; 
			}

		}else{
			if($user_id > 0){
				$Usergroup 	= KarmaHelper::getuserGroup();
				$All_groups = '';
				if(!$Usergroup->isEmpty()){ 
					foreach ($Usergroup as $key => $value) {
						$All_groups[] = $value->id;
					}	
					$getKcuser = DB::table('users as u')
							->join('users_groups', 'u.id', '=', 'users_groups.user_id')
							->select(array('u.userstatus','u.id','u.fname','u.lname','u.linkedinurl','u.piclink','u.headline','u.email','u.karmascore','u.location'))
				            ->whereIn('users_groups.group_id',$All_groups)
				            ->WhereNotIn('u.id',$getUser)
				            ->Where('u.id','!=',$Receiver)
				            ->Where('u.id','!=',$user_id)  
				            ->distinct()
				            ->where('u.userstatus','=','approved')
				            ->orderByRaw("RANd()")->take(3)  
				            ->get();
					if(empty($getKcuser))
					{
						$getKcuser = DB::table('users as u')
							->join('users_groups', 'u.id', '=', 'users_groups.user_id')
							->select(array('u.userstatus','u.id','u.fname','u.lname','u.linkedinurl','u.piclink','u.headline','u.email','u.karmascore','u.location')) 
				            //->whereIn('users_groups.group_id',$All_groups)
				            ->WhereNotIn('u.id',$getUser)
				            ->Where('u.id','!=',$Receiver)
				            ->Where('u.id','!=',$user_id)  
				            ->distinct()
				            ->where('u.userstatus','=','approved')
				            ->orderByRaw("RANd()")->take(3)   
				            ->get(); 
					}
				  
				    return $getKcuser;        
				}
			}
			else{
				$kscore = 12;
				$kscore =Adminoption::Where('option_name','=','Set Kscore Value')->select("option_value")->first();
				if(!empty($kscore))
				$kscore = $kscore->option_value;

				$getKcuser = DB::table('users as u')
							->join('users_groups', 'u.id', '=', 'users_groups.user_id')
							->select(array('u.userstatus','u.id','u.fname','u.lname','u.linkedinurl','u.piclink','u.headline','u.email','u.karmascore','u.location'))
				            ->where('u.userstatus','=','approved')
				            ->WhereNotIn('u.id',$getUser)
				           	->Where('u.id','!=',$Receiver)
				            ->Where('u.id','!=',$user_id) 
				            ->distinct()
				            ->Where('u.karmascore','>',$kscore) 
				            ->orderByRaw("RANd()")->take(3) 
				            ->get(); 
				return $getKcuser; 
			}
		}
		
	} 

 

	// fetch user groups
	public static function getuserGroup(){
		$Usergroup 	= Auth::User()->Groups;
		return $Usergroup;
	}

	// get all KC notes
	public static function getKarmanote(){ 
		$test_id = Adminoption::Where('option_name','=','Test User Emails')->first();
		$test_user_id = $getfintrorecords= array();
		if(!empty($test_id)){ 
			$test_user_id = explode(',',$test_id->option_value);
		}   
		$giver= $receiver=$conn='';
		$getKarmanote = KarmaHelper::getrandomnotes($test_user_id);
		if(!empty($getKarmanote))
		$getfintrorecords = KarmaHelper::getfintrorecords($test_user_id,$getKarmanote);

		foreach ($getfintrorecords as $key => $value) {
			$value->user_idreceiver = User::find($value->user_idreceiver);
			if(!empty($value->user_idgiver)){
				$value->user_idgiver = User::find($value->user_idgiver)->toArray();
			}
			else{
				$value->user_idgiver = Connection::find($value->connection_idgiver)->toArray();
			}
			
		}
		return $getfintrorecords;
	}

	
	public static function getfintrorecords($test_user_id,$getKarmanote){
		$kcarray = array();$count=0;
		$giver =$receiver = $connidgiver='';
		if(!empty($getKarmanote)){
			foreach ($getKarmanote as $key => $value) {
				if($count < 5){
					if(isset($value->user_idgiver))
					$giver = in_array($value->user_idgiver,$test_user_id);
					if(isset($value->user_idreceiver))
					$receiver = in_array($value->user_idreceiver,$test_user_id);
					if(isset($value->connection_idgiver)) 
					$connidgiver = in_array($value->connection_idgiver,$test_user_id);
					if($giver != 1 && $receiver != 1 && $connidgiver != 1 ){
						array_push($kcarray, $value);
						if(isset($value->user_idgiver))
						array_push($test_user_id, $value->user_idgiver);
						if(isset($value->user_idreceiver))
						array_push($test_user_id, $value->user_idreceiver);
						if(isset($value->connection_idgiver))
						array_push($test_user_id, $value->connection_idgiver);
						$count++;
					}
				}
				else{
					return $kcarray;
				}
			}
		}
		return $kcarray;
	}
 
	public static function getrandomnotes($test_user_id){
		if(!empty($test_user_id)){
			$getKcnote = DB::table('karmanotes as karmanotes')
							->select(array('karmanotes.*'))
							->WhereNotIn('user_idreceiver',$test_user_id)
							->orWhereNotIn('user_idgiver',$test_user_id)
							->orWhereNotIn('connection_idgiver',$test_user_id)
							//->orderByRaw("RANd()")->take($limit)->get(); 
							->orderBy('created_at','DESC')
							->orderByRaw("RANd()")->get(); 
		}
		else{  
			$getKcnote = DB::table('karmanotes as karmanotes')
							->select(array('karmanotes.*'))
							->orderBy('created_at','DESC')
							->orderByRaw("RANd()")->get(); 
		}
		return $getKcnote;				
 
	}
	
	/*fetch vanity URL of a user*/
	public static function getVanityURL($user_id){

		$user = User::find($user_id);
		$vanity = DB::table('vanityurls as vanityurls')
							->select(array('vanityurls.vanityurl'))
							->where('redirecturl','LIKE','%profile/'.$user->fname.'-'.$user->lname.'/'.$user_id.'%' )
							->get();
							
				
		//echo"<pre>";print_r($vanity);echo"</pre>";		
		if(!empty($vanity))		
		{	//die($vanity[0]->vanityurl);
			return URL::to('').'/'.$vanity[0]->vanityurl;
		}
		else{ 
			//die(URL::to('').'/profile/'.$user_id.'/'.$user->fname.'-'.$user->lname);
			return URL::to('').'/profile/'.strtolower($user->fname.'-'.$user->lname).'/'.$user_id;	
		}		
	}

	/* Fetch user group and users on KC platform in common groups.*/
	public static function fetchUserGroup($user_id){
		$Usergroup 	= User::find($user_id)->Groups;
		//get test users id		
		$getUser = KarmaHelper::getTestUsers();

		$All_groups = '';
		if(!$Usergroup->isEmpty()){ 
			foreach ($Usergroup as $key => $value) {
				$All_groups[] = $value->id;
			}	
			$getKcuser = DB::table('users as u') 
					->join('users_groups', 'u.id', '=', 'users_groups.user_id')
					->select(array('u.userstatus','u.id','u.fname','u.lname','u.linkedinurl','u.piclink','u.headline','u.email','u.karmascore','u.location'))
		            ->whereIn('users_groups.group_id',$All_groups)
		            ->WhereNotIn('u.id',$getUser)
		            ->where('u.userstatus','=','approved')
		            ->distinct()
		            ->orderByRaw("RANd()")->take(1) 
		            ->get();
		    return $getKcuser;         
		} 
	} 

	public static function CheckUserLinkedMgsLimit(){
		// $user_info = Auth::User();
		// $throttle = "";
		// if(!empty($user_info))
		// $throttle = DB::table('throttles')->where('throttles.user_id','=',$user_info->id)->first();
		
		// if(empty($throttle)){   
		// 	return 1;  
		// }	
		// else{ 
		// 	$diffDate = KarmaHelper::dateDiff(date("Y-m-d H:i:s"),$throttle->created_at);
		// 	//echo $diffDate->days.$diffDate->h;die; 
		// 	if($diffDate->days >= 1){  
		// 		DB::table('throttles')->where('user_id', '=', $user_info->id)->delete(); 
		// 		return 1;
		// 	}
		// 	else{
		// 		return 0;
		// 	}
		// } 
	}

	/*Function to get user connections  upto a limit of a user KC and non KC both*/
	public static function getLIMITEDUserConnection($user_id,$location,$start,$perpage){

		$searchquery = DB::table('users_connections')
					->join('connections', 'users_connections.connection_id', '=', 'connections.id')
					//->join('users', 'connections.user_id', '=', 'users.id')
					->select(array('connections.user_id as con_user_id','connections.networkid',
						'connections.fname','connections.lname','connections.piclink','connections.linkedinurl',
						'connections.headline','connections.location','connections.id',
						/*'users_connections.*','users.lname as user_lname','users.fname as user_fname'
						,'users.userstatus','users.karmascore',*/
						))
		            ->where('users_connections.user_id','=',$user_id)
		            ->skip($start)->take($perpage)
		            ->orderBy('con_user_id','DESC')
		            ->get();
		return $searchquery;
	}


	public static function getTopGroupQuery($CurrentUserId,$groupId,$start,$perpage){
		
		$group_question = DB::table('users_groups')
					->join('questions', 'users_groups.user_id', '=', 'questions.user_id')
					->leftjoin('users', 'questions.user_id', '=', 'users.id')
					->select(array('questions.*','users_groups.group_id','users.karmascore'))
		            ->where('questions.user_id','!=',$CurrentUserId)
		            ->where('users_groups.group_id','=',$groupId)
		            ->where('questions.queryStatus','=','open') 
		            ->orderBy('users.karmascore','DESC')
		             ->skip($start)->take($perpage)
		            ->get();
		//echo '<pre>';print_r($group_question);die;
		$toppers = array();
		if(!empty($group_question)){ 
			foreach ($group_question as $key => $value) {
				if(isset($value->user_id)) 
				$value->user_id = User::find($value->user_id); 
				if(isset($value->id)) 
				{
					$value->giver_Info = Question::find($value->id)->GiversHelp;
					$value->giver_Count = Question::find($value->id)->GiversHelp()->count();
				}
				$value->answered = 0;
				if(!$value->giver_Info->isEmpty()){
					foreach ($value->giver_Info as $key => $giver_Info) {
						if(isset($giver_Info->user_id)) {
							$toppers[]= $giver_Info->user_id;
								if($giver_Info->user_id == $CurrentUserId ){
									$value->answered = 1;
								}else{
									if($value->answered != '1'){
										$value->answered = 0;
									}
								}
								$giver_Info->user_id = User::find($giver_Info->user_id);
							}
						}
				}
				else{
					$value->answered = 0;
				}					
				$value->skills =  KarmaHelper::getSkillsname($value->skills);
			}
		}
		return $group_question;	
	}

	public static function GetToppersInGroup($groupId,$start,$perpage){
		$toppers ="";
		$searchquery = DB::table('groups')
			            ->join('users_groups', 'groups.id', '=', 'users_groups.group_id')
			            ->join('users', 'users.id', '=', 'users_groups.user_id')
			            ->where('users.userstatus', '=', 'approved')	            		            
			            ->groupBy('users_groups.user_id')
			            ->select('groups.name', 'groups.id', 'users_groups.user_id', 'users.fname', 'users.lname', 'users.email', 'users.piclink', 'users.linkedinid', 'users.linkedinurl', 'users.location', 'users.headline')
			           	->orderBy('users.karmascore','DESC')
			           	->where('groups.id','=',$groupId)
			           	->skip($start)->take($perpage)
			            ->get();
			    foreach ($searchquery as $key => $value) {
         	  		$toppers[$key]['fname'] = $value->fname;
         	  		$toppers[$key]['lname'] = $value->lname;
         	  		$toppers[$key]['linkedinurl'] = $value->linkedinurl;
         	  		$toppers[$key]['headline'] = $value->headline;
         	  		$toppers[$key]['location'] = $value->location;
         	  		$toppers[$key]['linkedinid'] = $value->linkedinid;
         	  		$toppers[$key]['piclink'] = $value->piclink;
         	  		$toppers[$key]['id'] = $value->id;
         	  		$linkedinid = $value->linkedinid;
         	  		$linkedinUserData = DB::table('users')->where('linkedinid', '=', $linkedinid)->first();
         	  		
         	  		if(!empty($linkedinUserData)){
	         	  		$tags = User::find($linkedinUserData->id)->Tags()->orderBy(DB::raw('RAND()'))->take(10)->get();
	         	  		$toppers[$key]['UserData'] = array();
	         	  		$toppers[$key]['Tags'] = array();
	         	  		$toppers[$key]['UserData'] = $linkedinUserData;
	         	  		$toppers[$key]['Tags'] = $tags;
         	  		}	
         	  	}
        return $toppers;	  	

	}

  
	public static function GroupSearchPeople($CurrentUserId,$groupId,$search,$start,$perpage,$action){
		//$All_groups = array($groupId);
		$set = "'".$groupId."','".$search."'";
		
		
		$searchresult = "";            
		if($action == "all_member")
		$searchquery = DB::select(DB::raw('select DISTINCT `u`.`userstatus`, `u`.`id`, `u`.`fname`, `u`.`lname`, `u`.`linkedinurl`,`u`.`linkedinid`, `u`.`piclink`, `u`.`headline`, `u`.`email`,`u`.`karmascore`, `u`.`location` from `users` as `u` inner join `users_groups` on `u`.`id` = `users_groups`.`user_id` where `users_groups`.`group_id` in ('.$groupId.')and `u`.`id` != '.$CurrentUserId.'  and `u`.`userstatus` = "approved" and concat(u.fname," ",u.lname) LIKE "%'.$search.'%'.'" limit '.$perpage.' offset '.$start));     
        else
        $searchquery = DB::select(DB::raw("SELECT * FROM `users` LEFT JOIN users_groups AS g1 ON ( g1.user_id = users.id ) LEFT JOIN users_groups AS g2 ON ( g2.user_id = users.id ) WHERE g1.group_id =".$groupId." AND g2.group_id =".$search." AND `users`.`id` != ".$CurrentUserId."  AND `users`.`userstatus` = 'approved' limit ".$perpage." offset ".$start)); 
 
		$searchresult = "";            
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
	    return $searchresult;   
	}

	public static function GroupSearchPeopleAll($CurrentUserId,$groupId,$search,$start,$perpage){
		//$All_groups = array($groupId);
		
		$searchresult = "";            
		$searchquery = DB::select(DB::raw('select DISTINCT `u`.`userstatus`, `u`.`id`, `u`.`fname`, `u`.`lname`, `u`.`linkedinurl`,`u`.`linkedinid`, `u`.`piclink`, `u`.`headline`, `u`.`email`,`u`.`karmascore`, `u`.`location` from `users` as `u` inner join `users_groups` on `u`.`id` = `users_groups`.`user_id` where `users_groups`.`group_id` in ('.$groupId.')and `u`.`id` != '.$CurrentUserId.'  and `u`.`userstatus` = "approved" and concat(u.fname," ",u.lname) limit '.$perpage.' offset '.$start));      
         
		$searchresult = "";            
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
	    return $searchresult;  
	}

	public static function GroupSearchSkill($CurrentUserId,$groupId,$search,$start,$perpage){
		$skillTag = array(); 
		$All_groups = array($groupId);

		
 	  	$searchquery = DB::table('tags')
	            ->join('users_tags', 'tags.id', '=', 'users_tags.tag_id')
	            ->join('users_groups', 'users_groups.user_id', '=', 'users_tags.user_id')
	            ->join('users', 'users.id', '=', 'users_tags.user_id')
	            ->where('tags.name','LIKE','%'.$search.'%')
	            ->whereIn('users_groups.group_id',$All_groups)
	            ->where('users.userstatus', '=', 'approved')
	            ->where('users.id', '!=', $CurrentUserId)	 		            
	            ->groupBy('users_tags.user_id')
	            ->select('tags.name', 'tags.id', 'users_tags.user_id', 'users.fname', 'users.lname', 'users.email', 'users.piclink', 'users.linkedinid', 'users.linkedinurl', 'users.location', 'users.headline')
	            ->skip($start)->take($perpage)
	            ->get();

	   ///  print_r($searchquery);  die;     


	    $searchresult = "";            
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
	    return $searchresult;              
	} 

	public static function GroupSearchIndustry($CurrentUserId,$groupId,$search,$start,$perpage){
		$skillTag = array(); 
		$All_groups = array($groupId);
 	  	$searchquery = DB::table('users as u')
					->join('users_groups', 'u.id', '=', 'users_groups.user_id')
					->select(array('u.userstatus','u.id','u.fname','u.lname','u.linkedinurl','u.linkedinid','u.piclink','u.headline','u.email','u.karmascore','u.location'))
		            ->whereIn('users_groups.group_id',$All_groups)
		            ->where('u.id','!=',$CurrentUserId)
		            ->where('u.userstatus','=','approved')
					->where('u.industry','LIKE','%'.$search.'%')
		            ->skip($start)->take($perpage) 
		            ->distinct()
		            ->get(); 
	    $searchresult = "";            
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
	    return $searchresult;          
	} 

	

	public static function GroupSearchLocation($CurrentUserId,$groupId,$search,$start,$perpage){
		$skillTag = array(); 
		$All_groups = array($groupId);
		
 	  	$searchquery = DB::table('users as u')
					->join('users_groups', 'u.id', '=', 'users_groups.user_id')
					->select(array('u.userstatus','u.id','u.fname','u.lname','u.linkedinurl','u.linkedinid','u.piclink','u.headline','u.email','u.karmascore','u.location'))
		            ->whereIn('users_groups.group_id',$All_groups)
		            ->where('u.id','!=',$CurrentUserId)
		            ->where('u.userstatus','=','approved')
					->where('u.location','LIKE','%'.$search.'%')
		            ->skip($start)->take($perpage) 
		            ->distinct()
		            ->get(); 
		$searchresult = "";            
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
	    return $searchresult;            
	} 


	public static function getTestUsers(){
		$test_id = Adminoption::Where('option_name','=','Test User Emails')->first();

		$test_user_id = "";
		if(!empty($test_id)){ 
			$test_user_id = explode(',',$test_id->option_value);
		}
		return $test_user_id; 

	}

	/*get ktrail for admin report generation for Karmanote*/
	public static function getAdminReportNote($request_id,$user_id,$begin,$end){
		$karmaTrailUser = $karmaTrailDet = $karmaTrail =  '';
		$getUser = "";
		$getUser = KarmaHelper::getTestUsers();
		if($user_id !=0)
		$testchk = in_array($user_id, $getUser);
		$query = Karmanote::query();

		if($request_id == 0)
		{ 
			if ($user_id != 0 && $testchk !=1 && $begin != 0 && $end != 0 ) {
				$karmaTrail = DB::select(DB::raw("select `karmanotes`.* from `karmanotes` where (`karmanotes`.`user_idreceiver` =  ".$user_id." or `karmanotes`.`user_idgiver` = ".$user_id." or `karmanotes`.`connection_idgiver` = ".$user_id." ) and `karmanotes`.`created_at` between '".$begin."' and '".$end."' order by `karmanotes`.`created_at` desc")); 
			}
			if ($user_id == 0 && $begin != 0 && $end != 0 ) {
				$karmaTrail = DB::select(DB::raw("select `karmanotes`.* from `karmanotes` where `karmanotes`.`created_at` between '".$begin."' and '".$end."' order by `karmanotes`.`created_at` desc")); 
			}
			if ($user_id == 0 && $begin == 0 && $end == 0 ) {
				$karmaTrail = DB::select(DB::raw("select `karmanotes`.* from `karmanotes` order by `karmanotes`.`created_at` desc")); 
			}         
		}
		else{
			$karmaTrailDet = DB::table('karmanotes')
		            ->where('karmanotes.req_id', '=', $request_id)     
		            ->orderBy('karmanotes.created_at','desc')
		            ->select('karmanotes.*')
		            ->distinct() 
		            ->first(); 
		}
		//echo"<pre>==".$user_id."====";print_r($karmaTrail);echo"</pre>=====";  die;

		if(!empty($karmaTrail)){
				foreach ($karmaTrail as $trail) {
					$userSkills = "";
					$rcvr = in_array($trail->user_idreceiver,$getUser);
					if(isset($trail->user_idreceiver) && $rcvr != 1)
					$karma['user_idreceiver'] = User::find($trail->user_idreceiver)->toArray();
					else $karma['user_idreceiver'] = "";

					$giver = in_array($trail->user_idgiver,$getUser);
					if(!empty($trail->user_idgiver) && $giver !=1 ){
						$karma['user_idgiver'] = User::find($trail->user_idgiver)->toArray();
					}
					else{
						$connection = in_array($trail->connection_idgiver,$getUser);
						if(!empty($trail->connection_idgiver) && $connection !=1 )
						$karma['user_idgiver'] = Connection::find($trail->connection_idgiver)->toArray();
						else $karma['user_idgiver'] = "";
					}
					if($user_id == $trail->user_idreceiver){
						$karma['status'] = $trail->statusreceiver;	
					}else{
						$karma['status'] = $trail->statusgiver;	
					}

					//$karma['piclink'] = $trail->piclink;
					$karma['karmaNotes'] = $trail->details;
					
					if(!empty($trail->skills)){
						$userSkills = KarmaHelper::getSkillsname($trail->skills);
					}
					$karma['skills'] = $userSkills;
					$karma['req_id'] = $trail->req_id;
					//$karma['meetingdatetime'] = date('F d, Y', strtotime($trail->meetingdatetime));
					$karma['created_at'] = date('F d, Y', strtotime($trail->created_at));
					$karmaTrailUser[] = $karma;
				}
			}
		if(!empty($karmaTrailDet)){
					$userSkills = "";
					$karma['karmaNotes'] = $karmaTrailDet->details;
					if(!empty($karmaTrailDet->skills)){
						$userSkills = KarmaHelper::getSkillsname($karmaTrailDet->skills);
					}
					$karma['skills'] = $userSkills;
					$karma['req_id'] = $karmaTrailDet->req_id;
					$karma['statusgiver'] = $karmaTrailDet->statusgiver;
					$karma['statusreceiver'] = $karmaTrailDet->statusreceiver;
					$karma['created_at'] = date('F d, Y', strtotime($karmaTrailDet->created_at));
					$karmaTrailUser[] = $karma;
			}
		return $karmaTrailUser;
	}

	/*get ktrail for admin report generation for Meeting request*/
	public static function getAdminReportMeet($request_id,$user_id,$begin,$end){
		$getUser = "";
		$getUser = KarmaHelper::getTestUsers();
		$testchk = in_array($user_id, $getUser);
		/*echo $chk;
		die();*/

		if($request_id == 0)
		{ 
			/*$query = DB::table('requests');

			if ( $user_id !=0  && $testchk !=1) {
				$query->where('requests.user_id_receiver', '=', $user_id);     
				$query->orwhere('requests.user_id_giver', '=', $user_id);
				$query->orwhere('requests.connection_id_giver', '=', $user_id);
			}
			if ( $begin != 0 && $end != 0){
				$query->whereBetween('requests.created_at', array("'".$begin."'", "'".$end."'"));
			}

			$meetTrail = $query
						//->WhereNotIn('requests.user_id_receiver',$getUser)
						//->WhereNotIn('requests.user_id_giver',$getUser)
						//->orWhereNotIn('requests.connection_id_giver',$getUser)
						->orderBy('requests.created_att','desc')
						->select('requests.*')
						->distinct() 
						->get(); */


			
			if ($user_id != 0 && $testchk !=1 && $begin != 0 && $end != 0 ) {
				$meetTrail = DB::select(DB::raw("select `requests`.* from `requests` where (`requests`.`user_id_receiver` =  ".$user_id." or `requests`.`user_id_giver` = ".$user_id." or `requests`.`connection_id_giver` = ".$user_id." ) and `requests`.`created_at` between '".$begin."' and '".$end."' order by `requests`.`created_at` desc")); 
			}
			if ($user_id == 0 && $begin != 0 && $end != 0 ) {
				$meetTrail = DB::select(DB::raw("select `requests`.* from `requests` where `requests`.`created_at` between '".$begin."' and '".$end."' order by `requests`.`created_at` desc")); 
			}
			if ($user_id == 0 && $begin == 0 && $end == 0 ) {
				$meetTrail = DB::select(DB::raw("select `requests`.* from `requests` order by `requests`.`created_at` desc")); 
			}    		
			
			//echo"<pre>==".$user_id."====";print_r($meetTrail);echo"</pre>=====";
		}
		else{
			$meetTrail = DB::table('requests')
						->where('requests.id', '=', $request_id)
						->orderBy('requests.created_at','desc')
						->select('requests.*')
						->distinct()
						->first();	
		}

		if(!empty($meetTrail)){
				$meetTrail = (array) $meetTrail;
				foreach ($meetTrail as $key => $value) {
					if(isset($value->user_id_introducer)){
						$introducer = in_array($value->user_id_introducer,$getUser);
						if($introducer != 1 )
						$value->user_id_introducer = User::find($value->user_id_introducer);
						else $value->user_id_introducer = "";
				    } 
				    if(isset($value->user_id_receiver)){
						$rcvr = in_array($value->user_id_receiver,$getUser);
						if($rcvr != 1)
						$value->user_id_receiver = User::find($value->user_id_receiver)->toArray();
					    else $value->user_id_receiver = "";
					}
					if(!empty($value->user_id_giver)){
					$giver = in_array($value->user_id_giver,$getUser);
						if($giver!=1)
						$value->user_id_giver = User::find($value->user_id_giver)->toArray();
					}
					else{
						if(isset($value->connection_id_giver)){
							$connection = in_array($value->connection_id_giver,$getUser);
							if($connection != 1)
							$value->user_id_giver = Connection::find($value->connection_id_giver)->toArray();
							else $value->user_id_giver = "";
						}
					}
				}		
		} 

		

		return $meetTrail;
	}

	/*public static function checkifUser_isconnection($user_id_giver,$user_id_receiver){
		$giverConnection = "";
		$giverConnectionId = Connection::where('user_id','=',$user_id_giver)->first()->id;
		if(isset($giverConnectionId))
		$giverConnection = Usersconnection::where('user_id','=',$user_id_receiver)->where('connection_id','=',$giverConnectionId)->first()->id;
		return $giverConnection;
	}*/

	//fetch random user group except karmasphere
	public static function getrandom_usergroup($user_id){
		$Usergroup 	=   "";
		$Usergroup 	= User::find($user_id)
						->Groups()
						->where('groups.id','!=',1)
						->orderByRaw("RANd()")
						->take(1) 
						->first();
		if(!empty($Usergroup))
		{
			$Usergroup 	= $Usergroup->toArray();
		}
		return $Usergroup;
	}
	
	//fuction to sort skills case insensitive
	public static function skill_sort($a,$keyname) {
		foreach($a as $k=>$v) {
			$b[$k] = strtolower($v[$keyname]); 
		}
		asort($b);
		foreach($b as $key=>$val) {
			$c[] = $a[$key];
		}
		return $c; 
	} 
	//function to check how much meeting are happend in one week.
	public static function karmaMeetingPendingCount($receiver_id,$id) {
		//echo 'here';exit;
		$giver_value = DB::table('requests')->where('status','<>', 'completed')->where('user_id_giver','=',$id)->where('user_id_receiver','=',$receiver_id)->take(1)->orderBy('created_at', 'DESC')->get();
		$giver_value_count = DB::table('requests')->where('status','<>', 'completed')->where('user_id_giver','=',$id)->where('user_id_receiver','=',$receiver_id)->orderBy('created_at', 'DESC')->count();
		if($giver_value_count >= 1){
			$first_created_date = Carbon::now();
			$second_created_date=$giver_value[0]->created_at;
			$created = new Carbon($first_created_date);
			$now =new Carbon($second_created_date);
			$date_difference = ($created->diff($now)->days);
			if($date_difference < 7){
		 		$MeetingRequestPending = DB::table('requests')->where('status','<>', 'completed')->where('user_id_giver','=',$id)->where('user_id_receiver','=',$receiver_id)->orderBy('created_at', 'DESC')->count();
		 		return $MeetingRequestPending;
		 	}else{
				$MeetingRequestPending=0;
				return $MeetingRequestPending;
			}
			
		}else{
				$MeetingRequestPending=0;
				return $MeetingRequestPending;
		}

		return $MeetingRequestPending;
			
			
	}

	public static function storeKarmacirclesRecord($giver,$receiver){
		
		if(!empty($receiver)){
				$UserskarmaCircle = Karmacircle::where('user_id','=',$receiver)->delete();
				$user_circle_giver = DB::table('karmanotes')->select('user_idgiver')->distinct()->where('karmanotes.user_idreceiver','=',$receiver)->get();
				$user_circle_receiver = DB::table('karmanotes')->select('user_idreceiver')->distinct()->where('karmanotes.user_idgiver','=',$receiver)->get();
				$karmaCircle = new Karmacircle;
				if(!empty($receiver)){
					$karmaCircle->user_id=$receiver;
				}
				//code to save givers id.
				if(!empty($user_circle_giver)){
					$user_circle_giver_data='';
					foreach ($user_circle_giver as $key => $value) {
						$user_circle_giver_data[]=$value->user_idgiver;
					}
					if(!empty($user_circle_giver_data)){
						$user_circle_giver_data=array_filter($user_circle_giver_data);
						$user_circle_giver_data=json_encode($user_circle_giver_data);
						$karmaCircle ->givers = $user_circle_giver_data;
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
						$user_circle_receiver_data=json_encode($user_circle_receiver_data);
						$karmaCircle ->receivers = $user_circle_receiver_data;
					}
				}
				
				//code to save receiver's receiver id.
				if(!empty($user_circle_receiver_data)){
					$user_circle_receiver_data=json_decode($user_circle_receiver_data);
					$users_givers_takers = DB::table('karmanotes')->select('user_idreceiver')->distinct()->whereIn('user_idgiver', $user_circle_receiver_data)->get();	
					$user_circle_giver_receiver_data='';
					foreach ($users_givers_takers as $key => $value_giver_taker) {
						$user_circle_giver_receiver_data[]=$value_giver_taker->user_idreceiver;
					}
				}	
				if(!empty($user_circle_giver_receiver_data)){
					$user_circle_giver_receiver_data=array_filter($user_circle_giver_receiver_data);
					$user_circle_giver_receiver_data=json_encode($user_circle_giver_receiver_data);
					$karmaCircle ->receivers_receivers = $user_circle_giver_receiver_data;
					
				}
				//code to save giver's giver id.
				/*
				if(!empty($user_circle_giver_data)){
					$user_circle_giver_data=array_filter($user_circle_giver_data);
					$users_givers_givers = DB::table('karmanotes')->select('user_idgiver')->distinct()->whereIn('user_idreceiver', $user_circle_giver_data)->get();	
					$user_circle_giver_giver_data='';
					foreach ($users_givers_givers as $key => $value_giver_giver) {
						$user_circle_giver_giver_data[]=$value_giver_giver->user_idgiver;
					}
				}
				if(!empty($user_circle_giver_giver_data)){
					$user_circle_giver_giver_data=array_filter($user_circle_giver_giver_data);
					$karmaCircle->givers_givers = implode(',', $user_circle_giver_giver_data);
				}*/
				$karmaCircle->save();
				
		}
		if(!empty($giver)){
				
				$UserskarmaCircle = Karmacircle::where('user_id','=',$giver)->delete();
				$user_circle_giver = DB::table('karmanotes')->select('user_idgiver')->distinct()->where('karmanotes.user_idreceiver','=',$giver)->get();
				$user_circle_receiver = DB::table('karmanotes')->select('user_idreceiver')->distinct()->where('karmanotes.user_idgiver','=',$giver)->get();
				
				$karmaCircle = new Karmacircle;
				if(!empty($giver)){
					$karmaCircle->user_id=$giver;
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
					$user_circle_receiver_data=array_filter($user_circle_receiver_data);
					$users_givers_takers = DB::table('karmanotes')->select('user_idreceiver')->distinct()->whereIn('user_idgiver', $user_circle_receiver_data)->get();	
					$user_circle_giver_receiver_data='';
					foreach ($users_givers_takers as $key => $value_giver_taker) {
						$user_circle_giver_receiver_data[]=$value_giver_taker->user_idreceiver;
					}
				}
				if(!empty($user_circle_giver_receiver_data)){
					$user_circle_giver_receiver_data=array_filter($user_circle_giver_receiver_data);
					$karmaCircle ->receivers_receivers = implode(',', $user_circle_giver_receiver_data);
				}
				//code to save giver's giver id.
				// if(!empty($user_circle_giver_data)){
				// 	$user_circle_giver_data=array_filter($user_circle_giver_data);
				// 	$users_givers_givers = DB::table('karmanotes')->select('user_idgiver')->distinct()->whereIn('user_idreceiver', $user_circle_giver_data)->get();	
				// 	$user_circle_giver_giver_data='';
				// 	foreach ($users_givers_givers as $key => $value_giver_giver) {
				// 		$user_circle_giver_giver_data[]=$value_giver_giver->user_idgiver;
				// 	}
				// }
				// if(!empty($user_circle_giver_giver_data)){
				// 	$user_circle_giver_giver_data=array_filter($user_circle_giver_giver_data);
				// 	$karmaCircle->givers_givers = implode(',', $user_circle_giver_giver_data);
				// }	
				$karmaCircle->save();
		}
			return true;

	}
/*
	public static function storeKarmacirclesRelation($giver,$receiver){
		if(!empty($giver) && (!empty($receiver))){
			$checkKarmacircleCount = DB::table('user_karmacircles')->where('user_karmacircles.user_id','=',$giver)->where('user_karmacircles.peer_id','=',$receiver)->count();
			if($checkKarmacircleCount < 1){
				
				$karmaCircleUser=new Userskarmacircle;
				$karmaCircleUser->user_id=$giver;
				$karmaCircleUser->peer_id=$receiver;
				$karmaCircleUser->receiver=1;
				$karmaCircleUser->receiver_date=Carbon::now();
				$karmaCircleUser->save();
			}else{
				$checkKarmacircleReceiverFlag = DB::table('user_karmacircles')->where('user_karmacircles.user_id','=',$giver)->where('user_karmacircles.peer_id','=',$receiver)->where('receiver','=',0)->first();
				if(!empty($checkKarmacircleReceiverFlag)){
					$karmaCircleUser=new Userskarmacircle;
					$karmaCircleUser->receiver=1;
					$karmaCircleUser->receiver_date=Carbon::now();
					$karmaCircleUser->save();
				}
			}
		}
		if(!empty($giver) && (!empty($receiver))){
			$karmacircleCount = DB::table('user_karmacircles')->where('user_karmacircles.user_id','=',$receiver)->where('user_karmacircles.peer_id','=',$giver)->count();
			if($karmacircleCount < 1){
				
				$karmaCircleUserData=new Userskarmacircle;
				$karmaCircleUserData->user_id=$receiver;
				$karmaCircleUserData->peer_id=$giver;
				$karmaCircleUserData->giver=1;
				$karmaCircleUserData->giver_date=Carbon::now();
				$karmaCircleUserData->save();
			}else{
				$checkKarmacircleReceiverFlag = DB::table('user_karmacircles')->where('user_karmacircles.user_id','=',$receiver)->where('user_karmacircles.peer_id','=',$giver)->where('giver','=',0)->first();
				if(!empty($checkKarmacircleReceiverFlag)){
					$karmaCircleUser=new Userskarmacircle;
					$karmaCircleUser->giver=1;
					$karmaCircleUser->giver_date=Carbon::now();
					$karmaCircleUser->save();
				}
			}
		}

		return true;	

	}*/
	public static function storeKarmacirclesRelation($giver,$receiver){
		//echo 'here';die;
		if(!empty($giver) && (!empty($receiver))){
			$checkKarmacircleCount = DB::table('user_karmacircles')->where('user_karmacircles.user_id','=',$giver)->where('user_karmacircles.peer_id','=',$receiver)->count();
			if($checkKarmacircleCount < 1){
				
				$karmaCircleUser=new Userskarmacircle;
				$karmaCircleUser->user_id=$giver;
				$karmaCircleUser->peer_id=$receiver;
				$karmaCircleUser->receiver=1;
				$karmaCircleUser->receiver_date=Carbon::now();
				$karmaCircleUser->save();
			}else{
				$checkKarmacircleReceiverFlag = DB::table('user_karmacircles')->where('user_karmacircles.user_id','=',$giver)->where('user_karmacircles.peer_id','=',$receiver)->first();
				if(!empty($checkKarmacircleReceiverFlag)){
					$currentTime=Carbon::now();
					$result=DB::table('user_karmacircles')->where('user_karmacircles.user_id','=',$giver)->where('user_karmacircles.peer_id','=',$receiver)->update(array('receiver' => '1','receiver_date' => $currentTime));
				}
			}
		}
		if(!empty($giver) && (!empty($receiver))){
			$karmacircleCount = DB::table('user_karmacircles')->where('user_karmacircles.user_id','=',$receiver)->where('user_karmacircles.peer_id','=',$giver)->count();
			if($karmacircleCount < 1){
				
				$karmaCircleUserData=new Userskarmacircle;
				$karmaCircleUserData->user_id=$receiver;
				$karmaCircleUserData->peer_id=$giver;
				$karmaCircleUserData->giver=1;
				$karmaCircleUserData->giver_date=Carbon::now();
				$karmaCircleUserData->save();
			}else{
				$checkKarmacircleReceiverFlag = DB::table('user_karmacircles')->where('user_karmacircles.user_id','=',$receiver)->where('user_karmacircles.peer_id','=',$giver)->first();
				if(!empty($checkKarmacircleReceiverFlag)){
					$currentTime=Carbon::now();
					$result=DB::table('user_karmacircles')->where('user_karmacircles.user_id','=',$receiver)->where('user_karmacircles.peer_id','=',$giver)->update(array('giver' => '1','giver_date' => $currentTime));
				}
			}
		}

		return true;	

	}

	//Function to store karmafeeds of karmacircles.
	public static function storeKarmacirclesfeed($giver,$receiver,$feedtype,$feedtypeId){
		
		if($feedtype=='KarmaNote'){
			$karmaNoteData=Karmanote::where('id','=',$feedtypeId)->select('id','user_idgiver','connection_idgiver')->first();
			$karmaCircleFeed=new Karmafeed;
			if(!empty($karmaNoteData->user_idgiver)){
				$karmaCircleFeed->giver_id=$karmaNoteData->user_idgiver;	
			}else{
				$karmaCircleFeed->giver_id='0';
			}
			if(!empty($karmaNoteData->connection_idgiver)){
				$karmaCircleFeed->karmafeed_connection_id=$karmaNoteData->connection_idgiver;	
			}else{
				$karmaCircleFeed->karmafeed_connection_id='0';
			}
			$karmaCircleFeed->receiver_id=$receiver;
			$karmaCircleFeed->message_type=$feedtype;
			$karmaCircleFeed->id_type=$feedtypeId;
			$karmaCircleFeed->save();
		}else{
			$karmaCircleFeed=new Karmafeed;
			$karmaCircleFeed->giver_id=$giver;
			$karmaCircleFeed->receiver_id=$receiver;
			$karmaCircleFeed->message_type=$feedtype;
			$karmaCircleFeed->id_type=$feedtypeId;
			$karmaCircleFeed->save();	
		}
		
		return true;	
	}
	//Function to get common connection.
	public static function commonConnection($userId,$otherUserId){
		$usersFirstCommonConnectionGiver=Karmanote::where('user_idreceiver','=',$userId)->where('user_idgiver','<>','null')->select('user_idgiver')->distinct('user_idgiver')->get();
		$usersFirstCommonConnectionReceiver=Karmanote::where('user_idgiver','=',$userId)->where('user_idreceiver','<>','null')->select('user_idreceiver')->distinct('user_idreceiver')->get();
		$userFirstData=array_merge($usersFirstCommonConnectionGiver->toArray(),$usersFirstCommonConnectionReceiver->toArray());
		$userFirstCommonId=array();
		foreach ($userFirstData as $key => $value) {
			if(isset($value['user_idreceiver'])){
				$userFirstCommonId[]=$value['user_idreceiver'];	
			}
			if(isset($value['user_idgiver'])){
				$userFirstCommonId[]=$value['user_idgiver'];	
			}
		}
		$userFirstCommonIdResult = $userFirstCommonId;
		$usersSecondCommonConnectionGiver=Karmanote::where('user_idreceiver','=',$otherUserId)->where('user_idgiver','<>','null')->select('user_idgiver')->distinct('user_idgiver')->get();
		$usersSecondCommonConnectionReceiver=Karmanote::where('user_idgiver','=',$otherUserId)->where('user_idreceiver','<>','null')->select('user_idreceiver')->distinct('user_idreceiver')->get();
		$userSecondData=array_merge($usersSecondCommonConnectionGiver->toArray(),$usersSecondCommonConnectionReceiver->toArray());
		$userSecondCommonId=array();
		foreach ($userSecondData as $key => $value) {
			if(isset($value['user_idreceiver'])){
				$userSecondCommonId[]=$value['user_idreceiver'];	
			}
			if(isset($value['user_idgiver'])){
				$userSecondCommonId[]=$value['user_idgiver'];	
			}
		}
		
		$userSecondCommonIdResult = $userSecondCommonId;
		$commonUser = array_intersect($userFirstCommonIdResult,$userSecondCommonIdResult);
		return $commonUser;	
	}
	//get random user;
	public static function getRandomKcuser($user_id){
		$getKcuser = DB::table('users as u')
							->select(array('u.userstatus','u.id','u.fname','u.lname','u.linkedinurl','u.piclink','u.headline','u.email','u.karmascore','u.location'))
				            ->where('u.userstatus','=','approved')
				            ->Where('u.id','!=',$user_id) 
				            ->distinct()
				            ->orderByRaw("RANd()")->take(3) 
				            ->get(); 
		return $getKcuser;
		
	}
	//function to get karma network.
	public static function getKarmaNetwork($userId){
		$usersGiver=Karmanote::where('user_idreceiver','=',$userId)->where('user_idgiver','<>','null')->select('user_idgiver')->distinct('user_idgiver')->get();
		$usersReceiver=Karmanote::where('user_idgiver','=',$userId)->where('user_idreceiver','<>','null')->select('user_idreceiver')->distinct('user_idreceiver')->get();
		
		$usersReceiverId=array();
		foreach ($usersReceiver as $value) {
			$usersReceiverId[]=$value->user_idreceiver;
		}
		$usersReceiversReceiver=Karmanote::whereIn('user_idgiver',$usersReceiverId)->where('user_idreceiver','<>','null')->select('user_idreceiver')->distinct('user_idreceiver')->get();
		$userKarmaNetwork=array_merge($usersGiver->toArray(),$usersReceiver->toArray(),$usersReceiversReceiver->toArray());
		$userNetwork=array();
		foreach ($userKarmaNetwork as $key => $value) {
			if(isset($value['user_idreceiver'])){
				$userNetwork[]=$value['user_idreceiver'];	
			}
			if(isset($value['user_idgiver'])){
				$userNetwork[]=$value['user_idgiver'];	
			}
		}
		$uniqueUserNetwork=array_unique($userNetwork);
		return $uniqueUserNetwork;
	}

	//get message for receiver mykarma;
	public static function getMykarmaMessageForReceiverGiver($status,$messageUser){
		if($messageUser=='Receiver')
		{
			if($status=='pending'){
				$message='Meeting pending. You can send reminder.';
			}else if($status=='archived'){
				$message='Meeting archived.';
			}else if($status=='confirmed'){
				$message='Meeting confirmed.';
			}else if($status=='completed'){
				$message='Meeting completed. Share KarmaNote.';
			}else if($status=='scheduled'){
				$message='Meeting scheduled. You can confirm.';
			}else if($status=='over'){
				$message='Send KarmaNote.';
			}else if($status=='responded'){
				$message='Meeting pending. You can message.';
			}else if($status=='cancelled'){
				$message='Meeting cancelled.';
			}else if($status=='happened'){
				$message='Send KarmaNote.';
			}else if($status=='spam'){
				$message='Meeting archived.';
			}
			else{
				$message='Please send the proper status.Status cant be match';
			}
		}else if($messageUser=='Giver'){
			if($status=='pending'){
				$message='Meeting pending. You may respond.';
			}else if($status=='archived'){
				$message='Meeting archived.';
			}else if($status=='confirmed'){
				$message='Meeting confirmed.';
			}else if($status=='completed'){
				$message='Meeting completed. Share KarmaNote.';
			}else if($status=='scheduled'){
				$message='Meeting scheduled.';
			}else if($status=='over'){
				$message='Meeting over.';
			}else if($status=='responded'){
				$message='Meeting pending. You may schedule.';
			}else if($status=='cancelled'){
				$message='Meeting cancelled.';
			}else if($status=='happened'){
				$message='Meeting over.';
			}else if($status=='spam'){
				$message='Meeting request marked as spam.';
			}
			else{
				$message='Please send the proper status.Status cant be match';
			}
		}else{
			$message='Select correct user type';
		}

		return $message;
	}

	/* Function for fetch MyKarma trail of user */
	public static function getKarmaData($user_id,$offset){
		$start=$offset*10;
		$perpage=10;
		if(!empty($user_id)){
			$karmaTrail = DB::table('users_mykarma')
						->where('user_id', '=', $user_id)
						->orderBy('entry_updated_on','desc')
			            ->skip($start)->take($perpage)
			            ->get();
			return $karmaTrail;
		}else return false;
	}
	/* Function for Update MyKarma trail of user */
	public static function updateMeetingStatus($entryId,$userRole){
		$userData=Meetingrequest::where('id','=',$entryId)->first();
		if($userRole=='Receiver'){
           DB::table('users_mykarma')->where('entry_id','=',$entryId)->where('users_role','=','Receiver')->update(array('unread_flag' => 'false', 'no_of_unread_items' => '0','entry_updated_on' => Carbon::now()));
           DB::table('users_mykarma')->where('entry_id','=',$entryId)->where('users_role','=','Giver')->update(array('unread_flag' => 'true', 'no_of_unread_items' => '1','entry_updated_on' => Carbon::now()));
           $getGiverData=User::where('id','=',$userData->user_id_giver)->first();
           if(!empty($getGiverData)){
           		$token=$getGiverData->deviceToken;
		   		$pushNotificationStatus=NotificationHelper::androidPushNotification($token);	
           }
           return true;
        }else if($userRole=='Giver'){
           	DB::table('users_mykarma')->where('entry_id','=',$entryId)->where('users_role','=','Receiver')->update(array('unread_flag' => 'true', 'no_of_unread_items' => '1','entry_updated_on' => Carbon::now()));
           	DB::table('users_mykarma')->where('entry_id','=',$entryId)->where('users_role','=','Giver')->update(array('unread_flag' => 'false', 'no_of_unread_items' => '0','entry_updated_on' => Carbon::now()));
            $getGiverData=User::where('id','=',$userData->user_id_receiver)->first();
            if(!empty($getGiverData)){
           		$token=$getGiverData->deviceToken;
		   		$pushNotificationStatus=NotificationHelper::androidPushNotification($token);	
            }
        	return true;
        }else{
        	return false;	
        } 
	}

	 /**
	 * Function to change the status to anystate to meeting happened.
	 *
	 * @return Response
	 */
     public static function meetingData($accessToken,$userId,$meetingId,$userRole)
    {
        	$getUser = User::where('id', '=', $userId)->first();
            	$meetingData=array();
            	$meetingData=Meetingrequest::where('id','=',$meetingId)->select('status','notes','meetingtype','user_id_receiver','user_id_giver')->first();
            	$meetingStatusText=KarmaHelper::getMykarmaMessageForReceiverGiver($meetingData->status,$userRole);
            	if(!empty($meetingData)){
            		if($userRole=='Receiver'){
            			$userProfileId=$meetingData->user_id_giver;
            			$userProfilePic=User::where('id','=',$userProfileId)->select('piclink')->first();
            			$userProfilePicLink=$userProfilePic->piclink;
            			DB::table('users_mykarma')->where('entry_id','=',$meetingId)->where('users_role','=','Receiver')->update(array('unread_flag' => 'false', 'no_of_unread_items' => '0'));	
            		}
            		if($userRole=='Giver'){
            			$userProfileId=$meetingData->user_id_receiver;
            			if($userProfileId=='' || $userProfileId=='null'){
            				$userProfilePicLink='null';	
            			}else{
            				$userProfilePic=User::where('id','=',$userProfileId)->select('piclink')->first();	
            				$userProfilePicLink=$userProfilePic->piclink;
            			}
            			
            			DB::table('users_mykarma')->where('entry_id','=',$meetingId)->where('users_role','=','Giver')->update(array('unread_flag' => 'false', 'no_of_unread_items' => '0'));	
            		}
					$meetingDetailData=new ArrayObject();
            		$karmaNoteDetailData=new ArrayObject();
            		$meetingTrailData=new ArrayObject();
            		$meetingTrailData=DB::table('requests_messages')->where('request_id','=',$meetingId)->orderBy('created_at','ASC')->get();
            		$meetingNote=Meetingrequest::where('id','=',$meetingId)->select('notes')->first();
            		$meetingMessageData=array();
            		if(!empty($meetingTrailData)){
            			foreach ($meetingTrailData as $key => $value) {
            				if($value->message_type=='user'){
	            				if($value->sender_id==$value->giver_id){
	            					$meetingMessageData[$key]['messageUser']='Giver';
	            					$meetingMessageData[$key]['userId']=$value->giver_id;
								}else if($value->sender_id==$value->receiver_id){
									$meetingMessageData[$key]['messageUser']='Receiver';
									$meetingMessageData[$key]['userId']=$value->receiver_id;
								}	
	            			}else{
	            				$meetingMessageData[$key]['messageUser']='System';
	            				$meetingMessageData[$key]['userId']='0';
	            			}
	            			$meetingMessageData[$key]['messageText']=$value->messageText;
	            			$meetingMessageData[$key]['MeetingMessage']=$meetingNote->notes;
	            			$meetingMessageData[$key]['date']=date('Y-m-d H:i:s', strtotime($value->created_at));
            			}	
            		}
            		
            		if($meetingData->status=='scheduled' || $meetingData->status=='confirmed' ){
	            		$noteData=Meetingrequest::where('id','=',$meetingId)->first();
	            		$meetingDetailData['payitforward']=$noteData->payitforward;
	            		$meetingDetailData['sendKarmaNote']=$noteData->sendKarmaNote;
	            		$meetingDetailData['buyyoucoffee']=$noteData->buyyoucoffee;
	            		$meetingDetailData['reply']=$noteData->reply;
	            		$meetingDetailData['status']=$noteData->status;
	            		$meetingDetailData['meetingduration']=$noteData->meetingduration;
	            		$meetingDetailData['meetingdatetime']=$noteData->meetingdatetime;
	            		$meetingDetailData['meetingtimezone']=$noteData->meetingtimezone;
	            		$meetingDetailData['meetingtimezonetext']=$noteData->meetingtimezonetext;
	            		$meetingDetailData['weekday_call_time']=$noteData->weekday_call_time;
	            		$meetingDetailData['meetinglocation']=$noteData->meetinglocation;
	            		$meetingDetailData['meetingtype']=$noteData->meetingtype;
	            		$dstValue =Adminoption::Where('option_name','=','Set DST value')->select("option_value")->first();
	            		$dstValueData=$dstValue->option_value;
	            		$meetingDetailData['dstValueData']=$dstValueData;
	            		return array(
    								'meetingStatus'=>$meetingData->status,
    								'meetingData'=>$meetingDetailData,
    								'meetingTrailData'=>$meetingMessageData,
    								'meetingUserId'=> $userProfileId,
    								'userProfilePic'=>$userProfilePicLink,
    								'meetingStatusText'=>$meetingStatusText
    					);
	            	}else if($meetingData->status=='completed'){
	            		$noteData=Karmanote::where('karmanotes.req_id','=',$meetingId)->first();
						if($noteData->sendKarmaNote=='' || $noteData->sendKarmaNote=='null'){
							$noteData->skills=array();
						}
						$receiverData=User::where('id','=',$noteData->user_idreceiver)->first();
	            		$giverData=User::where('id','=',$noteData->user_idgiver)->first();
	            		$karmaNoteDetailData['karmaNoteId']=$noteData->id;
	            		$karmaNoteDetailData['status']=$meetingData->status;
	            		$karmaNoteDetailData['skillTag']=$noteData->skills;
	            		$karmaNoteDetailData['karmaNoteDetail']=$noteData->details;
	            		$karmaNoteDetailData['receiver_id']=$noteData->user_idreceiver;
	            		$karmaNoteDetailData['giver_id']=$noteData->user_idgiver;
	            		$karmaNoteDetailData['description']=$noteData->meetingduration;
	            		$karmaNoteDetailData['receiverFirstName']=$receiverData->fname;
	            		$karmaNoteDetailData['receiverLastName']=$receiverData->lname;
	            		$karmaNoteDetailData['receiverPic']=$receiverData->piclink;
	            		$karmaNoteDetailData['giverFirstName']=$giverData->fname;
	            		$karmaNoteDetailData['giverLastName']=$giverData->lname;
	            		$karmaNoteDetailData['giverPic']=$giverData->piclink;
	            		$karmaNoteDetailData['receiverHeadline']=$receiverData->headline;
	            		$karmaNoteDetailData['giverHeadline']=$giverData->headline;
	            		$site_url=URL::to('/');
	            		$publicUrl=$site_url.'/meeting/'.$receiverData->fname.'-'.$receiverData->lname.'-'.$giverData->fname.'-'.$giverData->fname.'/'.$meetingId;
	            		$karmaNoteDetailData['publicUrl']=$publicUrl;
	            		return array(
    								'meetingStatus'=>$meetingData->status,
    								'karmaNoteData'=>$karmaNoteDetailData,
    								'meetingTrailData'=>$meetingMessageData,
    								'meetingUserId'=> $userProfileId,
    								'userProfilePic'=>$userProfilePicLink,
    								'meetingStatusText'=>$meetingStatusText
    					);
	            	}else{
	            		return array(
	            					'meetingStatus'=>$meetingData->status,
    								'meetingTrailData'=>$meetingMessageData,
    								'meetingUserId'=> $userProfileId,
    								'userProfilePic'=>$userProfilePicLink,
    								'meetingStatusText'=>$meetingStatusText
    					);
					}
	            	
        		}
        return false;
    	
    }

    public static function saveMeetingDataForMyKarma($meetingId,$userId,$giverId){
    	$getGiverData=User::where('id', '=', $giverId)->first();
    	$getUser=User::where('id', '=', $userId)->first();
        $getMeetingData=Meetingrequest::where('id','=',$meetingId)->first();
        //Add data on users_mykarma table for receiver
        $myKarmaDataReceiver = new Mykarma;
        $myKarmaDataReceiver->entry_id=$meetingId;
        $myKarmaDataReceiver->user_id=$userId;
        $myKarmaDataReceiver->fname=$getGiverData->fname;
        $myKarmaDataReceiver->lname=$getGiverData->lname;
        $myKarmaDataReceiver->piclink=$getGiverData->piclink;
        $myKarmaDataReceiver->entry_type='Meeting';
        $myKarmaDataReceiver->users_role='Receiver';
        $myKarmaDataReceiver->status='pending';
        $myKarmaDataReceiver->unread_flag='false';
        $myKarmaDataReceiver->no_of_unread_items='0';
        $myKarmaDataReceiver->entry_updated_on=Carbon::now();
        $myKarmaDataReceiver->save();
    //Add data on users_mykarma table for giver
        $myKarmaDataGiver = new Mykarma;
        $myKarmaDataGiver->entry_id=$meetingId;
        $myKarmaDataGiver->user_id=$giverId;
        $myKarmaDataGiver->fname=$getUser->fname;
        $myKarmaDataGiver->lname=$getUser->lname;
        $myKarmaDataGiver->piclink=$getUser->piclink;
        $myKarmaDataGiver->entry_type='Meeting';
        $myKarmaDataGiver->users_role='Giver';
        $myKarmaDataGiver->status='pending';
        $myKarmaDataGiver->unread_flag='true';
        $myKarmaDataGiver->no_of_unread_items='1';
        $myKarmaDataGiver->entry_updated_on=Carbon::now();
        $myKarmaDataGiver->save();
        $token=$getGiverData->deviceToken;
		$pushNotificationStatus=NotificationHelper::androidPushNotification($token);
        //Add message in requests_messages table
        $messageData = new Message;
        $messageData->request_id=$meetingId;
        $messageData->sender_id=$userId;
        $messageData->giver_id=$giverId;
        $messageData->receiver_id=$userId;
        $messageText=$getUser->fname.' '.$getUser->lname.' has sent a meeting request.';
        $messageData->messageText=$messageText;
        $messageData->save();
        $messageDataSecond = new Message;
        $messageDataSecond->message_type='user';
        $messageDataSecond->request_id=$meetingId;
        $messageDataSecond->sender_id=$userId;
        $messageDataSecond->giver_id=$giverId;
        $messageDataSecond->receiver_id=$userId;
        $messageDataSecond->messageText=$getMeetingData->notes;
        $messageDataSecond->save();
        $gratitudeText='In gratitude, I will do the following -';
         // Add regular messages in request_messages table.
        if( $getMeetingData->payitforward=='1'){
            $payitforwardText="I'll pay it forward";
        }else{
             $payitforwardText="";
        }
        if( $getMeetingData->buyyoucoffee=='1'){
          $buyyoucoffeeText="I'll buy you coffee (in-person meetings only)";  
        }else{
            $buyyoucoffeeText="";
        }
        if( $getMeetingData->sendKarmaNote=='1'){
           $sendKarmaNoteText="I'll send you a KarmaNote"; 
        }else{
          $sendKarmaNoteText="";  
        }
        if($getMeetingData->sendKarmaNote=='1'){
            $messageGratituteText=$gratitudeText."
".$sendKarmaNoteText.".
".$payitforwardText.".";    
        }else{
            $messageGratituteText=$gratitudeText."
".$payitforwardText.".";    
        }
        if (substr($messageGratituteText, 0, 1) === '.'){
                $messageGratituteText = substr($messageGratituteText, 1);
        }
        if($getMeetingData->payitforward=='1' || $getMeetingData->sendKarmaNote=='1' || $getMeetingData->buyyoucoffee=='1'){
            $messageDataSecond = new Message;
            $messageDataSecond->message_type='user';
            $messageDataSecond->request_id=$meetingId;
            $messageDataSecond->sender_id=$userId;
            $messageDataSecond->giver_id=$giverId;
            $messageDataSecond->receiver_id=$userId;
            $messageDataSecond->messageText=$messageGratituteText;
            $messageDataSecond->save();
        }
        return true;

    }

    //Save data of cancel request in message table and update status
    public static function saveMessageForMeetingCancel($entryId,$userId,$userRole){
    	$getGiverData=DB::table('requests')->where('requests.id','=',$entryId)->get();
                //Add message in requests_messages table
	    $getUser=User::where('id','=',$userId)->first();
	    if(!empty($getGiverData)){
	        if($userRole=='Receiver'){
	            $messageData=new Message;
	            $messageData->request_id=$entryId;
	            $messageData->sender_id=$userId;
	            $messageData->giver_id=$getGiverData[0]->user_id_giver;
	            $messageData->receiver_id=$userId;
	            $messageText=$getUser->fname.' '.$getUser->lname.' has canceled the meeting.';
	            $messageData->messageText=$messageText;
	            $messageData->save();
	            DB::table('users_mykarma')->where('entry_id','=',$entryId)->update(array('status' => 'cancelled','entry_updated_on' => Carbon::now()));
	       }else{
	            $messageData=new Message;
	            $messageData->request_id=$entryId;
	            $messageData->sender_id=$getGiverData[0]->user_id_giver;
	            $messageData->giver_id=$getGiverData[0]->user_id_giver;
	            $messageData->receiver_id=$userId;
	            $userData=User::find($getGiverData[0]->user_id_giver);
	            $messageText=$userData->fname.' '.$userData->lname.' has canceled the meeting.';
	            $messageData->messageText=$messageText;
	            $messageData->save();
	       		DB::table('users_mykarma')->where('entry_id','=',$entryId)->update(array('status' => 'responded','entry_updated_on' => Carbon::now()));
	       }
	        
	        
	   	}
	}

	 /**
	 * Function to save confirm meeting data.
	 *
	 * @return Response
	 */
     public static function commonConfirmMeeting($meetingId,$userId)
    {
    	$getUser=User::where('id','=',$userId)->first();
		DB::table('users_mykarma')->where('entry_id','=',$meetingId)->update(array('status' => 'confirmed','entry_updated_on' => Carbon::now()));
        DB::table('requests')->where('id','=',$meetingId)->update(array('status' => 'confirmed'));
        $getGiverData=DB::table('requests')->join('users','requests.user_id_giver','=','users.id')->where('requests.id','=',$meetingId)->select('users.fname','users.lname','requests.user_id_giver','requests.user_id_receiver','requests.status')->get();
       //Add message in requests_messages table
        if(!empty($getGiverData)){
            $messageData=new Message;
            $messageData->request_id=$meetingId;
            $messageData->sender_id=$userId;
            $messageData->giver_id=$getGiverData[0]->user_id_giver;
            $messageData->receiver_id=$userId;
            $messageText=$getUser->fname.' '.$getUser->lname.' has confirmed the meeting scheduled by '.$getGiverData[0]->fname.' '.$getGiverData[0]->lname;
            $messageData->messageText=$messageText;
            $messageData->save();
        }else{
        	$getGiverData=array();
        }
        return $getGiverData;
    }

    /**
	 * Function to request new time.
	 *
	 * @return Response
	 */
     public static function commonMeetingRequestNewTime($meetingId,$userId)
    {
    	$getUser=User::find($userId);
    	$meetingDetail = Meetingrequest::find($meetingId);
        $meetingDetail->meetingduration = 'null';
        $meetingDetail->meetingtimezonetext ='null';
        $meetingDetail->meetingtimezone ='null';
        $meetingDateTime = 'null';
        $meetingDetail->meetingdatetime = 'null';
        $meetingDetail->meetinglocation = 'null';
        $meetingDetail->meetingtimezone = 'null';
        $meetingDetail->meetingtype     = 'null';
        $meetingDetail->req_updatedate  = KarmaHelper::currentDate();
        $meetingDetail->reply           = 'null';
        $meetingDetail->status          = 'responded';
        $meetingDetail->save();
        $userRole='Receiver';
        DB::table('users_mykarma')->where('entry_id','=',$meetingId)->update(array('status' => 'responded','entry_updated_on' => Carbon::now()));
        $changeStatus=KarmaHelper::updateMeetingStatus($meetingId,$userRole);
        $getGiverData=DB::table('requests')->join('users','requests.user_id_giver','=','users.id')->where('requests.id','=',$meetingId)->select('users.fname','users.lname','requests.user_id_giver','requests.user_id_receiver','requests.status')->get();
        
        //Add message in requests_messages table
        if(!empty($getGiverData)){
            $messageData=new Message;
            $messageData->request_id=$meetingId;
            $messageData->sender_id=$userId;
            $messageData->giver_id=$getGiverData[0]->user_id_giver;
            $messageData->receiver_id=$userId;
            $messageText=$getUser->fname.' '.$getUser->lname.' has requested a new time for the meeting.';
            $messageData->messageText=$messageText;
            $messageData->save();
        }else{
        	$getGiverData=array();
        }
        return $getGiverData;
    }
    /**
	 * Function to save confirm meeting data.
	 *
	 * @return Response
	 */
     public static function commonMeetingNotHappened($meetingId,$userRole)
    {
    	$getMeetingId=Meetingrequest::where('id','=',$meetingId)->first();
    	$userId=$getMeetingId->user_id_receiver;
    	$getUser=User::find($userId);
    	DB::table('users_mykarma')->where('entry_id','=',$meetingId)->update(array('status' => 'responded','entry_updated_on' => Carbon::now()));
		DB::table('requests')->where('id','=',$meetingId)->update(array('status' => 'responded'));
		//Add message in requests_messages table
		$getGiverData=DB::table('requests')->join('users','requests.user_id_giver','=','users.id')->where('requests.id','=',$meetingId)->select('requests.user_id_giver','users.fname','users.lname')->get();
	    if(!empty($getGiverData)){
	    	$messageData = new Message;
	        $messageData->request_id=$meetingId;
	        $messageData->sender_id=$getGiverData[0]->user_id_giver;
	        $messageData->giver_id=$getGiverData[0]->user_id_giver;
	        $messageData->receiver_id=$userId;
	        if($userRole=='Receiver'){
	        	$messageText=$getUser->fname.' '.$getUser->lname.' has indicated that the meeting did not happened.';
	        }else if($userRole=='Giver'){
	        	$messageText=$getGiverData[0]->fname.' '.$getGiverData[0]->lname.' has indicated that the meeting did not happen.';
	        }
	        
	        $messageData->messageText=$messageText;
	        $messageData->save();
	        $userRole='Receiver';
	        $changeStatus=KarmaHelper::updateMeetingStatus($meetingId,$userRole);
	     }else{
	     	$getGiverData=array();
	     }
        return $getGiverData;
    }

    /**
	 * Function to save confirm meeting data.
	 *
	 * @return Response
	 */
     public static function commonMeetingHappened($meetingId,$userRole)
    {
    	$getMeetingId=Meetingrequest::where('id','=',$meetingId)->first();
    	$userId=$getMeetingId->user_id_receiver;
    	$getUser=User::find($userId);
    	DB::table('users_mykarma')->where('entry_id','=',$meetingId)->update(array('status' => 'happened','entry_updated_on' => Carbon::now()));
    	DB::table('requests')->where('id','=',$meetingId)->update(array('status' => 'happened'));
    	$getGiverData=DB::table('requests')->join('users','requests.user_id_giver','=','users.id')->where('requests.id','=',$meetingId)->select('requests.user_id_giver','users.fname','users.lname')->get();
        //Add message in requests_messages table
        if(!empty($getGiverData)){
            $messageData = new Message;
            $messageData->request_id=$meetingId;
            $messageData->sender_id=$getGiverData[0]->user_id_giver;
            $messageData->giver_id=$getGiverData[0]->user_id_giver;
            $messageData->receiver_id=$userId;
            $messageText=$getGiverData[0]->fname.' '.$getGiverData[0]->lname.' has indicated that the meeting has happened.';
            $messageData->messageText=$messageText;
            $messageData->save();
            $changeStatus=KarmaHelper::updateMeetingStatus($meetingId,$userRole);
	     }else{
	     	$getGiverData=array();
	     }
        return $getGiverData;
    }

    //Get meeting status
    public static function getMeetingStatusForWeb($receiverId,$giverId)
    {		
    		$userPrfileData = Auth::User();

            $checkMeetingStatus = DB::select(DB::raw("select user_id_receiver,user_id_giver,id,status from requests where (user_id_receiver=".$receiverId." OR user_id_receiver=".$giverId.") AND (user_id_giver=".$receiverId." OR user_id_giver=".$giverId." ) AND status NOT IN ('completed','archived','cancelled') order by created_at DESC limit 1"));  
               if(!empty($checkMeetingStatus)){
	               	if($userPrfileData->id==$checkMeetingStatus[0]->user_id_receiver){
	               		$userRole='Receiver';
	               	}else{
	               		$userRole='Giver';
	               	}
                    foreach ($checkMeetingStatus as $key => $value) {
                    	if($userRole=='Receiver'){
                    		$userData=User::find($value->user_id_giver);
                    	}else{
                    		$userData=User::find($value->user_id_receiver);
                    	}
                        
                        $meetingData['meetingRunning']='yes';
                        $meetingData['karmaId']=$value->id;
                        $meetingData['meetingStatus']=$value->status;
                        $meetingData['receiverId']=$value->user_id_receiver;
                        $meetingData['giverId']=$value->user_id_giver;
                        $meetingData['userId']=$userData->id;
                        $meetingData['fname']=$userData->fname;
                        $meetingData['lname']=$userData->lname;
                        $meetingData['piclink']=$userData->piclink;

                        if($value->user_id_receiver==$giverId){
                            $meetingData['userRole']='Giver';
                        }else{
                            $meetingData['userRole']='Receiver';
                        }
                    }
               }else{
                    $meetingData['meetingRunning']='no';
                    $meetingData['karmaId']='null';
                    $meetingData['userId']=$giverId;
                    $meetingData['receiverId']='null';
                    $meetingData['giverId']='null';
                    $meetingData['fname']='null';
                    $meetingData['lname']='null';
                    $meetingData['piclink']='null';
                    $meetingData['userRole']='null';
                    $meetingData['userRole']='null';
               }
               return $meetingData;
    }

     //Get meeting status
    public static function saveDataInMyKarma($receiverId,$giverId,$meetingId,$meetingStatus,$connectionGiverId,$updatedDate)
    {		
    	$checkKarmaData=Mykarma::where('entry_id','=',$meetingId)->count();
    	if($checkKarmaData < 1){
    		if($receiverId != '' || $receiverId != null){
    			if($giverId != 'null'){
    				$giverData=User::where('id','=',$giverId)->first();	
    			}else{
    				$giverData=Connection::where('id','=',$connectionGiverId)->first();
    			}
    			
    			$saveDataForReceiver=new MyKarma;
    			$saveDataForReceiver->user_id=$receiverId;
    			$saveDataForReceiver->entry_type='Meeting';
    			$saveDataForReceiver->entry_id=$meetingId;
    			$saveDataForReceiver->users_role='Receiver';
    			if(!empty($giverData)){
    				$saveDataForReceiver->fname=$giverData->fname;
    				$saveDataForReceiver->lname=$giverData->lname;
    				$saveDataForReceiver->piclink=$giverData->piclink;	
    			}else{
    				$saveDataForReceiver->fname='null';
    				$saveDataForReceiver->lname='null';
    				$saveDataForReceiver->piclink='null';
    			}
    			
    			$saveDataForReceiver->status=$meetingStatus;
    			if($meetingStatus =='responded' || $meetingStatus =='scheduled' || $meetingStatus =='over' || $meetingStatus =='happened'){
    				$saveDataForReceiver->unread_flag='true';
    				$saveDataForReceiver->no_of_unread_items='1';	
    			}else{
    				$saveDataForReceiver->unread_flag='false';
    				$saveDataForReceiver->no_of_unread_items='0';
    			}
    			$saveDataForReceiver->entry_updated_on=$updatedDate;
    			$saveDataForReceiver->created_at=Carbon::now();
    			$saveDataForReceiver->updated_at=Carbon::now();
    			$saveDataForReceiver->save();
    		}
    		if($giverId !='null'){
    			$receiverData=User::where('id','=',$receiverId)->first();
    			$saveDataForGiver=new MyKarma;
    			$saveDataForGiver->user_id=$giverId;
    			$saveDataForGiver->entry_type='Meeting';
    			$saveDataForGiver->entry_id=$meetingId;
    			$saveDataForGiver->users_role='Giver';	
    			if(!empty($receiverData)){
    				$saveDataForGiver->fname=$receiverData->fname;
    				$saveDataForGiver->lname=$receiverData->lname;
    				$saveDataForGiver->piclink=$receiverData->piclink;	
    			}else{
    				$saveDataForGiver->fname='null';
    				$saveDataForGiver->lname='null';
    				$saveDataForGiver->piclink='null';
    			}
    			$saveDataForGiver->status=$meetingStatus;
    			if($meetingStatus =='responded' || $meetingStatus =='pending' || $meetingStatus =='confirmed' || $meetingStatus =='over'){
    				$saveDataForGiver->unread_flag='true';
    				$saveDataForGiver->no_of_unread_items='1';	
    			}else{
    				$saveDataForGiver->unread_flag='false';
    				$saveDataForGiver->no_of_unread_items='0';
    			}
    			$saveDataForGiver->entry_updated_on=$updatedDate;
    			$saveDataForGiver->created_at=Carbon::now();
    			$saveDataForGiver->updated_at=Carbon::now();
    			$saveDataForGiver->save();
    		}else{
    			
	    			$receiverData=User::where('id','=',$receiverId)->first();
	    			$saveDataForGiver=new MyKarma;
	    			$saveDataForGiver->user_id='0';
	    			$saveDataForGiver->connection_id=$connectionGiverId;
	    			$saveDataForGiver->entry_type='Meeting';
	    			$saveDataForGiver->entry_id=$meetingId;
	    			$saveDataForGiver->users_role='Giver';	
	    			if(!empty($receiverData)){
	    				$saveDataForGiver->fname=$receiverData->fname;
	    				$saveDataForGiver->lname=$receiverData->lname;
	    				$saveDataForGiver->piclink=$receiverData->piclink;	
	    			}else{
	    				$saveDataForGiver->fname='null';
	    				$saveDataForGiver->lname='null';
	    				$saveDataForGiver->piclink='null';
	    			}
	    			$saveDataForGiver->status=$meetingStatus;
	    			if($meetingStatus =='responded' || $meetingStatus =='pending' || $meetingStatus =='confirmed' || $meetingStatus =='over'){
	    				$saveDataForGiver->unread_flag='true';
	    				$saveDataForGiver->no_of_unread_items='1';	
	    			}else{
	    				$saveDataForGiver->unread_flag='false';
	    				$saveDataForGiver->no_of_unread_items='0';
	    			}
	    			$saveDataForGiver->entry_updated_on=$updatedDate;
	    			$saveDataForGiver->created_at=Carbon::now();
	    			$saveDataForGiver->updated_at=Carbon::now();
	    			$saveDataForGiver->save();
	    		
    		}
    	}
    	return true;
    }


     public static function getMykarmaData(){
     	$userData=Auth::User();
     	return $userData;
     }
   

    
}

?>
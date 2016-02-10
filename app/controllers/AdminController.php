<?php


class AdminController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */ 
	public function index()
	{
		$Admin_Refresh_option =  Adminoption::Where('option_name','=','Connection Refresh Time')->first();
		$Email_Trigger_Time_Karmanote = Adminoption::Where('option_name','=','KarmaNote Email Trigger Time')->first();
		
		$weekly_suggestion = "";
		$weekly_suggestion =Adminoption::Where('option_name','=','Weekly Suggestion')->select("option_value")->first();
		if(!empty($weekly_suggestion))
		$weekly_suggestion = $weekly_suggestion->option_value;

		$dst_value = "";
		$dst_value =Adminoption::Where('option_name','=','Set DST value')->select("option_value")->first();
		if(!empty($dst_value))
		$dst_value = $dst_value->option_value;

		$kscore = "";
		$kscore =Adminoption::Where('option_name','=','Set Kscore Value')->select("option_value")->first();
		if(!empty($kscore))
		$kscore = $kscore->option_value;



		$test_user_id = Adminoption::Where('option_name','=','Test User Emails')->first();
		$emailset = '';  
		if($test_user_id!=""){  
			$user_id_set = explode(',',$test_user_id->option_value);
			
			foreach($user_id_set as $val){
				$user = DB::table('users')->where('id', $val)->select('users.email')->first();
				if(!empty($user))
				$emailset[]=$user->email;
			}
			if(!empty($emailset)) 
			$emailset = implode(',',$emailset);
		}
		
		//$Admin_Refresh_option =  DB::select(DB::raw('SELECT * FROM `admin_option` Where `option_name` = "Connection Refresh Time"');
		return View::make('admin.AdminDashboard',array('kscore'=>$kscore,'dst_value'=>$dst_value,'weekly_suggestion'=>$weekly_suggestion,'emailset'=>$emailset,'Admin_Refresh_option'=>$Admin_Refresh_option,'Email_Trigger_Time_Karmanote'=>$Email_Trigger_Time_Karmanote));
	}	
	public function userManagement(){
		$users = DB::select(DB::raw('SELECT * FROM `users` order by `userstatus` = "ready for approval" desc '));
		//echo "<pre>"; print_r($users);echo "</pre>";
		return View::make("admin.usermanagement",array('users'=>$users));
	}

		/*function for saving user detail*/
	public function updateUser(){
		$user_id = Input::get('id');
		$userDetail = User::find($user_id);
		$userstatus = Input::get('userstatus');
		$username = Input::get('name');
		$fname=$lname ='';
		if(isset($username))
			list($fname,$lname) = explode(" ", $username); 
		if($userstatus == "approved" && $userDetail->userstatus != "approved"){
				//MessageHelper::sendActivationMessage($userDetail); 
				Queue::push('MessageSender@sendActivationEmail',array('user_id'=> $user_id));

				// send an email and save a karmanote to user from kc team to show on his profile
				$Meetingrequest = new Meetingrequest;
				$Meetingrequest ->user_id_receiver 				= '430';
				$Meetingrequest ->user_id_giver 				= $user_id;
				$Meetingrequest ->subject 						= ''; 
				$Meetingrequest ->notes 						= "";
				$Meetingrequest ->status 						= 'completed';
				$Meetingrequest ->meetingdatetime			 	= date('Y-m-d H:i:s'); 
				$Meetingrequest ->replyviewstatus			 	= '1'; 
				$Meetingrequest ->requestviewstatus			 	= '1'; 
				$Meetingrequest ->meetingtimezone				= '';	
				$Meetingrequest ->req_createdate 				= KarmaHelper::currentDate();
				$Meetingrequest->save();
				$meetingId = $Meetingrequest->id;
 
				$meetingId = $Meetingrequest->id;
				$karmaNote = new Karmanote;
				$karmaNote ->req_id 							= $meetingId;
				$karmaNote ->user_idgiver 						= $user_id;
				$karmaNote ->user_idreceiver 					= '430'; 
				$karmaNote ->details 							= "Welcome to KarmaCircles. Thank you for joining the world's largest movement to spread good karma.";
				$karmaNote ->skills 							= '';
				$karmaNote ->statusreceiver						= 'hidden';
				$karmaNote ->viewstatus 						= 0;
				$karmaNote->created_at 							= KarmaHelper::currentDate();
				$karmaNote->save();


		} 
		$profileupdatedate = Input::get('profileupdatedate');
		$userrole = Input::get('role');
		$userupdate = User::find($user_id);
		$userupdate->userstatus = $userstatus ;
		$userupdate->fname = $fname ;
		$userupdate->lname = $lname ;
		$userupdate->profileupdatedate = $profileupdatedate;
		$userupdate->role = $userrole;
		$userupdate->save();

		/*Group Settings*/
		$group_ids 			=	Input::get('Groups');
		$meeting_setting = "accept from group only";
		/* $meeting_setting = Input::get('meeting_setting');
		if(empty($meeting_setting)){
			$meeting_setting = "accept from all";
		}
		else{
			$meeting_setting = "accept from group only";
		} */
	  	//echo "<pre>";print_r($group_ids);echo "</pre>";die;
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
	    	$user = User::find($user_id);   
			$user->meeting_setting = $meeting_setting;
			$user->save();
		 }
		 else{
		 	$user = User::find($user_id);
			//$user->meeting_setting = "accept from all";
			$user->meeting_setting = $meeting_setting; 
			$user->save();
		 }
		return Redirect::to('/admin/manageUser');
		//echo "<pre>"; print_r($_POST);echo "</pre>";
	}
	/*Function for Updating and inserting Refresh time & Email Trigger TimeupdateRefreshtime*/
	public function updateRefreshtime(){
		$option_name = Input::get('option_name');
		$option_value = Input::get('option_value');
		$adminOption = new Adminoption;
		$adminOption = Adminoption::firstOrCreate(array('option_name' => $option_name));
		$adminOption = Adminoption::find($adminOption['id']);
		$adminOption->option_name = $option_name;
		$adminOption->option_value = $option_value;
		$adminOption->save();
		return Redirect::to('/admin/dashboard');
	}
	/*function to show edit user detail in fancybox */
	public function edituserinfo($id){
		$userData = User::find($id); 
		$connection_count = $userData->Connections()->count();
		$Usersgroup = User::find($id)->Groups;
		//echo "<pre>"; print_r($userData);echo "</pre>";die();
		$groups = DB::select(DB::raw('SELECT * FROM `groups`'));
		return View::make("admin.UserData",array('element' => $userData,'connection_count'=>$connection_count,'groups'=>$groups,
			'Usersgroup'=>$Usersgroup));
	}
	/*function for searching user by email id*/
	public function adminSearchUserByEmail(){
		$searchEmail = Input::get('searchName');
		//$searchEmail = $email;
		$findbyUserByEmail = User::where('email', 'LIKE', '%'.$searchEmail.'%')->limit(10)->get();
		foreach ($findbyUserByEmail as $key => $element) {
			$count = $key + 1; 
			echo "<tr>";
	        			echo "<td>$count</td>"; 
		                echo "<td>$element->email</td>";
		                echo "<td>$element->fname $element->lname</td>";
		                echo "<td>$element->linkedinid</td>";
						echo "<td>$element->profileupdatedate</td>";
						echo "<td>$element->userstatus</td>";
						echo "<td>$element->role</td>";
						echo "<td align='center'>    
							<a href='$element->linkedinurl' target='_blank' ><img src='/images/linkdin.png' height='21' width='21'></a> 
						</td>";
						echo "<td><a class='edituserinfo fancybox.ajax' href='/admin/edituserinfo/$element->id'>Edit</a></td>";
	        echo  "</tr>";
		}
		die();
	}
	/*function for deleting the user*/
	/* public function deleteUser(){
		$id = Input::get('userId');
		$connections = Connection::Where('user_id','=',$id)->first();
		if(!empty($connections)){
			$connections->user_id = null;
			$connections->save();
		}   
		 
		$deletekarmanote = Karmanote::where('user_idreceiver', '=',$id)->delete();
		$deletekarmanote = Karmanote::where('user_idgiver', '=',$id)->delete();
		$deleterequest = Meetingrequest::where('user_id_receiver', '=',$id)->delete();
		$deletekarmanote = Meetingrequest::where('user_id_giver', '=',$id)->delete();
		$deleteusergroups = Usersgroup::where('user_id', '=',$id)->delete();
		$deletegroupquestion = Groupquestion::where('user_id', '=',$id)->delete();
		$deleteQuestionwillingtohelp = Questionwillingtohelp::where('user_id', '=',$id)->delete();
		$deletequestion = Question::where('user_id', '=',$id)->delete();

		$user = User::find($id);
		$user->delete();
		echo "User ".$id." deleted";  
	} */
	
	// public function deleteUser(){
	// 	$id = Input::get('userId'); 
	// 	$test_user = DB::table('admin_option')->where('option_name','Test User Emails')->select('option_value')->get();
	// 	if(!empty($test_user)){
	// 		$count=0;
	// 		foreach ($test_user as $value) {
	// 			foreach (explode(',', $value->option_value) as $test_user_id){
	// 				if($id == $test_user_id){
	// 					$count=$count+1;
	// 				}
	// 			}
	// 		}
	// 	}else{
	// 		$count=0;
	// 	}
	// 	if($count > 0 ){
	// 		$connections = Connection::Where('user_id','=',$id)->first();
	// 	 	if(!empty($connections)){
	// 			$connections->user_id = null;
	// 			$connections->save();
	// 		}
	// 		$findquestionid=DB::table('questions')->where('user_id','=',$id)->select('id')->get();
	// 		$deletekarmacircles = Karmacircle::where('user_id', '=',$id)->delete();
	// 		if(!empty($findquestionid)){
	// 			foreach ($findquestionid as $value) {
	// 				$questionId=$value->id;
	// 				$deleteQuestionwillingtohelp = Questionwillingtohelp::where('question_id', '=',$questionId)->delete();
	// 				$deleteGroupQuestion = Groupquestion::where('question_id', '=',$questionId)->delete();
	// 				$deleteQuestion = Question::where('id', '=',$questionId)->delete();
	// 			}
	// 		}
	// 		$deletekarmanote = Karmanote::where('user_idreceiver', '=',$id)->delete();
	// 		$deletekarmanote = Karmanote::where('user_idgiver', '=',$id)->delete();
	// 		$deleterequest = Meetingrequest::where('user_id_receiver', '=',$id)->delete();
	// 		$deletekarmanote = Meetingrequest::where('user_id_giver', '=',$id)->delete();  
	// 		$deletekarmanote = Meetingrequest::where('user_id_introducer', '=',$id)->delete();
	// 		$deleteusergroups = Usersgroup::where('user_id', '=',$id)->delete();
			
	// 		$findquestion = Question::Where('user_id','=',$id)->get();
	// 		if(!empty($findquestion)) { 
	// 			foreach($findquestion as $val){
	// 				$question_id = $val->id;
	// 				$findgroupquestion = Groupquestion::Where('user_id','=',$id)->where('question_id', '=', $question_id)->get();
	// 				if(!empty($findgroupquestion)) {
	// 					$deletegroupquestion = Groupquestion::where('user_id', '=',$id)->where('question_id', '=', $question_id)->delete();
	// 				}
	// 				$findQuestionwillingtohelp = Questionwillingtohelp::Where('question_id', '=', $question_id)->where('user_id','=',$id)->get();
	// 				if(!empty($findQuestionwillingtohelp)) { 
	// 					$deleteQuestionwillingtohelp = Questionwillingtohelp::Where('question_id', '=',$question_id)->where('user_id','=',$id)->delete();
	// 				} 
					 
	// 			}
	// 		} 
			
	// 		$user = User::find($id);
	// 		$user->delete();
	// 		echo "User ".$id." deleted";	
	// 	}else{

	// 		echo "User ".$id." cant be deleted.Its not a test user.";
	// 	}
		 
			
		
	// }
/*
	public function deleteUser(){
		$id = Input::get('userId'); 
		$test_user = DB::table('admin_option')->where('option_name','Test User Emails')->select('option_value')->get();
		if(!empty($test_user)){
			$count=0;
			foreach ($test_user as $value) {
				foreach (explode(',', $value->option_value) as $test_user_id){
					if($id == $test_user_id){
						$count=$count+1;
					}
				}
			}
		}else{
			$count=0;
		}
		if($count > 0 ){
			$connections = Connection::Where('user_id','=',$id)->first();
		 	if(!empty($connections)){
				$connections->user_id = null;
				$connections->save();
			}
			$deletekarmanote = Karmanote::where('user_idreceiver', '=',$id)->delete();
			$deletekarmanote = Karmanote::where('user_idgiver', '=',$id)->delete();
			$deleterequest = Meetingrequest::where('user_id_receiver', '=',$id)->delete();
			$deletekarmanote = Meetingrequest::where('user_id_giver', '=',$id)->delete();
			$deletekarmanote = Meetingrequest::where('user_id_introducer', '=',$id)->delete();
			$deleteusergroups = Usersgroup::where('user_id', '=',$id)->delete();
			$findquestion = Question::Where('user_id','=',$id)->get();
			if(!empty($findquestion)) { 
				foreach($findquestion as $val){
					$question_id = $val->id;
					$findgroupquestion = Groupquestion::Where('question_id', '=', $question_id)->get();
					if(!empty($findgroupquestion)) {
						$deletegroupquestion = Groupquestion::Where('question_id', '=', $question_id)->delete();
					}
					$findQuestionwillingtohelp = Questionwillingtohelp::Where('question_id', '=', $question_id)->get();
					if(!empty($findQuestionwillingtohelp)) { 
						$deleteQuestionwillingtohelp = Questionwillingtohelp::Where('question_id', '=',$question_id)->delete();
					} 
					 
				}
			} 
			$deleteQuestionwillingtohelp = Questionwillingtohelp::Where('user_id', '=',$id)->delete();
			if(!empty($findquestion)){
				$deletequestion = Question::where('user_id', '=',$id)->delete();  		
			}
			$deletekarmacircles = Karmacircle::where('user_id', '=',$id)->delete();
			$user = User::find($id);
			$user->delete();
			echo "User ".$id." deleted";

		}else{

			echo "User ".$id." cant be deleted.Its not a test user.";
		}
		 
			
		
	}


*/

public function deleteUser(){
		$id = Input::get('userId'); 
		$test_user = DB::table('admin_option')->where('option_name','Test User Emails')->select('option_value')->get();
		if(!empty($test_user)){
			$count=0;
			foreach ($test_user as $value) {
				foreach (explode(',', $value->option_value) as $test_user_id){
					if($id == $test_user_id){
						$count=$count+1;
					}
				}
			}
		}else{
			$count=0;
		}
		if($count > 0 ){
			$connections = Connection::Where('user_id','=',$id)->first();
		 	if(!empty($connections)){
				$connections->user_id = null;
				$connections->save();
			}
			$deletekarmanote = Karmanote::where('user_idreceiver', '=',$id)->delete();
			$deletekarmanote = Karmanote::where('user_idgiver', '=',$id)->delete();
			$deleterequest = Meetingrequest::where('user_id_receiver', '=',$id)->delete();
			$deletekarmanote = Meetingrequest::where('user_id_giver', '=',$id)->delete();
			$deletekarmanote = Meetingrequest::where('user_id_introducer', '=',$id)->delete();
			$deleteusergroups = Usersgroup::where('user_id', '=',$id)->delete();
			$findquestion = Question::Where('user_id','=',$id)->get();
			if(!empty($findquestion)) { 
				foreach($findquestion as $val){
					$question_id = $val->id;
					$findgroupquestion = Groupquestion::Where('question_id', '=', $question_id)->get();
					if(!empty($findgroupquestion)) {
						$deletegroupquestion = Groupquestion::Where('question_id', '=', $question_id)->delete();
					}
					$findQuestionwillingtohelp = Questionwillingtohelp::Where('question_id', '=', $question_id)->get();
					if(!empty($findQuestionwillingtohelp)) { 
						$deleteQuestionwillingtohelp = Questionwillingtohelp::Where('question_id', '=',$question_id)->delete();
					} 
					 
				}
			}
			$deleteQuestionwillingtohelp = Questionwillingtohelp::Where('user_id', '=',$id)->delete();
			if(!empty($findquestion)){
				$deletequestion = Question::where('user_id', '=',$id)->delete();  		
			}
			
			$deletekarmacircles = Karmacircle::where('user_id', '=',$id)->delete();
			$deletekarmaFeed = Karmafeed::where('receiver_id', '=',$id)->orWhere('giver_id', '=',$id)->delete();
			$userMykarmaDelete = Mykarma::where('user_id','=',$id)->delete();
			$userMessageDelete = Message::where('receiver_id','=',$id)->orWhere('giver_id','=',$id)->delete();
			$getIntroDataDelete=KarmaIntro::where('intro_giver_id','=',$id)->orWhere('intro_receiver_id','=',$id)->orWhere('intro_introducer_id','=',$id)->delete();
			$getMyKarmaDataDelete=Mykarma::where('user_id','=',$id)->delete();
			$user = User::find($id);
			$user->delete();
			echo "User ".$id." deleted";

		}else{

			echo "User ".$id." cant be deleted.Its not a test user.";
		}
		 
			
		
	}



	public function groupManagement(){
		$groups = DB::select(DB::raw('SELECT * FROM `groups`'));	
		foreach ($groups as $key => $value) {
			$groupCount = Group::find($value->id)->Users()->count();
			$groups[$key]->UserCount = $groupCount;
		}
			//echo "<pre>"; print_r($groups);echo "</pre>";
		return View::make("admin.groupmanagement",array('groups'=>$groups));
	}

	
	public function addGroup(){
		return View::make("admin.addgroup");
	}
	public function addgroupdata(){
		$name = Input::get('name');
		$description = Input::get('description');
		$url = Input::get('url');
		$Group = new Group;
		$Group = Group::firstOrCreate(array('name' => $name));
		if($Group->description == null){
			$Group = Group::find($Group['id']);
			$Group->name = $name;
			$Group->description = $description;
			$Group->url = $url; 
			$Group->save();
			$group_id = $Group->id;
			// save vanity URL for a group 
			/*$trimedName = trim(str_replace("-", " ", $name));
			$redirecturl = 'group/'.$group_id.'/'.$trimedName;
			$Vanityurl = new Vanityurl;
			$Vanityurl = Vanityurl::firstOrCreate(array('vanityurl' => $name));
			if($Vanityurl->redirecturl == null){
				$Vanityurl = Vanityurl::find($Vanityurl['id']);
				$Vanityurl->vanityurl = $name;
				$Vanityurl->redirecturl = $redirecturl;
				$Vanityurl->save();
			}*/

			return Redirect::to('/admin/manageGroup');
		}else{
			return Redirect::to('/admin/manageGroup');
		}
		
	}
	public function getrawconnectiondata($id){
		$check = User::find($id);
	if(!empty($check)){
			$token= $check->token;
			echo "User:".$check->fname." ".$check->lname."<br>";
			$user_connection = json_decode(file_get_contents("https://api.linkedin.com/v1/people/~/connections:(id,first-name,last-name,headline,summary,industry,member-url-resources,picture-urls::(original),location,public-profile-url,site-standard-profile-request)?format=json&oauth2_access_token=$token"));
			echo "totalConnection=".$user_connection->_total;
			echo "<pre>"; print_r($user_connection); echo "</pre>";die();
		}
		else{
			echo "Invalid User ID";
		}
	}
	public function editgroupdata(){
		$id = Input::get('id');
		$name = Input::get('name');
		$description = Input::get('description');
		$url = Input::get('url');
		$Group = Group::find($id);
		$Group->name = $name;
		$Group->description = $description;
		$Group->url = $url; 
		$Group->save();	

		// save vanity URL for a group
			/*$trimedName = trim(str_replace(' ', '-', $name)); 
			$redirecturl = 'group/'.$id.'/'.$trimedName;
			$Vanityurl = new Vanityurl;
			$Vanityurl = Vanityurl::firstOrCreate(array('vanityurl' => $name));
			if($Vanityurl->redirecturl == null){
				$Vanityurl = Vanityurl::find($Vanityurl['id']);
				$Vanityurl->vanityurl = $name;
				$Vanityurl->redirecturl = $redirecturl;
				$Vanityurl->save();
			}*/
		return Redirect::to('/admin/manageGroup');		
	}
	public function editgroupinfo($id){
		$groupinfo = Group::find($id);
		return View::make("admin.editgroup",array('groupinfo'=>$groupinfo));
	}

	public function deletegroup(){
	die;
		$id = Input::get('groupId'); 
		$Usersgroupdata = Usersgroup::where('group_id', '=',$id)->get();
			foreach ($Usersgroupdata as $key => $value) {
				$user_id = $value->user_id;
				$user = User::find($user_id);
				//$user->meeting_setting = "accept from all";
				$user->meeting_setting = "accept from group only";
				
				$user->save();
			}
		
		$deleteUsersgroup = Usersgroup::where('group_id', '=',$id)->delete();
		$deleteGroup = Group::where('id', '=',$id)->delete();
		echo "Group ".$id." deleted";
	}
	public function vanityUrlsManagement(){
		$vanityurls = DB::select(DB::raw('SELECT * FROM `vanityurls`'));
		//echo "<pre>"; print_r($users);echo "</pre>";
		return View::make("admin.vanityUrlsManagement",array('vanityurls'=>$vanityurls));
	}
	public function addvanity(){
		return View::make("admin.addvanity");
	}
	public function addvanitydata(){
		$name = Input::get('vanityurl');
		$redirecturl = Input::get('redirecturl');
		$Vanityurl = new Vanityurl;
		$Vanityurl = Vanityurl::firstOrCreate(array('vanityurl' => $name));
		if($Vanityurl->redirecturl == null){
			$Vanityurl = Vanityurl::find($Vanityurl['id']);
			$Vanityurl->vanityurl = $name;
			$Vanityurl->redirecturl = $redirecturl;
			$Vanityurl->save();
			return Redirect::to('/admin/manageVanityUrls');
		}else{
			return Redirect::to('/admin/manageVanityUrls');
		}
	}
	public function editvanityinfo($id){
		$vanityinfo = Vanityurl::find($id);
		//echo "<pre>"; print_r($vanityinfo);echo "</pre>";die();
		return View::make("admin.editvanity",array('vanityinfo'=>$vanityinfo));
	}
	public function editvanitydata(){
		$id = Input::get('id');
		$name = Input::get('vanityurl');
		$redirecturl = Input::get('redirecturl');
		$Vanityurl = Vanityurl::find($id);
		$Vanityurl->vanityurl = $name;
		$Vanityurl->redirecturl = $redirecturl;
		$Vanityurl->save();	
		return Redirect::to('/admin/manageVanityUrls');		
	}
	public function deletevanityurl(){
		die();
		$id = Input::get('vanityId'); 
		$deleteVanityurl = Vanityurl::where('id', '=',$id)->delete();
		echo "Vanity id ".$id." deleted";
	}
	public function PendingWork(){
		return View::make('error.404');
	}
	public function updateTestemail(){
	
		$email = Input::get('email');
		$femail = ''; 
		if($email){
			$emailset = explode(',',$email);
			$idset = '';
			foreach($emailset as $val){
				$user = DB::table('users')->where('email', $val)->select('users.id')->first();
				if(!empty($user))
				$idset[]=$user->id;
			}
			$femail = implode(',',$idset);
		}
			$option_name = Input::get('option_name');
			$adminOption = new Adminoption;
			$adminOption = Adminoption::firstOrCreate(array('option_name' => $option_name));
			$adminOption = Adminoption::find($adminOption['id']);
			$adminOption->option_name = $option_name;
			$adminOption->option_value = $femail;
			$adminOption->save();
		
		return Redirect::to('/admin/dashboard');
	}

	public function updateWeeklySuggestion(){
	
			$suggestion = Input::get('option_value');
			$option_name = Input::get('option_name');
			$adminOption = new Adminoption;
			$adminOption = Adminoption::firstOrCreate(array('option_name' => $option_name));
			$adminOption = Adminoption::find($adminOption['id']);
			$adminOption->option_name = $option_name;
			$adminOption->option_value = $suggestion;
			$adminOption->save();
		
		return Redirect::to('/admin/dashboard');
	}

	public function update_DST(){
			$suggestion = Input::get('option_value');
			$option_name = Input::get('option_name');
			$adminOption = new Adminoption;
			$adminOption = Adminoption::firstOrCreate(array('option_name' => $option_name));
			$adminOption = Adminoption::find($adminOption['id']);
			$adminOption->option_name = $option_name;
			$adminOption->option_value = $suggestion;
			$adminOption->save();
		return Redirect::to('/admin/dashboard');
	}

	public function update_user_kscore(){
			$suggestion = Input::get('option_value');
			$option_name = Input::get('option_name');
			$adminOption = new Adminoption;
			$adminOption = Adminoption::firstOrCreate(array('option_name' => $option_name));
			$adminOption = Adminoption::find($adminOption['id']);
			$adminOption->option_name = $option_name;
			$adminOption->option_value = $suggestion;
			$adminOption->save();
		return Redirect::to('/admin/dashboard');
	}
 
	public function report(){
		return View::make('admin.report',array());
	}

	public function getreport_data(){
		$email =  $searchquery="";
		$user_id = $end = $begin = $request_id = 0;
		if(!empty($_REQUEST['email'])) $email = $_REQUEST['email']; 
		if(!empty($_REQUEST['begin']))
		{
			$begin = date('Y-m-d H:i:s', strtotime($_REQUEST['begin']));
		}
		if(!empty($_REQUEST['end'])) 
		{
			$end = date('Y-m-d H:i:s', strtotime($_REQUEST['end']));
		}
		if($email !=""){
			$searchquery = DB::table('users as u')
					->select(array('u.email','u.id'))
		            ->where('u.userstatus','=','approved')
					->where('u.email','LIKE','%'.$email)
		            ->distinct()
		            ->first();
		   
		    if(!empty($searchquery)){
		    	$user_id = $searchquery->id;
		    	$karmaTrailNote = KarmaHelper::getAdminReportNote($request_id,$user_id,$begin,$end);
		    	$karmaTrailMeet = KarmaHelper::getAdminReportMeet($request_id,$user_id,$begin,$end); 
		    }
		}
		else{
			$karmaTrailNote = KarmaHelper::getAdminReportNote($request_id,$user_id,$begin,$end); 
			$karmaTrailMeet = KarmaHelper::getAdminReportMeet($request_id,$user_id,$begin,$end);
		} 

		//echo"<pre>==".$user_id."====";print_r($karmaTrailNote);echo"</pre>=====";  
		
		return View::make('admin.ajax_reportdata',array('karmaTrail'=>$karmaTrailNote,'karmaTrailMeet'=>$karmaTrailMeet));
	}

	public function viewnote_detail($request_id){
		$karmaTrailNote = KarmaHelper::getAdminReportNote($request_id,0,0,0); 
		
		return View::make('admin.viewnote_detail',array('karmaTrail'=>$karmaTrailNote));
	}  

	public function viewreq_detail($request_id,$action){
		$karmaTrailMeet = KarmaHelper::getAdminReportMeet($request_id,0,0,0); 
		

		return View::make('admin.viewreq_detail',array('karmaTrail'=>$karmaTrailMeet,'action'=>$action)); 
	} 

	//Function to manage queries
	public function queryManagement(){
		$questions = DB::select(DB::raw('SELECT * FROM `questions`'));	
		return View::make("admin.questionmanagement",array('questions'=>$questions));
	} 

	//function to delete queries

	public function editqueryinfo($id){
		$queryinfo = Question::find($id);
		return View::make("admin.editquery",array('queryinfo'=>$queryinfo));
	}
	//function to edit queries
	public function editquerydata(){
		$id = Input::get('id');
		$subject = Input::get('subject');
		$description = Input::get('description');
		$question_url = Input::get('question_url');
		$Question = Question::find($id);
		$Question->subject = $subject;
		$Question->description = $description;
		$Question->question_url = $question_url; 
		$Question->save();	

		return Redirect::to('/admin/managequeries');		
	}
	//function to Delete queries
	public function deletequery(){
		$id = Input::get('queryId');
		$deleteQuestionwillingtohelp = Questionwillingtohelp::where('question_id', '=',$id)->delete();
		$deleteGroupQuestion = Groupquestion::where('question_id', '=',$id)->delete();
		$deleteQuestion = Question::where('id', '=',$id)->delete();
		$deleteKarmafeed = Karmafeed::where('id_type', '=',$id)->whereIn('message_type',array('KarmaQuery','OfferHelpTo'))->delete();  
		$deleteMykarma = Mykarma::where('entry_id', '=',$id)->whereIn('users_role',array('PostedQuery','OfferedHelp'))->delete();
        echo "Question ".$id." deleted";
	}
}
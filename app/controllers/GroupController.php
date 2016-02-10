<?php

class GroupController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function __construct(User $user){
		$this->user = $user; 
	}

	public function groupsAll(){
			$user_info = "";
			$user_info = Auth::user();
			$group = DB::table('groups')->get();
			$user_all_groups =  $userinGroup ='';
			if(Auth::check()){
				$userinGroup = KarmaHelper::getuserGroup();
			}
			if(!empty($userinGroup)){ 
				foreach ($userinGroup as $key => $value) {
				$user_all_groups[] = $value->id;
			}	}
			return View::make('group.grouplist',array('pageTitle'=>'Groups Directory | KarmaCircles','pageDescription'=>'Search among the groups of like-minded professionals, create your own, or join one to explore the happiness of giving and taking free help.', 'user_all_groups' => $user_all_groups,'CurrentUser' => $user_info,'group'=>$group));
	}

	public function groupPage($name,$groupId){  
	 
		$CurrentUserId = 0;
		$groupDetail = $CurrentUser = $userinGroup = "";
		$groupDetail 	= DB::table('groups')->where('id','=',$groupId)->first();
		if(empty($groupDetail)){ 
			if(Auth::check()) return Redirect::to('404');
			else return Redirect::to('/groupsAll'); 
		}
		$trimedName = strtolower(trim(str_replace(' ', '-', $groupDetail->name))); 

		if($trimedName != strtolower($name)){
			if(Auth::check()) return Redirect::to('404');
			else return Redirect::to('/groupsAll'); 
		} 
		  
 
			if(Auth::check()){
				$CurrentUser 	= Auth::User();
				$CurrentUserId = $CurrentUser->id;
				if($CurrentUserId != "")	
					$CurrentUserId = $CurrentUserId;  
					$userinGroup =  DB::table('users_groups')->where('group_id','=',$groupId)->where('user_id','=',$CurrentUserId)->count();
			}
			/*Only Group Questions*/
			$start = 0; $perpage = 15;   
			$group_question = "";
			$group_question = KarmaHelper::getTopGroupQuery($CurrentUserId,$groupId,$start,$perpage);

			/*Only Group Top Givers*/
			$toppers = array();
			$toppers = KarmaHelper::GetToppersInGroup($groupId,$start,$perpage);

			return View::make('group.GroupPage',array('topperCount'=>0,'toppers'=>$toppers,'userinGroup'=>$userinGroup,'groupDetail'=>$groupDetail,'countTopGiver'=>0,'countSearchQuery'=>0,'coutTopQuery'=>0,'groupId'=>$groupId,'CurrentUser' => $CurrentUser,'group_question'=>$group_question));
		
	}

	public function getdataByorder(){
		$currentTab = Input::get('currentTab');
		$setting = Input::get('setting');
		$groupId = Input::get('groupId');
		$group_question = "";
		$All_groups[] = array($groupId);
		$CurrentUserId = 0;
		$CurrentUser =  "";
		
		if(Auth::check()){
			$CurrentUser 	= Auth::User();
			$CurrentUserId = $CurrentUser->id;
			if($CurrentUserId != "")	
				$CurrentUserId = $CurrentUserId;  
		}

		if($currentTab == "topquery"){
			   
				$group_question = DB::table('users_groups')
					->join('questions', 'users_groups.user_id', '=', 'questions.user_id')
					->select(array('questions.*','users_groups.group_id'))
		            ->where('questions.user_id','!=',$CurrentUserId)
		            ->where('users_groups.group_id','=',$groupId)
		            ->where('questions.queryStatus','=','open') 
		            ->orderBy('questions.created_at','DESC')
		            ->get();
			
			if($setting == 'Recent'){
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
			}
			
			elseif($setting == 'Unanswered'){
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

					$group_question = array_values(array_sort($group_question, function($value)
					{
						return $value->giver_Count;
					}));
				}	
			}
			elseif($setting == 'Populer'){
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

					$group_question = array_values(array_sort($group_question, function($value)
					{
					return $value->giver_Count;
					}));
					$group_question = array_reverse($group_question);
				}	
 
			}
		}

		return View::make('group.ajax_group_queryOrder',array(
			
			'group_question'=>$group_question,
			'coutTopQuery'=>0,
			'CurrentUserId'=>$CurrentUserId
			));

	}


	public function loadmoreGroupPage(){
		$TopGiver = $TopQueries = $SearchQueries  = '';
		if(!empty($_REQUEST))
		{
			$start=$_REQUEST['hitcount'];
			$action=$_REQUEST['action']; 
			$groupId=$_REQUEST['groupId']; 
			$groupSearchd=$_REQUEST['groupSearchd']; 
			$perpage = 10;  
			$CurrentUserId = 0;
			$CurrentUserDetail = '';
			$group_search = $group_question = $toppers = "";

			if(Auth::check()){
				$CurrentUserDetail 	= Auth::User();
				$CurrentUserId = $CurrentUserDetail->id;
				if($CurrentUserId != "")$CurrentUserId = $CurrentUserId;
			}
			/*Only Group Questions*/ 
			if($action == "TopQueries" ){
				$group_question = KarmaHelper::getTopGroupQuery($CurrentUserId,$groupId,$start,$perpage);
			}
 
			if($action == "TopGivers"){
				/*Only Group Top Givers*/
				$toppers = KarmaHelper::GetToppersInGroup($groupId,$start,$perpage);
			}

			if($action == "Search"){ 
				
				if($searchOption == "People"){
					$group_search = KarmaHelper::GroupSearchPeople($CurrentUserId,$GroupChoosenId,$search,$start,$perpage,'all_member');
				}
				if($searchOption == "Skills"){
					$group_search = KarmaHelper::GroupSearchSkill($CurrentUserId,$GroupChoosenId,$search,$start,$perpage);
				}
				if($searchOption == "Industry"){
					$group_search = KarmaHelper::GroupSearchIndustry($CurrentUserId,$GroupChoosenId,$search,$start,$perpage);
				}
				if($searchOption == "Location"){
					$group_search = KarmaHelper::GroupSearchLocation($CurrentUserId,$GroupChoosenId,$search,$start,$perpage);
				}
				if($searchOption == "Groups"){
					$group_search = KarmaHelper::GroupSearchPeople($CurrentUserId,$GroupChoosenId,$groupSearchd,$start,$perpage,'common_member');
				}
			}

			return View::make
				('group.ajax_loadmoreGroupPage',array('group_search'=>$group_search,'topperCount'=>0,'toppers'=>$toppers,'countTopGiver'=>0,'countSearchQuery'=>0,'coutTopQuery'=>0,'groupId'=>$groupId,'CurrentUser' => $CurrentUserDetail,'group_question'=>$group_question
						));
		}
	}
 
	public function GroupSearch(){
		$CurrentUserId = 0;
			$CurrentUserDetail = '';
			if(Auth::check()){
				$CurrentUserDetail 	= Auth::User();
				$CurrentUserId = $CurrentUserDetail->id;
				if($CurrentUserId != "")$CurrentUserId = $CurrentUserId;
		} 
		$group_searchId = "";
		$search = $_REQUEST['keyword'];
		if(isset($_REQUEST['group_searchId']))
		$group_searchId = $_REQUEST['group_searchId'];
		$searchOption = $_REQUEST['optionVal'];
		$GroupChoosenId = $_REQUEST['groupId'];

		 
		$start = 0; $perpage = 10;   
		$group_search = "";
		if($searchOption == "All"){
			$group_search = KarmaHelper::GroupSearchPeopleAll($CurrentUserId,$GroupChoosenId,$search,$start,$perpage);
		}
		if($searchOption == "People"){
			$group_search = KarmaHelper::GroupSearchPeople($CurrentUserId,$GroupChoosenId,$search,$start,$perpage,'all_member');
		}
		if($searchOption == "Skills"){
			$group_search = KarmaHelper::GroupSearchSkill($CurrentUserId,$GroupChoosenId,$search,$start,$perpage);
		}
		if($searchOption == "Industry"){
			$group_search = KarmaHelper::GroupSearchIndustry($CurrentUserId,$GroupChoosenId,$search,$start,$perpage);
		}
		if($searchOption == "Location"){
			$group_search = KarmaHelper::GroupSearchLocation($CurrentUserId,$GroupChoosenId,$search,$start,$perpage);
		}
		if($searchOption == "Groups"){
			$group_search = KarmaHelper::GroupSearchPeople($CurrentUserId,$GroupChoosenId,$group_searchId,$start,$perpage,'common_member');	
		}
		
		//echo "<pre>";print_r($group_search);echo "</pre>";die;
		return View::make
				('group.ajaxsearch_group_result',array(
					'CurrentUserId'=>$CurrentUserId,
					'group_search'=>$group_search,
					 'searchFor'=> $search,
					 'searchCat'=> $searchOption,
					

						));
	}

	public function searchGroupresult(){ 
		
		$CurrentUserId = 0;
			$CurrentUserDetail = '';
			if(Auth::check()){
				$CurrentUserDetail 	= Auth::User();
				$CurrentUserId = $CurrentUserDetail->id;
				if($CurrentUserId != "")$CurrentUserId = $CurrentUserId;
		} 
		 $location = $search = $totalCount =$searchCat = '';
		$searchresult = array();
		if(!empty($_REQUEST['keyword'])) $search = $_REQUEST['keyword'];
		if(!empty($_REQUEST['optionVal'])) $searchCat = $_REQUEST['optionVal'];
		if(!empty($_REQUEST['groupId'])) $groupId = $_REQUEST['groupId'];


		//die($search.$searchCat.$groupId);
		if($searchCat == 'People'){
			if(!empty($CurrentUserDetail)){
			$user_id = $CurrentUserDetail->id;
			$location = $CurrentUserDetail->location;
		}		
		$searchquery = DB::select(DB::raw('select DISTINCT `u`.`userstatus`, `u`.`id`, `u`.`fname`, `u`.`lname`, `u`.`linkedinurl`,`u`.`linkedinid`, `u`.`piclink`, `u`.`headline`, `u`.`email`,`u`.`karmascore`, `u`.`location` from `users` as `u` inner join `users_groups` on `u`.`id` = `users_groups`.`user_id` where `users_groups`.`group_id` in ('.$groupId.')and `u`.`id` != '.$CurrentUserId.'  and `u`.`userstatus` = "approved" and concat(u.fname," ",u.lname) LIKE "%'.$search.'%"'));    
	  	foreach ($searchquery as $key => $value) {
	  		$searchresult[$key]['fname'] = $value->fname;
	  		$searchresult[$key]['lname'] = $value->lname;
	  		$searchresult[$key]['linkedinurl'] = $value->linkedinurl;
	  		$searchresult[$key]['headline'] = $value->headline;
	  		$searchresult[$key]['location'] = $value->location;
	  		$searchresult[$key]['linkedinid'] = $value->linkedinid;
	  		$searchresult[$key]['piclink'] = $value->piclink;
	  		$searchresult[$key]['id'] = $value->id;
	  		//$searchresult[$key]['userstatus'] = $value->userstatus;
	  		$searchresult[$key]['unique_id'] = $key;
			//$checkKarmaUser = KarmaHelper::checkKarmaUser($value->networkid);
	  		if($value->id != '' && ($value->userstatus == 'approved' || $value->userstatus == ''))
	  			$karmaProfileLink = 'profile/'.strtolower($value->fname.'-'.$value->lname).'/'.$value->id;
	  		else
	  			$karmaProfileLink = '';
	  		$searchresult[$key]['karmaProfileLink'] = $karmaProfileLink;
	  	}
        $totalCount = 0;
		if(!empty($searchresult)) $totalCount = count($searchresult);
		
		return View::make('group.ajaxsearch_group_people',array('CurrentUser' => $CurrentUserDetail,'searchresult'=> $searchresult, 'totalResult' => $totalCount, 'searchFor'=> $search)); 	  	
		}
		elseif($searchCat == 'Groups'){
			$searchquery = 	DB::select(DB::raw('SELECT * FROM `groups` Where `groups`.`name` LIKE "%'.$search.'%" and groups.id != '.$groupId));
			if(!empty($searchquery)){
			foreach ($searchquery as $key => $value) {
		  		$searchresult[$key]['id'] = $value->id;
		  		$searchresult[$key]['name'] = $value->name;
		  		$searchresult[$key]['description'] = $value->description;
	  		}
	  	}
			
			if(!empty($searchresult)) $totalCount = count($searchresult);
			//echo "<pre>";print_r($searchquery);echo "</pre>";die();
			return View::make('group.ajaxresult_group_search_group',array('CurrentUser' => $CurrentUserDetail,'searchresult'=> $searchresult, 'totalResult' => $totalCount, 'searchFor'=> $search,'searchCat'=> $searchCat)); 	  	

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
			return View::make('group.ajaxresult_group_search_Skills',array('CurrentUser' => $CurrentUserDetail,'searchresult'=> $searchresult, 'totalResult' => $totalCount, 'searchFor'=> $search,'searchCat'=> $searchCat)); 	  	

		}
	}

	public function join_leaveGroup(){
		
		$group_id = $_POST['groupId'];
		$action = $_POST['action'];
		$CurrentUser 	= Auth::User();
		$user_id = $CurrentUser->id;
		$UsersgroupCount = Usersgroup::where('user_id','=',$user_id)->count();
		//die($action);
		if($action == 'leave'){   	
			if($UsersgroupCount > 1){
				$UsersgroupCount = Usersgroup::where('user_id','=',$user_id)->where('group_id','=',$group_id)->delete();
				die('success');
			}
			else{
				die("error");
			}
		}
		if($action == 'join')
		{	
			$UsersgroupCount = Usersgroup::where('user_id','=',$user_id)->where('group_id','=',$group_id)->delete();
			$usergroup = new Usersgroup;
			$usergroup->user_id = $user_id;
			$usergroup->group_id = $group_id;
			$usergroup->save();
			$user_id_giver='null';
			$feedType='KarmaGroup';
			KarmaHelper::storeKarmacirclesfeed($user_id_giver,$user_id,$feedType,$group_id);
			die('success');
		}
	}


}

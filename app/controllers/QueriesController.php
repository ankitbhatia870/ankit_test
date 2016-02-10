<?php

class QueriesController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$All_groups=$All_groupset= $group_question = '';
		$CurrentUser = Auth::User();
		$Usergroup 	= Auth::User()->Groups;
		$groupCount = count($Usergroup);
		$allquestion = array();
		/*My questions*/
		$myquestion = Auth::User()->Questions()->orderBy('created_at', 'DESC')->get();
		if(!empty($myquestion)){
			foreach ($myquestion as $key => $value) {
				$value->user_id = User::find($value->user_id); 
				$giver_que_detail = Question::find($value->id)->GiversHelp;
				if (count($giver_que_detail) === 0) {
					$value->giver_Info = "";
				} 
				else
				$value->giver_Info =$giver_que_detail;
				
				
				if(!empty($value->giver_Info)){ 
					foreach ($value->giver_Info as $key => $giver_Info) {
							$giver_Info->user_id = User::find($giver_Info->user_id);
					}
				}		
				$value->skills =  KarmaHelper::getSkillsname($value->skills);
			}
		}
		
		/*Only Group Questions*/
		$groupQue_id = array();
		if(!empty($CurrentUser)){
			$Userquery 	= DB::table('karmacircles_users')->where('user_id',$CurrentUser->id)->get();
			foreach ($Userquery as $key => $value) {
				if(!empty($value->givers)){
					$user_id_giver = explode(',',$value->givers);	
				}else{
					$user_id_giver=array();
				}
				if(!empty($value->takers)){
					$user_id_taker = explode(',',$value->takers);
				}else{
					$user_id_taker=array();
				}
				
				if(!empty($value->givers_takers)){
					$user_id_giver_taker = explode(',',$value->givers_takers);
				}else{
					$user_id_giver_taker=array();
				}
				$user_data = array_merge($user_id_giver,$user_id_taker,$user_id_giver_taker);
				$result_unique_data = array_unique($user_data);
				$result_unique_data=array_filter($result_unique_data);
				$result_unique_data_id = implode(',', $result_unique_data); 
				
			}
		}
			if(!empty($result_unique_data_id)){
				$group_question = DB::select(DB::raw('select questions.id,users.id as userId,questions.question_url,questions.subject,questions.skills,questions.queryStatus,questions.created_at,questions.user_id from questions right join users on questions.user_id = users.id where users.id IN ('.$result_unique_data_id.') And questions.querystatus="open" AND questions.user_id != "'.$CurrentUser->id.'" order by users.karmascore desc, questions.created_at desc'));
				if(!empty($group_question)){ 
					foreach ($group_question as $key => $value) {
						$value->user_id = User::find($value->user_id); 
						$user_id=$value->id;
						$giver_detail = Question::find($value->id)->GiversHelp;
						if (count($giver_detail) === 0) {
							
							$value->giver_Info = "";
						} 
						else
						$value->giver_Info =$giver_detail;
				
						$value->answered = 0;
						if(!empty($value->giver_Info)){ 
							foreach ($value->giver_Info as $key => $giver_Info) {
								if($giver_Info->user_id == $CurrentUser->id ){
									$value->answered = 1;
								}else{
									if($value->answered != '1'){
										$value->answered = 0;
									}							
								}
							$giver_Info->user_id = User::find($giver_Info->user_id);
							}
						}else{
							$value->answered = 0;
						}
						$value->skills =  KarmaHelper::getSkillsname($value->skills);
					}
				}	
			}
		
		
		$allquestion = DB::select(DB::raw('select questions.id,users.id as userId,questions.question_url,questions.subject,questions.skills,questions.queryStatus,questions.created_at,questions.user_id from questions right join users on questions.user_id = users.id where questions.querystatus="open" AND questions.user_id != "'.$CurrentUser->id.'" order by users.karmascore desc, questions.created_at desc'));
		
		
		if(!empty($allquestion)){ 
			foreach ($allquestion as $key => $value) {
				$value->user_id = User::find($value->user_id); 
				$giver_detail = Question::find($value->id)->GiversHelp;
						if (count($giver_detail) === 0) {
							
							$value->giver_Info = "";
						} 
						else
						$value->giver_Info =$giver_detail;
				
				$value->answered = 0;
				if(!empty($value->giver_Info)){ 
					foreach ($value->giver_Info as $key => $giver_Info) {
						if($giver_Info->user_id == $CurrentUser->id ){
							$value->answered = 1;
						}else{
							if($value->answered != '1'){
								$value->answered = 0;
							}							
						}
					$giver_Info->user_id = User::find($giver_Info->user_id);
					}
				}else{
					$value->answered = 0;
				}
				$value->skills =  KarmaHelper::getSkillsname($value->skills);
			}
		}	
		
		
		
		/* echo "<pre>=====";print_r($myquestion);echo "</pre>====";
		echo "<pre>++++++";print_r($group_question);echo "</pre>++++++++++";
		echo "<pre>[[[[[[[";print_r($allquestion);echo "</pre>]]]]]]]]";  */ 
		//die;
		return View::make('KarmaQueries',array('pageTitle' => 'Query | KarmaCircles','CurrentUser' => $CurrentUser,
			'myquestion'=>$myquestion,'group_question'=>$group_question,'allquestion'=>$allquestion));
	} 
  
	public function updateQuestionhelpstatus(){
		//echo "<pre>";print_r($_REQUEST);echo "</pre>";
		$CurrentUser = Auth::User();
		if(!empty($CurrentUser)){
			if(!empty($_REQUEST['quesId']))	$question_id = $_REQUEST['quesId'];
			$giver_id = Auth::user()->id;
			
			if(!empty($question_id) && !empty($giver_id)){
				$searchquery = 	DB::select(DB::raw("SELECT * FROM users_question_willingtohelp Where users_question_willingtohelp.question_id = '".$question_id."' and users_question_willingtohelp.user_id = '".$giver_id."'" ));
				if(empty($searchquery)){
					$Questionwillingtohelp = new Questionwillingtohelp;
					$Questionwillingtohelp->question_id = $question_id;
					$Questionwillingtohelp->user_id = $giver_id;
					if($Questionwillingtohelp->save()){
						$receiverId=DB::table('questions')->where('id','=',$question_id)->select('user_id')->first();
						$receiverId=$receiverId->user_id;
						$getUserData=User::where('id', '=', $receiverId)->first();
						$myKarmaOfferHelp = new Mykarma;
						$myKarmaOfferHelp->entry_id=$question_id;
						$myKarmaOfferHelp->user_id=$giver_id;
						$myKarmaOfferHelp->fname=$getUserData->fname;
						$myKarmaOfferHelp->lname=$getUserData->lname;
						$myKarmaOfferHelp->piclink=$getUserData->piclink;
						$myKarmaOfferHelp->entry_type='Query';
						$myKarmaOfferHelp->users_role='OfferedHelp';
						$myKarmaOfferHelp->status='Open';
						$myKarmaOfferHelp->unread_flag='false';
						$myKarmaOfferHelp->no_of_unread_items='0';
						$myKarmaOfferHelp->entry_updated_on=Carbon::now();
						$myKarmaOfferHelp->save();
						$feedType='OfferHelpTo';
						KarmaHelper::storeKarmacirclesfeed($receiverId,$giver_id,$feedType,$question_id);
						$helper = User::find($giver_id);
						$helper->karmascore = $helper->karmascore + 2;

						if($helper->save()){ 
							return View::make('ajax_helpgiver',array('giver_Info' => $CurrentUser)); 
						}	  	 
					}
				}
				else{
					
					return View::make('ajax_helpgiver',array('giver_Info' => $CurrentUser)); 	 
				}
			}
		}else{
			Redirect::to('login/linkedin');
		}
	}

	public function initiatequery()
	{
		$CurrentUser = Auth::User();
		$Usergroup 	= Auth::User()->Groups;
		$All_groups = '';
		$inkcGroup =  0; 	
		$count = '';
		//
		if(!$Usergroup->isEmpty()){
			foreach ($Usergroup as $key => $value) {
				$All_groups[] = $value->id;
			}	
			//echo "<pre>";print_r($All_groups);echo "</pre>";    
		
			$count = count($All_groups); 
			if(!empty($All_groups)){  
				if($count < 2){    
					$inkcGroup = in_array(1, $All_groups); 
				}
			} 
			$All_groups = implode(',', $All_groups); 
		} 
		return View::make('initiatequery',array('pageTitle' => 'KarmaCircles Query','CurrentUser' => $CurrentUser,'Usergroup'=>$All_groups,'inkcGroup'=>$inkcGroup));
	}
	public function QueryPage($question_url,$id){

		$CurrentUser = Auth::User();
		$question_detail = Question::find($id);
	/*	if(empty($question_detail) || $question_detail->question_url != $question_url){
			
			return Redirect::to('404');
		}else{
			if($question_detail->access == 'private' && !Auth::check()){
				return Redirect::to('404');
			}
			if($question_detail->access != 'public' && Auth::check() && $question_detail->user_id != $CurrentUser->id ){
				$CurrentUser_group = Auth::User()->Groups;
				$QuestionGroup = Question::find($id)->Groupquestion;
				foreach ($CurrentUser_group as $key => $valueUsers) {
					foreach ($QuestionGroup as $key => $valueQuestion) {
						if($valueUsers->id == $valueQuestion->group_id){
							return;
						}				
					}
				}
				return Redirect::to('404');	
			}*/

			if(!empty($question_detail)){				
					$question_detail->user_id = User::find($question_detail->user_id); 
					$question_detail->giver_Info = Question::find($question_detail->id)->GiversHelp;
					//echo '<pre>'; print_r($question_detail);die;
					$question_detail->answered = 0;
					if(!$question_detail->giver_Info->isEmpty()){
					foreach ($question_detail->giver_Info as $key => $giver_Info) {
						if(Auth::check()){
							if($giver_Info->user_id == $CurrentUser->id ){
							$question_detail->answered = 1;
							}else{
								if($question_detail->answered != '1'){
									$question_detail->answered = 0;
								}
							}
						}
						
						$giver_Info->user_id = User::find($giver_Info->user_id);
					}
				}	
					$question_detail->skills =  KarmaHelper::getSkillsname($question_detail->skills);				
			}			
			return View::make('Query_page',array('pageTitle' => 'KarmaCircles Query','CurrentUser' => $CurrentUser,'question'=>$question_detail));
		/*}*/		
	}
	
	/*
	Created at:17 june 2015
	Developer:Ankit Bhatia
	Work:Function to get data of karma query
	*/
	public function getdataByorder() 
	{
		$currentTab = Input::get('currentTab');
		$setting = Input::get('setting');
		$CurrentUser = Auth::User();
		$access = 'public';
		//$Usergroup 	= Auth::User()->Groups;
		//$groupCount = count($Usergroup);
		$All_groups_id=$All_groups=$All_groupset= $group_question = '';
		$groupQue_id = array();

		if(!empty($CurrentUser)){
			$Userquery 	= DB::table('karmacircles_users')->where('user_id',$CurrentUser->id)->get();
			foreach ($Userquery as $key => $value) {
				if(!empty($value->givers)){
					$user_id_giver = explode(',',$value->givers);	
				}else{
					$user_id_giver=array();
				}
				if(!empty($value->takers)){
					$user_id_taker = explode(',',$value->takers);
				}else{
					$user_id_taker=array();
				}
				
				if(!empty($value->givers_takers)){
					$user_id_giver_taker = explode(',',$value->givers_takers);
				}else{
					$user_id_giver_taker=array();
				}
				$user_data = array_merge($user_id_giver,$user_id_taker,$user_id_giver_taker);
				$result_unique_data = array_unique($user_data);
				$result_unique_data=array_filter($result_unique_data);
				$result_unique_data_id = implode(',', $result_unique_data); 
				
			}
		}
		
		if($currentTab == "myquestion"){
			$myquestion = "";
			if($setting == 'Recent'){
				$myquestion = Auth::User()->Questions()->orderBy('created_at', 'DESC')->get();
				if(!empty($myquestion)){
					foreach ($myquestion as $key => $value) {
						$value->user_id = User::find($value->user_id); 
						$value->giver_Info = Question::find($value->id)->GiversHelp;
						if(!$value->giver_Info->isEmpty()){
							foreach ($value->giver_Info as $key => $giver_Info) {
									$giver_Info->user_id = User::find($giver_Info->user_id);
							}
						}		
						$value->skills =  KarmaHelper::getSkillsname($value->skills);
					}
				}
			}elseif($setting == 'Unanswered'){
				$question_willingtohelp = DB::select(DB::raw("select count(question_id) as countQuestion,question_id from users_question_willingtohelp group by question_id"));
				if(!empty($question_willingtohelp)){
					$question_willingtohelp_after_sort = array_values(array_sort($question_willingtohelp, function($value)
						{
							return $value->countQuestion;  
						}));
					$question_value = array_reverse($question_willingtohelp_after_sort);
					foreach ($question_value as $key => $value) {
						$question_id=$value->question_id;
						$question_id_value[]=$question_id;
					}
				}else{
					$question_id_value[]=0;
				}
				$myquestion = Auth::User()->Questions()->where('questions.queryStatus','open')->whereNotIn('questions.id',$question_id_value)->orderBy('created_at', 'DESC')->get();
				if(!empty($myquestion)){
					foreach ($myquestion as $key => $value) {
						$value->user_id = User::find($value->user_id); 
						$value->giver_Info = Question::find($value->id)->GiversHelp;
						if(!$value->giver_Info->isEmpty()){
							foreach ($value->giver_Info as $key => $giver_Info) {
									$giver_Info->user_id = User::find($giver_Info->user_id);
							}
						}		
						$value->skills =  KarmaHelper::getSkillsname($value->skills);
					}
				}
				$myquestion = array_values(array_sort($myquestion, function($value)
				{
				return $value->giver_Count;
				}));				
			}
			elseif($setting == 'Populer'){
				$myquestion = DB::select(DB::raw("SELECT count(users_question_willingtohelp.question_id) as countId,users_question_willingtohelp.question_id,questions.id,questions.queryStatus,questions.question_url,questions.created_at,questions.skills,questions.subject,questions.user_id  FROM questions RIGHT JOIN users_question_willingtohelp ON questions.id=users_question_willingtohelp.question_id where questions.user_id=".$CurrentUser->id." group by users_question_willingtohelp.question_id"));
				if(!empty($myquestion)){
					foreach ($myquestion as $key => $value) {
						$value->user_id = User::find($value->user_id); 
						$value->giver_Info = Question::find($value->id)->GiversHelp;
						if(!$value->giver_Info->isEmpty()){
							foreach ($value->giver_Info as $key => $giver_Info) {
									$giver_Info->user_id = User::find($giver_Info->user_id);
							}
						}		
						$value->skills =  KarmaHelper::getSkillsname($value->skills);
					}
				}
				//echo "<pre>";print_r($myquestion);echo "</pre>";die();
				$myquestion = array_values(array_sort($myquestion, function($value)
					{
					return $value->countId;
					}));
				$myquestion = array_reverse($myquestion);
				//$myquestion[]=new question($myquestion,true);
			}
				
			return View::make('ajax_queryOrderMyquestion',array('CurrentUser' => $CurrentUser,'myquestion'=>$myquestion));
		} 

		elseif($currentTab == "groupquestion"){
			//$group_question = DB::select(DB::raw('select users.id,questions.question_url,questions.subject,questions.skills,questions.queryStatus,questions.created_at,questions.user_id from questions right join users on questions.user_id = users.id where users.id IN ('.$result_unique_data_id.') And questions.querystatus="open" AND questions.user_id != "'.$CurrentUser->id.'" order by users.karmascore desc, questions.created_at desc'));
			
			$group_question = '';
			if($setting == 'Recent')
			{
				//echo '<pre>';print_r($result_unique_data);die;
				$group_question = DB::table('questions')
										->leftJoin('users','questions.user_id', '=', 'users.id')
										->select(array('questions.*'))
										->whereIn('questions.user_id',$result_unique_data)
										->where('questions.querystatus','=','open')
										->where('questions.user_id','!=',$CurrentUser->id)
										->orderBy('questions.created_at','DESC')
										->get();
					
				if(!empty($group_question)){
					foreach ($group_question as $key => $value) {
						
						$value->user_id = User::find($value->user_id); 
						$value->giver_Info = Question::find($value->id)->GiversHelp;
						$value->answered = 0;
						if(!$value->giver_Info->isEmpty()){
							foreach ($value->giver_Info as $key => $giver_Info) {
								if($giver_Info->user_id == $CurrentUser->id ){
									$value->answered = 1;
								}else{
									if($value->answered != '1'){
										$value->answered = 0;
									}
								}
								$giver_Info->user_id = User::find($giver_Info->user_id);
							}
						}
						else{
							$value->answered = 0;
						}					
						$value->skills =  KarmaHelper::getSkillsname($value->skills);
						}
					}
					

				
			}elseif($setting == 'Unanswered'){
				
				$question_willingtohelp = DB::select(DB::raw("select count(question_id) as countQuestion,question_id from users_question_willingtohelp group by question_id"));
				if(!empty($question_willingtohelp)){
					$question_willingtohelp_after_sort = array_values(array_sort($question_willingtohelp, function($value)
					{
						return $value->countQuestion;  
					}));
					$question_value = array_reverse($question_willingtohelp_after_sort);
					foreach ($question_value as $key => $value) {
						$question_id=$value->question_id;
						$question_id_value[]=$question_id;
					}	
				}else{
					$question_id_value[]=0;
				}
				
				$group_question = DB::table('questions')->where('questions.querystatus','=','open')->where('questions.user_id','!=',$CurrentUser->id)->whereIn('questions.user_id',$result_unique_data)->whereNotIn('questions.id',$question_id_value)->orderBy('questions.created_at','DESC')->get();							
				//echo '<pre>';print_r($allquestion);die;
				
				if(!empty($group_question)){
					foreach ($group_question as $key => $value) {
						
						$value->user_id = User::find($value->user_id); 
						$value->giver_Info = Question::find($value->id)->GiversHelp;
						$value->giver_Count = Question::find($value->id)->GiversHelp()->count();
						$value->answered = 0;
						if(!$value->giver_Info->isEmpty()){
							foreach ($value->giver_Info as $key => $giver_Info) {
								if($giver_Info->user_id == $CurrentUser->id ){
									$value->answered = 1;
								}else{
									if($value->answered != '1'){
										$value->answered = 0;
									}
								}
								$giver_Info->user_id = User::find($giver_Info->user_id);
							}
						}
						else{
							$value->answered = 0;
						}					
						$value->skills =  KarmaHelper::getSkillsname($value->skills);
						}
					}
					$group_question = array_values(array_sort($group_question, function($value)
					{
						return $value->giver_Count;
					}));
						
			}
			elseif($setting == 'Populer'){
				$question_willingtohelp = DB::select(DB::raw("select count(question_id) as countQuestion,question_id from users_question_willingtohelp group by question_id"));
				if(!empty($question_willingtohelp)){
					$question_willingtohelp_after_sort = array_values(array_sort($question_willingtohelp, function($value)
					{
						return $value->countQuestion;  
					}));
					$question_value = array_reverse($question_willingtohelp_after_sort);
					foreach ($question_value as $key => $value) {
						$question_id=$value->question_id;
						$question_id_value[]=$question_id;
					}	
				}else{
					$question_id_value[]=0;
				}
				
				$group_question = DB::table('questions')->where('questions.querystatus','=','open')->where('questions.user_id','!=',$CurrentUser->id)->whereIn('questions.user_id',$result_unique_data)->whereIn('questions.id',$question_id_value)->orderBy('questions.created_at','DESC')->get();							
				     
				if(!empty($group_question)){
					foreach ($group_question as $key => $value) {
						
						$value->user_id = User::find($value->user_id); 
						$value->giver_Info = Question::find($value->id)->GiversHelp;
						$value->giver_Count = Question::find($value->id)->GiversHelp()->count();
						$value->answered = 0;
						if(!$value->giver_Info->isEmpty()){
							foreach ($value->giver_Info as $key => $giver_Info) {
								if($giver_Info->user_id == $CurrentUser->id ){
									$value->answered = 1;
								}else{
									if($value->answered != '1'){
										$value->answered = 0;
									}
								}
								$giver_Info->user_id = User::find($giver_Info->user_id);
							}
						}
						else{
							$value->answered = 0;
						}					
						$value->skills =  KarmaHelper::getSkillsname($value->skills);
						}
					}
					$group_question = array_values(array_sort($group_question, function($value)
					{
					return $value->giver_Count;
					}));
					$group_question = array_reverse($group_question);
				

			}  
			//echo "<pre>";print_r($group_question);echo "</pre>";die();
			return View::make('ajax_queryOrder',array('CurrentUser' => $CurrentUser,'questionType'=>$group_question));
		}
		elseif($currentTab == "allQuestion"){ 
			
			$allquestion = '';
			if($setting == 'Recent'){
				$allquestion = DB::table('questions')
										->leftJoin('users','questions.user_id', '=', 'users.id')
										->select(array('questions.*'))
										->where('questions.querystatus','=','open')
										->where('questions.user_id','!=',$CurrentUser->id)
										->orderBy('questions.created_at','DESC')
										->get();
				

				if(!empty($allquestion)){ 
					foreach ($allquestion as $key => $value) {
						$value->user_id = User::find($value->user_id); 
						$value->giver_Info = Question::find($value->id)->GiversHelp;
						$value->answered = 0;
						if(!$value->giver_Info->isEmpty()){
							foreach ($value->giver_Info as $key => $giver_Info) {
								if($giver_Info->user_id == $CurrentUser->id ){
									$value->answered = 1;
								}else{
									if($value->answered != '1'){
										$value->answered = 0;
									}							
								}
							$giver_Info->user_id = User::find($giver_Info->user_id);
							}
						}else{
							$value->answered = 0;
						}
						$value->skills =  KarmaHelper::getSkillsname($value->skills);
					}
				}		
			}elseif($setting == 'Unanswered'){
				$question_willingtohelp = DB::select(DB::raw("select count(question_id) as countQuestion,question_id from users_question_willingtohelp group by question_id"));
				if(!empty($question_willingtohelp)){
					$question_willingtohelp_after_sort = array_values(array_sort($question_willingtohelp, function($value)
					{
						return $value->countQuestion;  
					}));
					$question_value = array_reverse($question_willingtohelp_after_sort);
					foreach ($question_value as $key => $value) {
						$question_id=$value->question_id;
						$question_id_value[]=$question_id;
					}	
				}else{
					$question_id_value[]=0;
				}
				
				$allquestion = DB::table('questions')->where('questions.querystatus','=','open')->where('questions.user_id','!=',$CurrentUser->id)->whereNotIn('questions.id',$question_id_value)->orderBy('questions.created_at','DESC')->get();							
				//echo '<pre>';print_r($allquestion);die;
				
			if(!empty($allquestion)){ 
					foreach ($allquestion as $key => $value) {
						$value->user_id = User::find($value->user_id); 
						$value->giver_Info = Question::find($value->id)->GiversHelp;
						$value->giver_Count = Question::find($value->id)->GiversHelp()->count();
						$value->answered = 0;
						if(!$value->giver_Info->isEmpty()){
							foreach ($value->giver_Info as $key => $giver_Info) {
								if($giver_Info->user_id == $CurrentUser->id ){
									$value->answered = 1;
								}else{
									if($value->answered != '1'){
										$value->answered = 0;
									}							
								}
							$giver_Info->user_id = User::find($giver_Info->user_id);
							}
						}else{
							$value->answered = 0;
						}
						$value->skills =  KarmaHelper::getSkillsname($value->skills);
					}
					
				}			
			}
			elseif($setting == 'Populer'){

				$allquestion = DB::select(DB::raw("SELECT count(users_question_willingtohelp.question_id) as countId,users_question_willingtohelp.question_id,questions.id,questions.queryStatus,questions.question_url,questions.created_at,questions.skills,questions.subject,questions.user_id  FROM questions RIGHT JOIN users_question_willingtohelp ON questions.id=users_question_willingtohelp.question_id where questions.queryStatus='open' And questions.user_id !=".$CurrentUser->id." group by users_question_willingtohelp.question_id"));
				if(!empty($allquestion)){ 
					foreach ($allquestion as $key => $value) {
						$value->user_id = User::find($value->user_id); 
						$value->giver_Info = Question::find($value->id)->GiversHelp;
						$value->giver_Count = Question::find($value->id)->GiversHelp()->count();
						$value->answered = 0;
						if(!$value->giver_Info->isEmpty()){
							foreach ($value->giver_Info as $key => $giver_Info) {
								if($giver_Info->user_id == $CurrentUser->id ){
									$value->answered = 1;
								}else{
									if($value->answered != '1'){
										$value->answered = 0;
									}							
								}
							$giver_Info->user_id = User::find($giver_Info->user_id);
							}
						}else{
							$value->answered = 0;
						}
						$value->skills =  KarmaHelper::getSkillsname($value->skills);
					}
					$allquestion = array_values(array_sort($allquestion, function($value)
						{
							return $value->giver_Count;
						}));
					$allquestion = array_reverse($allquestion);
				}
			}
				//echo "<pre>";print_r($allquestion);echo "</pre>";die();
				return View::make('ajax_queryOrder',array('CurrentUser' => $CurrentUser,'questionType'=>$allquestion));
		}

	}
	public function submitforhelp(){
		if(!empty(Input::get('question_id')))	$question_id = Input::get('question_id');
		$giver_id = Auth::user()->id;
		if(!empty($question_id) && !empty($giver_id)){
			$Questionwillingtohelp = new Questionwillingtohelp;
			$Questionwillingtohelp->question_id = $question_id;
			$Questionwillingtohelp->user_id = $giver_id;
			if($Questionwillingtohelp->save()){
				$helper = User::find($giver_id); 
				$helper->karmascore = $helper->karmascore + 2;
				if($helper->save()){ 
					return Redirect::to('/karma-queries');
				} 
			}
		}
		
	}
	public function closeQuestion(){
		if(!empty(Input::get('question_id')))	$question_id = Input::get('question_id');
		if(!empty($question_id)){
			$Question = Question::find($question_id);
			$Question->queryStatus = 'closed';
			$Question->save();
			$usersFeedDelete = Karmafeed::where('id_type','=',$question_id)->delete();
			return Redirect::to('/karma-queries');
		}
	}
	public function submitQueryForm(){
		
		$shareOnLinkedin = '';
		if(!empty(Input::get('shareOnLinkedin')))	$shareOnLinkedin = Input::get('shareOnLinkedin');
		if(!empty(Input::get('subject')))	$subject = Input::get('subject');
		if(!empty(Input::get('description'))) $description = Input::get('description');
		if(!empty(Input::get('skillTags'))) $skills = Input::get('skillTags');
		if(!empty(Input::get('privacysetting')))	$privacysetting = Input::get('privacysetting');
		if(!empty(Input::get('receiver_id'))) $receiver_id = Input::get('receiver_id');
		if(!empty(Input::get('user_groups'))) $user_groups = Input::get('user_groups');
		$url_subject = $subject;
		$url_subject = strtolower($url_subject);
	    //Make alphanumeric (removes all other characters)
		$url_subject = preg_replace("/[^a-z0-9_\s-]/", "", $url_subject);
		$url_subject = trim($url_subject);
	    //Clean up multiple dashes or whitespaces
		$url_subject = preg_replace("/[\s-]+/", " ", $url_subject);
	    //Convert whitespaces and underscore to dash
		$url_subject = preg_replace("/[\s_]/", "-", $url_subject);		
		if(!empty($receiver_id)){
			$Question = new Question;
			$Question ->user_id 				= $receiver_id;
			$Question ->subject 				= strip_tags($subject);
			$Question ->description 			= strip_tags($description);
			if(!empty($skills)){
					$Question ->skills 						= implode(',', $skills);
				}
				else{
					$Question ->skills 						= '';
				}	
			$Question ->access 					= $privacysetting;
			$Question ->question_url 			= strtolower($url_subject);
			$Question->save();
			$questionId=$Question->id;
			$getUser=User::where('id', '=', $receiver_id)->first();
			//Add data on users_mykarma table for query
			$myKarmaDataQuery = new Mykarma;
			$myKarmaDataQuery->entry_id=$questionId;
			$myKarmaDataQuery->user_id=$receiver_id;
			$myKarmaDataQuery->fname=$getUser->fname;
			$myKarmaDataQuery->lname=$getUser->lname;
			$myKarmaDataQuery->piclink=$getUser->piclink;
			$myKarmaDataQuery->entry_type='Query';
			$myKarmaDataQuery->users_role='PostedQuery';
			$myKarmaDataQuery->status='Open';
			$myKarmaDataQuery->unread_flag='false';
			$myKarmaDataQuery->no_of_unread_items='0';
			$myKarmaDataQuery->entry_updated_on=Carbon::now();
			$myKarmaDataQuery->save();
			$user_id_giver='null';
			$feedType='KarmaQuery';
			KarmaHelper::storeKarmacirclesfeed($user_id_giver,$receiver_id,$feedType,$questionId);	
			if(!empty($user_groups)){
				$user_groups =  explode(',', $user_groups);
				foreach ($user_groups as $key => $value) {
					$Groupquestion = new Groupquestion;
					$Groupquestion->question_id = $Question->id;
					$Groupquestion->group_id = $value;
					$Groupquestion->user_id = $Question->user_id;
					$Groupquestion->save();
				}
			}
			//echo "<pre>";print_r($_POST);echo "</pre>";die();  
			if($shareOnLinkedin == '1' && $privacysetting =='public'){

				Queue::push('MessageSender@shareQuestionOnLinkedin', array('type' =>'9','question_id'=>$Question->id));
			}
			return Redirect::to('/karma-queries');
		}
	}
}

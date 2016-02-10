<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class MyKarmaDailyUpdate extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'mykarma:dailyupdate';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Command for send daily MyKarma snapshot and update of MyKarma on email';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	public function fire()
	{ 	  
		//fetch all users
		$getusers = DB::table('users')
						->select(array('users.*'))
						->where('users.userstatus','=','approved')   
						//->whereIn('users.id',array(530))      
						->orderBy('created_at','DESC')   
						->get();        
				
		//process for the records found
		if(!empty($getusers)){
			$queryOfferHelp=array();
			foreach ($getusers as $user_info) { 

				$diffDate = KarmaHelper::dateDiff(date("Y-m-d H:i:s"),$user_info->created_at);
				if($diffDate->days > -1){
				$user_id = $user_info->id;
				$location = $user_info->location;
				/*$myKarmaDataOfInCompleteState= DB::select(DB::raw("SELECT COUNT( * ) AS AGGREGATE FROM  `users_mykarma` WHERE  `status` =  'completed' AND  `user_id` =".$user_id." AND  created_at > DATE_SUB(NOW(), INTERVAL 1 DAY)"));
				$myKarmaDataOfInCompleteState= DB::select(DB::raw("SELECT COUNT( * ) AS AGGREGATE FROM  `users_mykarma` WHERE  `status` !=  'completed' AND  `user_id` =".$user_id." AND  created_at > DATE_SUB(NOW(), INTERVAL 1 DAY)"));*/
				$myKarmaDataOfCompleteState = Mykarma::where('status','=','completed')->where('user_id','=',$user_id)->where('unread_flag','=','true')->where('created_at', '>=', Carbon::now()->subDay(1))->count();	
				$myKarmaDataOfInCompleteState = Mykarma::where('status','!=','completed')->where('user_id','=',$user_id)->where('entry_type','=','Meeting')->where('unread_flag','=','true')->where('created_at', '>=', Carbon::now()->subDay(1))->count();
				$myKarmaDataOfQuery = Mykarma::where('user_id','=',$user_id)->where('entry_type','=','Query')->get();	
				foreach ($myKarmaDataOfQuery as $key => $value) {
						$allQuery[] = $value->entry_id;
				}
				if(!empty($allQuery)){
					$queryOfferHelp=Mykarma::whereIn('entry_id',$allQuery)->where('users_role','=','OfferedHelp')->where('unread_flag','=','true')->where('created_at', '>=', Carbon::now()->subDay(1))->count();	
				}
				
				// fetch user connections on KC
				$getUserConnection = KarmaHelper::getUserConnection($user_id,$location);
				$user_connection_onkc = 0; 
				if(!empty($getUserConnection)){
					foreach ($getUserConnection as $key => $value) {
						if(isset($value->con_user_id)) $user_connection_onkc++;
					}

				} 
				
				// fetch pending karmanote requests
				$totalPendingRequest =0;
				$PendingRequest = array();
				$PendingRequest = KarmaHelper::getPendingKarmaNotes($user_info->id);
				if(!empty($PendingRequest)) 
				$totalPendingRequest = count($PendingRequest);
				
				//fetch pending KM requests only received no read no unread
				$totalReceivedRequest = 0;				
				$GiverInMeeting = User::find($user_info->id)->Giver()->where('status', 'pending')->orderBy('updated_at', 'DESC')->get();
				if(!empty($GiverInMeeting))  
				$totalReceivedRequest = count($GiverInMeeting);

				//fetch pending karma intros 
				$totalintroductionInitiated=0;
				$IntroducerInitiated = User::find($user_info->id)->Introducer;
				if(!empty($IntroducerInitiated)) { 
					foreach ($IntroducerInitiated as $key => $value) {
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
				$Usergroup 	= User::find($user_info->id)->Groups;
				$All_groups = '';
				$group_question = 0;
				$totagroupquestion=0;
				$yesterday = Carbon::now()->subDays(1);
				$one_week_ago = Carbon::now()->subWeeks(1);
				if(!$Usergroup->isEmpty()){ 
					foreach ($Usergroup as $key => $value) {
						$All_groups[] = $value->id;
					}	
					if(!empty($All_groups)){ 
						
						$group_question = DB::table('group_questions')
									->join('questions', 'group_questions.question_id', '=', 'questions.id')
									->select(array('questions.id'))
						            ->whereIn('group_questions.group_id',$All_groups)
						            ->where('questions.user_id','!=',$user_info->id)
						            ->where('questions.queryStatus','=','open')
						            ->where('questions.created_at', '>=', $one_week_ago)
			       					->where('questions.created_at', '<=', $yesterday)
						            ->orderBy('questions.created_at','DESC')
						            ->groupBy('question_id')
						            ->get();
						if(!empty($group_question)){
							$totagroupquestion = count($group_question); 
						}
					}
							
				} 
				// fetch weekly suggestion option value set from admin
				$weekly_suggestion = "KarmaNote";
				$weekly_suggestion =Adminoption::Where("option_name","=","Weekly Suggestion")->select("option_value")->first();
				if(!empty($weekly_suggestion)) 
				$weekly_suggestion = $weekly_suggestion->option_value; 
				 
				$getkcUser = $getsuggestion = array();
				if($weekly_suggestion == "KarmaMeeting")
				{
					// fetch a random users on KC platform with a common group of logged in user
					
					$getkcUser = KarmaHelper::fetchUserGroup($user_id);
					if(!empty($getkcUser))	 $getkcUser= $getkcUser[0]; 
					
				}
				else
				{

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
					
				}
				
				//fetch random 5 unique notes
				$getKarmanote="";
				$getKarmanote = KarmaHelper::getKarmanote(); 
					$type=19;
					$meetingIncomplete=$myKarmaDataOfInCompleteState;
					$meetingComplete=$myKarmaDataOfCompleteState;
					$offeredHelp=$queryOfferHelp;
					$user_id = $user_id;
					$user_connection_onkc=$user_connection_onkc;
					$totalPendingRequest = $totalPendingRequest;
					$totalReceivedRequest=$totalReceivedRequest;
					$totalintroductionInitiated=$totalintroductionInitiated;
					$totagroupquestion=$totagroupquestion;
					$getsuggestion=$getsuggestion;
					$getkcUser=$getkcUser;
					$getKarmanote=$getKarmanote;
				
				// call a function to send email
				if($meetingIncomplete > 0 || $meetingComplete > 0 || $offeredHelp > 0){
					$sendLinkedinMessage        =  MessageHelper::dailyUpdateMykarmaScreen($type,$user_id,$meetingIncomplete,$meetingComplete,$offeredHelp,$user_connection_onkc, $totalPendingRequest,$totalReceivedRequest,$totalintroductionInitiated,$totagroupquestion,$getsuggestion,$getkcUser,$getKarmanote);
				}
				
				/*Queue::push('MessageSender@MyKarmaDailyUpdateScreen',array('type' =>19,'meetingIncomplete'=>$myKarmaDataOfInCompleteState,'meetingComplete'=>$myKarmaDataOfCompleteState,'offeredHelp'=>$queryOfferHelp,'user_id' => $user_id,'user_connection_onkc'=>$user_connection_onkc,'totalPendingRequest' => $totalPendingRequest,'totalReceivedRequest'=>$totalReceivedRequest,'totalintroductionInitiated'=>$totalintroductionInitiated,'totagroupquestion'=>$totagroupquestion,'getsuggestion'=>$getsuggestion,'getkcUser'=>$getkcUser,'getKarmanote'=>$getKarmanote));*/
			
			}} //endforeach
		}// endif
		  
		Log::alert('Executed Daily Cron Job');  
	}

	
	protected function getArguments()
	{
		return array(
			array('example', InputArgument::OPTIONAL, 'An example argument.'),
		);
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
			array('example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
		);
	}

}

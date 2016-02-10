<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class WeeklyNoteRequestEmail extends Command { 

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'karma:email_pending_note_request'; 

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Command for KarmaNote and Karma Meeting pending Email Trigger';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct() 
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{	  
		Log::alert('Executed Cron Job to send weekly mail for pending karma note and meeting requests.');
		$KarmaNotePending = "";
		$KarmaNotePending = Meetingrequest::whereIn('status',array('pending','accepted'))->get();
		if(!empty($KarmaNotePending)){
			$test_id ="";
			$test_id = Adminoption::Where('option_name','=','Test User Emails')->first();
			$test_user_id = $getfintrorecords= array();
			if(!empty($test_id)){ 
				$test_user_id = explode(',',$test_id->option_value);
			}   
			$introducer = $giver = $receiver = $connidgiver='';
			foreach ($KarmaNotePending as $key => $value) {
				$diffDate = KarmaHelper::dateDiff(date("Y-m-d H:i:s"),$value->created_at);
				if(isset($value->user_id_giver))
				$giver = in_array($value->user_id_giver,$test_user_id);
				if(isset($value->user_id_receiver))
				$receiver = in_array($value->user_id_receiver,$test_user_id);
				if(isset($value->connection_id_giver)) 
				$connidgiver = in_array($value->connection_id_giver,$test_user_id);
				if(isset($value->user_id_introducer)) 
				$introducer = in_array($value->user_id_introducer,$test_user_id);
				if($giver != 1 && $receiver != 1 && $connidgiver != 1 && $introducer != 1 ){
					if($diffDate->days > 7){
						$meetingtimezone 	 = $value->meetingtimezone; 
						$meetingdatetime 	 = $value->meetingdatetime; 
						$user_id_giver   	 = $value->user_id_giver; 
						$user_id_receiver    = $value->user_id_receiver; 
						$meetingId   		 = $value->id; 
						$status   			 = $value->status;  
						$CurrentTimeWithZone = KarmaHelper::calculateTime($meetingtimezone);
						if($CurrentTimeWithZone > $meetingdatetime && ($status == 'accepted') ){      
							Queue::push('MessageSender', array('type' =>'4','user_id_giver' => $user_id_giver,'user_id_receiver' => $user_id_receiver,'meetingId'=> $meetingId));
						}   
						if($status == 'pending' ){ 
							//echo"<pre>";print_r($value->id);echo"</pre>";   
							if(isset($value->user_id_giver))
								Queue::push('MessageSender', array('type' =>'1','user_id_giver' => $user_id_giver,'user_id_receiver' => $user_id_receiver,'meetingId'=> $meetingId));
							else
								Queue::push('MessageSender', array('type' =>'2','user_id_giver' => $value->connection_id_giver,'user_id_receiver' => $user_id_receiver,'meetingId'=> $meetingId));
						}
					}
				}
			} 
		}
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
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

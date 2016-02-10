<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class KarmaNoteEmailTrigger extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'karmanote:emailtrigger';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Command for KarmaNote Email Trigger';

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
		Log::alert('Executed Cron Job');
		$KarmaNotePending = Meetingrequest::where('status','=','confirmed')->where('cronjobflag','=','0')->get();
		
		if(!empty($KarmaNotePending)){
			foreach ($KarmaNotePending as $key => $value) {
				$meetingtimezone 	 = $value->meetingtimezone; 
				$meetingdatetime 	 = $value->meetingdatetime; 
				$user_id_giver   	 = $value->user_id_giver; 
				$user_id_receiver    = $value->user_id_receiver; 
				$meetingId   		 = $value->id; 
				$CurrentTimeWithZone = KarmaHelper::calculateTime($meetingtimezone);
				//echo "<pre>";print_r($meetingdatetime);echo "</pre>";
				//echo "<pre>";print_r($meetingdatetime);echo "</pre>";die();
				if($CurrentTimeWithZone > $meetingdatetime){
					$diffDate = KarmaHelper::dateDiff($CurrentTimeWithZone,$meetingdatetime);
					$diffDate = $diffDate->days * 24 + $diffDate->h; 
					
					$EmailTriggerTime = Adminoption::where('option_name','=','KarmaNote Email Trigger Time')->first();
					if(!empty($EmailTriggerTime)){
						$EmailTriggerTime = $EmailTriggerTime->toArray();
						$EmailTriggerTime = $EmailTriggerTime['option_value'];
					}
					else{
						$EmailTriggerTime = '24';
					}
					if($diffDate >= $EmailTriggerTime){
						//$date = Carbon::now()->addMinutes(5);
						Queue::push('MessageSender', array('type' =>'4','user_id_giver' => $user_id_giver,'user_id_receiver' => $user_id_receiver,'meetingId'=> $meetingId));
						$Meetingrequest = Meetingrequest::find($meetingId);
						$Meetingrequest->cronjobflag = '1';
						$Meetingrequest->status = 'over';
						$Meetingrequest->save();
						DB::table('users_mykarma')->where('entry_id','=',$meetingId)->update(array('status' => 'over','unread_flag' => 'true','entry_updated_on' => Carbon::now()));
	                   	$messageData=new Message;
		                $messageData->request_id=$meetingId;
		                $messageData->sender_id=$user_id_giver;
		                $messageData->giver_id=$user_id_giver;
		                $messageData->receiver_id=$user_id_receiver;
		                $messageData->message_type='system';
		                $messageText='Meeting should be over now.';
		                $messageData->messageText=$messageText;
		                $messageData->save();
						//Queue::push('UpdateUser', array('id' => $user_id,'result' => $result));
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

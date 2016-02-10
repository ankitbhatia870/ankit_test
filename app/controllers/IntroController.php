<?php

class IntroController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$CurrentUser = Auth::User(); 
		//echo "<pre>";print_r($CurrentUser);echo "</pre>";
		//$IntroducerInitiated = Auth::User()->Introducer;
		$IntroducerInitiated = DB::select(DB::raw("select * from requests where requests.user_id_introducer= ".$CurrentUser->id." order by requests.updated_at DESC"));	

		//echo "<pre>";print_r($IntroducerInitiated->toArray());echo "</pre>";
		//echo "<pre>";print_r($IntroducerInitiated1);echo "</pre>";
		foreach ($IntroducerInitiated as $key => $value) {

			$value->user_id_receiver = User::find($value->user_id_receiver);
			if(!empty($value->user_id_giver)){ 
				$value->user_id_giver = User::find($value->user_id_giver);
			}
			else{
				$value->user_id_giver = Connection::find($value->connection_id_giver);
			}
		} 
	//echo "<pre>";print_r($IntroducerInitiated);echo "</pre>";die();
	$totalintroductionInitiated = count($IntroducerInitiated);
		return View::make('karmaIntro',array('pageTitle' =>'Intro | KarmaCircles','CurrentUser' => $CurrentUser,'IntroducerInitiated'=>$IntroducerInitiated,'totalintroductionInitiated'=>$totalintroductionInitiated,'countIntro'=>'0'));
	}
	
	public function initiatekarmaIntro()	{
		$CurrentUser = Auth::User();
		return View::make('initiatekarmaIntro',array('CurrentUser' => $CurrentUser));
	}
	public function submitIntroform(){
		$payitforward = $sendKarmaNote = $buyyoucoffee = '0'; 
		$receiverWR = Input::get('receiverWR');	
		if(!empty($receiverWR)){
			foreach ($receiverWR as $key => $value) {
				if($value == "I'd pay it forward"){
					$payitforward = '1';
				}
				elseif($value == "I'd send you a Karma Note"){
					$sendKarmaNote = '1';
				}
			}		
		}
		$giver_email 				= Input::get('giver_email');

		$user_id_receiver 			= Input::get('receiver_id');
		$connection_id_receiver 	= Input::get('receiver_conn_id');
		if(!empty(Input::get('giver_id') && Input::get('giver_id') != 'undefined' )) echo $user_id_giver  			= Input::get('giver_id');		
		$connection_id_giver  		= Input::get('giver_conn_id');
		$user_id_introducer 		= Input::get('user_id_Intro');
		$detail  					= Input::get('note');
		if(!empty($user_id_giver)){
			$Meetingrequest = new Meetingrequest;
			$Meetingrequest ->user_id_introducer 				= $user_id_introducer;
			$Meetingrequest ->user_id_receiver 					= $user_id_receiver;
			$Meetingrequest ->user_id_giver 					= $user_id_giver;
			$Meetingrequest ->connection_id_giver 				= $connection_id_giver;
			$Meetingrequest ->notes 							= strip_tags($detail);
			$Meetingrequest ->payitforward 						= $payitforward ;
			$Meetingrequest ->sendKarmaNote 					= $sendKarmaNote ;
			$Meetingrequest ->buyyoucoffee 						= $buyyoucoffee ;
			$Meetingrequest ->status 							= 'pending';			
			$Meetingrequest ->req_createdate					= KarmaHelper::currentDate();
			$Meetingrequest->save();	
			$meetingId = $Meetingrequest->id;
			Queue::push('MessageSender', array('type' =>'12','user_id_giver' => $user_id_giver,'user_id_receiver' => $user_id_receiver,'meetingId'=> $meetingId));
			//$sendLinkedinMessage =  MessageHelper::triggerEmailAndMessage($user_id_giver,$user_id_receiver,'12',$meetingId);
			//echo "<pre>";print_r($sendLinkedinMessage);echo "</pre>";die();
			return Redirect::to('/karma-intro');
		}
		else{
			
			$Meetingrequest = new Meetingrequest;
			$Meetingrequest ->user_id_introducer 				= $user_id_introducer;
			$Meetingrequest ->user_id_receiver 					= $user_id_receiver;
			$Meetingrequest ->connection_id_giver 				= $connection_id_giver;
			$Meetingrequest ->notes 							= strip_tags($detail);
			$Meetingrequest ->payitforward 						= $payitforward ;
			$Meetingrequest ->sendKarmaNote 					= $sendKarmaNote ;
			$Meetingrequest ->buyyoucoffee 						= $buyyoucoffee ;
			$Meetingrequest ->status 							= 'pending';			
			$Meetingrequest ->req_createdate					= KarmaHelper::currentDate();
			$Meetingrequest->save();	
			$meetingId = $Meetingrequest->id;
			
			 
			if($giver_email != "")
			{ 				
				Queue::push('MessageSender@IntroEmailToNonKarmaGiver', array('type' =>'15','user_id_giver' => $connection_id_giver,'user_id_receiver' => $user_id_receiver,'giver_email'=> $giver_email,'meetingId'=>$meetingId));					
			}
			Queue::push('MessageSender', array('type' =>'13','user_id_giver' => $connection_id_giver,'user_id_receiver' => $user_id_receiver,'meetingId'=> $meetingId));
			return Redirect::to('/karma-intro');
			/*For Sending Intro Email to Non Karma User if email id is given*/
		   


		}
		

	}


}

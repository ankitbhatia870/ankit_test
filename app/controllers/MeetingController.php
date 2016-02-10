
<?php

class MeetingController extends \BaseController {

	/**
	 * Display a listing of the resources.
	 *
	 * @return Response
	 */
	public function __construct(Meetingrequest $Meetingrequest){
		$this->Meetingrequest = $Meetingrequest;
	}
	public function index(){
	$CurrentUser = Auth::User();
	$totalReceivedRequest = $totalSentRequest = $totalArchivedRequest = 0;
	$ReceiverInMeeting = Auth::User()->Receiver()->orderBy('updated_at', 'DESC')->get();
	$GiverInMeeting = Auth::User()->Giver()->orderBy('updated_at', 'DESC')->get();
	foreach ($ReceiverInMeeting as $key => $value) {
		$value['user_id_receiver'] = User::find($value['user_id_receiver'])->toArray();
		if(!empty($value['user_id_giver'])){
			$value['user_id_giver'] = User::find($value['user_id_giver']);
			if(!empty($value['user_id_giver'])){
				$value['user_id_giver']=$value['user_id_giver']->toArray();
			}
		}
		else{
			$value['user_id_giver'] = Connection::find($value['connection_id_giver']);
			if(!empty($value['user_id_giver'])){
				$value['user_id_giver']=$value['user_id_giver']->toArray();
			}
			
		}
	}
	$totalReceivedRequest = count($ReceiverInMeeting);
	foreach ($GiverInMeeting as $key => $value) {
		$value['user_id_giver'] = User::find($value['user_id_giver'])->toArray();
		$value['user_id_receiver'] = User::find($value['user_id_receiver'])->toArray();
	}
	//echo "<pre>===";print_r($ReceiverInMeeting);echo "===</pre>";die;
	return View::make('karmaMeetings',array('pageTitle' => 'Meeting | KarmaCircles','key'=>'','CurrentUser' => $CurrentUser,'GiverInMeeting'=>$GiverInMeeting,
		'ReceiverInMeeting'=>$ReceiverInMeeting,'countRec'=>'0','countArc'=>'0','countSent'=>'0', 'totalReceivedRequest'=>$totalReceivedRequest));
	}

	public function CreateMeeting($Giver){
		$CurrentUser = Auth::User();
		if(!empty($CurrentUser)){
			$Receiver=$CurrentUser->id;
		}
		$ReceiverDetail=User::find($Receiver);
		$GiverDetail = User::find($Giver);
		$Connection_id_giver = DB::table('connections')->where('networkid', $GiverDetail->linkedinid)->pluck('id');
		
		if($CurrentUser->id == $GiverDetail->id){
			return Redirect::to('404');
		}
		$MeetingRequestPending = $GiverDetail->Giver()->Where('status','=','pending')->count();
		//$MeetingRequestPending = KarmaHelper::karmaMeetingPendingCount($Receiver,$Giver);
		return View::make('send_meeting_request',array('pageTitle' => 'KarmaCircles Meeting','CurrentUser' => $CurrentUser,'GiverDetail'=>$GiverDetail,
			'MeetingRequestPending'=>$MeetingRequestPending,'Connection_id_giver'=>$Connection_id_giver));
	}

	public function CreateMeetingNonKarma($Giver){
		$CurrentUser = Auth::User();
		$checkMsgLimit = KarmaHelper::CheckUserLinkedMgsLimit(); 
		
		$ConnectionDetail = Auth::User()->connections()->where('connections.id','=',$Giver)->get()->first();
		/*echo "<pre>";print_r($ConnectionDetail->toArray());echo "</pre>";*/
		if(empty($ConnectionDetail)){
		return Redirect::to('404');
		}
		return View::make('send_meeting_request_Nokarma',array('pageTitle' => 'KarmaCircles Meeting','checkMsgLimit' => $checkMsgLimit,'CurrentUser' => $CurrentUser,'ConnectionDetail' => $ConnectionDetail));
	
		
	}

	public function SendMeetingRequest(){
		if (!$this->Meetingrequest->isValid(Input::all())) {		
		// redirect our user back to the form with the errors from the validator
		return Redirect::back()->withInput()->withErrors($this->Meetingrequest->errors);

		} 
		else {
			$user_id_giver = Input::get('user_id_giver');
			$user_id_receiver = Input::get('user_id_receiver');
			$weekday_call = Input::get('weekday_call');
			$weekday_call_time = Input::get('weekday_call_time');
			$payitforward = $sendKarmaNote = $buyyoucoffee = '0'; 
			$getUser=User::where('id', '=', $user_id_receiver)->first();
			if(!empty($user_id_giver)){
				$checkMeetingStatus=KarmaHelper::getMeetingStatusForWeb($user_id_receiver,$user_id_giver);
				$receiverWR = Input::get('receiverWR');	
				if(!empty($receiverWR)){
					foreach ($receiverWR as $key => $value) {
						if($value == "I'd pay it forward"){
							$payitforward = '1';
						}
						elseif($value == "I'd send you a Karma Note"){
							$sendKarmaNote = '1';
						}
						elseif($value == "I'd buy you coffee (in-person meetings only)"){
							$buyyoucoffee = '0';
						}
					}	
				}	
				$userId=Input::get('user_id_receiver');
				$giverId=Input::get('user_id_giver');
				$Meetingrequest = new Meetingrequest;
				$Meetingrequest ->user_id_receiver 				= Input::get('user_id_receiver');
				$Meetingrequest ->user_id_giver 				= Input::get('user_id_giver');
				$Meetingrequest ->payitforward 					= $payitforward ;
				$Meetingrequest ->sendKarmaNote 				= $sendKarmaNote ;
				$Meetingrequest ->buyyoucoffee 					= $buyyoucoffee ;
				$Meetingrequest ->weekday_call_time 			= $weekday_call_time ;
				$Meetingrequest ->weekday_call 					= $weekday_call ;
				$Meetingrequest ->notes 						= strip_tags(Input::get('notes'));
				$Meetingrequest ->status 						= 'pending';
				$Meetingrequest ->connection_id_giver 			= Input::get('connection_id_giver');
				$Meetingrequest ->req_createdate 				= KarmaHelper::currentDate();
				$Meetingrequest->save();
				$meetingId = $Meetingrequest->id;
				$getIntroData=KarmaIntro::where('intro_giver_id','=',$giverId)->where('intro_receiver_id','=',$userId)->first();
                  if(!empty($getIntroData)){
                    	if($getIntroData->request_id =='' || $getIntroData->request_id =='0'){
                    		$messageDataSecond = new Message;
	                        $messageDataSecond->message_type='system';
	                        $messageDataSecond->request_id=$meetingId;
	                        $messageDataSecond->sender_id=$getIntroData->intro_introducer_id;
	                        $messageDataSecond->giver_id=$giverId;
	                        $messageDataSecond->receiver_id=$userId;
	                        $messageText=$getIntroData->intro_introducer_name.' introduced '.$getIntroData->intro_receiver_name.' to '.$getIntroData->intro_giver_name.'.';
	                        $messageDataSecond->messageText=$messageText;
	                        $messageDataSecond->save();
	                        $messageDataSecond = new Message;
	                        if($getIntroData->intro_message != '' && $getIntroData->intro_message != 'null'){
	                        	$messageDataSecond->message_type='user';
		                        $messageDataSecond->request_id=$meetingId;
		                        $messageDataSecond->sender_id=$getIntroData->intro_introducer_id;
		                        $messageDataSecond->giver_id=$giverId;
		                        $messageDataSecond->receiver_id=$userId;
		                        $messageDataSecond->messageText=$getIntroData->intro_message;
		                        $messageDataSecond->save();
		                    }
	                        $getIntroData->request_id=$meetingId;
	                        $getIntroData->meeting_status='pending';
	                        $getIntroData->save();
                    	}
                        
                    }
				$saveMeetingDataForMyKarma=KarmaHelper::saveMeetingDataForMyKarma($meetingId,$user_id_receiver,$user_id_giver);
				
				Queue::push('MessageSender', array('type' =>'1','user_id_giver' => $user_id_giver,'user_id_receiver' => $user_id_receiver,'meetingId'=> $meetingId));
				return Redirect::to('/KarmaMeetings');
			}
			else 
			{
				$weekday_call = Input::get('weekday_call');
				$weekday_call_time = Input::get('weekday_call_time');
				$connection_id_giver = Input::get('connection_id_giver');
				$receiverWR = Input::get('receiverWR');	
				$giver_email = Input::get('giver_email');	
				if(!empty($receiverWR)){
					foreach ($receiverWR as $key => $value) {
						if($value == "I'd pay it forward"){
							$payitforward = '1';
						}
						elseif($value == "I'd send you a Karma Note"){
							$sendKarmaNote = '1';
						}
						elseif($value == "I'd buy you coffee (in-person meetings only)"){
							$buyyoucoffee = '0';
						}
					}		
				}
				$Meetingrequest = new Meetingrequest;
				$Meetingrequest ->user_id_receiver 				= Input::get('user_id_receiver');
				$Meetingrequest ->notes 						= strip_tags(Input::get('notes'));
				$Meetingrequest ->payitforward 					= $payitforward ;
				$Meetingrequest ->sendKarmaNote 				= $sendKarmaNote ;
				$Meetingrequest ->buyyoucoffee 					= $buyyoucoffee ;
				$Meetingrequest ->weekday_call_time 			= $weekday_call_time ;
				$Meetingrequest ->weekday_call 					= $weekday_call ;
				$Meetingrequest ->status 						= 'pending';
				$Meetingrequest ->connection_id_giver 			= Input::get('connection_id_giver');
				$Meetingrequest ->req_createdate 				= KarmaHelper::currentDate();
				$Meetingrequest->save();
				$meetingId = $Meetingrequest->id;
				$connectionId=Input::get('connection_id_giver');
				$receiverId=Input::get('user_id_receiver');
				$getGiverData=Connection::where('id', '=', $connectionId)->first();
				$getReceiverData=Connection::where('id', '=', $receiverId)->first();
				//Add data on users_mykarma table for receiver
				if($meetingId !='' && $meetingId !='null'){
							$myKarmaDataReceiver = new Mykarma;
							$myKarmaDataReceiver->entry_id=$meetingId;
							$myKarmaDataReceiver->user_id=Input::get('user_id_receiver');
							$myKarmaDataReceiver->fname=$getGiverData->fname;
							$myKarmaDataReceiver->lname=$getGiverData->lname;
							$myKarmaDataReceiver->piclink='null';
							$myKarmaDataReceiver->entry_type='Meeting';
							$myKarmaDataReceiver->users_role='Receiver';
							$myKarmaDataReceiver->status='completed';
							$myKarmaDataReceiver->unread_flag='false';
							$myKarmaDataReceiver->no_of_unread_items='0';
							$myKarmaDataReceiver->entry_updated_on=Carbon::now();
							$myKarmaDataReceiver->save();
						//Add data on users_mykarma table for giver
							$myKarmaDataGiver = new Mykarma;
							$myKarmaDataGiver->entry_id=$meetingId;
							$myKarmaDataGiver->user_id=Input::get('connection_id_giver');
							$myKarmaDataGiver->fname=$getReceiverData->fname;
							$myKarmaDataGiver->lname=$getReceiverData->lname;
							$myKarmaDataGiver->piclink=$getReceiverData->piclink;
							$myKarmaDataGiver->entry_type='Meeting';
							$myKarmaDataGiver->users_role='Giver';
							$myKarmaDataGiver->status='completed';
							$myKarmaDataGiver->unread_flag='true';
							$myKarmaDataGiver->no_of_unread_items='1';
							$myKarmaDataGiver->entry_updated_on=Carbon::now();
							$myKarmaDataGiver->save();
							 //Add message in requests_messages table
					        $messageData = new Message;
					        $messageData->request_id=$meetingId;
					        $messageData->sender_id=$userId;
					        $messageData->giver_id=Input::get('connection_id_giver');
					        $messageData->receiver_id=$userId;
					        $messageText=$getReceiverData->fname.' '.$getReceiverData->lname.' has sent a meeting request.';
					        $messageData->messageText=$messageText;
					        $messageData->save();
					        $messageDataSecond = new Message;
					        $messageDataSecond->message_type='user';
					        $messageDataSecond->request_id=$meetingId;
					        $messageDataSecond->sender_id=$userId;
					        $messageDataSecond->giver_id=Input::get('connection_id_giver');
					        $messageDataSecond->receiver_id=$userId;
					        $messageDataSecond->messageText=Input::get('notes');
					        $messageDataSecond->save();
			        $gratitudeText='In gratitude, I will do the following - -';
			         // Add regular messages in request_messages table.
			        if( $Meetingrequest->payitforward=='1'){
			            $payitforwardText="I'll pay it forward";
			        }else{
			             $payitforwardText="";
			        }
			        if( $Meetingrequest->buyyoucoffee=='1'){
			          $buyyoucoffeeText="I'll buy you coffee (in-person meetings only)";  
			        }else{
			            $buyyoucoffeeText="";
			        }
			        if( $Meetingrequest->sendKarmaNote=='1'){
			           $sendKarmaNoteText="I'll send you a KarmaNote"; 
			        }else{
			          $sendKarmaNoteText="";  
			        }
			        if($Meetingrequest->sendKarmaNote=='1'){
			            $messageGratituteText=$gratitudeText."
			".$buyyoucoffeeText.".
			".$sendKarmaNoteText.".
			".$payitforwardText.".";    
			        }else{
			            $messageGratituteText=$gratitudeText."
			".$buyyoucoffeeText.".
			".$payitforwardText.".";    
			        }
			        if (substr($messageGratituteText, 0, 1) === '.'){
			                $messageGratituteText = substr($messageGratituteText, 1);
			        }
			        if($Meetingrequest->payitforward=='1' || $Meetingrequest->sendKarmaNote=='1' || $Meetingrequest->buyyoucoffee=='1'){
			            $messageDataSecond = new Message;
			            $messageDataSecond->message_type='user';
			            $messageDataSecond->request_id=$meetingId;
			            $messageDataSecond->sender_id=$userId;
			            $messageDataSecond->giver_id=Input::get('connection_id_giver');
			            $messageDataSecond->receiver_id=$userId;
			            $messageDataSecond->messageText=$messageGratituteText;
			            $messageDataSecond->save();
			        }
    		}
				if($giver_email != "") {
					Queue::push('MessageSender@MeetingRequestMailNonKc', array('type' =>'16','user_id_giver' => $connection_id_giver,'user_id_receiver' => $user_id_receiver,'meetingId'=> $meetingId,'giver_email'=>$giver_email));
				}
				
				Queue::push('MessageSender', array('type' =>'2','user_id_giver' => $connection_id_giver,'user_id_receiver' => $user_id_receiver,'meetingId'=> $meetingId));
				/*$sendLinkedinMessage =  MessageHelper::triggerEmailAndMessage($connection_id_giver,$user_id_receiver,'2',$meetingId);*/
				//echo "<pre>";print_r($sendLinkedinMessage);echo "</pre>";die();
				return Redirect::to('/KarmaMeetings');
			}
		}
	}
	
	public function MeetingPage($Receiver,$Giver,$MeetingId){
		$CurrentUser = Auth::User();
		$skillSet = '';
		$meetingDetail = Meetingrequest::find($MeetingId);
		$meetingTrailData=DB::table('requests_messages')->where('request_id','=',$MeetingId)->orderBy('created_at','ASC')->get();		
			if(!empty($meetingDetail)){
				//echo '<pre>';print_r();die;
				if($CurrentUser['id']==$meetingDetail['user_id_receiver']){
					$userRole='Receiver';
				}else{
					$userRole='Giver';
				}
				$meetingStatusText=KarmaHelper::getMykarmaMessageForReceiverGiver($meetingDetail->status,$userRole);
				$receiverDetail = User::find($meetingDetail->user_id_receiver);
			if(empty($meetingDetail->user_id_giver)){
				$giverDetail = 	Connection::find($meetingDetail->connection_id_giver);
			}
			else{
				$giverDetail = 	User::find($meetingDetail->user_id_giver); 
			}	
			$CheckReceiver_Giver = 	$receiverDetail->fname.'-'.$receiverDetail->lname.'-'.$giverDetail->fname.'-'.$giverDetail->lname;	
			
			if(strtolower($CheckReceiver_Giver) != strtolower($Receiver.'-'.$Giver))	{
				return Redirect::to('404');
			}		
			if(Auth::check() && ($meetingDetail->user_id_receiver == Auth::user()->id || $meetingDetail->user_id_giver == Auth::user()->id || $meetingDetail->user_id_introducer == Auth::user()->id)){
				if($meetingDetail->user_id_introducer != ''){
					
					return $this->IntroPageDisplay($skillSet,$CurrentUser,$receiverDetail,$giverDetail,$meetingDetail);
				}
				else{
					return $this->normalMeetingPageDisplay($skillSet,$CurrentUser,$receiverDetail,$giverDetail,$meetingDetail,$meetingTrailData,$meetingStatusText,$userRole);	
				}
			}
			else{

				if($meetingDetail->status == 'completed'){
					return $this->publicMeetingPage($skillSet,$CurrentUser,$receiverDetail,$giverDetail,$meetingDetail);	
				}
				else{
					return Redirect::to('KarmaMeetings');
				}			
			}			
		}
		else
		{
			return Redirect::to('404');
		}	
	}

	public function IntroPageDisplay($skillSet,$CurrentUser,$receiverDetail,$giverDetail,$meetingDetail){
		$introducerDetail = User::find($meetingDetail->user_id_introducer);
		if($meetingDetail->status == 'pending' || $meetingDetail->status == 'archived'){
			if(Auth::User()->id == $meetingDetail->user_id_giver){
				KarmaHelper::ChangeReadStatus($meetingDetail,'requestviewstatus');
			}
			return View::make('intro_request_pending_send',array('pageTitle' => 'KarmaCircles Meeting',
				'CurrentUser' => $CurrentUser,'receiverDetail'=>$receiverDetail,
			'giverDetail'=>$giverDetail,'meetingDetail'=>$meetingDetail,'introducerDetail'=>$introducerDetail));
		}
		elseif($meetingDetail->status == 'accepted'){
			if(Auth::User()->id == $meetingDetail->user_id_receiver){
				KarmaHelper::ChangeReadStatus($meetingDetail,'replyviewstatus');
			}			
			$MettingActualCurrentTimeWithZone = KarmaHelper::calculateTime($meetingDetail->meetingtimezone);
			return View::make('intro_request_accepted',array('pageTitle' => 'KarmaCircles Meeting', 'CurrentUser' => $CurrentUser,'receiverDetail'=>$receiverDetail,
			'giverDetail'=>$giverDetail,'meetingDetail'=>$meetingDetail,'MettingActualCurrentTimeWithZone'=>$MettingActualCurrentTimeWithZone,'introducerDetail'=>$introducerDetail));
		}
		elseif($meetingDetail->status == 'completed'){					
			$karmaNoteDetail = 	DB::table('karmanotes')
								->where('req_id', '=', $meetingDetail->id)
								->select('id', 'details', 'skills', 'created_at', 'statusreceiver', 'statusgiver', 'user_idgiver', 'user_idreceiver')
								->first();
			$tags = explode(',', $karmaNoteDetail->skills);
			if(!empty($karmaNoteDetail->skills)){
				foreach ($tags as $name) {
					$skillTag = Tag::find($name)->toArray();
					$skills['name'] = $skillTag['name'];
					$skills['id'] = $skillTag['id'];
					$skillSet[] = $skills;
				}
			}
			if(Auth::User()->id == $karmaNoteDetail->user_idgiver){
				KarmaHelper::ChangeReadStatus($karmaNoteDetail,'KarmaNoteStatus');
			}					
			return View::make('intro_request_completed',array('pageTitle' => 'KarmaCircles Meeting','CurrentUser' => $CurrentUser,'receiverDetail'=>$receiverDetail,
			'giverDetail'=>$giverDetail,'meetingDetail'=>$meetingDetail, 'karmaNoteDetail' => $karmaNoteDetail, 'skillSet' => $skillSet,'introducerDetail'=>$introducerDetail));			
		}
	}

	public function normalMeetingPageDisplay($skillSet,$CurrentUser,$receiverDetail,$giverDetail,$meetingDetail,$meetingTrailData,$meetingStatusText,$userRole){
		$dst_value =Adminoption::Where('option_name','=','Set DST value')->select("option_value")->first();
		if($meetingDetail->status == 'pending' || $meetingDetail->status == 'archived' || $meetingDetail->status == 'cancelled' || $meetingDetail->status == 'scheduled' || $meetingDetail->status == 'responded' || $meetingDetail->status == 'over' || $meetingDetail->status == 'happened' || $meetingDetail->status == 'spam'){
			if(Auth::User()->id == $meetingDetail->user_id_giver){
				KarmaHelper::ChangeReadStatus($meetingDetail,'requestviewstatus');
			}
			return View::make('meeting_detail_page',array('pageTitle' => 'KarmaCircles Meeting','CurrentUser' => $CurrentUser,'receiverDetail'=>$receiverDetail,
			'giverDetail'=>$giverDetail,'dst_value'=>$dst_value,'meetingDetail'=>$meetingDetail,'meetingStatusText'=>$meetingStatusText,'meetingTrailData'=>$meetingTrailData,'userRole'=>$userRole));
		}
		elseif($meetingDetail->status == 'confirmed'){
			$dst_value =Adminoption::Where('option_name','=','Set DST value')->select("option_value")->first();
			if(Auth::User()->id == $meetingDetail->user_id_receiver){
				KarmaHelper::ChangeReadStatus($meetingDetail,'replyviewstatus');
			}			
			$MettingActualCurrentTimeWithZone = KarmaHelper::calculateTime($meetingDetail->meetingtimezone);
			return View::make('meeting_detail_page',array('pageTitle' => 'KarmaCircles Meeting','CurrentUser' => $CurrentUser,'receiverDetail'=>$receiverDetail,
			'giverDetail'=>$giverDetail,'dst_value'=>$dst_value,'meetingDetail'=>$meetingDetail,'MettingActualCurrentTimeWithZone'=>$MettingActualCurrentTimeWithZone,'dst_value'=>$dst_value,'meetingStatusText'=>$meetingStatusText,'meetingTrailData'=>$meetingTrailData,'userRole'=>$userRole));
		}
		elseif($meetingDetail->status == 'completed'){
			if(Auth::User()->id == $meetingDetail->user_id_receiver){
				KarmaHelper::ChangeReadStatus($meetingDetail,'replyviewstatus');
			}			
			$MettingActualCurrentTimeWithZone = KarmaHelper::calculateTime($meetingDetail->meetingtimezone);
			$karmaNoteDetail = DB::table('karmanotes')
					            ->where('req_id', '=', $meetingDetail->id)
					            ->select('id', 'details', 'skills', 'created_at', 'statusreceiver', 'statusgiver', 'user_idgiver', 'user_idreceiver')
					            ->first();
			if(!empty($karmaNoteDetail->skills))
			$tags = explode(',', $karmaNoteDetail->skills);
			if(!empty($karmaNoteDetail->skills)){
				foreach ($tags as $name) {
					$skillTag = Tag::find($name)->toArray();
					$skills['name'] = $skillTag['name'];
					$skills['id'] = $skillTag['id'];
					$skillSet[] = $skills;
				}
			}
			if(Auth::User()->id == $karmaNoteDetail->user_idgiver){
				KarmaHelper::ChangeReadStatus($karmaNoteDetail,'KarmaNoteStatus');
			}
			return View::make('meeting_request_completed',array('pageTitle' => 'KarmaCircles Meeting','CurrentUser' => $CurrentUser,'receiverDetail'=>$receiverDetail,
			'giverDetail'=>$giverDetail,'meetingDetail'=>$meetingDetail, 'karmaNoteDetail' => $karmaNoteDetail, 'skillSet' => $skillSet,'MettingActualCurrentTimeWithZone'=>$MettingActualCurrentTimeWithZone,'dst_value'=>$dst_value,'meetingStatusText'=>$meetingStatusText,'meetingTrailData'=>$meetingTrailData,'userRole'=>$userRole));
		}
	}
	public function publicMeetingPage($skillSet,$CurrentUser,$receiverDetail,$giverDetail,$meetingDetail){
		$introducerDetail = User::find($meetingDetail->user_id_introducer);
		if(!empty($introducerDetail)){
			$karmaNoteDetail = DB::table('karmanotes')
				            ->where('req_id', '=', $meetingDetail->id)
				            ->select('id', 'details', 'skills', 'created_at', 'statusreceiver','statusgiver','user_idreceiver', 'user_idgiver')
				            ->first();
			$tags = explode(',', $karmaNoteDetail->skills);
			if(!empty($karmaNoteDetail->skills)){
				foreach ($tags as $name) {
					$skillTag = Tag::find($name)->toArray();
					$skills['name'] = $skillTag['name'];
					$skills['id'] = $skillTag['id'];
					$skillSet[] = $skills;
				}
			}
			return View::make('intro_public_meeting',array('pageTitle' => 'KarmaCircles Meeting','CurrentUser' => $CurrentUser,'receiverDetail'=>$receiverDetail,
			'giverDetail'=>$giverDetail,'meetingDetail'=>$meetingDetail, 'karmaNoteDetail' => $karmaNoteDetail, 'skillSet' => $skillSet,'introducerDetail'=>$introducerDetail));
		}
		else{
			$karmaNoteDetail = DB::table('karmanotes')
				            ->where('req_id', '=', $meetingDetail->id)
				            ->select('id', 'details', 'skills', 'created_at', 'statusreceiver','statusgiver','user_idreceiver', 'user_idgiver')
				            ->first();
			$tags = explode(',', $karmaNoteDetail->skills);
			if(!empty($karmaNoteDetail->skills)){
				foreach ($tags as $name) {
					$skillTag = Tag::find($name)->toArray();
					$skills['name'] = $skillTag['name'];
					$skills['id'] = $skillTag['id'];
					$skillSet[] = $skills;
				}
			}
			return View::make('public_meeting',array('pageTitle' => 'KarmaCircles Meeting','CurrentUser' => $CurrentUser,'receiverDetail'=>$receiverDetail,
			'giverDetail'=>$giverDetail,'meetingDetail'=>$meetingDetail, 'karmaNoteDetail' => $karmaNoteDetail, 'skillSet' => $skillSet));
		}				
	}
	public function meetingAccept($MeetingId){
		$CurrentUser = Auth::User();
		$meetingDetail = Meetingrequest::find($MeetingId);
		if(!empty($meetingDetail)){
			$receiverDetail = User::find($meetingDetail->user_id_receiver);
			$giverDetail = 	User::find($meetingDetail->user_id_giver);
			$dst_value = "";
			$dst_value =Adminoption::Where('option_name','=','Set DST value')->select("option_value")->first();
			if(!empty($dst_value))
			$dst_value = $dst_value->option_value;
			/*echo "<pre>";print_r($meetingDetail->toArray());echo "</pre>";*/
			return View::make('meeting_accept',array('pageTitle' => 'KarmaCircles Meeting','dst_value' => $dst_value,'CurrentUser' => $CurrentUser,'receiverDetail'=>$receiverDetail,
			'giverDetail'=>$giverDetail,'meetingDetail'=>$meetingDetail));
		}
		else
		{
			return Redirect::to('404');
		}			
	}

	public function acceptMeetingRequest(){
		$getUser = Auth::User();
		$userId=$getUser->id;
		//echo "<pre>";print_r($_POST);echo "</pre>";die();
		$dateTime = Input::get('meetingdate')." ".Input::get('meetingtime');
		$Meeting_Name = Input::get('receiverName')."-".Input::get('giverName');
		$meetingId = Input::get('meetingId');
		$checkStatus=DB::table('requests')->where('id','=',$meetingId)->whereIn('status',array('scheduled','confirmed'))->count();
		$meetingtimezone = Input::get('meetingtimezone');
		$meetingdatetime = date('Y-m-d H:i:s',strtotime($dateTime));
		$meetingDetail = Meetingrequest::find(Input::get('meetingId'));
		$meetingDetail->meetingduration = Input::get('meetingduration');
		$meetingDetail->meetingtimezonetext = Input::get('meetingtimezonetext');
		$meetingDetail->meetingdatetime = $meetingdatetime;
		$meetingDetail->meetingtimezone = $meetingtimezone;
		$meetingDetail->meetingtype 	= Input::get('meetingtype');
		$meetingDetail->req_updatedate 	= KarmaHelper::currentDate();
		$meetingDetail->meetinglocation = strip_tags(Input::get('meetinglocation'));
		$meetingDetail->reply 			= strip_tags(Input::get('reply'));
		$meetingDetail->status 			= 'scheduled';
		$meetingDetail->save();
		$user_id_giver 		= $meetingDetail->user_id_giver;
		$user_id_receiver 	= $meetingDetail->user_id_receiver;
		$meetingId 			= $meetingDetail->id;
		$feedType='MeetingRequestAccepted';
		//KarmaHelper::storeKarmacirclesfeed($user_id_giver,$user_id_receiver,$feedType,$meetingId);
		Queue::push('MessageSender', array('type' =>'3','user_id_giver' => $user_id_giver,'user_id_receiver' => $user_id_receiver,'meetingId'=> $meetingId));
		//app code
		$meetingDuration=Input::get('meetingduration');
		$meetingTime=Input::get('meetingtime');
		$meetingType=Input::get('meetingtype');
		$meetingLocation=Input::get('meetinglocation');
		DB::table('users_mykarma')->where('entry_id','=',$meetingId)->update(array('status' => 'scheduled','entry_updated_on' => Carbon::now()));
        $getReceiverData=DB::table('requests')->where('id','=',$meetingId)->select('user_id_receiver')->get();
        $messageData = new Message;
        $messageData->request_id=$meetingId;
        $messageData->sender_id=$userId;
        $messageData->giver_id=$userId;
        $messageData->receiver_id=$getReceiverData[0]->user_id_receiver;
        if($checkStatus < 1){
            $messageText=$getUser->fname.' '.$getUser->lname.' has scheduled a meeting.';    
        }else{
            $messageText=$getUser->fname.' '.$getUser->lname.' has rescheduled a meeting.';    
        }
        $messageData->messageText=$messageText;
        $messageData->save();
        //$meetingTimeZoneText=$meetingTimezoneText;
        if ($meetingDetail->meetingtimezone > '0'){
        	$meetingTimezoneText='+'.$meetingDetail->meetingtimezone;
        }else{
        	$meetingTimezoneText=$meetingDetail->meetingtimezone;
        }
    	$meetingDateValue=date('M d, Y', strtotime(Input::get('meetingdate')));
        $messageText='Meeting scheduled for '.$meetingDuration.' on '.$meetingDateValue.' at '.$meetingTime. ' GMT('.$meetingTimezoneText. ') '.$meetingType.': '.$meetingLocation.'.';
        $userMessageData = new Message;
        $userMessageData->message_type='user';
        $userMessageData->request_id=$meetingId;
        $userMessageData->sender_id=$userId;
        $userMessageData->giver_id=$userId;
        $userMessageData->receiver_id=$getReceiverData[0]->user_id_receiver;
        $userMessageData->messageText=$messageText;
        $userMessageData->save();
        if(Input::get('reply') !='' && Input::get('reply') !='null'){
        	$messageData = new Message;
        	$messageData->message_type='user';
	        $messageData->request_id=$meetingId;
	        $messageData->sender_id=$userId;
	        $messageData->giver_id=$userId;
	        $messageData->receiver_id=$getReceiverData[0]->user_id_receiver;
	        $messageText=Input::get('reply');
	        $messageData->messageText=$messageText;
	        $messageData->save();
        }
        $userRole='Giver';
        $updatedStatus=KarmaHelper::updateMeetingStatus($meetingId,$userRole);
		return Redirect::to("/meeting/$Meeting_Name/$meetingId");
	}

	/**
		*Function name: saveNonKarmanote()
		*Created by : Evon
		*Created on : 04/10/2014
	**/
	public function saveDirectKarmaNote(){
		$CurrentUser = Auth::User();
		$receiverId = $connection_giverId = $user_giverId = $note = $meetingtime = $meetingzone = $meetingdate = $giverName = '';
		if(!empty(Input::get('user_id_receiver'))) $receiverId = Input::get('user_id_receiver');
		if(!empty(Input::get('connection_id_giver'))) $connection_giverId = Input::get('connection_id_giver');
		if(!empty(Input::get('user_id_giver'))) $user_giverId = Input::get('user_id_giver');
		//if(!empty(Input::get('meetingmonth'))) $meetingmonth = Input::get('meetingmonth');
		//if(!empty(Input::get('meetingyear'))) $meetingyear = Input::get('meetingyear');
		if(!empty(Input::get('skillTags'))) $skills = Input::get('skillTags');
		if(!empty(Input::get('meetingdate'))) $meetingdate = Input::get('meetingdate');
		if(!empty(Input::get('meetingtime'))) $meetingtime = Input::get('meetingtime');
		if(!empty(Input::get('meetingtimezone'))) $meetingzone = Input::get('meetingtimezone');
		if(!empty(Input::get('details')))	$note = Input::get('details');
		if(!empty(Input::get('ShareKarmaNote')))	$ShareKarmaNote = Input::get('ShareKarmaNote');
		// get giver email ID when user is not on KC
		if(!empty(Input::get('giver_email')))	$giver_email = Input::get('giver_email');
		
		//print_r($ShareKarmaNote);die;  
		if (!empty($user_id_receiver)) {
			return Redirect::to('404');
		}else{ 
				
				$getUser=User::where('id', '=', $receiverId)->first();
				$dateTime = $meetingdate.' '.$meetingtime;
				$Meetingrequest = new Meetingrequest;
				
				$Meetingrequest ->user_id_receiver 				= $receiverId;
				if(!empty($user_giverId))
					$Meetingrequest ->user_id_giver 			= $user_giverId;
				$Meetingrequest ->notes 						= '';
				$Meetingrequest ->status 						= 'approved';
				if(!empty($connection_giverId))
				$Meetingrequest ->connection_id_giver 			= $connection_giverId;
				$Meetingrequest ->meetingdatetime			 	= date('Y-m-d H:i:s',strtotime($dateTime)); 
				//$Meetingrequest ->meetingdatetime			 	= $dateTime; 
				$Meetingrequest ->replyviewstatus			 	= '1'; 
				$Meetingrequest ->requestviewstatus			 	= '1'; 
				$Meetingrequest ->meetingtimezone				= $meetingzone;	
				$Meetingrequest ->req_createdate 				= KarmaHelper::currentDate();
				$Meetingrequest->save();
				$getGiverData=User::where('id', '=', $user_giverId)->first();
				$meetingId = $Meetingrequest->id;
				$myKarmaDataReceiver = new Mykarma;
				$myKarmaDataReceiver->entry_id=$meetingId;
				$myKarmaDataReceiver->user_id=$receiverId;
				$myKarmaDataReceiver->fname=$getGiverData->fname;
				$myKarmaDataReceiver->lname=$getGiverData->lname;
				$myKarmaDataReceiver->piclink=$getGiverData->piclink;
				$myKarmaDataReceiver->entry_type='Meeting';
				$myKarmaDataReceiver->users_role='Receiver';
				$myKarmaDataReceiver->status='completed';
				$myKarmaDataReceiver->unread_flag='false';
				$myKarmaDataReceiver->no_of_unread_items='0';
				$myKarmaDataReceiver->entry_updated_on=Carbon::now();
				$myKarmaDataReceiver->save();
				//Add data on users_mykarma table for giver
				$myKarmaDataGiver = new Mykarma;
				$myKarmaDataGiver->entry_id=$meetingId;
				$myKarmaDataGiver->user_id=$user_giverId;
				$myKarmaDataGiver->fname=$getUser->fname;
				$myKarmaDataGiver->lname=$getUser->lname;
				$myKarmaDataGiver->piclink=$getUser->piclink;
				$myKarmaDataGiver->entry_type='Meeting';
				$myKarmaDataGiver->users_role='Giver';
				$myKarmaDataGiver->status='completed';
				$myKarmaDataGiver->unread_flag='true';
				$myKarmaDataGiver->no_of_unread_items='1';
				$myKarmaDataGiver->entry_updated_on=Carbon::now();
				$myKarmaDataGiver->save();	
				$token=$getGiverData->deviceToken;
				$pushNotificationStatus=NotificationHelper::androidPushNotification($token);
				$karmaNote = new Karmanote;
				$karmaNote ->req_id 							= $meetingId;
				if(!empty($connection_giverId))
					$karmaNote ->connection_idgiver 			= $connection_giverId;
				elseif(!empty($user_giverId))
					$karmaNote ->user_idgiver 					= $user_giverId;
				$karmaNote ->user_idreceiver 					= $receiverId;
				$karmaNote ->details 							= strip_tags($note);
				if(!empty($skills)){
					$karmaNote ->skills 						= implode(',', $skills);
				}
				else{
					$karmaNote ->skills 						= '';
				}	
				$karmaNote ->viewstatus 						= 0;
				$karmaNote->created_at 							= KarmaHelper::currentDate();
				if(!empty($ShareKarmaNote)){
					$karmaNote ->share_onlinkedin 					= $ShareKarmaNote[0]; 
				}
				$karmaNote->save();
				$karmaNoteId = $karmaNote->id;
				$karmaNoteMessage=$karmaNote->details;
				if(!empty($skills)){
							$getName = $getSkillDataName = "";
							foreach ($skills as $key => $value) {
								$getName = Tag::where('id','=',$value)->select('name')->first(); 
								$getSkillDataName .= $getName->name . ",";
							}
							$result=rtrim($getSkillDataName,",");
						$karmaNoteMessage=$karmaNote ->details."
Endorsements: ".$result;
						}
						else{
							$karmaNoteMessage=$karmaNote ->details;
						}	
					$userMessageData = new Message;
	                $userMessageData->message_type='system';
	                $userMessageData->request_id=$meetingId;
	                $userMessageData->sender_id=$Meetingrequest->user_id_receiver;
	                $userMessageData->giver_id=$Meetingrequest->user_id_giver;
	                $userMessageData->receiver_id=$Meetingrequest->user_id_receiver;
	               	$messageText=$getUser->fname.' '.$getUser->lname.' has sent a KarmaNote.';
	               	$userMessageData->messageText=$messageText;
	                $userMessageData->save();
	                $userMessageData = new Message;
	                $userMessageData->message_type='user';
	                $userMessageData->request_id=$meetingId;
	                $userMessageData->sender_id=$Meetingrequest->user_id_receiver;
	                $userMessageData->giver_id=$Meetingrequest->user_id_giver;
	                $userMessageData->receiver_id=$Meetingrequest->user_id_receiver;
	                $userMessageData->messageText=$karmaNoteMessage;
	                $userMessageData->save();	
	                
				if(!empty($user_giverId)){
					$feedType='KarmaNote';
					KarmaHelper::updateKarmaScore($user_giverId,$receiverId);
					//KarmaHelper::storeKarmacirclesRecord($user_giverId,$receiverId);
					//$sendLinkedinMessage =  MessageHelper::triggerEmailAndMessage($user_giverId,$receiverId,'5',$meetingId);
					//KarmaHelper::storeKarmacirclesRelation($user_giverId,$receiverId);
					KarmaHelper::storeKarmacirclesfeed($user_giverId,$receiverId,$feedType,$karmaNoteId);
					Queue::push('MessageSender', array('type' =>'5','user_id_giver' => $user_giverId,'user_id_receiver' => $receiverId,'meetingId'=> $meetingId));
					if(!empty($ShareKarmaNote)){
						//$sendLinkedinMessage =  MessageHelper::triggerEmailAndMessage($user_giverId,$receiverId,'9',$meetingId);
						Queue::push('MessageSender', array('type' =>'9','user_id_giver' => $user_giverId,'user_id_receiver' => $receiverId,'meetingId'=> $meetingId));
					}
				}
				else{
					if(!empty($ShareKarmaNote)){
						//$sendLinkedinMessage =  MessageHelper::triggerEmailAndMessage($connection_giverId,$receiverId,'10',$meetingId);
						Queue::push('MessageSender', array('type' =>'10','user_id_giver' => $connection_giverId,'user_id_receiver' => $receiverId,'meetingId'=> $meetingId));
					}
					//$sendLinkedinMessage =  MessageHelper::triggerEmailAndMessage($connection_giverId,$receiverId,'6',$meetingId);
					Queue::push('MessageSender', array('type' =>'6','user_id_giver' => $connection_giverId,'user_id_receiver' => $receiverId,'meetingId'=> $meetingId));
				}
				/* Send a Karma Note to the Giver not on Karma Platform.*/
			    if(!empty($giver_email))
				{ 						
					Queue::push('MessageSender@SendEmailToNonKarma', array('type' =>'14','user_id_giver' => $connection_giverId,'user_id_receiver' => $receiverId,'giver_email'=> $giver_email,'meetingId'=>$meetingId));					
				}				
				$newMeetingrequest = Meetingrequest::find($meetingId);
				$receiverDetail = User::find($receiverId);
				$receiverNameArr = $receiverDetail->toArray();
				if(!empty($connection_giverId)){
					$ConnectionDetail = Auth::User()->connections()->where('connections.id','=',$connection_giverId)->get()->first();
					$giverName = $ConnectionDetail->fname.'-'.$ConnectionDetail->lname;
				}elseif(!empty($user_giverId)){
					$userDetail = User::find($user_giverId);
					$userDetailArr = $userDetail->toArray();
					$giverName = $userDetailArr['fname'].'-'.$userDetailArr['lname'];
				}
				$newMeetingrequest->status 			= 'completed';
				$newMeetingrequest->save();
				return Redirect::to('meeting/'.$receiverNameArr['fname'].'-'.$receiverNameArr['lname'].'-'.$giverName.'/'.$meetingId);
		}
	}
	public function archiveMeeting(){
		$meetingId = Input::get('meetingid');
		$meetingDetail =  Meetingrequest::find($meetingId);
		$meetingDetail->status 			= 'archived';
		$meetingDetail->save();
		$meetingId=$meetingDetail->id;
		$feedType='MeetingRequestArchived';
		$user_id_giver=$meetingDetail->user_id_giver;
		$user_id_receiver=$meetingDetail->user_id_receiver;
		//KarmaHelper::storeKarmacirclesfeed($user_id_giver,$user_id_receiver,$feedType,$meetingId);		
		Queue::push('MessageSender', array('type' =>'8','user_id_giver' => $meetingDetail->user_id_giver,'user_id_receiver' => $meetingDetail->user_id_receiver,'meetingId'=> $meetingId));
	}

	/*public function getdataByorder for meeting request received tab*/
	public function getdataByorder()
	{
		$currentTab = Input::get('currentTab');
		$setting = Input::get('setting');
		$CurrentUser = Auth::User();
		if($currentTab == "requestReceived"){
			if($setting == 'Recent'){
				$totalReceivedRequest = $totalSentRequest = $totalArchivedRequest = 0;
				$ReceiverInMeeting = Auth::User()->Receiver()->orderBy('updated_at', 'DESC')->where('status','pending')->get();
				$GiverInMeeting = Auth::User()->Giver()->orderBy('updated_at', 'DESC')->where('status','pending')->get();
				foreach ($GiverInMeeting as $key => $value) {
					$value['user_id_giver'] = User::find($value['user_id_giver'])->toArray();
					$value['user_id_receiver'] = User::find($value['user_id_receiver'])->toArray();
				}
				return View::make('ajax_RequestOrder',array('pageTitle' => 'KarmaCircles Meeting','key'=>'','CurrentUser' => $CurrentUser,'GiverInMeeting'=>$GiverInMeeting,
		'ReceiverInMeeting'=>$ReceiverInMeeting,'countRec'=>'0','countArc'=>'0','countSent'=>'0'));

			}
			if($setting == 'TopGivers'){  
				/*
				$totalReceivedRequest = $totalSentRequest = $totalArchivedRequest = 0;
				$ReceiverInMeeting = Auth::User()->Receiver()->orderBy('created_at', 'ASC')->get();
				$GiverInMeeting = Auth::User()->Giver()->orderBy('created_at', 'ASC')->get();
				//echo '<pre>';print_r($GiverInMeeting);exit;
				foreach ($GiverInMeeting as $key => $value) {
					$value['user_id_giver'] = User::find($value['user_id_giver'])->toArray();
					$value['user_id_receiver'] = User::find($value['user_id_receiver'])->toArray();
				}
				$GiverInMeeting_value = array_values(array_sort($GiverInMeeting, function($value)
					{
						return $value->user_id_receiver['karmascore'];  
					}));    
				$GiverInMeeting = array_reverse($GiverInMeeting_value, true);
				//echo '<pre>';print_r($GiverInMeeting);exit;
				*/
				$totalReceivedRequest = $totalSentRequest = $totalArchivedRequest = 0;
				$ReceiverInMeeting = Auth::User()->Receiver()->orderBy('created_at', 'DESC')->where('status','pending')->get();
				$GiverInMeeting = Auth::User()->Giver()->orderBy('created_at', 'DESC')->where('status','pending')->get();
				foreach ($GiverInMeeting as $key => $value) {
					$value['user_id_giver'] = User::find($value['user_id_giver'])->toArray();
					$value['user_id_receiver'] = User::find($value['user_id_receiver'])->toArray();
				}
				$GiverInMeeting_value = array_values(array_sort($GiverInMeeting, function($value)
					{
						return $value->user_id_receiver['karmascore'];  
					}));  
				$GiverInMeeting = array_reverse($GiverInMeeting_value, true);
				return View::make('ajax_RequestOrder',array('pageTitle' => 'KarmaCircles Meeting','key'=>'','CurrentUser' => $CurrentUser,'GiverInMeeting'=>$GiverInMeeting,
		'ReceiverInMeeting'=>$ReceiverInMeeting,'countRec'=>'0','countArc'=>'0','countSent'=>'0'));
			}
			if($setting == 'KarmaIntro'){ 
				$CurrentUser = Auth::User(); 
				$IntroducerInitiated = DB::select(DB::raw("select * from requests where requests.user_id_introducer= ".$CurrentUser->id." order by requests.updated_at DESC"));	
				foreach ($IntroducerInitiated as $key => $value) {
					$value->user_id_receiver = User::find($value->user_id_receiver);
					if(!empty($value->user_id_giver)){ 
					$value->user_id_giver = User::find($value->user_id_giver);
					}
					else{
					$value->user_id_giver = Connection::find($value->connection_id_giver);
					}
				} 
				$totalintroductionInitiated = count($IntroducerInitiated);
				//echo "<pre>";print_r($IntroducerInitiated);echo "</pre>"; 
				return View::make('ajax_RequestkarmaIntro',array('pageTitle' => 'Karma Intro','CurrentUser' => $CurrentUser,'IntroducerInitiated'=>$IntroducerInitiated,'totalintroductionInitiated'=>$totalintroductionInitiated,'countIntro'=>'0'));
			}
			if($setting == 'All'){ 
				$totalReceivedRequest = $totalSentRequest = $totalArchivedRequest = 0;
				$ReceiverInMeeting = Auth::User()->Receiver()->orderBy('updated_at', 'DESC')->get();
				$GiverInMeeting = Auth::User()->Giver()->orderBy('updated_at', 'DESC')->get();
				foreach ($ReceiverInMeeting as $key => $value) {
					$value['user_id_receiver'] = User::find($value['user_id_receiver'])->toArray();
					if(!empty($value['user_id_giver'])){
						$value['user_id_giver'] = User::find($value['user_id_giver'])->toArray();
					}
					else{
						$value['user_id_giver'] = Connection::find($value['connection_id_giver'])->toArray();
					}
				}
				$totalReceivedRequest = count($ReceiverInMeeting);
				foreach ($GiverInMeeting as $key => $value) {
					$value['user_id_giver'] = User::find($value['user_id_giver'])->toArray();
					$value['user_id_receiver'] = User::find($value['user_id_receiver'])->toArray();
				}
			return View::make('ajax_RequestOrderAll',array('key'=>'','CurrentUser' => $CurrentUser,'GiverInMeeting'=>$GiverInMeeting,
		'ReceiverInMeeting'=>$ReceiverInMeeting,'countRec'=>'0','countArc'=>'0','countSent'=>'0','setting'=>'All'));
			}

		}
		
		die;
	}

	//Cancel meeting request and meeting
	public function cancelMeeting(){
		$currentUser = Auth::User();
		$userId=$currentUser->id;
		$meetingId = $_POST['meetingId'];
		$userRole = $_POST['userRole'];
		$saveMessageForMeetingCancel=KarmaHelper::saveMessageForMeetingCancel($meetingId,$userId,$userRole);	
		$changeStatus=KarmaHelper::updateMeetingStatus($meetingId,$userRole);
        if($userRole=='Receiver'){
            DB::table('requests')->where('id','=',$meetingId)->update(array('status' => 'cancelled'));        
        }else{
            DB::table('requests')->where('id','=',$meetingId)->update(array('status' => 'responded'));        
        }
        $meetingData=Meetingrequest::where('id','=',$meetingId)->select('status','notes','meetingtype','user_id_receiver','user_id_giver')->first();
        $meetingStatusText=KarmaHelper::getMykarmaMessageForReceiverGiver($meetingData->status,$userRole);
    	if(!empty($meetingData)){
    		if($userRole=='Receiver'){
    			$userProfileId=$meetingData->user_id_giver;
    			$userProfilePic=User::where('id','=',$userProfileId)->select('piclink','fname','lname')->first();
    			$userProfilePicLink=$userProfilePic->piclink;
    			DB::table('users_mykarma')->where('entry_id','=',$meetingId)->where('users_role','=','Receiver')->update(array('unread_flag' => 'false', 'no_of_unread_items' => '0'));	
    		}
    		if($userRole=='Giver'){
    			$userProfileId=$meetingData->user_id_receiver;
    			if($userProfileId=='' || $userProfileId=='null'){
    				$userProfilePicLink='null';	
    			}else{
    				$userProfilePic=User::where('id','=',$userProfileId)->select('piclink','fname','lname')->first();	
    				$userProfilePicLink=$userProfilePic->piclink;
    			}
    			
    			DB::table('users_mykarma')->where('entry_id','=',$meetingId)->where('users_role','=','Giver')->update(array('unread_flag' => 'false', 'no_of_unread_items' => '0'));	
    		}
    	}
    	return Redirect::to('meeting/'.$currentUser->fname.'-'.$currentUser->lname.'-'.$userProfilePic->fname.'-'.$userProfilePic->lname.'/'.$meetingId);
	}

	// Send reminder 
	public function sendMeetingReminder($userRole,$entryId){
		$currentUser = Auth::User();
		$userId=$currentUser->id;
		$yesterday = Carbon::now()->subMinutes(5);
        if($userRole=='Receiver'){
            $checkStatus=Mykarma::where('entry_id','=',$entryId)->where('entry_updated_on','<',$yesterday)->where('users_role','=','Receiver')->count();    
        }else if($userRole=='Giver'){
            $checkStatus=Mykarma::where('entry_id','=',$entryId)->where('entry_updated_on','<',$yesterday)->where('users_role','=','Giver')->count();    
        }else{
            $checkStatus='0';
        }
        $getGiverData=Meetingrequest::where('id','=',$entryId)->first();
        $getGiverName=User::find($getGiverData->user_id_giver);
        if($checkStatus > 0){
        	$saveMessageData = new Message;
	        $saveMessageData->request_id=$entryId;
	        $saveMessageData->sender_id=$userId;
	        $saveMessageData->giver_id=$getGiverData->user_id_giver;
	        $saveMessageData->receiver_id=$userId;
	        $messageText=$currentUser->fname.' '.$currentUser->lname.' has sent a reminder.';
	        $saveMessageData->messageText=$messageText;
	        $saveMessageData->save();
	        $updatedStatus=KarmaHelper::updateMeetingStatus($entryId,$userRole); 
            $meetingDataResult=KarmaHelper::meetingData($currentUser->token,$userId,$entryId,$userRole);
            
		}
		return Redirect::to('meeting/'.$currentUser->fname.'-'.$currentUser->lname.'-'.$getGiverName->fname.'-'.$getGiverName->lname.'/'.$entryId);
	}

	/**
	 * Function to change the status to anystate to meeting happened.
	 *
	 * @return Response
	 */
     public function meetingDetailPage()
    {
        $rules = Validator::make(Request::all(), [
                    'accessToken' => 'required',
                    'userId' => 'required',
                    'meetingId' => 'required',
                    'userRole' => 'required'
        ]);
        if ($rules->fails()) {
            $this->status = 'Validation fails';
            $this->message = 'arguments missing';
        } else {
            $accessToken = Request::get('accessToken');
            $userId = Request::get('userId');
            $meetingId = Request::get('meetingId');
            $userRole = Request::get('userRole');
           	$getUser = User::where('id', '=', $userId)->where('site_token','=',$accessToken)->first();
            if(!empty($getUser)){
            	$meetingData=array();
            	$meetingData=Meetingrequest::where('id','=',$meetingId)->select('status','notes','meetingtype','user_id_receiver','user_id_giver')->first();
            	if(!empty($meetingData)){
            		if($userRole=='Receiver'){
            			$userProfileId=$meetingData->user_id_giver;
            			$userProfilePic=User::where('id','=',$userProfileId)->select('piclink')->first();
            			if(!empty($userProfilePic)){
            				if($userProfilePic->piclink==''){
            					$userProfilePic->piclink='null';
            				}	
            			}else{
            				$userProfilePic->piclink='null';
            			}
            			DB::table('users_mykarma')->where('entry_id','=',$meetingId)->where('users_role','=','Receiver')->update(array('unread_flag' => 'false', 'no_of_unread_items' => '0'));	
            		}
            		if($userRole=='Giver'){
            			$userProfileId=$meetingData->user_id_receiver;
            			$userProfilePic=User::where('id','=',$userProfileId)->select('piclink')->first();
            			DB::table('users_mykarma')->where('entry_id','=',$meetingId)->where('users_role','=','Giver')->update(array('unread_flag' => 'false', 'no_of_unread_items' => '0'));	
            		}
            		$meetingStatusText=KarmaHelper::getMykarmaMessageForReceiverGiver($meetingData->status,$userRole);
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
	            		$this->status='Success';
            			$this->message='Karma Meeting data';

	            		return Response::json(array('status'=>$this->status,
    								'message'=>$this->message,
    								'meetingStatus'=>$meetingData->status,
    								'meetingUserId'=>$userProfileId,
    								'userProfilePic'=>$userProfilePic->piclink,
    								'meetingStatusText'=>$meetingStatusText,
    								'meetingData'=>$meetingDetailData,
    								'meetingTrailData'=>$meetingMessageData
    					));
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
	            		$this->status='Success';
            			$this->message='Karma Meeting data';
	            		return Response::json(array('status'=>$this->status,
    								'message'=>$this->message,
    								'meetingStatus'=>$meetingData->status,
    								'meetingUserId'=>$userProfileId,
    								'userProfilePic'=>$userProfilePic->piclink,
    								'meetingStatusText'=>$meetingStatusText,
    								'karmaNoteData'=>$karmaNoteDetailData,
    								'meetingTrailData'=>$meetingMessageData
    					));
	            	}else{
	            		$this->status='Success';
            			$this->message='Karma Meeting data';
	            		return Response::json(array('status'=>$this->status,
	            					'message'=>$this->message,
	            					'meetingStatus'=>$meetingData->status,
	            					'meetingUserId'=>$userProfileId,
    								'userProfilePic'=>$userProfilePic->piclink,
	            					'meetingStatusText'=>$meetingStatusText,
    								'meetingTrailData'=>$meetingMessageData
    					));
					}
	            	
        		}else{
            		$this->status='Failure';
            		$this->message='Please enter correct meeting id';
            	}
            }else{
            	$this->status = 'failure';
             	$this->message = 'You are not a login user.';
            }
        }
        return Response::json(array('status'=>$this->status,
    								'message'=>$this->message,
    	));
    }

    //Cancel meeting request and meeting
	public function meetingArchiveFromWeb(){
		$currentUser = Auth::User();
		$userId=$currentUser->id;
		$meetingId = $_POST['meetingId'];
		$userRole = $_POST['userRole'];
		$getGiverData=DB::table('requests')->join('users','requests.user_id_giver','=','users.id')->where('requests.id','=',$meetingId)->select('requests.user_id_giver','users.fname','users.lname')->get();
                //Add message in requests_messages table
                if(!empty($getGiverData)){
                    $messageData = new Message;
                    $messageData->request_id=$meetingId;
                    $messageData->sender_id=$getGiverData[0]->user_id_giver;
                    $messageData->giver_id=$getGiverData[0]->user_id_giver;
                    $messageData->receiver_id=$userId;
                    $messageText=$getGiverData[0]->fname.' '.$getGiverData[0]->lname.' is currently busy. Please contact another KarmaGiver.';
                    $messageData->messageText=$messageText;
                    $messageData->save();
                    DB::table('users_mykarma')->where('entry_id','=',$meetingId)->update(array('status' => 'archived','entry_updated_on' => Carbon::now()));
                    DB::table('requests')->where('id','=',$meetingId)->update(array('status' => 'archived'));    
                    $changeStatus=KarmaHelper::updateMeetingStatus($meetingId,$userRole);
                }
    	return Redirect::to('meeting/'.$currentUser->fname.'-'.$currentUser->lname.'-'.$getGiverData[0]->fname.'-'.$getGiverData[0]->lname.'/'.$meetingId);
	}

	//Confirm meeting from web
	public function confirmMeetingFromWeb($meetingId){
		$getUser=Auth::User();
		$userId=$getUser->id;
		$userRole='Receiver';
		$getMeetingUserData=KarmaHelper::commonConfirmMeeting($meetingId,$userId);
		$changeStatus=KarmaHelper::updateMeetingStatus($meetingId,$userRole);
        return Redirect::to('meeting/'.$getUser->fname.'-'.$getUser->lname.'-'.$getMeetingUserData[0]->fname.'-'.$getMeetingUserData[0]->lname.'/'.$meetingId);
	}

	//Request new time from web
	public function requestNewTimeFromWeb(){
		$getUser=Auth::User();
		$meetingId = $_POST['meetingId'];
		$userRole = $_POST['userRole'];
		$getMeetingUserData=KarmaHelper::commonMeetingRequestNewTime($meetingId,$getUser->id);
		$changeStatus=KarmaHelper::updateMeetingStatus($meetingId,$userRole);
        return Redirect::to('meeting/'.$getUser->fname.'-'.$getUser->lname.'-'.$getMeetingUserData[0]->fname.'-'.$getMeetingUserData[0]->lname.'/'.$meetingId);
	}

	//Request did not happen from web
	public function meetingNotHappenedFromWeb(){
		$getUser=Auth::User();
		$meetingId = $_POST['meetingId'];
		$userRole = $_POST['userRole'];
		$getMeetingUserData=KarmaHelper::commonMeetingNotHappened($meetingId,$userRole);
		$changeStatus=KarmaHelper::updateMeetingStatus($meetingId,$userRole);
        return Redirect::to('meeting/'.$getUser->fname.'-'.$getUser->lname.'-'.$getMeetingUserData[0]->fname.'-'.$getMeetingUserData[0]->lname.'/'.$meetingId);
	}

	//Request happen from web
	public function meetingHappenedFromWeb(){
		$getUser=Auth::User();
		$meetingId = $_POST['meetingId'];
		$userRole = $_POST['userRole'];
		$getMeetingUserData=KarmaHelper::commonMeetingHappened($meetingId,$userRole);
		$changeStatus=KarmaHelper::updateMeetingStatus($meetingId,$userRole);
        return Redirect::to('meeting/'.$getUser->fname.'-'.$getUser->lname.'-'.$getMeetingUserData[0]->fname.'-'.$getMeetingUserData[0]->lname.'/'.$meetingId);
	}

	//Request happen from web
	public function meetingHideShowFromWeb(){
		$getUser=Auth::User();
		$meetingId = $_POST['meetingId'];
		$userRole = $_POST['userRole'];
		$getMeetingUserData=KarmaHelper::commonMeetingHappened($meetingId,$userRole);
		$changeStatus=KarmaHelper::updateMeetingStatus($meetingId,$userRole);
        return Redirect::to('meeting/'.$getUser->fname.'-'.$getUser->lname.'-'.$getMeetingUserData[0]->fname.'-'.$getMeetingUserData[0]->lname.'/'.$meetingId);
	}

	//Save message between receiver and giver
	public function meetingMessageSaveFromWeb(){
		$getUser=Auth::User();
		$userId=$getUser->id;
		$meetingId = $_POST['meetingId'];
		$userRole = $_POST['userRole'];
		$messageText= $_POST['message'];
		$getGiverReceiverData=DB::table('requests')->where('requests.id','=',$meetingId)->get();
        $getReceiverData=User::where('id','=',$getGiverReceiverData[0]->user_id_receiver)->first();
        $getGiverData=User::where('id','=',$getGiverReceiverData[0]->user_id_giver)->first();
		if($userRole=='Receiver'){
            //Add message in requests_messages table
            if(!empty($getGiverData)){
                $messageData=new Message;
                $messageData->request_id=$meetingId;
                $messageData->sender_id=$userId;
                $messageData->giver_id=$getGiverReceiverData[0]->user_id_giver;
                $messageData->receiver_id=$userId;
                $messageData->message_type='user';
                $messageData->messageText=$messageText;
                $messageData->save();
            }
        }else{
        	//Add message in requests_messages table
            if(!empty($getReceiverData)){
            	if($getGiverReceiverData[0]->status=='pending'){
                        DB::table('users_mykarma')->where('entry_id','=',$meetingId)->update(array('status' => 'responded','entry_updated_on' => Carbon::now()));
                        DB::table('requests')->where('id','=',$meetingId)->update(array('status' => 'responded'));
                }
                $messageData=new Message;
                $messageData->request_id=$meetingId;
                $messageData->sender_id=$userId;
                $messageData->giver_id=$userId;
                $messageData->receiver_id=$getGiverReceiverData[0]->user_id_receiver;
                $messageData->message_type='user';
                $messageData->messageText=$messageText;
                $messageData->save();
            }
        }
		$changeStatus=KarmaHelper::updateMeetingStatus($meetingId,$userRole);
        return Redirect::to('meeting/'.$getReceiverData->fname.'-'.$getReceiverData->lname.'-'.$getGiverData->fname.'-'.$getGiverData->lname.'/'.$meetingId);
	}


	//Save message in request_messages table between receiver and giver
	public function scriptForSavingData(){
		die();
		$getUserData=Auth::User();
		$getMeetingData=DB::table('requests')->whereIn('status',array('pending','archived','accepted','completed'))->get();
		foreach ($getMeetingData as $key => $meetingData) {
			$meetingId=$meetingData->id;
			$getUser=Meetingrequest::join('users','requests.user_id_receiver','=','users.id')->where('requests.id','=',$meetingId)->select('requests.user_id_giver','users.fname','users.lname')->first();
			$getGiverData=DB::table('requests')->join('users','requests.user_id_giver','=','users.id')->where('requests.id','=',$meetingId)->select('requests.user_id_giver','users.fname','users.lname')->get();
			$userId=$meetingData->user_id_receiver;
			if($meetingData->status=='pending'){
				 //Add message in requests_messages table
		        $messageData = new Message;
		        $messageData->request_id=$meetingData->id;
		        $messageData->sender_id=$userId;
		       	if($meetingData->user_id_giver !='' && $meetingData->user_id_giver !='null'){
		        	$messageData->giver_id=$meetingData->user_id_giver;	
		        	
		        }else{
		        	$messageData->giver_id=$meetingData->connection_id_giver;
		        }
		       	$messageData->receiver_id=$userId;
		        $messageText=$getUser->fname.' '.$getUser->lname.' has sent a meeting request.';
		        $messageData->messageText=$messageText;
		        $messageData->save();
		        $messageDataSecond = new Message;
		        $messageDataSecond->message_type='user';
		        $messageDataSecond->request_id=$meetingData->id;
		        $messageDataSecond->sender_id=$userId;
		        if(!empty($meetingData->user_id_giver)){
		        	$messageDataSecond->giver_id=$meetingData->user_id_giver;	
		        }else{
		        	$messageDataSecond->giver_id=$meetingData->connection_id_giver;
		        }
		        
		        $messageDataSecond->receiver_id=$userId;
		        $messageDataSecond->messageText=$meetingData->notes;
		        $messageDataSecond->save();
		        $gratitudeText='In gratitude, I will do the following - -';
		         // Add regular messages in request_messages table.
		        if( $meetingData->payitforward=='1'){
		            $payitforwardText="I'll pay it forward";
		        }else{
		             $payitforwardText="";
		        }
		        if( $meetingData->sendKarmaNote=='1'){
		           $sendKarmaNoteText="I'll send you a KarmaNote"; 
		        }else{
		          $sendKarmaNoteText="";  
		        }
	        if($meetingData->sendKarmaNote=='1'){
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
		        if($meetingData->payitforward=='1' || $meetingData->sendKarmaNote=='1'){
		            $messageDataSecond = new Message;
		            $messageDataSecond->message_type='user';
		            $messageDataSecond->request_id=$meetingId;
		            $messageDataSecond->sender_id=$meetingData->user_id_receiver;
		            if(!empty($meetingData->user_id_giver)){
		        		$messageDataSecond->giver_id=$meetingData->user_id_giver;	
			        }else{
			        	$messageDataSecond->giver_id=$meetingData->connection_id_giver;
			        }
		            $messageDataSecond->receiver_id=$meetingData->user_id_receiver;
		            $messageDataSecond->messageText=$messageGratituteText;
		            $messageDataSecond->save();
		        }
			}else if($meetingData->status=='archived'){
				
                //Add message in requests_messages table
                if(!empty($getGiverData)){
                	$messageData = new Message;
			        $messageData->request_id=$meetingData->id;
			        $messageData->sender_id=$meetingData->user_id_receiver;
			         if(!empty($meetingData->user_id_giver)){
		        		$messageData->giver_id=$meetingData->user_id_giver;	
			        }else{
			        	$messageData->giver_id=$meetingData->connection_id_giver;
			        }
			        $messageData->receiver_id=$meetingData->user_id_receiver;
			        $messageText=$getUser->fname.' '.$getUser->lname.' has sent a meeting request.';
			        $messageData->messageText=$messageText;
			        $messageData->save();
			        $messageDataSecond = new Message;
			        $messageDataSecond->message_type='user';
			        $messageDataSecond->request_id=$meetingData->id;
			        $messageDataSecond->sender_id=$meetingData->user_id_receiver;
			        if(!empty($meetingData->user_id_giver)){
		        		$messageDataSecond->giver_id=$meetingData->user_id_giver;	
			        }else{
			        	$messageDataSecond->giver_id=$meetingData->connection_id_giver;
			        }
			        $messageDataSecond->receiver_id=$meetingData->user_id_receiver;
			        $messageDataSecond->messageText=$meetingData->notes;
			        $messageDataSecond->save();
			        $gratitudeText='In gratitude, I will do the following - -';
			         // Add regular messages in request_messages table.
			        if( $meetingData->payitforward=='1'){
			            $payitforwardText="I'll pay it forward";
			        }else{
			             $payitforwardText="";
			        }
			        if( $meetingData->sendKarmaNote=='1'){
			           $sendKarmaNoteText="I'll send you a KarmaNote"; 
			        }else{
			          $sendKarmaNoteText="";  
			        }
		        if($meetingData->sendKarmaNote=='1'){
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
			        if($meetingData->payitforward=='1' || $meetingData->sendKarmaNote=='1' ){
			            $messageDataSecond = new Message;
			            $messageDataSecond->message_type='user';
			            $messageDataSecond->request_id=$meetingId;
			            $messageDataSecond->sender_id=$meetingData->user_id_receiver;
			            if(!empty($meetingData->user_id_giver)){
		        			$messageDataSecond->giver_id=$meetingData->user_id_giver;	
				        }else{
				        	$messageDataSecond->giver_id=$meetingData->connection_id_giver;
				        }
			            $messageDataSecond->receiver_id=$meetingData->user_id_receiver;
			            $messageDataSecond->messageText=$messageGratituteText;
			            $messageDataSecond->save();
			        }
                    $messageData = new Message;
                    $messageData->request_id=$meetingId;
                    $messageData->sender_id=$meetingData->user_id_giver;
                   	if(!empty($meetingData->user_id_giver)){
		        			$messageData->giver_id=$meetingData->user_id_giver;	
				    }else{
				        	$messageData->giver_id=$meetingData->connection_id_giver;
				    }
                    $messageData->receiver_id=$meetingData->user_id_receiver;
                    $messageText=$getGiverData[0]->fname.' '.$getGiverData[0]->lname.' is currently busy. Please contact another KarmaGiver.';
                    $messageData->messageText=$messageText;
                    $messageData->save();
                    
                }

			}else if($meetingData->status=='accepted'){
				DB::table('requests')->where('id','=',$meetingData->id)->update(array('status' => 'over'));
				 //Add message in requests_messages table
		        $messageData = new Message;
		        $messageData->request_id=$meetingData->id;
		        $messageData->sender_id=$userId;
		        if(!empty($meetingData->user_id_giver)){
		        	$messageData->giver_id=$meetingData->user_id_giver;	
			    }else{
			        $messageData->giver_id=$meetingData->connection_id_giver;
			    }
		        $messageData->receiver_id=$userId;
		        $messageText=$getUser->fname.' '.$getUser->lname.' has sent a meeting request.';
		        $messageData->messageText=$messageText;
		        $messageData->save();
		        $messageDataSecond = new Message;
		        $messageDataSecond->message_type='user';
		        $messageDataSecond->request_id=$meetingData->id;
		        $messageDataSecond->sender_id=$userId;
		        if(!empty($meetingData->user_id_giver)){
		        	$messageDataSecond->giver_id=$meetingData->user_id_giver;	
			    }else{
			        $messageDataSecond->giver_id=$meetingData->connection_id_giver;
			    }
		        $messageDataSecond->receiver_id=$userId;
		        $messageDataSecond->messageText=$meetingData->notes;
		        $messageDataSecond->save();
		        $gratitudeText='In gratitude, I will do the following - -';
		         // Add regular messages in request_messages table.
		        if( $meetingData->payitforward=='1'){
		            $payitforwardText="I'll pay it forward";
		        }else{
		             $payitforwardText="";
		        }
		        if( $meetingData->sendKarmaNote=='1'){
		           $sendKarmaNoteText="I'll send you a KarmaNote"; 
		        }else{
		          $sendKarmaNoteText="";  
		        }
	        if($meetingData->sendKarmaNote=='1'){
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
		        if($meetingData->payitforward=='1' || $meetingData->sendKarmaNote=='1'){
		            $messageDataSecond = new Message;
		            $messageDataSecond->message_type='user';
		            $messageDataSecond->request_id=$meetingId;
		            $messageDataSecond->sender_id=$userId;
		            if(!empty($meetingData->user_id_giver)){
		        		$messageDataSecond->giver_id=$meetingData->user_id_giver;	
				    }else{
				        $messageDataSecond->giver_id=$meetingData->connection_id_giver;
				    }
		            $messageDataSecond->receiver_id=$userId;
		            $messageDataSecond->messageText=$messageGratituteText;
		            $messageDataSecond->save();
		        }
		        
		      // Save schedule meeting data
		        $messageData = new Message;
                $messageData->request_id=$meetingId;
                $messageData->sender_id=$meetingData->user_id_giver;
                if(!empty($meetingData->user_id_giver)){
		        	$messageData->giver_id=$meetingData->user_id_giver;	
				}else{
				    $messageData->giver_id=$meetingData->connection_id_giver;
				}
				if(!empty($meetingData->user_id_giver)){
		        	$meetingUser=User::where('id','=',$meetingData->user_id_giver)->first();
				}else{
				    $meetingUser=Connection::where('id','=',$meetingData->connection_id_giver)->first();
				}
                $messageData->receiver_id=$meetingData->user_id_receiver;
               	$messageText=$meetingUser->fname.' '.$meetingUser->lname.' has scheduled a meeting.';    
                $messageData->messageText=$messageText;
                $messageData->save();
                if ($meetingData->meetingtimezone > '0'){
                    $meetingTimezoneText='+'.$meetingData->meetingtimezone;
                }else{
                    $meetingTimezoneText=$meetingData->meetingtimezone;
                }
                $meetingDuration=$meetingData->meetingduration;
                $meetingDateValue=date('M d, Y', strtotime($meetingData->meetingdatetime));
                $meetingTime=date('h:i', strtotime($meetingData->meetingdatetime));
                $meetingType=$meetingData->meetingtype;
                $meetingLocation=$meetingData->meetinglocation;
                $messageText='Meeting scheduled for '.$meetingDuration.' on '.$meetingDateValue.' at '.$meetingTime. ' GMT('.$meetingTimezoneText. ') '.$meetingType.': '.$meetingLocation.'.';
                $userMessageData = new Message;
                $userMessageData->message_type='user';
                $userMessageData->request_id=$meetingId;
                $userMessageData->sender_id=$meetingData->user_id_giver;
                $userMessageData->giver_id=$meetingData->user_id_giver;
                $userMessageData->receiver_id=$meetingData->user_id_receiver;
                $userMessageData->messageText=$messageText;
                $userMessageData->save();
			}else if($meetingData->status=='completed'){
				//Add message in requests_messages table
				if($meetingData->meetingduration !='' && $meetingData->meetingduration !='null')
				{


			        $messageData = new Message;
			        $messageData->request_id=$meetingData->id;
			        $messageData->sender_id=$userId;
			        if(!empty($meetingData->user_id_giver)){
			        	$messageData->giver_id=$meetingData->user_id_giver;	
				    }else{
				        $messageData->giver_id=$meetingData->connection_id_giver;
				    }
			        $messageData->receiver_id=$userId;
			        $messageText=$getUser->fname.' '.$getUser->lname.' has sent a meeting request.';
			        $messageData->messageText=$messageText;
			        $messageData->save();
			        if(!empty($meetingData->notes)){
			        	$messageDataSecond = new Message;
				        $messageDataSecond->message_type='user';
				        $messageDataSecond->request_id=$meetingData->id;
				        $messageDataSecond->sender_id=$userId;
				        if(!empty($meetingData->user_id_giver)){
				        	$messageDataSecond->giver_id=$meetingData->user_id_giver;	
					    }else{
					        $messageDataSecond->giver_id=$meetingData->connection_id_giver;
					    }
				        $messageDataSecond->receiver_id=$userId;
				        $messageDataSecond->messageText=$meetingData->notes;
				        $messageDataSecond->save();
					}
			        
			        $gratitudeText='In gratitude, I will do the following - -';
			         // Add regular messages in request_messages table.
			        if( $meetingData->payitforward=='1'){
			            $payitforwardText="I'll pay it forward";
			        }else{
			             $payitforwardText="";
			        }
			        if( $meetingData->sendKarmaNote=='1'){
			           $sendKarmaNoteText="I'll send you a KarmaNote"; 
			        }else{
			          $sendKarmaNoteText="";  
			        }
		        if($meetingData->sendKarmaNote=='1'){
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
			        if($meetingData->payitforward=='1' || $meetingData->sendKarmaNote=='1'){
			            $messageDataSecond = new Message;
			            $messageDataSecond->message_type='user';
			            $messageDataSecond->request_id=$meetingId;
			            $messageDataSecond->sender_id=$userId;
			            if(!empty($meetingData->user_id_giver)){
			        		$messageDataSecond->giver_id=$meetingData->user_id_giver;	
					    }else{
					        $messageDataSecond->giver_id=$meetingData->connection_id_giver;
					    }
			            $messageDataSecond->receiver_id=$userId;
			            $messageDataSecond->messageText=$messageGratituteText;
			            $messageDataSecond->save();
			        }
			        
			      // Save schedule meeting data
			        
			        $messageData = new Message;
	                $messageData->request_id=$meetingId;
	                $messageData->sender_id=$meetingData->user_id_giver;
	                if(!empty($meetingData->user_id_giver)){
			        	$messageData->giver_id=$meetingData->user_id_giver;	
					}else{
					    $messageData->giver_id=$meetingData->connection_id_giver;
					}
					if($meetingData->user_id_giver !='' || $meetingData->user_id_giver !='null'){
			        	$meetingUser=User::where('id','=',$meetingData->user_id_giver)->first();
					}else{
					    $meetingUser=Connection::where('id','=',$meetingData->connection_id_giver)->first();
					}
	                $messageData->receiver_id=$meetingData->user_id_receiver;
	               	if(!empty($meetingUser)){
	               		$messageText=$meetingUser->fname.' '.$meetingUser->lname.' has scheduled a meeting.';    
	                	$messageData->messageText=$messageText;
	               	}else{
	               		$messageText='User has scheduled a meeting.';    
	                	$messageData->messageText=$messageText;
	               	}
	               			
	               	$messageData->save();

	                if ($meetingData->meetingtimezone > '0'){
	                    $meetingTimezoneText='+'.$meetingData->meetingtimezone;
	                }else{
	                    $meetingTimezoneText=$meetingData->meetingtimezone;
	                }
	                $meetingDuration=$meetingData->meetingduration;
	                $meetingDateValue=date('M d, Y', strtotime($meetingData->meetingdatetime));
	                $meetingTime=date('h:i', strtotime($meetingData->meetingdatetime));
	                $meetingType=$meetingData->meetingtype;
	                $meetingLocation=$meetingData->meetinglocation;
	                $messageText='Meeting scheduled for '.$meetingDuration.' on '.$meetingDateValue.' at '.$meetingTime. ' GMT('.$meetingTimezoneText. ') '.$meetingType.': '.$meetingLocation.'.';
	                $userMessageData = new Message;
	                $userMessageData->message_type='user';
	                $userMessageData->request_id=$meetingId;
	                $userMessageData->sender_id=$meetingData->user_id_giver;
	                $userMessageData->giver_id=$meetingData->user_id_giver;
	                $userMessageData->receiver_id=$meetingData->user_id_receiver;
	                $userMessageData->messageText=$messageText;
	                $userMessageData->save();

	                //save Karma note messages
	                $messageData=new Message;
	                $userMessageData->message_type='system';
	                $messageData->request_id=$meetingId;
	                $messageData->sender_id=$meetingData->user_id_receiver;
	                if(!empty($meetingData->user_id_giver)){
			        	$messageData->giver_id=$meetingData->user_id_giver;	
					}else{
					    $messageData->giver_id=$meetingData->connection_id_giver;
					}
	                $messageData->receiver_id=$meetingData->user_id_receiver;
	                $messageText=$getUser->fname.' '.$getUser->lname.' has sent a KarmaNote.';
	                $messageData->messageText=$messageText;
	                $messageData->save();
	                if($meetingData->notes !='' && $meetingData->notes !='null'){
	                	$meetingMessageForNote=Karmanote::where('req_id','=',$meetingData->id)->first();
	                	$messageData=new Message;
	                	$messageData->message_type='user';
		                $messageData->request_id=$meetingId;
		                $messageData->sender_id=$meetingData->user_id_receiver;
		                if(!empty($meetingData->user_id_giver)){
				        	$messageData->giver_id=$meetingData->user_id_giver;	
						}else{
						    $messageData->giver_id=$meetingData->connection_id_giver;
						}
		                $messageData->receiver_id=$meetingData->user_id_receiver;
		                if(!empty($meetingMessageForNote)){
		                	$messageData->messageText=$meetingMessageForNote->details;	
		                }else{
		                	$messageData->messageText='There is no meeting detail.';
		                }
		                
		                $messageData->save();	
	                }
	             }else{
	             	$userMessageData = new Message;
	                $userMessageData->message_type='system';
	                $userMessageData->request_id=$meetingId;
	                $userMessageData->sender_id=$meetingData->user_id_receiver;
	                $userMessageData->giver_id=$meetingData->user_id_giver;
	                $userMessageData->receiver_id=$meetingData->user_id_receiver;
	               	$messageText=$getUser->fname.' '.$getUser->lname.' has sent a KarmaNote.';
	               	$userMessageData->messageText=$messageText;
	                $userMessageData->save();
	                $meetingMessageForNote=Karmanote::where('req_id','=',$meetingData->id)->first();
	                if(!empty($meetingMessageForNote)){
	                	$userMessageData = new Message;
	                $userMessageData->message_type='user';
	                $userMessageData->request_id=$meetingId;
	                $userMessageData->sender_id=$meetingData->user_id_receiver;
	                $userMessageData->giver_id=$meetingData->user_id_giver;
	                $userMessageData->receiver_id=$meetingData->user_id_receiver;
	                $userMessageData->messageText=$meetingMessageForNote->details;
	                $userMessageData->save();	
	                }
	                

	                 
	             }
                
			}
		}
		

	}
	//function for saving old karmanotes to karmafeed table.
	public function scriptForKarmaFeedSavingData(){
		$karmaNoteData=DB::table('karmanotes')->select('id','user_idreceiver','user_idgiver','connection_idgiver','created_at','updated_at')->get();
		die();
		//echo '<pre>';print_r($karmaNoteData);die;
		foreach ($karmaNoteData as $key => $value) {
			$checkStatus=Karmafeed::where('id_type','=',$value->id)->where('message_type','=','KarmaNote')->first();
			if(empty($checkStatus)){
				$saveFeedData=new Karmafeed;
				$saveFeedData->receiver_id=$value->user_idreceiver;
				if($value->user_idgiver !=null && $value->user_idgiver !=''){
					$saveFeedData->giver_id=$value->user_idgiver;	
					$saveFeedData->karmafeed_connection_id='0';
				}else{
					$saveFeedData->karmafeed_connection_id=$value->connection_idgiver;
					$saveFeedData->giver_id='0';	
				}
				$saveFeedData->message_type='KarmaNote';
				$saveFeedData->id_type=$value->id;
				$saveFeedData->created_at=$value->created_at;
				$saveFeedData->updated_at=$value->updated_at;
				$saveFeedData->save();
			}
		}
		echo 'success';exit;
	}


}


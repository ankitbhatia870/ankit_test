<?php

class NoteController extends \BaseController {

	public function __construct(Karmanote $Karmanote){
		$this->Karmanote = $Karmanote;
	}
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$CurrentUser = Auth::User();
		$CurrentUserSkills = Auth::User()->Tags;		
		$PendingRequest = $ReceivedRequest = $sentRequest = $ReceivedRequestUser = $sentRequestUser = '';
		$totalPendingRequest = $totalReceivedRequest = $totalSendRequest = 0;
		$PendingRequest = KarmaHelper::getPendingKarmaNotes($CurrentUser->id);
		$totalPendingRequest = count($PendingRequest);
		$ReceivedRequest = Auth::user()->KarmanoteReceiver()->orderBy('created_at', 'DESC')->get();
		$sentRequest  = Auth::user()->KarmanoteGiver()->orderBy('created_at', 'DESC')->get();
		if(!empty($ReceivedRequest)){
			foreach ($ReceivedRequest->toArray() as $received) {
				$received['user_id_giver'] = User::find($received['user_id_giver'])->toArray();
				$received['user_id_receiver'] = User::find($received['user_idreceiver'])->toArray();
				$received['status'] = $received['statusgiver'];
				$received['meetingId'] = $received['req_id'];
				$meetingDetail  = KarmaHelper::getMeetingDetail($received['req_id']);
				$received['meetingBody'] = $meetingDetail['notes'];
				$ReceivedRequestUser[]  = $received;
			}
			$totalReceivedRequest = count($ReceivedRequest);
		}
		if(!empty($sentRequest)){
			foreach ($sentRequest->toArray() as $sent) {
				$sentReq['receiver_detail'] = User::find($sent['user_idreceiver'])->toArray();
				if(!empty($sent['user_idgiver'])){
					$sentReq['giver_detail'] = User::find($sent['user_idgiver'])->toArray();
				}else{
					$sentReq['giver_detail'] = Connection::find($sent['connection_idgiver'])->toArray();
				}
				$sentReq['status'] = $sent['statusreceiver'];
				$sentReq['karmanotedetail'] = $sent['details'];
				$sentReq['created_at'] = $sent['created_at'];
				$sentReq['meetingId'] = $sent['req_id'];
				$meetingSentDetail  = KarmaHelper::getMeetingDetail($sent['req_id']);
				$sentReq['meetingBody'] = $meetingSentDetail['notes'];
				$sentRequestUser[] = $sentReq;
			}
			$totalSendRequest = count($sentRequest);
		}
		//echo "<pre>";print_r($sentRequestUser);echo "</pre>";
		//die;
		//echo "<pre>";print_r(count($totalSentRequest));echo "</pre>";die;
		//$sentRequest  = KarmaHelper::getSentKarmaNotes($CurrentUser->id);
		return View::make('KarmaNotes',array('pageTitle' => 'KarmaNotes | KarmaCircles','CurrentUser' => $CurrentUser,'PendingRequest'=>$PendingRequest, 'ReceivedRequest'=> $ReceivedRequestUser, 'sentRequest'=> $sentRequestUser,'countPen'=>'0','countRec'=>'0','countSent'=>'0', 'totalSentRequest'=>$totalSendRequest, 'totalPendingRequest'=>$totalPendingRequest, 'totalReceivedRequest'=>$totalReceivedRequest));
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function sendKarmaNote($MeetingId,$Receiver_Giver)
	{
		$CurrentUser = Auth::User();
		$receiverWR=""; $userTags = $userSkills = array();
		$meetingDetail = Meetingrequest::find($MeetingId);	
		$MettingActualCurrentTimeWithZone = KarmaHelper::calculateTime($meetingDetail->meetingtimezone);
		
			if(!empty($meetingDetail) || $meetingDetail->user_id_receiver == Auth::user()->id || $meetingDetail->user_id_giver == Auth::user()->id){
					$receiverDetail = User::find($meetingDetail->user_id_receiver);
				if(empty($meetingDetail->user_id_giver)){
					$giverDetail = 	Connection::find($meetingDetail->connection_id_giver);
				}
				else{
					$giverDetail = 	User::find($meetingDetail->user_id_giver);
				}	
				$CheckReceiver_Giver = 	$receiverDetail->fname.'-'.$receiverDetail->lname.'_'.$giverDetail->fname.'-'.$giverDetail->lname;	
				$userTags = User::find($giverDetail->id)->Tags->take(15)->toArray();

				foreach ($userTags as $key => $value) {
					$skills['name'] = $value['name'];
					$skills['id'] = $value['id'];
				} 
				if(empty($userSkills)){
					$userSkills = "";
					$userSkills = DB::table('tags')->get();
					foreach ($userSkills as $key => $value) {
						$countSkill = DB::table('users_tags')->Where('tag_id','=',$value->id)->count();
						$userSkills[$key]->UserCount = $countSkill;
					}
					$userSkills = array_values(array_sort($userSkills, function($value)
					{
					return $value->UserCount;
					})); 
					$userSkills = array_reverse($userSkills); 
				
				} 

 
				//echo "<pre>+++++";print_r($userSkills);echo "</pre>++++++++";die;
				if (!empty($meetingDetail->receiverWR)) {
					$receiverWR = explode(',', $meetingDetail->receiverWR);
				}
				if($CheckReceiver_Giver != $Receiver_Giver)	{
					return Redirect::to('404');
				}		
				if($meetingDetail->status == 'confirmed' || $meetingDetail->status == 'responded' || $meetingDetail->status == 'completed' || $meetingDetail->status == 'happened' || $meetingDetail->status == 'over'){
					//echo '<pre>';print_r($userSkills);die;
					return View::make('send_karma_note',array('pageTitle' => 'KarmaNotes','userSkills' => $userSkills, 'CurrentUser' => $CurrentUser, 'meetingDetail'=>$meetingDetail, 'receiverDetail'=>$receiverDetail, 'giverDetail'=>$giverDetail, 'Skills'=>$userSkills));
				}
			}
			else
			{
				return Redirect::to('404');
			}
		
	}

	/**
	 	Function name: sendNonKarmaNote()
		Created by : Evon
		Created on : 04/10/2014
	*/
	public function SendDirectkarmaNote($userType, $Giver)
	{ 
		//print_r($userType);
		$CurrentUser = $ConnectionDetail = $userDetail = $userDetailArr = '';
		$CurrentUser = Auth::User();
		$checkMsgLimit = KarmaHelper::CheckUserLinkedMgsLimit(); 
		if($userType == 'NoKarma'){
			
			$ConnectionDetail = Auth::User()->connections()->where('connections.id','=',$Giver)->get()->first();
			
		}else{
			$userDetail = User::find($Giver);
			$userDetailArr = $userDetail->toArray();
		}
		
		if($userType == 'NoKarma'){
			$skills = "";
			$skills = DB::table('tags')->get();
			foreach ($skills as $key => $value) {
				$countSkill = DB::table('users_tags')->Where('tag_id','=',$value->id)->count();
				$skills[$key]->UserCount = $countSkill;
			}
			$skills = array_values(array_sort($skills, function($value)
			{
			    return $value->UserCount;
			}));
			$skills = array_reverse($skills); 
		}
		else{
			$skills = $userskills = "";
			$userskills = DB::table('tags')
					->join('users_tags', 'tags.id', '=', 'users_tags.tag_id')
					->select(array('tags.name','tags.id','users_tags.user_id'))
		            ->where('users_tags.user_id','=',$userDetail->id)
		            ->orderByRaw("RANd()")->take(10) 
		            ->get();
		    $skills = $userskills;
		    if(empty($skills))
		    {
		    	$skills = DB::table('tags')->get();
				foreach ($skills as $key => $value) {
					$countSkill = DB::table('users_tags')->Where('tag_id','=',$value->id)->count();
					$skills[$key]->UserCount = $countSkill;
				}
				$skills = array_values(array_sort($skills, function($value)
				{
				    return $value->UserCount;
				}));
				$skills = array_reverse($skills); 	
			    }
		}

		//echo "<pre>===========";print_r($skills);echo "</pre>======";
		if (!empty($Giver)){
			return View::make('send_nonkarma_note',array('pageTitle' => 'KarmaNotes','checkMsgLimit' => $checkMsgLimit,'CurrentUser' => $CurrentUser, 'ConnectionDetail'=>$ConnectionDetail, 'UserDetail'=>$userDetailArr,'Skills'=>$skills,'userType'=>$userType));
		}else{
			return Redirect::to('404');	
		}
	}

	/**
		Function name: saveKarmanote()
		Created by : Evon
		Created on : 04/10/2014
	**/
	public function saveKarmanote(){
		$CurrentUser = Auth::User();
		$meetingId = $receiverId = $giverId = $note = $receiverName = $giverName = $ShareKarmaNote = '';
		$note = array();

		if(!empty(Input::get('ShareKarmaNote')))	$ShareKarmaNote = Input::get('ShareKarmaNote');
		if(!empty(Input::get('meetingId')))	$meetingId = Input::get('meetingId');
		if(!empty(Input::get('receiverId'))) $receiverId = Input::get('receiverId');
		if(!empty(Input::get('introducerId'))) $introducerId = Input::get('introducerId');
		if(!empty(Input::get('giverId'))) $giverId = Input::get('giverId');
		if(!empty(Input::get('skillTags'))) $skills = Input::get('skillTags');
		if(!empty(Input::get('details')))	$note = Input::get('details');
		if(!empty(Input::get('receiverName'))) $receiverName = Input::get('receiverName');
		if(!empty(Input::get('giverName'))) $giverName = Input::get('giverName');
		
		//print_r($ShareKarmaNote);die; 

		if (!$this->Karmanote->isValid(Input::all())) {
			return Redirect::back()->withInput()->withErrors($this->Karmanote->errors);
		}else{
				$feedType='KarmaNote';
				$karmaNote = new Karmanote;
				$karmaNote ->req_id 				= $meetingId;
				$karmaNote ->user_idgiver 			= $giverId;
				$karmaNote ->user_idreceiver 		= $receiverId;
				$karmaNote ->details 				= strip_tags($note);
				if(!empty($skills)){
					$karmaNote ->skills 				= implode(',', $skills);
				}
				else{
					$karmaNote ->skills 				= '';
				}				
				$karmaNote ->viewstatus 			= 0;
				if($ShareKarmaNote == 1)
				$karmaNote->share_onlinkedin 			= $ShareKarmaNote;  
				$karmaNote->created_at 				= KarmaHelper::currentDate();
				$karmaNote->save();
				$karmaNoteId = $karmaNote->id;
				//$karmaNoteMessage=$karmaNote->details;
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
				$Meetingrequest = Meetingrequest::find($meetingId);
				$Meetingrequest->status 			= 'completed';
				$Meetingrequest->save();
				$messageData=new Message;
                $messageData->request_id=$meetingId;
                $messageData->sender_id=$CurrentUser->id;
                $messageData->giver_id=$giverId;
                $messageData->receiver_id=$CurrentUser->id;
                $messageText=$CurrentUser->fname.' '.$CurrentUser->lname.' has sent a KarmaNote.';
                $messageData->messageText=$messageText;
                $messageData->save();
                $messageData=new Message;
				$messageData->message_type='user';
                $messageData->request_id=$meetingId;
                $messageData->sender_id=$CurrentUser->id;
                $messageData->giver_id=$giverId;
                $messageData->receiver_id=$CurrentUser->id;
                $messageTextOfKarmaNote=$karmaNoteMessage;
                $messageData->messageText=$messageTextOfKarmaNote;
                $messageData->save();
                DB::table('users_mykarma')->where('entry_id','=',$meetingId)->update(array('status' => 'completed','entry_updated_on' => Carbon::now()));
                DB::table('requests')->where('id','=',$meetingId)->update(array('status' => 'completed'));
                $userRole='Receiver';
                $changeStatus=KarmaHelper::updateMeetingStatus($meetingId,$userRole);
				KarmaHelper::updateKarmaScore($giverId,$receiverId);
				//KarmaHelper::storeKarmacirclesRecord($giverId,$receiverId);
				//KarmaHelper::storeKarmacirclesRelation($giverId,$receiverId);
				KarmaHelper::storeKarmacirclesfeed($giverId,$receiverId,$feedType,$karmaNoteId);
				if(!empty($introducerId))
				KarmaHelper::updateIntroducerKarmaScore($introducerId);  

				if(!empty($ShareKarmaNote)){
					$receiverDetail = User::find($receiverId);
					//MessageHelper::shareOnLinkedin($receiverDetail->token,'asdasd');die();
					Queue::push('MessageSender', array('type' =>'9','user_id_giver' => $giverId,'user_id_receiver' => $receiverId,'meetingId'=> $meetingId));
				}
				//$sendLinkedinMessage =  MessageHelper::triggerEmailAndMessage($giverId,$receiverId,'5',$meetingId);
				Queue::push('MessageSender', array('type' =>'5','user_id_giver' => $giverId,'user_id_receiver' => $receiverId,'meetingId'=> $meetingId));
				return Redirect::to('meeting/'.$receiverName.'-'.$giverName.'/'.$meetingId);
		}
	}



	/**
	*	Function name: updateKarmaNoteStatus()
	*	Created by : Evon
	*	Created on : 04/10/2014
	*	Arguments  : $noteId, $status
	**/
	public function updateKarmaNoteStatus(){
		
		$CurrentUser = Auth::user();
		$user_id = $noteId = $status = '';
		$user_id = Input::get('user_id');
		$noteId = Input::get('noteId');
		$status = Input::get('status');
		$publicPage = Input::get('publicPage');
		if($user_id && $noteId && $status){
			if($user_id == 'receiver'){
				$updateColumn = 'statusreceiver';
			}else{
				$updateColumn = 'statusgiver';
			}
			if($status == 'Hide'){
				$updateStatus = 'hidden';
			}elseif($status == 'Show'){
				$updateStatus = 'visible';
			}
			$KarmaNotes = Karmanote::find($noteId);			
			$KarmaNotes->$updateColumn = $updateStatus;
			$KarmaNotes->save();
			$KarmaNotes = $KarmaNotes->toArray();
			
			$Meetingrequest = Meetingrequest::find($KarmaNotes['req_id']);
			$receiverDetail = User::find($Meetingrequest->user_id_receiver)->toArray();
			//echo "<pre>";print_r($Meetingrequest);echo "</pre>";die;
			if(!empty($Meetingrequest->user_id_giver)){
				$giverDetail    = User::find($Meetingrequest->user_id_giver)->toArray();
			}
			else{
					$giverDetail    = Connection::find($Meetingrequest->connection_id_giver)->toArray();
			}
			
			if($publicPage == '1'){
				return Redirect::to('profile/'.strtolower($CurrentUser->fname.'-'.$CurrentUser->lname).'/'.$CurrentUser->id);	
			}
			else{
				return Redirect::to('KarmaNotes');	
			}
			
		}else{
			return Redirect::to('404');	
		}
	}
}

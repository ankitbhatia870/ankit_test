<?php
namespace API;
use Validator;
use Request;
use Response;
use KarmaIntro;use Mykarma;use Carbon;use Message;use Connection;use Meetingrequest;use URL;use User;use Userstag;use Usersgroup;use Question;use Tag;Use KarmaHelper;use Karmafeed;use Group;use Karmanote;use Questionwillingtohelp; use Queue;//Models
use Illuminate\Support\Facades\DB; //To queries directly

class MeetingApiController extends \BaseController {

    /**
     * Save a meeting request.
     *
     * @return Response
     */
    public function saveMeetingRequest()
    {
        $rules = Validator::make(Request::all(), [
                    'accessToken' => 'required',
                    'userId' => 'required',
                    'karmameetingDetail' => 'required',
                    'payitforward'=>'required',
                    'sendKarmaNote'=> 'required',
                    'buyyoucoffee'=> 'required',
                    'giverId' =>'required'
                    
        ]);
        if ($rules->fails()) {
            $this->status = 'Validation fails';
            $this->message = 'arguments missing';
            
        } else {
            $accessToken = Request::get('accessToken');
            $userId = Request::get('userId');
            $giverId = Request::get('giverId');
            $karmameetingDetail = Request::get('karmameetingDetail');
            $payitforward = Request::get('payitforward');
            $sendKarmaNote = Request::get('sendKarmaNote');
            $buyyoucoffee = Request::get('buyyoucoffee');
            $getUser = User::where('id', '=', $userId)->where('site_token','=',$accessToken)->first();
            $connectionId=Connection::where('user_id','=',$userId)->first();
            if(!empty($getUser)){
                if($userId != $giverId){
                    $meetingRequest = new Meetingrequest;
                    $meetingRequest ->user_id_receiver              = $userId;
                    $meetingRequest ->user_id_giver                 = $giverId;
                    if(!empty($connectionId)){
                         $meetingRequest ->connection_id_giver      = $connectionId->id;
                    }
                    if($payitforward=='yes'){
                        $payitforward='1';
                    }else{
                        $payitforward='0';
                    }
                    $meetingRequest ->payitforward                  = $payitforward ;
                    if($sendKarmaNote=='yes'){
                        $sendKarmaNote='1';
                    }else{
                        $sendKarmaNote='0';
                    }
                    $meetingRequest ->sendKarmaNote                 = $sendKarmaNote ;
                    if($buyyoucoffee=='yes'){
                        $buyyoucoffee='1';
                    }else{
                        $buyyoucoffee='0';
                    }
                    $meetingRequest ->buyyoucoffee                  = $buyyoucoffee ;
                    $meetingRequest ->notes                         = $karmameetingDetail;
                    $meetingRequest ->status                        = 'pending';
                    $meetingRequest->save();
                    $meetingId = $meetingRequest->id;
                    if($meetingId !='' && $meetingId !='null'){
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
                                if($getIntroData->intro_message != '' && $getIntroData->intro_message != 'null'){
                                    $messageDataSecond = new Message;
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
                        $saveMeetingDataForMyKarma=KarmaHelper::saveMeetingDataForMyKarma($meetingId,$userId,$giverId);
                    }
                    
                    $userRole='Receiver';
                    Queue::push('MessageSender', array('type' =>'1','user_id_giver' => $giverId,'user_id_receiver' => $userId,'meetingId'=> $meetingId));
                    
                    $this->status = 'Success';
                    $this->message = 'Karma Meeting Request has saved.';
                    return Response::json(array('status'=>$this->status,
                                    'message'=>$this->message,
                    ));

            }else{
                    $this->status = 'Success';
                    $this->message = 'Cannot send Karma Meeting Request to yourself.';
                    return Response::json(array('status'=>$this->status,
                                    'message'=>$this->message,
                    ));
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

     /**
     * Set reminder for meeting request.
     *
     * @return Response
     */
    public function meetingReminder()
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
            $entryId = Request::get('meetingId');
            $userRole = Request::get('userRole');
            $getUser = User::where('id', '=', $userId)->where('site_token','=',$accessToken)->first();
            if(!empty($getUser)){
                $yesterday = Carbon::now()->subMinutes(5);
                if($userRole=='Receiver'){
                    $checkStatus=Mykarma::where('entry_id','=',$entryId)->where('entry_updated_on','<',$yesterday)->where('users_role','=','Receiver')->count();    
                }else if($userRole=='Giver'){
                    $checkStatus=Mykarma::where('entry_id','=',$entryId)->where('entry_updated_on','<',$yesterday)->where('users_role','=','Giver')->count();    
                }else{
                    $checkStatus='0';
                }
                if($checkStatus > 0){
                    $getGiverData=Meetingrequest::where('id','=',$entryId)->first();
                    $saveMessageData = new Message;
                    $saveMessageData->request_id=$entryId;
                    $saveMessageData->sender_id=$userId;
                    $saveMessageData->giver_id=$getGiverData->user_id_giver;
                    $saveMessageData->receiver_id=$userId;
                    $messageText=$getUser->fname.' '.$getUser->lname.' has sent a reminder.';
                    $saveMessageData->messageText=$messageText;
                    $saveMessageData->save();
                    $updatedStatus=KarmaHelper::updateMeetingStatus($entryId,$userRole); 
                    $meetingDataResult=KarmaHelper::meetingData($accessToken,$userId,$entryId,$userRole);
                    $meetingUserId=$meetingDataResult['meetingUserId'];
                    $userProfilePic=$meetingDataResult['userProfilePic'];
                    $meetingStatusText=$meetingDataResult['meetingStatusText'];
                    $meetingStatus=$meetingDataResult['meetingStatus'];
                    $meetingTrailData=$meetingDataResult['meetingTrailData'];
                    $this->status = 'success';
                    $this->message = 'Reminder is updated.';
                    return Response::json(array('status'=>$this->status,
                                    'message'=>$this->message,
                                    'meetingStatus'=>$meetingStatus,
                                    'meetingTrailData'=>$meetingTrailData,
                                    'meetingUserId'=> $meetingUserId,
                                    'userProfilePic'=> $userProfilePic,
                                    'meetingStatusText'=>$meetingStatusText                    
                    ));
                }else{
                    $this->status = 'failure';
                    $this->message = 'You cant send more than one reminder at single day.';
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


      /**
     * Sedule meeting request using App.
     *
     * @return Response
     */
    public function acceptedMeetingRequest()
    {
        $rules = Validator::make(Request::all(), [
                    'accessToken' => 'required',
                    'userId' => 'required',
                    'meetingId' => 'required',
                    'meetingDate'=>'required',
                    'meetingTime'=> 'required',
                    'meetingTimezone' =>'required',
                    'meetingTimezonetext' =>'required',
                    'meetingDuration'=> 'required',
                    'meetingType'=>'required',
                    'meetingLocation' =>'required'
        ]);
        if ($rules->fails()) {
            $this->status = 'Validation fails';
            $this->message = 'arguments missing';
            
        } else {
            $accessToken = Request::get('accessToken');
            $userId = Request::get('userId');
            $meetingId = Request::get('meetingId');
            $meetingDate = Request::get('meetingDate');
            $meetingTime = Request::get('meetingTime');
            $meetingTimezone = Request::get('meetingTimezone');
            $meetingTimezoneText = Request::get('meetingTimezonetext');
            $meetingDuration = Request::get('meetingDuration');
            $meetingType = Request::get('meetingType');
            $meetingLocation = Request::get('meetingLocation');
            $meetingReply = Request::get('meetingReply');
            $getUser = User::where('id', '=', $userId)->where('site_token','=',$accessToken)->first();
            if(!empty($getUser)){
                $checkStatus=DB::table('requests')->where('id','=',$meetingId)->where('status','=','scheduled')->count();
                $meetingDetail = Meetingrequest::find($meetingId);
                $meetingDetail->meetingduration = $meetingDuration;
                $meetingDetail->meetingtimezonetext =$meetingTimezoneText;
                $meetingDetail->meetingtimezone =$meetingTimezone;
                $dateTime = $meetingDate." ".$meetingTime;
                $meetingDateTime = date('Y-m-d H:i:s',strtotime($dateTime));
                $meetingDetail->meetingdatetime = $meetingDateTime;
                $meetingDetail->meetinglocation = $meetingLocation;
                $meetingDetail->meetingtimezone = $meetingTimezone;
                $meetingDetail->meetingtype     = $meetingType;
                $meetingDetail->req_updatedate  = KarmaHelper::currentDate();
                $meetingDetail->reply           = $meetingReply;
                $meetingDetail->status          = 'scheduled';
                $meetingDetail->save();
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
                $meetingDateValue=date('M d, Y', strtotime($meetingDate));
                $messageText='Meeting scheduled for '.$meetingDuration.' on '.$meetingDateValue.' at '.$meetingTime. ' GMT('.$meetingTimezoneText. ') '.$meetingType.': '.$meetingLocation.'.';
                $userMessageData = new Message;
                $userMessageData->message_type='user';
                $userMessageData->request_id=$meetingId;
                $userMessageData->sender_id=$userId;
                $userMessageData->giver_id=$userId;
                $userMessageData->receiver_id=$getReceiverData[0]->user_id_receiver;
                $userMessageData->messageText=$messageText;
                $userMessageData->save();
                if(isset($meetingReply) && $meetingReply != 'null'){
                    //echo '<pre>';print_r($meetingReply);die;
                    $messageDataForReplay = new Message;
                    $messageDataForReplay->message_type='user';
                    $messageDataForReplay->request_id=$meetingId;
                    $messageDataForReplay->sender_id=$userId;
                    $messageDataForReplay->giver_id=$userId;
                    $messageDataForReplay->receiver_id=$getReceiverData[0]->user_id_receiver;
                    $messageText=$meetingReply;
                    $messageDataForReplay->messageText=$messageText;
                    $messageDataForReplay->save();
                }
                $userRole='Giver';
                $updatedStatus=KarmaHelper::updateMeetingStatus($meetingId,$userRole);
                $this->status = 'Success';
                if($checkStatus < 1){
                   $this->message = 'Meeting is scheduled successfully.';
                }else{
                    $this->message = 'Meeting is rescheduled successfully.';
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

     /**
     * Set Archive request for meeting.
     *
     * @return Response
     */
    public function meetingArchive()
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
            $entryId = Request::get('meetingId');
            $userRole = Request::get('userRole');
            $getUser = User::where('id', '=', $userId)->where('site_token','=',$accessToken)->first();
            if(!empty($getUser)){
                $getGiverData=DB::table('requests')->join('users','requests.user_id_giver','=','users.id')->where('requests.id','=',$entryId)->select('requests.user_id_giver','users.fname','users.lname')->get();
                //Add message in requests_messages table
                if(!empty($getGiverData)){
                    $messageData = new Message;
                    $messageData->request_id=$entryId;
                    $messageData->sender_id=$getGiverData[0]->user_id_giver;
                    $messageData->giver_id=$getGiverData[0]->user_id_giver;
                    $messageData->receiver_id=$userId;
                    $messageText=$getGiverData[0]->fname.' '.$getGiverData[0]->lname.' is currently busy. Please contact another KarmaGiver.';
                    $messageData->messageText=$messageText;
                    $messageData->save();
                    DB::table('users_mykarma')->where('entry_id','=',$entryId)->update(array('status' => 'archived','entry_updated_on' => Carbon::now()));
                    DB::table('requests')->where('id','=',$entryId)->update(array('status' => 'archived'));    
                    $changeStatus=KarmaHelper::updateMeetingStatus($entryId,$userRole);
                    $meetingDataResult=KarmaHelper::meetingData($accessToken,$userId,$entryId,$userRole);
                    $meetingUserId=$meetingDataResult['meetingUserId'];
                    $userProfilePic=$meetingDataResult['userProfilePic'];
                    $meetingStatusText=$meetingDataResult['meetingStatusText'];
                    $meetingStatus=$meetingDataResult['meetingStatus'];
                    $meetingTrailData=$meetingDataResult['meetingTrailData'];
                    $this->status = 'success';
                    $this->message = 'Meeting is archived successfully.';
                    return Response::json(array('status'=>$this->status,
                                    'message'=>$this->message,
                                    'meetingStatus'=>$meetingStatus,
                                    'meetingTrailData'=>$meetingTrailData,
                                    'meetingUserId'=> $meetingUserId,
                                    'userProfilePic'=> $userProfilePic,
                                    'meetingStatusText'=>$meetingStatusText                  
                    ));
                }else{
                    $this->status = 'failure';
                    $this->message = 'There is no such meeting id.';    
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

      /**
     * Set Cancel request for meeting..
     *
     * @return Response
     */
    public function meetingCancel()
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
            $entryId = Request::get('meetingId');
            $userRole = Request::get('userRole');
            $getUser = User::where('id', '=', $userId)->where('site_token','=',$accessToken)->first();
            if(!empty($getUser)){
                $saveMessageForMeetingCancel=KarmaHelper::saveMessageForMeetingCancel($entryId,$userId,$userRole);
                $changeStatus=KarmaHelper::updateMeetingStatus($entryId,$userRole);
                    if($userRole=='Receiver'){
                        DB::table('requests')->where('id','=',$entryId)->update(array('status' => 'cancelled'));        
                    }else{
                        DB::table('requests')->where('id','=',$entryId)->update(array('status' => 'responded'));        
                    }
                    
                    $meetingDataResult=KarmaHelper::meetingData($accessToken,$userId,$entryId,$userRole);
                    $meetingUserId=$meetingDataResult['meetingUserId'];
                    $userProfilePic=$meetingDataResult['userProfilePic'];
                    $meetingStatusText=$meetingDataResult['meetingStatusText'];
                    $meetingStatus=$meetingDataResult['meetingStatus'];
                    $meetingTrailData=$meetingDataResult['meetingTrailData'];
                    
                    $this->status = 'success';
                    $this->message = 'Meeting is cancelled successfully.';
                    return Response::json(array('status'=>$this->status,
                                    'message'=>$this->message,
                                    'meetingStatus'=>$meetingStatus,
                                    'meetingTrailData'=>$meetingTrailData,
                                    'meetingUserId'=> $meetingUserId,
                                    'userProfilePic'=> $userProfilePic,
                                    'meetingStatusText'=>$meetingStatusText                    
                    ));
            }else{
                $this->status = 'failure';
                $this->message = 'You are not a login user.';
            }
        }
        return Response::json(array('status'=>$this->status,
                                    'message'=>$this->message,
                                        
        ));
    }

     /**
     * Set Confirm meeting in request table.
     *
     * @return Response
     */
    public function meetingConfirm()
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
                    $getMeetingMessageData=KarmaHelper::commonConfirmMeeting($meetingId,$userId);
                    $changeStatus=KarmaHelper::updateMeetingStatus($meetingId,$userRole);
                    $meetingDataResult=KarmaHelper::meetingData($accessToken,$userId,$meetingId,$userRole);
                    $meetingUserId=$meetingDataResult['meetingUserId'];
                    $userProfilePic=$meetingDataResult['userProfilePic'];
                    $meetingStatusText=$meetingDataResult['meetingStatusText'];
                    $meetingStatus=$meetingDataResult['meetingStatus'];
                    $meetingData=$meetingDataResult['meetingData'];
                    $meetingTrailData=$meetingDataResult['meetingTrailData'];
                    $this->status = 'success';
                    $this->message = 'Meeting is confirmed successfully.';
                    return Response::json(array('status'=>$this->status,
                                    'message'=>$this->message,
                                    'meetingStatus'=>$meetingStatus,
                                    'meetingData'=>$meetingData,
                                    'meetingTrailData'=>$meetingTrailData,
                                    'meetingUserId'=> $meetingUserId,
                                    'userProfilePic'=> $userProfilePic,
                                    'meetingStatusText'=>$meetingStatusText

                    ));
            }else{
                $this->status = 'failure';
                $this->message = 'You are not a login user.';
            }
        }
        return Response::json(array('status'=>$this->status,
                                    'message'=>$this->message,
                                        
        ));
    }

     /**
     * Set meeting request new time in request table.
     *
     * @return Response
     */
    public function meetingRequestNewTime()
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
                    $getRequestNewTimeData=KarmaHelper::commonMeetingRequestNewTime($meetingId,$userId);
                    $this->status = 'success';
                    $this->message = 'Meeting new time has updated successfully.';
                    $meetingDataResult=KarmaHelper::meetingData($accessToken,$userId,$meetingId,$userRole);
                    $meetingUserId=$meetingDataResult['meetingUserId'];
                    $userProfilePic=$meetingDataResult['userProfilePic'];
                    $meetingStatusText=$meetingDataResult['meetingStatusText'];
                    $meetingStatus=$meetingDataResult['meetingStatus'];
                    $meetingTrailData=$meetingDataResult['meetingTrailData'];
                    return Response::json(array('status'=>$this->status,
                                    'message'=>$this->message,
                                    'meetingStatus'=>$meetingStatus,
                                    'meetingTrailData'=>$meetingTrailData,
                                    'meetingUserId'=> $meetingUserId,
                                    'userProfilePic'=> $userProfilePic,
                                    'meetingStatusText'=>$meetingStatusText

                    ));
            }else{
                $this->status = 'failure';
                $this->message = 'You are not a login user.';
            }
        }
        return Response::json(array('status'=>$this->status,
                                    'message'=>$this->message,
                                        
        ));
    }

      /**
     * Save messages of meeting detail page.
     *
     * @return Response
     */
    public function meetingMessageSave()
    {
        $rules = Validator::make(Request::all(), [
                    'accessToken' => 'required',
                    'userId' => 'required',
                    'meetingId' => 'required',
                    'userRole' => 'required',
                    'messageText' => 'required'
        ]);
        if ($rules->fails()) {
            $this->status = 'Validation fails';
            $this->message = 'arguments missing';
            
        } else {
            $accessToken = Request::get('accessToken');
            $userId = Request::get('userId');
            $meetingId = Request::get('meetingId');
            $userRole = Request::get('userRole');
            $messageText = Request::get('messageText');
            $getUser = User::where('id', '=', $userId)->where('site_token','=',$accessToken)->first();
            if(!empty($getUser)){
                if($userRole=='Receiver'){
                    $getGiverData=DB::table('requests')->where('requests.id','=',$meetingId)->get();
                    //Add message in requests_messages table
                    if(!empty($getGiverData)){
                        $messageData=new Message;
                        $messageData->request_id=$meetingId;
                        $messageData->sender_id=$userId;
                        $messageData->giver_id=$getGiverData[0]->user_id_giver;
                        $messageData->receiver_id=$userId;
                        $messageData->message_type='user';
                        $messageData->messageText=$messageText;
                        $messageData->save();
                        $meetingTrailData=DB::table('requests_messages')->where('request_id','=',$meetingId)->orderBy('created_at','ASC')->get();
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
                                $meetingMessageData[$key]['date']=date('Y-m-d H:i:s', strtotime($value->created_at));
                            }   
                        }
                       
                        $changeStatus=KarmaHelper::updateMeetingStatus($meetingId,$userRole);
                        $this->status = 'success';
                        $this->message = 'Message is successfully saved.';
                        return Response::json(array('status'=>$this->status,
                                        'message'=>$this->message,
                                        'meetingTrailData'=>$meetingMessageData
                        ));
                    }else{
                        $this->status = 'failure';
                        $this->message = 'Please enter correct meeting id.';
                    }    
                }else if($userRole=='Giver'){
                    $getReceiverData=DB::table('requests')->where('requests.id','=',$meetingId)->get();
                    //Add message in requests_messages table
                    if($getReceiverData[0]->status=='pending'){
                        DB::table('users_mykarma')->where('entry_id','=',$meetingId)->update(array('status' => 'responded','entry_updated_on' => Carbon::now()));
                        DB::table('requests')->where('id','=',$meetingId)->update(array('status' => 'responded'));
                    }
                    if(!empty($getReceiverData)){
                        $messageData=new Message;
                        $messageData->request_id=$meetingId;
                        $messageData->sender_id=$userId;
                        $messageData->giver_id=$userId;
                        $messageData->receiver_id=$getReceiverData[0]->user_id_receiver;
                        $messageData->message_type='user';
                        $messageData->messageText=$messageText;
                        $messageData->save();
                        $meetingTrailData=DB::table('requests_messages')->where('request_id','=',$meetingId)->orderBy('created_at','ASC')->get();
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
                                $meetingMessageData[$key]['date']=date('Y-m-d H:i:s', strtotime($value->created_at));
                            }   
                        }
                        $meetingData=Meetingrequest::find($meetingId);
                            if($meetingData->status=='pending'){
                                DB::table('users_mykarma')->where('entry_id','=',$meetingId)->update(array('status' => 'responded'));
                                DB::table('requests')->where('id','=',$meetingId)->update(array('status' => 'responded'));    
                            }
                        $changeStatus=KarmaHelper::updateMeetingStatus($meetingId,$userRole);
                        $this->status = 'success';
                        $this->message = 'Message is successfully saved.';
                        return Response::json(array('status'=>$this->status,
                                        'message'=>$this->message,
                                        'meetingTrailData'=>$meetingMessageData
                        ));
                    }else{
                        $this->status = 'failure';
                        $this->message = 'Please enter correct meeting id.';
                    }    
                }else{
                    $this->status = 'failure';
                    $this->message = 'Please enter correct user role.';
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

    /**
     * Check meeting status.
     *
     * @return Response
     */
    public function getMeetingStatus()
    {
        $rules = Validator::make(Request::all(), [
                    'accessToken' => 'required',
                    'userId' => 'required',
                    'otherUserId' => 'required'
        ]);
        if ($rules->fails()) {
            $this->status = 'Validation fails';
            $this->message = 'arguments missing';
            
        } else {
            $accessToken = Request::get('accessToken');
            $userId = Request::get('userId');
            $otherUserId = Request::get('otherUserId');
            $getUser = User::where('id', '=', $userId)->where('site_token','=',$accessToken)->first();
            if(!empty($getUser)){
               $checkMeetingStatus = DB::select(DB::raw("select user_id_receiver,user_id_giver,id,status from requests where (user_id_receiver=".$userId." OR user_id_receiver=".$otherUserId.") AND (user_id_giver=".$userId." OR user_id_giver=".$otherUserId." ) AND status NOT IN ('completed','archived','cancelled') order by created_at DESC limit 1"));  
               if(!empty($checkMeetingStatus)){
                    foreach ($checkMeetingStatus as $key => $value) {
                        $userData=User::find($otherUserId);
                        $meetingData['meetingRunning']='yes';
                        $meetingData['karmaId']=$value->id;
                        $meetingData['meetingStatus']=$value->status;
                        $meetingData['userId']=$userData->id;
                        $meetingData['fname']=$userData->fname;
                        $meetingData['lname']=$userData->lname;
                        $meetingData['piclink']=$userData->piclink;
                        if($value->user_id_receiver==$otherUserId){
                            $meetingData['userRole']='Giver';
                        }else{
                            $meetingData['userRole']='Receiver';
                        }
                    }
               }else{
                    $meetingData['meetingRunning']='no';
                    $meetingData['karmaId']='null';
                    $meetingData['userId']=$otherUserId;
                    $meetingData['fname']='null';
                    $meetingData['lname']='null';
                    $meetingData['piclink']='null';
                    $meetingData['userRole']='null';
                    $meetingData['userRole']='null';
               }
               $this->status = 'Success';
               $this->message = 'Check meeting already happen or not';
                return Response::json(array('status'=>$this->status,
                                        'message'=>$this->message,
                                        'meetingData'=>$meetingData
                ));
            }else{
                $this->status = 'failure';
                $this->message = 'You are not a login user.';
            }
        }
        return Response::json(array('status'=>$this->status,
                                    'message'=>$this->message,
                                        
        ));
    }

      /**
     * Set Archive request for meeting.
     *
     * @return Response
     */
    public function reportAbuse()
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
            $entryId = Request::get('meetingId');
            $userRole = Request::get('userRole');
            $getUser = User::where('id', '=', $userId)->where('site_token','=',$accessToken)->first();
            if(!empty($getUser)){
                $getGiverData=DB::table('requests')->join('users','requests.user_id_giver','=','users.id')->where('requests.id','=',$entryId)->select('requests.user_id_giver','users.fname','users.lname')->get();
                //Add message in requests_messages table
                if(!empty($getGiverData)){
                    $messageData = new Message;
                    $messageData->request_id=$entryId;
                    $messageData->sender_id=$getGiverData[0]->user_id_giver;
                    $messageData->giver_id=$getGiverData[0]->user_id_giver;
                    $messageData->receiver_id=$userId;
                    $messageText=$getGiverData[0]->fname.' '.$getGiverData[0]->lname.' is currently busy. Please contact another KarmaGiver.';
                    $messageData->messageText=$messageText;
                    $messageData->save();
                    if($userRole=='Giver'){
                        DB::table('users_mykarma')->where('entry_id','=',$entryId)->update(array('status' => 'spam','entry_updated_on' => Carbon::now()));    
                    }else{
                        DB::table('users_mykarma')->where('entry_id','=',$entryId)->update(array('status' => 'spam','entry_updated_on' => Carbon::now()));
                    }
                    
                    DB::table('requests')->where('id','=',$entryId)->update(array('status' => 'spam'));    
                     $changeStatus=KarmaHelper::updateMeetingStatus($entryId,$userRole);
                    $meetingDataResult=KarmaHelper::meetingData($accessToken,$userId,$entryId,$userRole);
                    $meetingUserId=$meetingDataResult['meetingUserId'];
                    $userProfilePic=$meetingDataResult['userProfilePic'];
                    $meetingStatusText=$meetingDataResult['meetingStatusText'];
                    $meetingStatus=$meetingDataResult['meetingStatus'];
                    $meetingTrailData=$meetingDataResult['meetingTrailData'];
                    $this->status = 'success';
                    $this->message = 'User has spam meeting.';
                    return Response::json(array('status'=>$this->status,
                                    'message'=>$this->message,
                                    'meetingStatus'=>$meetingStatus,
                                    'meetingTrailData'=>$meetingTrailData,
                                    'meetingUserId'=> $meetingUserId,
                                    'userProfilePic'=> $userProfilePic,
                                    'meetingStatusText'=>$meetingStatusText                  
                    ));
                }else{
                    $this->status = 'failure';
                    $this->message = 'There is no such meeting id.';    
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



}

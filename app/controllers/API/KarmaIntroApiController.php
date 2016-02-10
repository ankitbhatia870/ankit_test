<?php
namespace API;
use Validator;
use Request;
use Response;
use KarmaIntro;use Mykarma;use Carbon;use Message;use Connection;use Meetingrequest;use URL;use User;use Userstag;use Usersgroup;use Question;use Tag;Use KarmaHelper;use Karmafeed;use Group;use Karmanote;use Questionwillingtohelp; use Queue;//Models
use Illuminate\Support\Facades\DB; //To queries directly

class KarmaIntroApiController extends \BaseController {

	//initiate karma intro.
	public function karmaIntroInitiate()
    {
        $rules = Validator::make(Request::all(), [
                    'accessToken' => 'required',
                    'userId' => 'required',
                    'receiverId' => 'required',
                    'giverId' => 'required'
        ]);
        if ($rules->fails()) {
            $this->status = 'Validation fails';
            $this->message = 'arguments missing';
            
        } else {
            $accessToken = Request::get('accessToken');
            $userId = Request::get('userId');
            $receiverId = Request::get('receiverId');
            $giverId = Request::get('giverId');
            $comment = Request::get('comments');
            $getUser = User::where('id', '=', $userId)->where('site_token','=',$accessToken)->first();
            if(!empty($getUser)){
                $getReceiverData=User::find($receiverId);
                $getGiverData=User::find($giverId);
                //Add  user data in users_ table
                $getIntroStatus=KarmaIntro::where('intro_giver_id','=',$giverId)->where('intro_receiver_id','=',$receiverId)->first();
                if(empty($getIntroStatus)){
                	$site_url=URL::to('/');
                	$userData = new KarmaIntro;
                    $userData->intro_giver_id=$giverId;
                    $userData->intro_giver_name=$getGiverData->fname.' '.$getGiverData->lname;
                    $userData->intro_giver_piclink=$getGiverData->piclink;
                    $userData->intro_giver_profile_link=$site_url.'/profile/'.$getGiverData->fname.'-'.$getGiverData->lname.'/'.$getGiverData->id;
                    $userData->intro_receiver_id=$receiverId;
                    $userData->intro_receiver_name=$getReceiverData->fname.' '.$getReceiverData->lname;
                    $userData->intro_receiver_piclink=$getReceiverData->piclink;
                    $userData->intro_receiver_profile_link=$site_url.'/profile/'.$getReceiverData->fname.'-'.$getReceiverData->lname.'/'.$getReceiverData->id;
                    $userData->intro_introducer_id=$userId;
                    $userData->intro_introducer_name=$getUser->fname.' '.$getUser->lname;
                    $userData->intro_introducer_piclink=$getUser->piclink;
                    $userData->intro_introducer_profile_link=$site_url.'/profile/'.$getUser->fname.'-'.$getUser->lname.'/'.$getUser->id;
                    if($comment !='' && $comment !='null'){
                    	$userData->intro_message=$comment;	
                    }else{
                    	$userData->intro_message='null';
                    }
                    $userData->request_id='null';
                    $userData->meeting_status='introInitiated';
                    $userData->karmanote_link='null';
                    $userData->created_at=Carbon::now();
                    $userData->updated_at=Carbon::now();
                    $userData->save();
                    $this->status = 'success';
                    $this->message = 'You has succefully introduce both.';
                    return Response::json(array('status'=>$this->status,
                                    'message'=>$this->message,
                    ));
                }else{
                	$this->status = 'failure';
                	$this->message = 'Introduction cant be sent more than once.';
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

    //initiate karma intro.
	public function karmaIntroFeeds()
    {
        $rules = Validator::make(Request::all(), [
                    'accessToken' => 'required',
                    'userId' => 'required',
        ]);
        if ($rules->fails()) {
            $this->status = 'Validation fails';
            $this->message = 'arguments missing';
            
        } else {
            $accessToken = Request::get('accessToken');
            $userId = Request::get('userId');
            $getUser = User::where('id', '=', $userId)->where('site_token','=',$accessToken)->first();
            if(!empty($getUser)){
                $getIntroAllData=KarmaIntro::where('intro_giver_id','=',$userId)->orWhere('intro_receiver_id','=',$userId)->orWhere('intro_introducer_id','=',$userId)->orderBy('created_at','DESC')->get();
                if(empty($getIntroAllData)){
                	$getIntroData=array();
                }else{
                    foreach ($getIntroAllData as $key => $value) {
                        $getIntroData[$key]['id']=$value->id;
                        $getIntroData[$key]['intro_giver_id']=$value->intro_giver_id;
                        $getIntroData[$key]['intro_giver_name']=$value->intro_giver_name;
                        $getIntroData[$key]['intro_giver_piclink']=$value->intro_giver_piclink;
                        $getIntroData[$key]['intro_giver_profile_link']=$value->intro_giver_profile_link;
                        $getIntroData[$key]['intro_receiver_id']=$value->intro_receiver_id;
                        $getIntroData[$key]['intro_receiver_name']=$value->intro_receiver_name;
                        $getIntroData[$key]['intro_receiver_piclink']=$value->intro_receiver_piclink;
                        $getIntroData[$key]['intro_receiver_profile_link']=$value->intro_receiver_profile_link;
                        $getIntroData[$key]['intro_introducer_id']=$value->intro_introducer_id;
                        $getIntroData[$key]['intro_introducer_name']=$value->intro_introducer_name;
                        $getIntroData[$key]['intro_introducer_piclink']=$value->intro_introducer_piclink;
                        $getIntroData[$key]['intro_introducer_profile_link']=$value->intro_introducer_profile_link;
                        $getIntroData[$key]['intro_message']=$value->intro_message;
                        $getIntroData[$key]['request_id']=$value->request_id;
                        if(trim($value->meeting_status) == 'introInitiated'){
                            $getIntroData[$key]['meeting_status']='Intro-Initiated';
                        } else {
                            $getIntroData[$key]['meeting_status']=$value->meeting_status;
                        }
                        $getIntroData[$key]['karmanote_link']=$value->karmanote_link;
                        $getIntroData[$key]['created_at']=date('Y-m-d H:i:s',strtotime($value->created_at));
                        $getIntroData[$key]['updated_at']=date('Y-m-d H:i:s',strtotime($value->updated_at));
                    }
                }
                if(empty($getIntroData)){
                    $getIntroData=array();
                }
                $this->status = 'success';
                $this->message = 'You has succefully introduce both.';
                return Response::json(array('status'=>$this->status,
                                'message'=>$this->message,
                                'introFeeds'=>$getIntroData
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

    // update karma intro status
	public function updateKarmaIntroMeetingStatus()
    {
        $rules = Validator::make(Request::all(), [
                    'accessToken' => 'required',
                    'userId' => 'required',
        ]);
        if ($rules->fails()) {
            $this->status = 'Validation fails';
            $this->message = 'arguments missing';
            
        } else {
            $site_url=URL::to('/');
            $accessToken = Request::get('accessToken');
            $userId = Request::get('userId');
            $getUser = User::where('id', '=', $userId)->where('site_token','=',$accessToken)->first();
            if(!empty($getUser)){
                $getUpdatedIntroData=KarmaIntro::where('intro_giver_id','=',$userId)->orWhere('intro_receiver_id','=',$userId)->orWhere('intro_introducer_id','=',$userId)->orderBy('created_at','DESC')->get();
                if(empty($getUpdatedIntroData)){
                	$getUpdatedIntroData=array();
                }else{
                    foreach ($getUpdatedIntroData as $key => $value) {
                        $getUpdatedIntroData[$key]['id']=$value->id;
                        $getUpdatedIntroData[$key]['intro_giver_id']=$value->intro_giver_id;
                        $getUpdatedIntroData[$key]['intro_giver_name']=$value->intro_giver_name;
                        $getUpdatedIntroData[$key]['intro_giver_piclink']=$value->intro_giver_piclink;
                        $getUpdatedIntroData[$key]['intro_giver_profile_link']=$value->intro_giver_profile_link;
                        $getUpdatedIntroData[$key]['intro_receiver_id']=$value->intro_receiver_id;
                        $getUpdatedIntroData[$key]['intro_receiver_name']=$value->intro_receiver_name;
                        $getUpdatedIntroData[$key]['intro_receiver_piclink']=$value->intro_receiver_piclink;
                        $getUpdatedIntroData[$key]['intro_receiver_profile_link']=$value->intro_receiver_profile_link;
                        $getUpdatedIntroData[$key]['intro_introducer_id']=$value->intro_introducer_id;
                        $getUpdatedIntroData[$key]['intro_introducer_name']=$value->intro_introducer_name;
                        $getUpdatedIntroData[$key]['intro_introducer_piclink']=$value->intro_introducer_piclink;
                        $getUpdatedIntroData[$key]['intro_introducer_profile_link']=$value->intro_introducer_profile_link;
                        $getUpdatedIntroData[$key]['intro_message']=$value->intro_message;
                        $getUpdatedIntroData[$key]['request_id']=$value->request_id;
                        if(trim($value->meeting_status) == 'introInitiated'){
                            $getUpdatedIntroData[$key]['meeting_status']='Intro-Initiated';
                        } else {
                            $getUpdatedIntroData[$key]['meeting_status']=$value->meeting_status;
                        }
                        $getUpdatedIntroData[$key]['karmanote_link']=$value->karmanote_link;
                        $getUpdatedIntroData[$key]['created_at']=date('Y-m-d H:i:s',strtotime($value->created_at));
                        $getUpdatedIntroData[$key]['updated_at']=date('Y-m-d H:i:s',strtotime($value->updated_at));
                        $requestId=$value->request_id;
                        $getMeetingDetail=Meetingrequest::where('id','=',$requestId)->select('status','user_id_receiver','user_id_giver','id')->first();
                        $getIntroDetail=KarmaIntro::where('id','=',$value->id)->first();
                        if(!empty($getMeetingDetail)){
                            if($getMeetingDetail->status=='completed'){
                                $getIntroDetail->meeting_status=$getMeetingDetail->status;
                                 $getKarmaNoteId=Karmanote::where('req_id','=',$getMeetingDetail->id)->first();
                                 $getIntroDetail->karmanote_link=$getKarmaNoteId->id;
                                 $getIntroDetail->save();
                            }else{
                                $getIntroDetail->meeting_status=$getMeetingDetail->status;
                                $getIntroDetail->save();
                            }    
                        }
                        
                    }

                }
                $this->status = 'success';
                $this->message = 'You has succefully introduce both.';
                return Response::json(array('status'=>$this->status,
                                'message'=>$this->message,
                                'introFeeds'=>$getUpdatedIntroData
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
	


}

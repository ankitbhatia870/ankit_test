<?php
namespace API;
use Validator;
use Request;
use Response;
use Meetingrequest;use Connection;use URL;use User;use Userstag;use Usersgroup;use Question;use Tag;Use KarmaHelper;use Karmafeed;use Group;use Karmanote;use Questionwillingtohelp; //Models
use Illuminate\Support\Facades\DB; //To queries directly

class UserApiController extends \BaseController {

  /*
   * Function to see other profile of particular user.
   *
   * @return In Response we will send status{success or failure}.
  */
  
  public function otherProfileShow() {
    $validator = Validator::make(Request::all(), [
                    'accessToken' => 'required',
                    'userId' => 'required',
                    'otherProfileUserId'=> 'required'
        ]);
        if ($validator->fails()) {
            #display error if validation fails                                            $this->status = 'Validation fails';
            $this->message = 'arguments missing';
        } else {
          $siteUrl=URL::to('/');
          $accesstoken = Request::get('accessToken');
            $userId = Request::get('userId');
            $otherUserId = Request::get('otherProfileUserId');
            $profileUserDetail  = User::find($otherUserId);
            if(!empty($profileUserDetail)){
              $meetingRequestPending = $profileUserDetail->Giver()->Where('status','=','pending')->count();  
            }else{
              $meetingRequestPending ='0';
            }
            
                  
            //fetching other profile data.
            $commonConnectionDataCount=KarmaHelper::commonConnection($userId,$otherUserId);
            $getCommonConnectionData=array_unique($commonConnectionDataCount);
            $commonConnectionData=count($getCommonConnectionData);
            $getOtherUserProfileDataResult = DB::table('users')
                        ->leftjoin('questions', 'users.id', '=', 'questions.user_id')
                        ->select(array('questions.id as questionId','users.id','fname','lname','piclink','headline','industry','summary','location','karmascore','comments','noofmeetingspm','causesupported','urlcause','donationtypeforcause','linkedinurl','questions.subject','questions.description','questions.queryStatus','questions.skills','questions.created_at As QueryPostedDate'))
                        ->where('users.id','=', $otherUserId)
                        ->orderby('questions.created_at')
                        ->first();
            if(!empty($getOtherUserProfileDataResult)){
                $getOtherUserProfileData['piclink']=$getOtherUserProfileDataResult->piclink;
                $getOtherUserProfileData['fname']=$getOtherUserProfileDataResult->fname;
                $getOtherUserProfileData['lname']=$getOtherUserProfileDataResult->lname;
                $getOtherUserProfileData['noofmeetingspm']=$getOtherUserProfileDataResult->noofmeetingspm;
                $getOtherUserProfileData['industry']=$getOtherUserProfileDataResult->industry;
                $getOtherUserProfileData['summary']=$getOtherUserProfileDataResult->summary;
                $getOtherUserProfileData['comments']=$getOtherUserProfileDataResult->comments;
                $getOtherUserProfileData['causesupported']=$getOtherUserProfileDataResult->causesupported;
                $getOtherUserProfileData['karmascore']=$getOtherUserProfileDataResult->karmascore;
                $getOtherUserProfileData['location']=$getOtherUserProfileDataResult->location;
                $getOtherUserProfileData['headline']=$getOtherUserProfileDataResult->headline;
                $getOtherUserProfileData['urlcause']=$getOtherUserProfileDataResult->urlcause;
                $getOtherUserProfileData['donationtypeforcause']=$getOtherUserProfileDataResult->donationtypeforcause;
                $getOtherUserProfileData['linkedinurl']=$getOtherUserProfileDataResult->linkedinurl;
                $getOtherUserProfileData['subject']=$getOtherUserProfileDataResult->subject;
                $getOtherUserProfileData['description']=$getOtherUserProfileDataResult->description;
                $getOtherUserProfileData['queryStatus']=$getOtherUserProfileDataResult->queryStatus;
                $getOtherUserProfileData['queryId']=$getOtherUserProfileDataResult->questionId;
                $getOtherUserProfileData['skills']=$getOtherUserProfileDataResult->skills;
                $getOtherUserProfileData['QueryPostedDate']=$getOtherUserProfileDataResult->QueryPostedDate;
                $dynamic_name=$getOtherUserProfileDataResult->fname.'-'.$getOtherUserProfileDataResult->lname.'/'.$getOtherUserProfileDataResult->id;
                $public_profile_url=$siteUrl.'/profile/'.$dynamic_name;
                $getOtherUserProfileData['publicUrl']=$public_profile_url;
                $getOtherUserProfileData['connectionCount']=$commonConnectionData;
                $getOtherUserProfileData['meetingRequestPending']=$meetingRequestPending;
                $getOtherUserProfileData['noofmeetingspm']=$getOtherUserProfileDataResult->noofmeetingspm;

            }
        
            //fetching other user query.
            $getQuestion=Question::where('user_id', '=', $otherUserId)->select('id','questions.subject','questions.description','questions.queryStatus','questions.skills','questions.created_at')->orderby('questions.created_at','DESC')->first();
            //display skill of other user.
            if(!empty($getQuestion)){
              if(!empty($getQuestion->skills)){
                $getSkill =  KarmaHelper::getSkillsname($getQuestion->skills);    
              }else{
                $getSkill=array();
              }
            }else{
              $getSkill=array();
            }
            
            $getUser = User::where('id', '=', $userId)->where('site_token','=',$accesstoken)->select('fname','lname','piclink','headline','industry','summary','location','karmascore','comments','noofmeetingspm','causesupported','urlcause','donationtypeforcause')->first();
            
        $karmaReceiver = DB::table('karmanotes')
                        ->join('users', 'karmanotes.user_idreceiver', '=', 'users.id')
                        ->select('users.fname','users.lname','users.piclink','karmanotes.user_idreceiver As receiverId')
                        ->whereIn('karmanotes.user_idreceiver',$getCommonConnectionData)
                        ->where('karmanotes.user_idgiver','=', $otherUserId)
                        ->distinct('receiver_id')->get();
        $karmaGiver = DB::table('karmanotes')
                        ->join('users', 'karmanotes.user_idgiver', '=', 'users.id')
                        ->select('users.fname','users.lname','users.piclink','karmanotes.user_idgiver As giverId')
                        ->whereIn('karmanotes.user_idgiver',$getCommonConnectionData)
                        ->where('karmanotes.user_idreceiver','=', $otherUserId)
                        ->distinct('receiver_id')->get();
        
        if(!empty($getUser)){
              if(!empty($profileUserDetail)){
                $profileUserSkills  = $profileUserDetail->Tags;
                $users_group = $profileUserDetail->Groups;
                $this->status = 'success';
                //$this->message = '';
                return Response::json(
                  array('status'=>$this->status,
                  'UserData'=>$getOtherUserProfileData,
                  'SkillData'=>$profileUserSkills,
                  'UserGroup'=>$users_group,
                  'KarmaReceiver'=>$karmaReceiver,
                  'KarmaGiver'=>$karmaGiver,
                  'QuerySkill'=>$getSkill,
              ));

              }else{
                $this->status = 'failure';
                $this->message= 'There is no such user.';
              }
            }else{
              $this->status = 'failure';
              $this->message= 'You are not a login user.';
            }

    }
    return Response::json(array(
      'status'=>$this->status,
      'message'=>$this->message
    ));
  }

    /**
   * Function to see other profile trail of particular user.
   *
   * @return In Response we will send status{success or failure}.
   */

   public function commonTrail() {
    $validator = Validator::make(Request::all(), [
                    'accessToken' => 'required',
                    'userId' => 'required',
                    'feedId'=> 'required',
                    'trailType' => 'required',
                    'offset' => 'required',
                    'filterFeed'=> 'required',
                    'usersFilter'=>'required'
        ]);
        if ($validator->fails()) {
            #display error if validation fails                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                       
            $this->status = 'Validation fails';
            $this->message = 'arguments missing';
        } else {

          $accesstoken = Request::get('accessToken');
          $userId = Request::get('userId');
          $otherUserId = Request::get('feedId');
          $offset=Request::get('offset');
          $trailType=Request::get('trailType');
          $filterFeed=Request::get('filterFeed');
          $usersFilter=Request::get('usersFilter');
          $start=0+$offset*5;
          $perpage=5;
          $siteUrl=URL::to('/');
          if($trailType=='GroupTrail'){
            $groupId=Usersgroup::select('user_id')->where('group_id','=',$otherUserId)->get();
            $groupId=$groupId->toArray();
            $karmacircleFeed = Karmafeed::select('message_type As type','id_type','receiver_id','giver_id','updated_at')->whereIn('receiver_id',$groupId)->orWhereIn('giver_id',$groupId)->orderby('updated_at','DESC')->skip($start)->take($perpage)->get(); 
          }else if($trailType=='SingleProfileTrail'){
            $karmacircleFeed = Karmafeed::select('message_type As type','id_type','receiver_id','giver_id','updated_at')->where('receiver_id','=',$otherUserId)->orWhere('giver_id','=',$otherUserId)->orderby('updated_at','DESC')->skip($start)->take($perpage)->get(); 
          }else if($trailType=='All'){
            if($filterFeed=='All' && $usersFilter=='All'){
              $karmacircleFeed = Karmafeed::select('message_type As type','id_type','receiver_id','giver_id','updated_at')->orderby('updated_at','DESC')->skip($start)->take($perpage)->get();  
            }else if($filterFeed=='Query' && $usersFilter=='All'){
              $karmacircleFeed = Karmafeed::select('message_type As type','id_type','receiver_id','giver_id','updated_at')->where('message_type','=','KarmaQuery')->orderby('updated_at','DESC')->skip($start)->take($perpage)->get();  
            }else if($filterFeed=='All' && $usersFilter=='KarmaNetwork'){
              $userKarmaNetwork=KarmaHelper::getKarmaNetwork($userId);
              $karmacircleFeed = Karmafeed::select('message_type As type','id_type','receiver_id','giver_id','updated_at')->whereIn('receiver_id',$userKarmaNetwork)->orWhereIn('giver_id',$userKarmaNetwork)->orderby('updated_at','DESC')->skip($start)->take($perpage)->get();  
            }else if($filterFeed=='Query' && $usersFilter=='KarmaNetwork'){
              $userKarmaNetwork=KarmaHelper::getKarmaNetwork($userId);
              $karmacircleFeed = Karmafeed::select('message_type As type','id_type','receiver_id','giver_id','updated_at')->where('message_type','=','KarmaQuery')->whereIn('receiver_id',$userKarmaNetwork)->orderby('updated_at','DESC')->skip($start)->take($perpage)->get();  
            }else{
              $this->status = 'failure';
              $this->message = 'Please proive correct userFilter type & $feedType.';
              return Response::json(array(
                'status'=>$this->status,
                'message'=>$this->message
              ));  
            }
            $getKcuserResult = KarmaHelper::getRandomKcuser($userId);
            $getKcuser=array();
            foreach ($getKcuserResult as $key => $value) {
              $commonConnectionDataCount=KarmaHelper::commonConnection($userId,$value->id);
              $getCommonConnectionData=array_unique($commonConnectionDataCount);
              $commonConnectionDataCount=count($getCommonConnectionData);
              $getKcuser[$key]['name']=$value->userstatus;
              $getKcuser[$key]['id']=$value->id;
              $getKcuser[$key]['fname']=$value->fname;
              $getKcuser[$key]['lname']=$value->lname;
              $getKcuser[$key]['karmascore']=$value->karmascore;
              $getKcuser[$key]['location']=$value->location;
              $getKcuser[$key]['headline']=$value->headline;
              $getKcuser[$key]['piclink']=$value->piclink;
              $getKcuser[$key]['linkedinurl']=$value->linkedinurl;
              $getKcuser[$key]['email']=$value->email;
              $getKcuser[$key]['connectionCount']=$commonConnectionDataCount;
            }
          }else{
            $this->status = 'failure';
            $this->message = 'Please proive correct trail type.';
            return Response::json(array(
              'status'=>$this->status,
              'message'=>$this->message
            ));
          }
            
            //$karmacircleFeedCount = Karmafeed::select('message_type As type','id_type','receiver_id','giver_id','updated_at')->where('receiver_id','=',$otherUserId)->orWhere('giver_id','=',$otherUserId)->count();
            $checkUserExist = User::select('message_type As type','id_type','receiver_id','giver_id','updated_at')->where('id','=',$userId)->where('site_token','=',$accesstoken)->count();
      if($checkUserExist > 0){
        if(!empty($karmacircleFeed)){
          $groupFeed=array(); 
          $karmaNoteFeed=array();
          $queryFeed=array();
          $offerHelpToFeed=array();
          $giverData=array();
          $karmaFeed=array();
          foreach ($karmacircleFeed as $karmaFeed) {

            $receiverId=$karmaFeed->receiver_id;
            $giverId=$karmaFeed->giver_id;
            $karmaFeedMessage=$karmaFeed->type;
            $karmaFeedId=$karmaFeed->id_type;
            $karmaFeedDate=$karmaFeed->updated_at;
            //$receiverId != NULL or $receiverId !=""

            if(!empty($giverId)){
            //if($receiverId != NULL || $receiverId !=""){
              $giverData=User::where('id','=',$giverId)->select('fname As receiverFirstName','lname As receiverLastName','piclink As receiverPic','headline As receiverHeadline')->first(); 
            }else{
              $giverData=Connection::where('id','=',$giverId)->select('fname As receiverFirstName','lname As receiverLastName','piclink As receiverPic','headline As receiverHeadline')->first(); 
            }
            if(!empty($receiverId)){
            //if($receiverId != NULL || $receiverId !=""){
              $receiverData=User::where('id','=',$receiverId)->select('fname As receiverFirstName','lname As receiverLastName','piclink As receiverPic','headline As receiverHeadline')->first(); 
              $fname=$receiverData->receiverFirstName;
                $lname=$receiverData->receiverLastName;
                $karmaNoteFullReceiverName=$fname.'-'.$lname;
            }
            if($karmaFeedMessage=='KarmaNote' && $trailType=='SingleProfileTrail'){
              $getKarmaNoteDetails = Karmanote::where('id','=',$karmaFeedId)
              ->select(array('id','user_idreceiver','user_idgiver','connection_idgiver','karmanotes.details As description','req_id','statusgiver','statusreceiver'))
              ->first();
              
            //if($giverId != ''){
              $giverData=User::where('id','=',$getKarmaNoteDetails->user_idgiver)->select('fname As giverFirstName','lname As giverLastName','piclink As giverPic','headline As giverHeadline')->first(); 
              if(empty($giverData)){
        $giverData=Connection::where('id','=',$getKarmaNoteDetails->connection_idgiver)->select('fname As giverFirstName','lname As giverLastName')->first();
              }
              $fname=$giverData->giverFirstName;
                $lname=$giverData->giverLastName;
                $karmaNoteFullGiverName=$fname.'-'.$lname;
              
              if(!empty($getKarmaNoteDetails)){
                if($getKarmaNoteDetails->user_idreceiver==$otherUserId && $getKarmaNoteDetails->statusreceiver=='visible' || $getKarmaNoteDetails->user_idgiver==$otherUserId && $getKarmaNoteDetails->statusgiver=='visible'){
                  $dynamicMeeting=$karmaNoteFullReceiverName.'-'.$karmaNoteFullGiverName.'/'.$getKarmaNoteDetails->req_id;
                  $meetingUrl['publicUrl']=$siteUrl.'/meeting/'.$dynamicMeeting;
          
                 $karmaNoteFeed[] = array_merge($karmaFeed->toArray(),$getKarmaNoteDetails->toArray(), $giverData->toArray(),$receiverData->toArray(),$meetingUrl); 
                }
                  
              }
            }else if($karmaFeedMessage=='KarmaNote' && $trailType!='SingleProfileTrail'){
              $getKarmaNoteDetails = Karmanote::where('id','=',$karmaFeedId)
                    ->select(array('karmanotes.details As description','req_id','statusgiver','statusreceiver','user_idgiver','connection_idgiver'))
                          ->first();
              
              if($getKarmaNoteDetails['user_idgiver'] !=null){
        
                $giverData=User::where('id','=',$getKarmaNoteDetails['user_idgiver'])->select('fname As giverFirstName','lname As giverLastName','piclink As giverPic','headline As giverHeadline')->first(); 
              }else{
                $giverData=Connection::where('id','=',$getKarmaNoteDetails['connection_idgiver'])->select('fname As giverFirstName','lname As giverLastName')->first();
              }
            
                $fname=$giverData['giverFirstName'];
                $lname=$giverData['giverLastName'];
                $karmaNoteFullGiverName=$fname.'-'.$lname;
              if(!empty($getKarmaNoteDetails)){
                $dynamicMeeting=$karmaNoteFullReceiverName.'-'.$karmaNoteFullGiverName.'/'.$getKarmaNoteDetails->req_id;
                $meetingUrl['publicUrl']=$siteUrl.'/meeting/'.$dynamicMeeting;
                $karmaNoteFeed[] = array_merge($karmaFeed->toArray(),$getKarmaNoteDetails->toArray(), $giverData->toArray(),$receiverData->toArray(),$meetingUrl);  
              }
              
            }else if($karmaFeedMessage=='KarmaGroup'){
              $getGroupDetails = Group::where('id','=',$karmaFeedId)
                    ->select(array('groups.name As name','groups.description As description'))
                          ->first();
              $userCheckCount=Usersgroup::where('user_id','=',$userId)->where('group_id','=',$karmaFeedId)->count();
              if($userCheckCount > 0){
                $groupData['groupJoinLeave']='join';  
              }else{
                $groupData['groupJoinLeave']='leave';
              }
              $groupName=strtolower(trim(str_replace(' ', '-', $getGroupDetails->name)));
              $dynamicGroup=$groupName.'/'.$karmaFeedId;
              $groupUrl['publicUrl']=$siteUrl.'/groups/'.$dynamicGroup;
              $groupFeed[] = array_merge($karmaFeed->toArray(),$getGroupDetails->toArray(),$receiverData->toArray(),$groupData,$groupUrl);
            }else if($karmaFeedMessage=='KarmaQuery'){
              $getQueryDetails = Question::where('id','=',$karmaFeedId)
                    ->select(array('questions.question_url','questions.queryStatus As queryStatus','questions.subject As description'))
                          ->first();
              $getQueryCount['countHelp']=Questionwillingtohelp::where('user_id','<>',$userId)->where('question_id','=',$karmaFeedId)->count();
              $dynamicGroup=$getQueryDetails->question_url.'/'.$karmaFeedId;
              $queryUrl['publicUrl']=$siteUrl.'/question/'.$dynamicGroup;
              
              $queryFeed[] = array_merge($karmaFeed->toArray(),$getQueryDetails->toArray(),$receiverData->toArray(),$getQueryCount,$queryUrl);
            }else if($karmaFeedMessage=='OfferHelpTo'){
              $getOfferHelpDetails = Question::where('id','=',$karmaFeedId)
                    ->select(array('questions.question_url','questions.queryStatus As queryStatus','questions.subject As description'))
                          ->first();
                  $getOfferHelpCount['countHelp']=Questionwillingtohelp::where('question_id','=',$karmaFeedId)->count();
                   $dynamicGroup=$getOfferHelpDetails->question_url.'/'.$karmaFeedId;
                  $offerHelpToUrl['publicUrl']=$siteUrl.'/question/'.$dynamicGroup;
                  $offerHelpToFeed[] = array_merge($karmaFeed->toArray(),$getOfferHelpDetails->toArray(),$giverData->toArray(),$getOfferHelpCount,$offerHelpToUrl);
              
            }
            
          }//foreach loop finish
          $feed=array_merge($karmaNoteFeed,$groupFeed,$queryFeed,$offerHelpToFeed);
          $sort = array();
            foreach($feed as $k=>$feedResult) {
                $sort['updated_at'][$k] = $feedResult['updated_at'];
            }
            if(!empty($feed)){
              array_multisort($sort['updated_at'], SORT_DESC, $feed); 
            }
             
             if(empty($getKcuser)){
              $getKcuser=array();
             }
            $this->status = 'success';
                  $this->message = 'Karmafeed result in descending order.';
            return Response::json(array(
              'status'=>$this->status,
              'message'=>$this->message,
              'feed'=>$feed,
              'randomUser'=>$getKcuser
            ));
          
        }else{
          $this->status = 'success';
          $this->message = 'There is no new feed for this user.';
        }
      }else{
        $this->status = 'failure';
              $this->message = 'You are not a login user.';
      }
    }
      return Response::json(array(
      'status'=>$this->status,
      'message'=>$this->message
    ));
    }
    /**
   * Function to see all contacts of user.
   *
   * @return In Response we will send status{success or failure}.
   */

    public function allContacts() {
      $validator = Validator::make(Request::all(), [
                   // 'accessToken' => 'required',
                   // 'userId' => 'required',
                   // 'allContacts' => 'required'
      ]);
      if ($validator->fails()) {
            #display error if validation fails                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                       
            $this->status = 'Validation fails';
            $this->message = 'arguments missing';
      } else {

          $accesstoken = Request::get('accessToken');
          $userId = Request::get('userId');
          $allContact = Request::all();
          $allContact=$allContact['contacts'];
          $checkUserExist=User::where('id','=',$userId)->where('site_token','=',$accesstoken)->get();
          if(!empty($checkUserExist)){
            foreach ($allContact as $key => $value) {
              if($value['phonenumber'] != '' && $value['phonenumber'] != 'null'){
                  $countNumber=strlen($value['phonenumber']);
                  if($countNumber > 9){
                    //$value['number']=$value['phonenumber'];
                    $value['number']=substr($value['phonenumber'], -10);
                  }else{
                    $value['number']=$value['phonenumber'];
                  }
                  $registerUser=DB::table('users')->select('id','fname','lname','phone_number','karmascore','piclink','headline','industry','summary','linkedinurl')->where('phone_number','=',$value['number'])->where('phone_number','<>','null')->first();
                  $allContactCount=User::where('phone_number','=',$value['number'])->where('phone_number','<>','null')->count();
                  if($allContactCount > 0){ 
                    $allContactResult['user_id']=$registerUser->id;
                    $allContactResult['fname']=$registerUser->fname;
                    $allContactResult['lname']=$registerUser->lname;
                    $allContactResult['phonenumber']=$registerUser->phone_number;
                    $allContactResult['karmascore']=$registerUser->karmascore;
                    $allContactResult['piclink']=$registerUser->piclink;
                    $allContactResult['headline']=$registerUser->headline;
                    $allContactResult['industry']=$registerUser->industry;
                    $allContactResult['summary']=$registerUser->summary;
                    $allContactResult['linkedinurl']=$registerUser->linkedinurl;
                    $siteUrl=URL::to('/');
                    $dynamic_name=$registerUser->fname.'-'.$registerUser->lname.'/'.$registerUser->id;
                    $public_profile_url=$siteUrl.'/profile/'.$dynamic_name;
                    $allContactResult['publicUrl']=$public_profile_url;
                    $allContactData['registered'][]=$allContactResult;

                  }else{
                    $allContactUnregisterResult['phonenumber']=$value['phonenumber'];
                    $allContactUnregisterResult['fname']=$value['fname'];
                    $allContactUnregisterResult['lname']=$value['lname'];
                    $allContactData['unregister'][]=$allContactUnregisterResult;
                    
                  }
                }
            }
            if(empty($allContactResult)){
              $allContactData['registered']=array();
            }else{
              $allContactData['registered'] = array_values(array_sort($allContactData['registered'], function($value)
                {
                  if(!empty($value))
                    return $value['fname']; 
                }));
            }
            if(empty($allContactUnregisterResult)){
              $allContactData['unregister']=array();
            }else{
              $allContactData['unregister'] = array_values(array_sort($allContactData['unregister'], function($value)
                {
                  if(!empty($value))
                    return $value['fname']; 
                }));
            }
            $this->status='success';
            $this->message='Displaying all register and unregister user.';
            return Response::json(array(
              'status'=>$this->status,
              'message'=>$this->message,
              'allContact' => $allContactData
            ));
          }else{
            $this->status = 'failure';
            $this->message = 'You are not a login user.';
          }
          
      }
      return Response::json(array(
        'status'=>$this->status,
        'message'=>$this->message
      ));
    }

    public function getReceiverData()
    {
        $rules = Validator::make(Request::all(), [
                    'accessToken' => 'required',
                    'userId' => 'required'
        ]);
        if ($rules->fails()) {
            $this->status = 'Validation fails';
            $this->message = 'arguments missing';
            
        } else {
            $accessToken = Request::get('accessToken');
            $userId = Request::get('userId');
            $getUser = User::where('id', '=', $userId)->where('site_token','=',$accessToken)->first();
            if(!empty($getUser)){
              $searchqueryReceiverResult=DB::table('requests')->join('users','requests.user_id_receiver','=','users.id')->where('user_id_receiver','<>',$userId)->where('user_id_giver','=',$userId)->select('users.id','users.fname','users.lname','users.karmascore','users.linkedinid','users.headline','users.location','users.email','users.piclink','noofmeetingspm','requests.user_id_receiver')->distinct('user_id_receiver')->get();
              if(!empty($searchqueryReceiverResult)){
                foreach ($searchqueryReceiverResult as $key => $value) {
                  $receiver=$value->id;
                  $receiverDetail = User::find($receiver);
                  $meetingRequestPending = $receiverDetail->Giver()->Where('status','=','pending')->count();
                  $commonConnectionDataCount=KarmaHelper::commonConnection($userId,$receiver);
                  $getCommonConnectionData=array_unique($commonConnectionDataCount);
                  $commonConnectionDataCount=count($getCommonConnectionData);
                  $searchqueryReceiverData[$key]['user_id']=$value->id;
                  $searchqueryReceiverData[$key]['fname']=$value->fname;
                  $searchqueryReceiverData[$key]['lname']=$value->lname;
                  $searchqueryReceiverData[$key]['karmascore']=$value->karmascore;
                  $searchqueryReceiverData[$key]['email']=$value->email;
                  $searchqueryReceiverData[$key]['location']=$value->location;
                  $searchqueryReceiverData[$key]['headline']=$value->headline;
                  $searchqueryReceiverData[$key]['piclink']=$value->piclink;
                  $searchqueryReceiverData[$key]['linkedinid']=$value->linkedinid;
                  $siteUrl=URL::to('/');
                  $dynamic_name=$value->fname.'-'.$value->lname.'/'.$value->id;
                  $public_profile_url=$siteUrl.'/profile/'.$dynamic_name;
                  $searchqueryReceiverData[$key]['publicUrl']=$public_profile_url;
                  $searchqueryReceiverData[$key]['connectionCount']="$commonConnectionDataCount";
                  $searchqueryReceiverData[$key]['meetingRequestPending']="$meetingRequestPending";
                  $searchqueryReceiverData[$key]['noofmeetingspm']=$value->noofmeetingspm;
                }
              }else{
                $searchqueryReceiverData=array();
              }
              $this->status = 'Success';
              $this->message = 'list of receiver data.';
              return Response::json(array(
                'status'=>$this->status,
                'message'=>$this->message,
                'receiverData'=>$searchqueryReceiverData,
              ));
            }else{
              $this->status = 'failure';
              $this->message = 'You are not a login user.';
            }
        }
        return Response::json(array(
          'status'=>$this->status,
          'message'=>$this->message
        ));
    }

      public function getGiverData(){
        $rules = Validator::make(Request::all(), [
                    'accessToken' => 'required',
                    'userId' => 'required'
        ]);
        if ($rules->fails()) {
            $this->status = 'Validation fails';
            $this->message = 'arguments missing';
            
        } else {
            $accessToken = Request::get('accessToken');
            $userId = Request::get('userId');
            $getUser = User::where('id', '=', $userId)->where('site_token','=',$accessToken)->first();
            $searchqueryGiverData=array();
            if(!empty($getUser)){
              $searchqueryGiverResult=Meetingrequest::join('users','requests.user_id_giver','=','users.id')->where('user_id_giver','<>',$userId)->where('user_id_receiver','=',$userId)->select('users.id','users.fname','users.lname','users.karmascore','users.linkedinid','users.headline','users.location','users.email','users.piclink','noofmeetingspm','requests.user_id_giver')->distinct('requests.user_id_giver')->get();
              if(!empty($searchqueryGiverResult)){
                foreach ($searchqueryGiverResult as $key => $value) {
                  $receiver=$value->id;
                  $receiverDetail = User::find($receiver);
                  $meetingRequestPending = $receiverDetail->Giver()->Where('status','=','pending')->count();
                  $commonConnectionDataCount=KarmaHelper::commonConnection($userId,$receiver);
                  $getCommonConnectionData=array_unique($commonConnectionDataCount);
                  $commonConnectionDataCount=count($getCommonConnectionData);
                  $searchqueryGiverData[$key]['user_id']=$value->id;
                  $searchqueryGiverData[$key]['fname']=$value->fname;
                  $searchqueryGiverData[$key]['lname']=$value->lname;
                  $searchqueryGiverData[$key]['karmascore']=$value->karmascore;
                  $searchqueryGiverData[$key]['email']=$value->email;
                  $searchqueryGiverData[$key]['location']=$value->location;
                  $searchqueryGiverData[$key]['headline']=$value->headline;
                  $searchqueryGiverData[$key]['piclink']=$value->piclink;
                  $searchqueryGiverData[$key]['linkedinid']=$value->linkedinid;
                  $siteUrl=URL::to('/');
                  $dynamic_name=$value->fname.'-'.$value->lname.'/'.$value->id;
                  $public_profile_url=$siteUrl.'/profile/'.$dynamic_name;
                  $searchqueryGiverData[$key]['publicUrl']=$public_profile_url;
                  $searchqueryGiverData[$key]['connectionCount']="$commonConnectionDataCount";
                  $searchqueryGiverData[$key]['meetingRequestPending']="$meetingRequestPending";
                  $searchqueryGiverData[$key]['noofmeetingspm']=$value->noofmeetingspm;
                }
              }
              $this->status = 'Success';
              $this->message = 'list of giver data.';
              return Response::json(array(
                'status'=>$this->status,
                'message'=>$this->message,
                'giverData'=>$searchqueryGiverData,
              ));

            }else{
              $this->status = 'failure';
              $this->message = 'You are not a login user.';
            }
        }
        return Response::json(array(
          'status'=>$this->status,
          'message'=>$this->message,
        ));
      }

      public function getReceiversReceiverData(){
        $rules = Validator::make(Request::all(), [
                    'accessToken' => 'required',
                    'userId' => 'required'
        ]);
        if ($rules->fails()) {
            $this->status = 'Validation fails';
            $this->message = 'arguments missing';
            
        } else {
            $accessToken = Request::get('accessToken');
            $userId = Request::get('userId');
            $getUser = User::where('id', '=', $userId)->where('site_token','=',$accessToken)->first();
            if(!empty($getUser)){
              $searchqueryReceiverResult=DB::table('requests')->join('users','requests.user_id_receiver','=','users.id')->where('user_id_receiver','<>',$userId)->where('user_id_giver','=',$userId)->select('users.id','users.fname','users.lname','users.karmascore','users.linkedinid','users.headline','users.location','users.email','users.piclink','noofmeetingspm','requests.user_id_receiver')->distinct('user_id_receiver')->get();
              $receiverId=array();
              foreach ($searchqueryReceiverResult as $key => $value) {
                $receiverId[]=$value->user_id_receiver;   
              }
              $receiversReceiver=DB::table('requests')->join('users','requests.user_id_receiver','=','users.id')->whereIn('user_id_giver',$receiverId)->where('user_id_receiver','<>',$userId)->select('users.id','users.fname','users.lname','users.karmascore','users.linkedinid','users.headline','users.location','users.email','users.piclink','noofmeetingspm','requests.user_id_receiver')->distinct('user_id_receiver')->get();
              if(!empty($receiversReceiver)){
                foreach ($receiversReceiver as $key => $value) {
                  $receiver=$value->id;
                  $receiverDetail = User::find($receiver);
                  $meetingRequestPending = $receiverDetail->Giver()->Where('status','=','pending')->count();
                  $commonConnectionDataCount=KarmaHelper::commonConnection($userId,$receiver);
                  $getCommonConnectionData=array_unique($commonConnectionDataCount);
                  $commonConnectionDataCount=count($getCommonConnectionData);
                  $searchqueryReceiversReceiverData[$key]['user_id']=$value->id;
                  $searchqueryReceiversReceiverData[$key]['fname']=$value->fname;
                  $searchqueryReceiversReceiverData[$key]['lname']=$value->lname;
                  $searchqueryReceiversReceiverData[$key]['karmascore']=$value->karmascore;
                  $searchqueryReceiversReceiverData[$key]['email']=$value->email;
                  $searchqueryReceiversReceiverData[$key]['location']=$value->location;
                  $searchqueryReceiversReceiverData[$key]['headline']=$value->headline;
                  $searchqueryReceiversReceiverData[$key]['piclink']=$value->piclink;
                  $searchqueryReceiversReceiverData[$key]['linkedinid']=$value->linkedinid;
                  $siteUrl=URL::to('/');
                  $dynamic_name=$value->fname.'-'.$value->lname.'/'.$value->id;
                  $public_profile_url=$siteUrl.'/profile/'.$dynamic_name;
                  $searchqueryReceiversReceiverData[$key]['publicUrl']=$public_profile_url;
                  $searchqueryReceiversReceiverData[$key]['connectionCount']="$commonConnectionDataCount";
                  $searchqueryReceiversReceiverData[$key]['meetingRequestPending']="$meetingRequestPending";
                  $searchqueryReceiversReceiverData[$key]['noofmeetingspm']=$value->noofmeetingspm;
                }
              }else{
                $searchqueryReceiversReceiverData=array();
              }
              $this->status = 'Success';
              $this->message = 'list of receivers receiver data.';
              return Response::json(array(
                'status'=>$this->status,
                'message'=>$this->message,
                'receiversReceiverData'=>$searchqueryReceiversReceiverData,
              ));

            }else{
              $this->status = 'failure';
              $this->message = 'You are not a login user.';
            }
        }
        return Response::json(array(
          'status'=>$this->status,
          'message'=>$this->message,
        ));
      }

}

<?php
namespace API;
use Validator;
use Request;
use Response;
use NotificationHelper;use Adminoption;use ArrayObject;use Message;use Mykarma;use Connection;use Carbon;use Meetingrequest;use URL;use User;use Userstag;use Usersgroup;use Question;use Tag;use KarmaHelper;use Karmafeed;use Group;use Karmanote;use Questionwillingtohelp; use Queue;//Models
use Illuminate\Support\Facades\DB; //To queries directly

class NoteApiController extends \BaseController {

	/**
	 * Display a listing of the resources.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
	}


	/**
	 * Function to show Group Detail and Top Giver of that group.
	 *
	 * @return Response
	 */
	public function karmaNoteDetail()
	{
		$rules = Validator::make(Request::all(), [
                    'accessToken' => 'required',
                    'userId' => 'required',
                    'karmaNoteId' => 'required',
		]);
        if ($rules->fails()) {
            $this->status = 'Validation fails';
            $this->message = 'arguments missing';
            
        } else {
        	$accessToken = Request::get('accessToken');
            $userId = Request::get('userId');
        	$karmaNoteId=Request::get('karmaNoteId');
        	$siteUrl=URL::to('/');
        	$getUser = User::where('id', '=', $userId)->where('site_token','=',$accessToken)->first();
			if(!empty($getUser)){
				$karmaNote = Karmanote::select('user_idreceiver As receiver_id','user_idgiver As giver_id','connection_idgiver','details As description','skills as skillSet','updated_at','req_id')->where('id','=',$karmaNoteId)->first();
				if(!empty($karmaNote)){
					$receiverId=$karmaNote->receiver_id;
					$giverId=$karmaNote->giver_id;
					$karmaNoteDetail=$karmaNote->description;
					$connectionId=$karmaNote->connection_idgiver;
					$karmaNoteDate=$karmaNote->updated_at;
					if(!empty($receiverId)){
						$receiverData=User::where('id','=',$receiverId)->select('fname As receiverFirstName','lname As receiverLastName','piclink As receiverPic','headline As receiverHeadline')->first();	
						$fname=$receiverData->receiverFirstName;
		 	  			$lname=$receiverData->receiverLastName;
		 	  			$karmaNoteFullReceiverName=$fname.'-'.$lname;
					}
					if(!empty($giverId)){
						$giverData=User::where('id','=',$giverId)->select('fname As giverFirstName','lname As giverLastName','piclink As giverPic','headline As giverHeadline')->first();	
						$fname=$giverData->giverFirstName;
		 	  			$lname=$giverData->giverLastName;
		 	  			$karmaNoteFullGiverName=$fname.'-'.$lname;
					}else{
						$giverData=Connection::where('id','=',$connectionId)->select('fname As giverFirstName','lname As giverLastName')->first();	
						$fname=$giverData->giverFirstName;
		 	  			$lname=$giverData->giverLastName;
		 	  			$karmaNoteFullGiverName=$fname.'-'.$lname;
					}
					
						if(!empty($karmaNote->skillSet)){
                            $tags = explode(',', $karmaNote->skillSet);
							foreach ($tags as $name) {
								$skillTag[] = Tag::find($name);
							}
						}else{
							$skillTag=array();
						}

						$dynamicMeeting=$karmaNoteFullReceiverName.'-'.$karmaNoteFullGiverName.'/'.$karmaNote->req_id;
						$meetingUrl['publicUrl']=$siteUrl.'/meeting/'.$dynamicMeeting;
						$karmaNoteFeed = array_merge($karmaNote->toArray(), $giverData->toArray(),$receiverData->toArray(),$meetingUrl);
						$this->status = 'Success';
						return Response::json(array('status'=>$this->status,
								'karmaNoteDetail'=>$karmaNoteFeed,
								'skillTag'=>$skillTag
    					));
						
				}else{
					$this->status = 'failure';
            		$this->message = 'You are no such karmanote exist.';
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
	 * Save karmanote with thier skills.
	 *
	 * @return Response
	 */
	public function karmaNoteSave()
	{
		
		 $rules = Validator::make(Request::all(), [
                    
		 ]);
        if ($rules->fails()) {
            $this->status = 'Validation fails';
            $this->message = 'arguments missing';
            
        } else {
        	$allData=Request::all();
        	$userId=$allData['userId'];
			$accessToken=$allData['accessToken'];
			$date=$allData['date'];
			$description=$allData['karmanote'];
			//$skills=$allData['skillTags'];
        	$giverId=$allData['giverId'];
        	$fname=$allData['fname'];
        	$lname=$allData['lname'];
        	$phoneNumber=$allData['phoneNumber'];
        	$userType=$allData['userType'];
        	$skills=$allData['skillTags'];
        	$getUser = User::where('id', '=', $userId)->where('site_token','=',$accessToken)->first();
			if(!empty($getUser)){
				if($userId != $giverId){	
					if($userType=='register'){
						$meetingtime='00:00';
						$dateTime = $date.' '.$meetingtime;
						$meetingRequest = new Meetingrequest;
						$meetingRequest ->user_id_receiver 				= $userId;
						if(!empty($giverId)){
							$meetingRequest ->user_id_giver 				= $giverId;
						}
						$meetingRequest ->notes 						= '';
						$meetingRequest ->status 						= 'completed';
						$meetingRequest ->meetingdatetime			 	= date('Y-m-d H:i:s',strtotime($dateTime)); 
						$meetingRequest ->replyviewstatus			 	= '1'; 
						$meetingRequest ->requestviewstatus			 	= '1'; 
						$meetingRequest ->req_createdate 				= KarmaHelper::currentDate();
						$meetingRequest->save();
						$meetingId = $meetingRequest->id;
						$getGiverData=User::where('id', '=', $giverId)->first();
						$getReceiverData=User::where('id', '=', $userId)->first();
						//Add data on users_mykarma table for receiver
						$myKarmaDataReceiver = new Mykarma;
						$myKarmaDataReceiver->entry_id=$meetingId;
						$myKarmaDataReceiver->user_id=$userId;
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
						$myKarmaDataGiver->user_id=$giverId;
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
						$token=$getGiverData->deviceToken;
						$pushNotificationStatus=NotificationHelper::androidPushNotification($token);
						$karmaNote = new Karmanote;
						$karmaNote ->req_id 							= $meetingId;
						if(!empty($giverId)){
							$karmaNote ->user_idgiver 					= $giverId;
						}
						$karmaNote ->user_idreceiver 					= $userId;
						$karmaNote ->details 							= strip_tags($description);
						if(!empty($skills)){
							foreach ($skills as $key => $value) {
									$skillData[]=$value['id'];	
							}
							$karmaNote ->skills 						= implode(',', $skillData);
						}
						else{
							$karmaNote ->skills 						= '';
						}	
						$karmaNote ->viewstatus 						= 0;
						$karmaNote->created_at 							= KarmaHelper::currentDate();
						$karmaNote->save();
						$karmaNoteId = $karmaNote->id;
						if(!empty($skills)){
							$getName = $getSkillDataName = "";
							foreach ($skills as $key => $value) {
								$getName = Tag::where('id','=',$value['id'])->select('name')->first(); 
								$getSkillDataName .= $getName->name . ",";
							}
							$result=rtrim($getSkillDataName,",");
							$karmaNoteMessage=$karmaNote->details."
	Endorsements: ".$result;
						}
						else{
							$karmaNoteMessage=$karmaNote ->details;
						}	
						
						//update karmascore
						$messageData=new Message;
	                    $messageData->request_id=$meetingId;
	                    $messageData->sender_id=$userId;
	                    $messageData->giver_id=$giverId;
	                    $messageData->receiver_id=$userId;
	                    $messageText=$getUser->fname.' '.$getUser->lname.' has sent a KarmaNote.';
	                    $messageData->messageText=$messageText;
	                    $messageData->save();
	                    $messageData=new Message;
	                    $messageData->message_type='user';
	                    $messageData->request_id=$meetingId;
	                    $messageData->sender_id=$userId;
	                    $messageData->giver_id=$giverId;
	                    $messageData->receiver_id=$userId;
	                    $messageTextOfKarmaNote=$karmaNoteMessage;
	                    $messageData->messageText=$messageTextOfKarmaNote;
	                    $messageData->save();
						if(!empty($giverId)){
							$feedType='KarmaNote';
							KarmaHelper::updateKarmaScore($giverId,$userId);
							KarmaHelper::storeKarmacirclesfeed($giverId,$userId,$feedType,$karmaNoteId);
							Queue::push('MessageSender', array('type' =>'5','user_id_giver' => $giverId,'user_id_receiver' => $userId,'meetingId'=> $meetingId));
						}
					}else if($userType=='unregister'){
						$lastTenDightPhoneNumber=substr($phoneNumber, -10);
						$checkData=Connection::where('phone_number','LIKE', '%'.$lastTenDightPhoneNumber)->first();
						if(empty($checkData)){
							$connectionData = new Connection;
							$string = rand(1000, 9999);
	                		$randomNumber = strtoupper($string);
							$connectionData->networkid=$randomNumber;
							$connectionData->fname=$fname;
							$connectionData->lname=$lname;
							$connectionData->phone_number=$phoneNumber;
							$connectionData->save();
							$connectionId=$connectionData->id;
						}else{
							$connectionId=$checkData->id;
						}
							$dateTime = $date;
							$meetingRequest = new Meetingrequest;
							$meetingRequest ->user_id_receiver 				= $userId;
							$meetingRequest ->connection_id_giver 			= $connectionId;
							$meetingRequest ->notes 						= '';
							$meetingRequest ->status 						= 'completed';
							$meetingRequest ->meetingdatetime			 	= date('Y-m-d H:i:s',strtotime($dateTime)); 
							//$Meetingrequest ->meetingdatetime			 	= $dateTime; 
							$meetingRequest ->replyviewstatus			 	= '1'; 
							$meetingRequest ->requestviewstatus			 	= '1'; 
							$meetingRequest ->req_createdate 				= KarmaHelper::currentDate();
							$meetingRequest->save();
							$meetingId = $meetingRequest->id;
							//Add data on users_mykarma table for receiver
							$getGiverData=Connection::where('id', '=', $connectionId)->first();
							$myKarmaDataReceiver = new Mykarma;
							$myKarmaDataReceiver->entry_id=$meetingId;
							$myKarmaDataReceiver->user_id=$userId;
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
							$myKarmaDataGiver->user_id='0';
							$myKarmaDataGiver->connection_id=$connectionId;
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
							$karmaNote = new Karmanote;
							$karmaNote ->req_id 							= $meetingId;
							$karmaNote ->connection_idgiver 			= $connectionId;
							$karmaNote ->user_idreceiver 					= $userId;
							$karmaNote ->details 							= strip_tags($description);
							if(!empty($skills)){
								foreach ($skills as $key => $value) {
										$skillData[]=$value['id'];	
								}
								$karmaNote ->skills 						= implode(',', $skillData);
							}
							else{
								$karmaNote ->skills 						= '';
							}	
							$karmaNote ->viewstatus 						= 0;
							$karmaNote->created_at 							= KarmaHelper::currentDate();
							$karmaNote->save();
							$karmaNoteId = $karmaNote->id;
							//$karmaNoteMessage=$karmaNote->details;
							if(!empty($skillSet)){
								$getName = $getSkillDataName = "";
								foreach ($skills as $key => $value) {
									$getName = Tag::where('id','=',$value['id'])->select('name')->first(); 
									$getSkillDataName .= $getName->name . ",";
								}
								$result=rtrim($getSkillDataName,",");
							$karmaNoteMessage=$karmaNote->details."
	Endorsements: ".$result;
							}
							else{
								$karmaNoteMessage=$karmaNote->details;
							}	
							//echo '<pre>';print_r($karmaNoteMessage);die;
							//update karmascore
							$messageData=new Message;
		                    $messageData->request_id=$meetingId;
		                    $messageData->sender_id=$userId;
		                    $messageData->giver_id=$connectionId;
		                    $messageData->receiver_id=$userId;
		                    $messageText=$getUser->fname.' '.$getUser->lname.' has sent a KarmaNote.';
		                    $messageData->messageText=$messageText;
		                    $messageData->save();
		                    $messageDataTwo=new Message;
		                    $messageDataTwo->message_type='user';
		                    $messageDataTwo->request_id=$meetingId;
		                    $messageDataTwo->sender_id=$userId;
		                    $messageDataTwo->giver_id=$connectionId;
		                    $messageDataTwo->receiver_id=$userId;
		                    $messageDataTwo->messageText=$karmaNoteMessage;
		                    $messageDataTwo->save();
							if(!empty($connectionId)){
								$feedType='KarmaNote';
								KarmaHelper::storeKarmacirclesfeed($connectionId,$userId,$feedType,$karmaNoteId);
							}
						
						
					}
					$this->status = 'Success';
	           		$this->message = 'Karma Note is saved';
	           		return Response::json(array('status'=>$this->status,
	    								'message'=>$this->message,
	    								'karmanoteId' => $karmaNoteId 
	    									
	    			));
				}else{
					$this->status = 'Success';
	           		$this->message = 'Cannot send KarmaNote to yourself';
	           		$karmaNoteId='null';
	           		return Response::json(array('status'=>$this->status,
	    								'message'=>$this->message,
	    								'karmanoteId' => $karmaNoteId 
	    									
	    			));

				}
					
				
			}else{
				$this->status = 'Failure';
           		$this->message = 'There is no such user';
			}
			
	    }

	    return Response::json(array('status'=>$this->status,
    								'message'=>$this->message
    	));

    }


	/**
	 * Function to display my karma trail.
	 *
	 * @return Response
	 */
	public function myKarmaTrail()
	{
		$rules = Validator::make(Request::all(), [
                    'accessToken' => 'required',
                    'userId' => 'required',
                    'offset' => 'required'
        ]);
        if ($rules->fails()) {
            $this->status = 'Validation fails';
            $this->message = 'arguments missing';
            
        } else {
        	$accessToken = Request::get('accessToken');
            $userId = Request::get('userId');
            $offset = Request::get('offset');
           	$getUser = User::where('id', '=', $userId)->where('site_token','=',$accessToken)->first();
           	if(!empty($getUser)){
	           	$karmaTrail=KarmaHelper::getKarmaData($userId,$offset);
	           	foreach ($karmaTrail as $key => $value) {
	           		$myKarmafeed[$key]['karmaId']=$value->entry_id;
	           		if($value->users_role=='OfferedHelp'){
	           			$userData=Question::where('id','=',$value->entry_id)->first();
	           			if(!empty($userData)){
	           				$myKarmafeed[$key]['userId']=$userData->user_id;	
	           			}else{
	           				$myKarmafeed[$key]['userId']='null';
	           			}
	           			
	           		}else if($value->users_role=='PostedQuery'){
	           			$myKarmafeed[$key]['userId']=$value->user_id;	
	           		}else if($value->users_role=='Receiver'){
	           			$userDataGiverId=Meetingrequest::where('user_id_receiver','=',$value->user_id)->where('id','=',$value->entry_id)->first();
	           			if(!empty($userDataGiverId)){
	           				$myKarmafeed[$key]['userId']=$userDataGiverId->user_id_giver;	
	           			}else{
	           				$myKarmafeed[$key]['userId']='null';
	           			}
	           			
	           		}else{
	           			$userDataReceiverId=Meetingrequest::where('user_id_giver','=',$value->user_id)->orWhere('connection_id_giver','=',$value->user_id)->where('id','=',$value->entry_id)->first();
	           			if(!empty($userDataReceiverId)){
	           				$myKarmafeed[$key]['userId']=$userDataReceiverId->user_id_receiver;		
	           			}else{
	           				$myKarmafeed[$key]['userId']='null';
	           			}
	           			
	           		}
	           		if($value->status=='completed'){
	           			$karmaNoteData=DB::table('karmanotes')->where('req_id','=',$value->entry_id)->select('id','req_id','user_idreceiver','user_idgiver','connection_idgiver','statusgiver','statusreceiver')->get();
		           		if(!empty($karmaNoteData)){
		           			$receiverData=User::where('id','=',$karmaNoteData[0]->user_idreceiver)->select('fname','lname')->first();
		           			$giverData=User::where('id','=',$karmaNoteData[0]->user_idgiver)->select('fname','lname')->first();
							if($value->users_role=='Receiver'){
		           				$myKarmafeed[$key]['karmaNoteId']=$karmaNoteData[0]->id;
		           				$myKarmafeed[$key]['karmaNoteStatus']=$karmaNoteData[0]->statusreceiver;
		           			}else if($value->users_role=='Giver'){
		           				$myKarmafeed[$key]['karmaNoteId']=$karmaNoteData[0]->id;
		           				$myKarmafeed[$key]['karmaNoteStatus']=$karmaNoteData[0]->statusgiver;
		           			}else{
		           				$myKarmafeed[$key]['karmaNoteId']='null';
		           				$myKarmafeed[$key]['karmaNoteStatus']='null';
		           			}
		           		$site_url=URL::to('/');	
		           		if(!empty($giverData)){
		           			$sharePublicUrl=$site_url.'/meeting/'.$receiverData->fname.'-'.$receiverData->lname.'-'.$giverData->fname.'-'.$giverData->lname.'/'.$karmaNoteData[0]->req_id;	
	           				$myKarmafeed[$key]['sharePublicUrl']=$sharePublicUrl;	
		           		}else{
		           			$giverDataConnection=Connection::where('id','=',$karmaNoteData[0]->connection_idgiver)->select('fname','lname')->first();
		           			$sharePublicUrl=$site_url.'/meeting/'.$receiverData->fname.'-'.$receiverData->lname.'-'.$giverDataConnection->fname.'-'.$giverDataConnection->lname.'/'.$karmaNoteData[0]->req_id;	
		           			$myKarmafeed[$key]['sharePublicUrl']=$sharePublicUrl;
		           		}
		           		
	           			}else{
	           				$myKarmafeed[$key]['karmaNoteId']='null';
		           			$myKarmafeed[$key]['karmaNoteStatus']='null';
		           			$myKarmafeed[$key]['sharePublicUrl']='null';
	           			}
	           			
	           		}else{
	           			$myKarmafeed[$key]['karmaNoteId']='null';
	           			$myKarmafeed[$key]['karmaNoteStatus']='null';
	           			$myKarmafeed[$key]['sharePublicUrl']='null';
	           		}
	           		$myKarmafeed[$key]['fname']=$value->fname;
	           		$myKarmafeed[$key]['lname']=$value->lname;
	           		$myKarmafeed[$key]['piclink']=$value->piclink;
	           		$myKarmafeed[$key]['checkUnreadStatus']=$value->unread_flag;
	           		$myKarmafeed[$key]['userRoll']=$value->users_role;
					$myKarmafeed[$key]['status']=$value->status;
					$myKarmafeed[$key]['actionType']=$value->entry_type;
					if($myKarmafeed[$key]['actionType']=='Meeting'){
						$getMessage=KarmaHelper::getMykarmaMessageForReceiverGiver($value->status,$value->users_role);	
						$myKarmafeed[$key]['message']=$getMessage;
					}else if($myKarmafeed[$key]['actionType']=='Query'){
						$getQueryDetail=Question::where('id','=',$value->entry_id)->first();
						if($value->users_role=='OfferedHelp'){
							if(!empty($getQueryDetail)){
								$myKarmafeed[$key]['message']='Offered help: '.$getQueryDetail->subject;	
							}else{
								$myKarmafeed[$key]['message']='null';
							}
						}else if($value->users_role=='PostedQuery'){
							if(!empty($getQueryDetail)){
								$myKarmafeed[$key]['message']='Your Query: '.$getQueryDetail->subject;
							}else{
								$myKarmafeed[$key]['message']='null';
							}						
						}else{
							$myKarmafeed[$key]['message']='null';
						}
					}else{
						$myKarmafeed[$key]['message']='null';
					}
					$myKarmafeed[$key]['date']=$value->entry_updated_on;

				}
				if(empty($myKarmafeed) && $offset=='0'){
					$myKarmafeed=array();
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
					$getKcuser=array();
				}
				if(empty($myKarmafeed)){
					$myKarmafeed=array();
				}
				$this->status = 'Success';
             	$this->message = 'MyKarmaFeed in desending order.';
				return Response::json(array('status'=>$this->status,
    								'message'=>$this->message,
    								'piclink'=>$getUser->piclink,
    								'MyKarmaFeed'=>$myKarmafeed,
    								'suggestedUser'=>$getKcuser
    			));
			}else{
            	$this->status = 'failure';
             	$this->message = 'You are not a login user.';
            }

        }
		return Response::json(array('status'=>$this->status,
    								'message'=>$this->message
    	));
    }

    /**
	 * Function to display my karma trail.
	 *
	 * @return Response
	 */
	public function saveMeetingKarmanote()
	{
		$rules = Validator::make(Request::all(), [
                   'accessToken' => 'required',
                    'userId' => 'required',
                    'karmanote' => 'required',
                    'meetingId'=> 'required',
                    'giverId'=>'required',
                    'fname'=> 'required',
                    'lname'=> 'required',
                    'userType' => 'required',
                    //'skillTags' => 'required'
        ]);
        if ($rules->fails()) {
            $this->status = 'Validation fails';
            $this->message = 'arguments missing';
            
        } else {
        	$allData=Request::all();
			$userId=$allData['userId'];
			$accessToken=$allData['accessToken'];
			$meetingId=$allData['meetingId'];
			$description=$allData['karmanote'];
			$skills=$allData['skillTags'];
        	$giverId=$allData['giverId'];
        	$fname=$allData['fname'];
        	$lname=$allData['lname'];
        	$userType=$allData['userType'];
            $getUser = User::where('id', '=', $userId)->where('site_token','=',$accessToken)->first();
            if(!empty($getUser)){
            		$karmaNote = new Karmanote;
					$karmaNote ->req_id 							= $meetingId;
					if(!empty($giverId)){
						$karmaNote ->user_idgiver 					= $giverId;
					}
					$karmaNote ->user_idreceiver 					= $userId;
					$karmaNote ->details 							= strip_tags($description);
					if(!empty($skills)){
						foreach ($skills as $key => $value) {
								$skillData[]=$value['id'];	
						}
						$karmaNote ->skills 						= implode(',', $skillData);
					}
					else{
						$karmaNote ->skills 						= '';
					}	
					$karmaNote ->viewstatus 						= 0;
					$karmaNote->created_at 							= KarmaHelper::currentDate();
					$karmaNote->save();
					$karmaNoteId = $karmaNote->id;
					//$karmaNoteMessage=$karmaNote->details;
					if(!empty($skills)){
							$getName = $getSkillDataName = "";
							foreach ($skills as $key => $value) {
								$getName = Tag::where('id','=',$value['id'])->select('name')->first(); 
								$getSkillDataName .= $getName->name . ",";
							}
							$result=rtrim($getSkillDataName,",");
						$karmaNoteMessage=$karmaNote ->details."
Endorsements: ".$result;
						}
					else{
						$karmaNoteMessage=$karmaNote ->details;
					}	
					//update karmascore
					$Meetingrequest = Meetingrequest::find($meetingId);
					$Meetingrequest->status 			= 'completed';
					if(!empty($giverId)){
						$feedType='KarmaNote';
						KarmaHelper::updateKarmaScore($giverId,$userId);
						KarmaHelper::storeKarmacirclesfeed($giverId,$userId,$feedType,$karmaNoteId);
						//Queue::push('MessageSender', array('type' =>'5','user_id_giver' => $giverId,'user_id_receiver' => $userId,'meetingId'=> $meetingId));
					}
					$messageData=new Message;
                    $messageData->request_id=$meetingId;
                    $messageData->sender_id=$userId;
                    $messageData->giver_id=$giverId;
                    $messageData->receiver_id=$userId;
                    $messageText=$getUser->fname.' '.$getUser->lname.' has sent a KarmaNote.';
                    $messageData->messageText=$messageText;
                    $messageData->save();
                    $messageData=new Message;
                    $messageData->message_type='user';
                    $messageData->request_id=$meetingId;
                    $messageData->sender_id=$userId;
                    $messageData->giver_id=$giverId;
                    $messageData->receiver_id=$userId;
                    $messageTextOfKarmaNote=$karmaNoteMessage;
                    $messageData->messageText=$messageTextOfKarmaNote;
                    $messageData->save();
                    DB::table('users_mykarma')->where('entry_id','=',$meetingId)->update(array('status' => 'completed','entry_updated_on' => Carbon::now()));
                    DB::table('requests')->where('id','=',$meetingId)->update(array('status' => 'completed'));
                    $userRole='Receiver';
                    $changeStatus=KarmaHelper::updateMeetingStatus($meetingId,$userRole);
           			$this->status = 'Success';
             		$this->message = 'Karmanote is saved successfully.';
             		 return Response::json(array('status'=>$this->status,
    								'message'=>$this->message,
    								'karmanoteId'=>$karmaNoteId
    				));
            }else{
            	$this->status = 'failure';
             	$this->message = 'You are not a login user.';
            }
        }
        return Response::json(array('status'=>$this->status,
    								'message'=>$this->message,
    								//'karmanoteId'=>$karmaNoteId
    	));
    }

    /**
	 * Function to Show and Hide Karmanote.
	 *
	 * @return Response
	 */
     public function meetingShowHide()
    {
        $rules = Validator::make(Request::all(), [
                    'accessToken' => 'required',
                    'userId' => 'required',
                    'karmaNoteId' => 'required',
                    'userRole'=>'required',
                    'karmaNoteStatus'=>'required'
        ]);
        if ($rules->fails()) {
            $this->status = 'Validation fails';
            $this->message = 'arguments missing';
            
        } else {
            $accessToken = Request::get('accessToken');
            $userId = Request::get('userId');
            $karmanoteId = Request::get('karmaNoteId');
            $userRole = Request::get('userRole');
            $karmanoteStatus = Request::get('karmaNoteStatus');
           	$getUser = User::where('id', '=', $userId)->where('site_token','=',$accessToken)->first();
           	if(!empty($getUser)){
            	if($userRole=='Receiver'){
            		if($karmanoteStatus=='hidden'){
            			DB::table('karmanotes')->where('id','=',$karmanoteId)->update(array('statusreceiver' => 'visible'));
            			$this->status = 'Success';
             			$this->message = 'Karmanote status is successfully visible';
             		}else{
            			DB::table('karmanotes')->where('id','=',$karmanoteId)->update(array('statusreceiver' => 'hidden'));	
            			$this->status = 'Success';
             			$this->message = 'Karmanote status is successfully hide';
             		}
            		
            	}else if($userRole=='Giver'){
            		if($karmanoteStatus=='hidden'){
            			DB::table('karmanotes')->where('id','=',$karmanoteId)->update(array('statusgiver' => 'visible'));
            			$this->status = 'Success';
             			$this->message = 'Karmanote status is successfully visible';
             		}else{
            			DB::table('karmanotes')->where('id','=',$karmanoteId)->update(array('statusgiver' => 'hidden'));	
            			$this->status = 'Success';
             			$this->message = 'Karmanote status is successfully hide';
             			
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
    								'message'=>$this->message
    	));
    }

    /**
	 * Function to change the status to anystate to meeting happened.
	 *
	 * @return Response
	 */
     public function meetingHappened()
    {
        $rules = Validator::make(Request::all(), [
                    'accessToken' => 'required',
                    'userId' => 'required',
                    'meetingId' => 'required',
                    'userRole'=>'required'
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
            		$getMeetingUserData=KarmaHelper::commonMeetingHappened($meetingId,$userRole);
                    $meetingDataResult=KarmaHelper::meetingData($accessToken,$userId,$meetingId,$userRole);
                    $meetingUserId=$meetingDataResult['meetingUserId'];
                    $userProfilePic=$meetingDataResult['userProfilePic'];
                    $meetingStatusText=$meetingDataResult['meetingStatusText'];
                    $meetingStatus=$meetingDataResult['meetingStatus'];
                    $meetingTrailData=$meetingDataResult['meetingTrailData'];
                    $this->status = 'Success';
             		$this->message = 'Meeting happened';
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
    								'message'=>$this->message
    	));
    }
    /**
	 * Function to change the status to any state to meeting did not happen.
	 *
	 * @return Response
	 */
     public function meetingNotHappened()
    {
        $rules = Validator::make(Request::all(), [
                    'accessToken' => 'required',
                    'userId' => 'required',
                    'meetingId' => 'required',
                    'userRole'=>'required'
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
            		$getMeetingUserData=KarmaHelper::commonMeetingNotHappened($meetingId,$userRole);
                    $meetingDataResult=KarmaHelper::meetingData($accessToken,$userId,$meetingId,$userRole);
                    $meetingUserId=$meetingDataResult['meetingUserId'];
                    $userProfilePic=$meetingDataResult['userProfilePic'];
                    $meetingStatusText=$meetingDataResult['meetingStatusText'];
                    $meetingStatus=$meetingDataResult['meetingStatus'];
                    $meetingTrailData=$meetingDataResult['meetingTrailData'];
                    $this->status = 'Success';
             		$this->message = 'Meeting not happened';
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
    								'message'=>$this->message
    	));
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
						if(empty($giverData)){
							$giverData=Connection::where('id','=',$noteData->connection_idgiver)->first();
						}
						$karmaNoteDetailData['karmaNoteId']=$noteData->id;
	            		if($userRole=='Receiver'){
							$karmaNoteDetailData['karmaNoteStatus']=$noteData->statusreceiver;
						}else{
							$karmaNoteDetailData['karmaNoteStatus']=$noteData->statusgiver;
						}
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
						if(empty($userProfilePic->piclink)){
							$picLinkData = 'null';
						}else{
							$picLinkData=$userProfilePic->piclink;
						}
	            		$this->status='Success';
            			$this->message='Karma Meeting data';
            			
	            		return Response::json(array('status'=>$this->status,
    								'message'=>$this->message,
    								'meetingStatus'=>$meetingData->status,
    								'meetingUserId'=>$userProfileId,
    								'userProfilePic'=>$picLinkData,
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

}

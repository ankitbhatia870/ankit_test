<?php
namespace API;
use Validator;
use Request;
use Response;
use Carbon;use Mykarma;use Groupquestion;use URL;use User;use Userstag;use Usersgroup;use Question;use Tag;Use KarmaHelper;use Karmafeed;use Group;use Karmanote;use Questionwillingtohelp; //Models
use Illuminate\Support\Facades\DB; //To queries directly
class QueriesApiController extends \BaseController {

	/**
	 * Function to show Query Detail and offerHelp users.
	 *
	 * @return Response
	 */

	
	public function karmaQueryDetail()
	{
		$rules = Validator::make(Request::all(), [
                    'accessToken' => 'required',
                    'userId' => 'required',
                    'queryId' => 'required',
		]);
        if ($rules->fails()) {
            $this->status = 'Validation fails';
            $this->message = 'arguments missing';
            
        } else {
        	$accessToken = Request::get('accessToken');
            $userId = Request::get('userId');
        	$queryId=Request::get('queryId');
        	$siteUrl=URL::to('/');
        	$getuser = User::where('id', '=', $userId)->where('site_token','=',$accessToken)->first();
			if(!empty($getuser)){
				DB::table('users_mykarma')->where('entry_id','=',$queryId)->where('user_id','=', $userId)->update(array('no_of_unread_items'=>'0','unread_flag' => 'false'));
				$getQueryDetails = Question::where('id','=',$queryId)
										->select(array('user_id as userId','questions.queryStatus','questions.description','questions.subject','questions.skills','questions.question_url','questions.created_at'))
							            ->first();
				$getQueryHelpCount['countHelp']=Questionwillingtohelp::where('question_id','=',$queryId)->count();
				$getOfferHelpUser = DB::table('users_question_willingtohelp')
									->join('users', 'users_question_willingtohelp.user_id', '=', 'users.id')
									->select(array('users.id','users.fname','users.lname','users.piclink','users.headline','users_question_willingtohelp.comment'))
						            ->where('users_question_willingtohelp.question_id','=',$queryId)
						           	->get();					
					
				if(!empty($getQueryDetails)){
					$receiverId=$getQueryDetails->userId;
					if(!empty($receiverId)){
						$receiverData=User::where('id','=',$receiverId)->select('fname As receiverFirstName','lname As receiverLastName','piclink As receiverPic','headline As receiverHeadline')->first();	
					}
					$tags = explode(',', $getQueryDetails->skills);
						if(!empty($getQueryDetails->skills)){
							
							foreach ($tags as $name) {
								$skillTag[] = Tag::find($name);
							}
						}else{
							$skillTag=array();
						}
						$dynamicGroup=$getQueryDetails->question_url.'/'.$queryId;
						$queryUrl['publicUrl']=$siteUrl.'/question/'.$dynamicGroup;
						$karmaQueryFeed = array_merge($getQueryDetails->toArray(),$receiverData->toArray(),$getQueryHelpCount,$queryUrl);
						$this->status = 'Success';
						return Response::json(array('status'=>$this->status,
								'karmaNoteDetail'=>$karmaQueryFeed,
								'skillTag'=>$skillTag,
								'offerHelpToUser'=>$getOfferHelpUser
    					));
						
				}else{
					$this->status = 'failure';
            		$this->message = 'There is no such karmaQuery exist.';
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
	 * Function to save Query Detail.
	 *
	 * @return Response
	 */
	public function karmaQuerySave()
	{
		
		$rules = Validator::make(Request::all(), [
                    'accessToken' => 'required',
                    'userId' => 'required',
                    'subject' => 'required',
                    'description'=> 'required',
                    
		]);
        if ($rules->fails()) {
            $this->status = 'Validation fails';
            $this->message = 'arguments missing';
            
        } else {
        	$allData=Request::all();
			$userId=$allData['userId'];
			$subject=$allData['subject'];
			$description=$allData['description'];
			$skills=$allData['skillTags'];
        	$accessToken=$allData['accessToken'];
        	$url_subject = $subject;
			$url_subject = strtolower($url_subject);
		    //Make alphanumeric (removes all other characters)
			$url_subject = preg_replace("/[^a-z0-9_\s-]/", "", $url_subject);
			$url_subject = trim($url_subject);
		    //Clean up multiple dashes or whitespaces
			$url_subject = preg_replace("/[\s-]+/", " ", $url_subject);
		    //Convert whitespaces and underscore to dash
			$url_subject = preg_replace("/[\s_]/", "-", $url_subject);		
			$getUser = User::where('id', '=', $userId)->where('site_token','=',$accessToken)->first();
			if(!empty($getUser)){
				$Question = new Question;
				$Question ->user_id 				= $userId;
				$Question ->subject 				= strip_tags($subject);
				$Question ->description 			= strip_tags($description);
				if(!empty($skills)){
						foreach ($skills as $key => $value) {
							$skillData[]=$value['id'];	
						}
						$Question ->skills 						= implode(',', $skillData);
					}
					else{
						$Question ->skills 						= '';
					}	
				$Question ->access 					= 'public';
				$Question ->question_url 			= strtolower($url_subject);
				$Question->save();
				$questionId=$Question->id;
				//Add data on users_mykarma table for query
				$myKarmaDataQuery = new Mykarma;
				$myKarmaDataQuery->entry_id=$questionId;
				$myKarmaDataQuery->user_id=$userId;
				$myKarmaDataQuery->fname=$getUser->fname;
				$myKarmaDataQuery->lname=$getUser->lname;
				$myKarmaDataQuery->piclink=$getUser->piclink;
				$myKarmaDataQuery->entry_type='Query';
				$myKarmaDataQuery->users_role='PostedQuery';
				$myKarmaDataQuery->status='Open';
				$myKarmaDataQuery->unread_flag='false';
				$myKarmaDataQuery->no_of_unread_items='0';
				$myKarmaDataQuery->entry_updated_on=Carbon::now();
				$myKarmaDataQuery->save();
				$user_id_giver='null';
				$feedType='KarmaQuery';
				KarmaHelper::storeKarmacirclesfeed($user_id_giver,$userId,$feedType,$questionId);	
				$user_groups=Usersgroup::where('user_id','=',$userId)->get();

				if(!empty($user_groups)){
					$user_groups=$user_groups->toArray();
					foreach ($user_groups as $key => $value) {
						$Groupquestion = new Groupquestion;
						$Groupquestion->question_id = $Question->id;
						$Groupquestion->group_id = $value['group_id'];
						$Groupquestion->user_id = $value['user_id'];
						$Groupquestion->save();
					}
				}
				$this->status = 'Success';
           		$this->message = 'Karma Query is saved';
				
			}else{
				$this->status = 'Failure';
           		$this->message = 'There is no such user';
			}
			
	    }

	    return Response::json(array('status'=>$this->status,
    								'message'=>$this->message,
    									
    	));

    }

    /**
	 * Function to Display offer help user.
	 *
	 * @return Response
	 */
	public function karmaQueryHelp()
	{
		
		$rules = Validator::make(Request::all(), [
                    'accessToken' => 'required',
                    'userId' => 'required',
                    'queryId' => 'required',
                    
		]);
        if ($rules->fails()) {
            $this->status = 'Validation fails';
            $this->message = 'arguments missing';
            
        } else {
        	$accessToken = Request::get('accessToken');
            $userId = Request::get('userId');
        	$queryId=Request::get('queryId');
        	$comment=Request::get('comment');
        	$getUser = User::where('id', '=', $userId)->where('site_token','=',$accessToken)->first();
        	if(!empty($getUser)){
        		$checkCloseQuery=DB::table('questions')->where('id','=',$queryId)->where('queryStatus','=','close')->count();
        		if($checkCloseQuery < 1){
        			$checkUser = Questionwillingtohelp::where('user_id','=',$userId)->where('question_id','=',$queryId)->count();
	        		if($checkUser < 1){
	        			$checkSameGiver=DB::table('questions')->where('id','=',$queryId)->where('user_id','=',$userId)->count();
	        			if($checkSameGiver < 1){
							$questionHelp = new Questionwillingtohelp;
							$questionHelp->question_id = $queryId;
							$questionHelp->user_id = $userId;
							$questionHelp->comment = $comment;
							$questionHelp->save();
							$giver=DB::table('questions')->where('id','=',$queryId)->select('user_id')->first();
							$giverId=$giver->user_id;
							$getUserData=User::where('id', '=', $giverId)->first();
							//update read/unread functionality of users_mykarma
							DB::table('users_mykarma')->where('entry_id','=',$queryId)->update(array('unread_flag' => 'true','no_of_unread_items' => '1','entry_updated_on' => Carbon::now()));
							//Add offer help to users_mykarma
							$myKarmaOfferHelp = new Mykarma;
							$myKarmaOfferHelp->entry_id=$queryId;
							$myKarmaOfferHelp->user_id=$userId;
							$myKarmaOfferHelp->fname=$getUserData->fname;
							$myKarmaOfferHelp->lname=$getUserData->lname;
							$myKarmaOfferHelp->piclink=$getUserData->piclink;
							$myKarmaOfferHelp->entry_type='Query';
							$myKarmaOfferHelp->users_role='OfferedHelp';
							$myKarmaOfferHelp->status='Open';
							$myKarmaOfferHelp->unread_flag='false';
							$myKarmaOfferHelp->no_of_unread_items='0';
							$myKarmaOfferHelp->entry_updated_on=Carbon::now();
							$myKarmaOfferHelp->save();
							$feedType='OfferHelpTo';
							KarmaHelper::storeKarmacirclesfeed($userId,$giverId,$feedType,$queryId);
							$this->status = 'Success';
	           				$this->message = 'Your request for help is saved';
						}else{
							$this->status = 'Failure';
	           				$this->message = 'You cannot help your own query';
						}
	        		}else{
	        			$this->status = 'Faliur';
	           			$this->message = 'Your have already accept this query for help';
	        		}
				}else{
        			$this->status = 'Failure';
           			$this->message = 'You cannot help close query';
        		}
        	}else{
				$this->status = 'Failure';
           		$this->message = 'There is no such user';
			}
        }
        return Response::json(array('status'=>$this->status,
    								'message'=>$this->message,
    									
    	));
    }

     /**
	 * Function to close karma query.
	 *
	 * @return Response
	 */
    public function closeKarmaQuery()
	{
		
		$rules = Validator::make(Request::all(), [
                    'accessToken' => 'required',
                    'userId' => 'required',
                    'queryId' => 'required'
                    
		]);
        if ($rules->fails()) {
            $this->status = 'Validation fails';
            $this->message = 'arguments missing';
            
        }else {
        	$accessToken = Request::get('accessToken');
            $userId = Request::get('userId');
        	$queryId=Request::get('queryId');
        	$getUser = User::where('id', '=', $userId)->where('site_token','=',$accessToken)->first();
        	if(!empty($getUser)){
        		DB::table('users_mykarma')->where('entry_id','=',$queryId)->update(array('status'=>'close','unread_flag' => 'true','no_of_unread_items'=>'0','entry_updated_on' => Carbon::now()));
        		DB::table('questions')->where('id','=',$queryId)->update(array('queryStatus' => 'close'));
        		$usersFeedDelete = Karmafeed::where('id_type','=',$queryId)->delete();
        		$myKarmaFeedDelete = Mykarma::where('entry_id','=',$queryId)->delete();
        		$this->status = 'Success';
	        	$this->message = 'Your query is closed';
        	}else{
        		$this->status = 'Failure';
	        	$this->message = 'You are not a login user';
        	}
        	
		}
        return Response::json(array('status'=>$this->status,
    								'message'=>$this->message,
    									
    	));
    }


}

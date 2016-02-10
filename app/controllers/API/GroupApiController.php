<?php
namespace API;
use Validator;
use Request;
use Response;
use User;use Userstag;use Usersgroup;use Question;use Tag;Use KarmaHelper;use Karmafeed;use Group;use Karmanote;use Questionwillingtohelp;use URL; //Models
use Illuminate\Support\Facades\DB; //To queries directly
class GroupApiController extends \BaseController {

	/**
	 * Display a listing of the resource.
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
	public function groupDetail()
	{
		 $rules = Validator::make(Request::all(), [
                    'accessToken' => 'required',
                    'userId' => 'required',
                    'groupId' => 'required',
                    'offset' => 'required'
                    
        ]);
        if ($rules->fails()) {
            $this->status = 'Validation fails';
            $this->message = 'arguments missing';
            
        } else {
        	$siteUrl=URL::to('/');
        	$accessToken = Request::get('accessToken');
            $userId = Request::get('userId');
        	$groupId=Request::get('groupId');
        	$offset=Request::get('offset');
        	$start=$offset*10;
        	$perpage=10;
			$getuser = User::where('id', '=', $userId)->where('site_token','=',$accessToken)->first();
			if(!empty($getuser)){
				$groupDetail=DB::table('groups')->where('groups.id','=',$groupId)->select('groups.name As groupName','groups.description As groupDescription')->first();
				$topGiver = DB::table('groups')
				            ->join('users_groups', 'groups.id', '=', 'users_groups.group_id')
				            ->join('users', 'users.id', '=', 'users_groups.user_id')
				            ->where('users.userstatus', '=', 'approved')	            		            
				            ->groupBy('users_groups.user_id')
				            ->select('users_groups.user_id','users.id','users.fname', 'users.lname', 'users.email', 'users.piclink','users.karmascore','users.linkedinid', 'users.linkedinurl', 'users.location', 'users.headline','users.noofmeetingspm')
				           	->orderBy('users.karmascore','DESC')
				           	->where('groups.id','=',$groupId)
				           	->where('users.id','<>',$userId)
				           	->skip($start)->take($perpage)
				            ->get();
					foreach ($topGiver as $key => $value) {
								$receiver=$value->id;
					  			$receiverDetail = User::find($receiver);
					  			$meetingRequestPending = $receiverDetail->Giver()->Where('status','=','pending')->count();
								$commonConnectionDataCount=KarmaHelper::commonConnection($userId,$receiver);
								$getCommonConnectionData=array_unique($commonConnectionDataCount);
           						$commonConnectionDataCount=count($getCommonConnectionData);
								$topGiverResult[$key]['user_id']=$value->user_id;
					  			$topGiverResult[$key]['fname']=$value->fname;
					  			$topGiverResult[$key]['lname']=$value->lname;
					  			$topGiverResult[$key]['karmascore']=$value->karmascore;
					  			$topGiverResult[$key]['email']=$value->email;
					  			$topGiverResult[$key]['linkedinurl']=$value->linkedinurl;
					  			$topGiverResult[$key]['linkedinid']=$value->linkedinid;
					  			$topGiverResult[$key]['location']=$value->location;
					  			$topGiverResult[$key]['headline']=$value->headline;
					  			$topGiverResult[$key]['piclink']=$value->piclink;
					  			$dynamic_name=$value->fname.'-'.$value->lname.'/'.$value->user_id;
								$public_profile_url=$siteUrl.'/profile/'.$dynamic_name;
					  			$topGiverResult[$key]['publicUrl']=$public_profile_url;
					  			$topGiverResult[$key]['connectionCount']=$commonConnectionDataCount;
					  			$topGiverResult[$key]['meetingRequestPending']=$meetingRequestPending;
					  			$topGiverResult[$key]['noofmeetingspm']=$value->noofmeetingspm;
					}
					if(empty($topGiver)){
						$topGiver=array();
					}
				$this->status = 'Success';
				return Response::json(array('status'=>$this->status,
									'GroupDetail'=>$groupDetail,
    								'topGiver'=>$topGiverResult
    									
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

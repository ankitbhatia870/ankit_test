<?php
namespace API;
use Validator;
use Request;
use Response;
use User;use Userstag;use Tag;use Group;use connection;use Usersgroup;use URL;use KarmaHelper; //Models
use Illuminate\Support\Facades\DB; //To queries directly
class SearchApiController extends \BaseController {
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
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function searchConnectionData(){
		 $validator = Validator::make(Request::all(), [
                    'userId' => 'required',
                    'accessToken' => 'required',
                    'searchKeyword'=> 'required',
                    'searchCategory'=>'required'
        ]);
        if ($validator->fails()) {
            #display error if validation fails                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                       
            $this->status = 'Validation fails';
            $this->message = 'arguments missing';
        } else {
        	$siteUrl=URL::to('/');
            #If validation suceeded
            #get inputs
			$accesstoken = Request::get('accessToken');
            $user_id = Request::get('userId');
			$search = Request::get('searchKeyword');
            $searchCat = Request::get('searchCategory');
            $user = User::find($user_id);
            if(!empty($user)){
				if($accesstoken==$user->site_token){
	            	if($searchCat == 'People'){
	            		$searchqueryResult =DB::select(DB::raw('select * from users WHERE userstatus="approved" and concat(users.fname," ",users.lname) LIKE "%'.$search.'%" or `users`.`headline` LIKE "%'.$search.'%" or `users`.`location` LIKE "%'.$search.'%" or `users`.`summary` LIKE "%'.$search.'%"'));
						$searchquery=array();
						if(!empty($searchqueryResult)){
							$this->status='Success';
							foreach ($searchqueryResult as $key => $value) {
					  			$receiver=$value->id;
					  			$receiverDetail = User::find($receiver);
					  			if(!empty($value->id)){
					  				$meetingRequestPending = $receiverDetail->Giver()->Where('status','=','pending')->count();	
					  			}else{
					  				$meetingRequestPending =0;
					  			}
					  			//$meetingRequestPending = $receiverDetail->Giver()->Where('status','=','pending')->count();
					  			$commonConnectionDataCount=KarmaHelper::commonConnection($user_id,$value->id);
           						$getCommonConnectionData=array_unique($commonConnectionDataCount);
           						$commonConnectionDataCount=count($getCommonConnectionData);
								$searchquery[$key]['fname']=$value->fname;
					  			$searchquery[$key]['user_id']=$value->id;
					  			$searchquery[$key]['lname']=$value->lname;
					  			$searchquery[$key]['karmascore']=$value->karmascore;
					  			$searchquery[$key]['email']=$value->email;
					  			$searchquery[$key]['location']=$value->location;
					  			$searchquery[$key]['headline']=$value->headline;
					  			$searchquery[$key]['piclink']=$value->piclink;
					  			$dynamic_name=$value->fname.'-'.$value->lname.'/'.$value->id;
								$public_profile_url=$siteUrl.'/profile/'.$dynamic_name;
					  			$searchquery[$key]['publicUrl']=$public_profile_url;
					  			$searchquery[$key]['connectionCount']=$commonConnectionDataCount;
					  			$searchquery[$key]['meetingRequestPending']=$meetingRequestPending;
					  			$searchquery[$key]['noofmeetingspm']=$value->noofmeetingspm;
					  		}
					  		return Response::json(array('status'=>$this->status,
		    									'userId'=>$user_id,
		    									'searchresult'=>$searchquery
		    				));
					  	}else{
					  		$this->status='Success';
					  		$this->message='There is no data exist on this';
					  		return Response::json(array('status'=>$this->status,
		    									'message'=>$this->message,
		    									'searchresult'=>$searchquery
		    				));
					  	}
					}elseif($searchCat == 'Groups'){
						//for getting search record of group.
						$searchquery = DB::select(DB::raw( "select count(users_groups.group_id) As userCount,name,groups.id from groups,users_groups where groups.id=users_groups.group_id AND group_id IN (select id from groups where name LIKE'%".$search."%') group by groups.id" ));	
						$groupData=array();
						foreach ($searchquery as $key => $value) {
							$groupData[$key]['userCount']=$value->userCount;
							$groupData[$key]['name']=$value->name;
							$groupData[$key]['id']=$value->id;
							$userCheckCount=Usersgroup::where('user_id','=',$user_id)->where('group_id','=',$value->id)->count();
							if($userCheckCount > 0){
								$groupData[$key]['groupJoinLeave']='join';	
							}else{
								$groupData[$key]['groupJoinLeave']='leave';
							}
						}
						if(!empty($searchquery)){
					  		$this->status='Success';
					  		return Response::json(array('status'=>$this->status,
		    									'userId'=>$user_id,
		    									'searchresult'=>$groupData
		    									
		    				));
					  	}else{
					  		$this->status='Success';
					  		$this->message='There is no data exist on this';
					  		return Response::json(array('status'=>$this->status,
		    									'message'=>$this->message,
		    									'searchresult'=>$groupData
		    				));
					  	}
					}elseif($searchCat == 'Skills'){
						//for getting search record of skills.
						$searchquery =	Tag::where('name', 'LIKE', '%'.$search.'%')->select('id','name')->get();
						if(!empty($searchquery)){
					  		$this->status='Success';
					  		return Response::json(array('status'=>$this->status,
		    									'userId'=>$user_id,
		    									'searchresult'=>$searchquery
		    				));
					  	}else{
					  		$this->status='Success';
					  		$this->message='There is no data exist on this';
					  		return Response::json(array('status'=>$this->status,
		    									'message'=>$this->message,
		    									'searchresult'=>$searchquery
		    				));
					  	}
					}elseif($searchCat == 'Locations'){
						//for getting search record of location.
						$searchquery =	User::where('location', 'LIKE', '%'.$search.'%')->select('location')->distinct('location')->get();
						//$searchquery = 	DB::select(DB::raw('SELECT name FROM `tags` Where `tags`.`name` LIKE "%'.$search.'%"'));
						if(!empty($searchquery)){
					  		$this->status='Success';
					  		return Response::json(array('status'=>$this->status,
		    									'userId'=>$user_id,
		    									'searchresult'=>$searchquery
		    				));
					  	}else{
					  		$this->status='Success';
					  		$this->message='There is no data exist on this';
					  		return Response::json(array('status'=>$this->status,
		    									'message'=>$this->message,
		    									'searchresult'=>$searchquery
		    				));
					  	}
					}
					else{
						//If there is no valid group type.
							$this->status='Failure';
							$this->message='This is not a valid category type';
					}
				}else{
					//If accessToken cant match.
	            	$this->status = 'failure';
		        	$this->message= 'You are not a login user.';
	            }
	        }else{
	        	$this->status = 'failure';
		        $this->message= 'Please enter correct user Id.';
	        }
        }
        return Response::json(array(
        	'status'=>$this->status,
        	'message'=>$this->message
    	));
	}
/**
	 * Display a search user on the basis of group,skill and people.
	 *
	 * @return Response
	 */
	public function searchUsers(){
		$validator = Validator::make(Request::all(), [
                    'accessToken' => 'required',
                    'userId' => 'required',
                    'searchUser' => 'required',
                    'searchOption' => 'required'
        ]);
        if ($validator->fails()) {
            #display error if validation fails                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                       
            $this->status = 'Validation fails';
            $this->message = 'arguments missing';
        } else {
        	$siteUrl=URL::to('/');
			$result=Request::all();
			$accesstoken = Request::get('accessToken');
	        $user_id = Request::get('userId');
			$user_info = User::find($user_id);
			if(isset($user_info)){
				$user_id = $user_info->id;
				$location = $user_info->location;	
			}else{
				$user_id = 0;
				$location = '';
			}
			$searchresult = array();
			$start =0; $perpage=10;
			if(!empty($user_info)){
				if($accesstoken == $user_info->site_token){
				if(!empty($result)) 
				{
					$search = Request::get('searchUser');
		        	$searchOption = Request::get('searchOption');
					if($searchOption == 'People'){
					    $searchqueryResult =User::where('id','!=',$user_id)->where('fname', 'LIKE', '%'.$search.'%')->orWhere('lname','LIKE', '%'.$search.'%')->orWhere('location','LIKE', '%'.$search.'%')->orWhere('summary','LIKE', '%'.$search.'%')->orWhere('headline','LIKE', '%'.$search.'%')->where('userstatus','=','approved')->orderBy('karmascore','DESC')->get();
						if(!empty($searchqueryResult)){
					  		foreach ($searchqueryResult as $key => $value) {
					  			$receiver=$value->id;
					  			$receiverDetail = User::find($receiver);
					  			$meetingRequestPending = $receiverDetail->Giver()->Where('status','=','pending')->count();
					  			$commonConnectionData=KarmaHelper::commonConnection($user_id,$value->id);
					  			$getCommonConnectionData=array_unique($commonConnectionData);
					  			$commonConnectionDataCount=count($getCommonConnectionData);
								$searchquery[$key]['fname']=$value->fname;
					  			$searchquery[$key]['lname']=$value->lname;
					  			$searchquery[$key]['karmascore']=$value->karmascore;
					  			$searchquery[$key]['email']=$value->email;
					  			$searchquery[$key]['location']=$value->location;
					  			$searchquery[$key]['headline']=$value->headline;
					  			$searchquery[$key]['piclink']=$value->piclink;
					  			$searchquery[$key]['linkedinid']=$value->linkedinid;
					  			$dynamic_name=$value->fname.'-'.$value->lname.'/'.$value->id;
								$public_profile_url=$siteUrl.'/profile/'.$dynamic_name;
					  			$searchquery[$key]['publicUrl']=$public_profile_url;
					  			$searchquery[$key]['connectionCount']=$commonConnectionDataCount;
					  			$searchquery[$key]['meetingRequestPending']=$meetingRequestPending;
					  			$searchquery[$key]['noofmeetingspm']=$value->noofmeetingspm;
					  		}
					  	}
					  	if(empty($searchquery)){
					  		$searchquery=array();
					  	}
					  	foreach ($searchquery as $key => $value) {
		         	  		$linkedinid = $value['linkedinid'];
							$linkedinUserData = DB::table('users')->where('linkedinid', '=', $linkedinid)->select('id','fname','lname','location','piclink','karmascore','headline','email')->first();
		         	  		if(!empty($linkedinUserData)){
			         	  		$tags = DB::select(DB::raw( "select `name` from `tags` inner join `users_tags` on `tags`.`id` = `users_tags`.`tag_id` where `user_id` = $linkedinUserData->id order by `tags`.`name` = '$search' desc" ));			
			         	  		$searchresult[$key]['UserData'] = array();
			         	  		$searchresult[$key]['Tags'] = array();
			         	  		$searchresult[$key]['UserData'] = $linkedinUserData;
			         	  		//$searchresult[$key]['Tags'] = $tags;
		         	  		}	
		         	  	}
					}elseif($searchOption == 'Skills'){
						$skillTag = array(); 
		         	  	$searchqueryResult = DB::table('tags')
					            ->join('users_tags', 'tags.id', '=', 'users_tags.tag_id')
					            ->join('users', 'users.id', '=', 'users_tags.user_id')
					            ->where('name', 'LIKE', $search)
					            ->where('users.userstatus', '=', 'approved')
					            ->where('users.id', '!=', $user_id)			            
					            ->groupBy('users_tags.user_id')
					            ->orderBy('users.karmascore','DESC')
					            ->select('tags.name', 'tags.id', 'users_tags.user_id', 'users.fname', 'users.lname','users.karmascore', 'users.email', 'users.piclink', 'users.linkedinid', 'users.linkedinurl', 'users.location', 'users.headline')
					            //->skip($start)->take($perpage)
					            ->get();
					    foreach ($searchqueryResult as $key => $value) {
					    		$commonConnectionDataCount=KarmaHelper::commonConnection($user_id,$value->user_id);
           						$getCommonConnectionData=array_unique($commonConnectionDataCount);
           						$commonConnectionDataCount=count($getCommonConnectionData);
								$searchquery[$key]['name']=$value->name;
					     		$searchquery[$key]['id']=$value->id;
					     		$searchquery[$key]['user_id']=$value->user_id;
					     		$searchquery[$key]['fname']=$value->fname;
					  			$searchquery[$key]['lname']=$value->lname;
					  			$searchquery[$key]['karmascore']=$value->karmascore;
					  			$searchquery[$key]['email']=$value->email;
					  			$searchquery[$key]['email']=$value->email;
					  			$searchquery[$key]['location']=$value->location;
					  			$searchquery[$key]['headline']=$value->headline;
					  			$searchquery[$key]['piclink']=$value->piclink;
					  			$searchquery[$key]['linkedinid']=$value->linkedinid;
					  			$dynamic_name=$value->fname.'-'.$value->lname.'/'.$value->user_id;
								$public_profile_url=$siteUrl.'/profile/'.$dynamic_name;
					  			$searchquery[$key]['publicUrl']=$public_profile_url;
					  			$searchquery[$key]['connectionCount']=$commonConnectionDataCount;
					     	
					     }
					     if(empty($searchquery)){
					  		$searchquery=array();
					  	}
					    foreach ($searchquery as $key => $value) {
							$linkedinid = $value['linkedinid'];
		         	  		$linkedinUserData = DB::table('users')->where('linkedinid', '=', $linkedinid)->select('id','fname','lname','location','piclink','karmascore','headline','email')->first();
		         	  		if(!empty($value->user_id)){
			         	  		$tags = DB::select(DB::raw( "select `name` from `tags` inner join `users_tags` on `tags`.`id` = `users_tags`.`tag_id` where `user_id` = $linkedinUserData->id order by `tags`.`name` = '$search' desc" ));			
								foreach ($tags as $skillkey => $skillvalue) {
			         	  			$skillTag[$skillkey]['name'] = $skillvalue->name;
			         	  		}
								$searchresult[$key]['UserData'] = array();
			         	  		$searchresult[$key]['Tags'] = array();
			         	  		$searchresult[$key]['UserData'] = $linkedinUserData;
			         	  		//$searchresult[$key]['Tags'] = $skillTag;
			         	  		//echo "<pre>";print_r($searchresult);echo "</pre>";die;
		         	  		}	
		         	  		
		         	  		
		         	  	}
		         	}elseif($searchOption == 'Location'){
						$searchqueryResult = DB::select(DB::raw( 'select * from (select `users`.`fname`, `users`.`lname`, `users`.`piclink`, `users`.`linkedinid`,
												 `users`.`karmascore`, `users`.`headline`, `users`.`location`, `users`.`id` As user_id from 
												`users` where location LIKE "%'.$search.'%" and `users`.`id` != '.$user_id.' and
												 `users`.`userstatus` = "approved" union select `connections`.`fname`,
												 `connections`.`lname`, `connections`.`piclink`, `connections`.`networkid`,
												 `connections`.`linkedinurl`, `connections`.`headline`, `connections`.`location`,
												 `users_connections`.`connection_id` from `connections`
												 inner join `users_connections` on `connections`.`id` = `users_connections`.`connection_id`
												 where location LIKE "%'.$search.'%" and `users_connections`.`user_id` = '.$user_id.') as 
												result group by result.linkedinid order by result.location limit 10 offset '.$start ));        	
					   // print_r($searchqueryResult);die;
					    foreach ($searchqueryResult as $key => $value) {
					    		$commonConnectionDataCount=KarmaHelper::commonConnection($user_id,$value->user_id);
           						$getCommonConnectionData=array_unique($commonConnectionDataCount);
           						$commonConnectionDataCount=count($getCommonConnectionData);
								$searchquery[$key]['user_id']=$value->user_id;
					     		$searchquery[$key]['fname']=$value->fname;
					  			$searchquery[$key]['lname']=$value->lname;
					  			$searchquery[$key]['karmascore']=$value->karmascore;
					  			$searchquery[$key]['location']=$value->location;
					  			$searchquery[$key]['headline']=$value->headline;
					  			$searchquery[$key]['piclink']=$value->piclink;
					  			$searchquery[$key]['linkedinid']=$value->linkedinid;
					  			$dynamic_name=$value->fname.'-'.$value->lname.'/'.$value->user_id;
								$public_profile_url=$siteUrl.'/profile/'.$dynamic_name;
					  			$searchquery[$key]['publicUrl']=$public_profile_url;
					  			$searchquery[$key]['connectionCount']=$commonConnectionDataCount;
					     	
					     }
					     if(empty($searchquery)){
					  		$searchquery=array();
					  	}
		         	  	foreach ($searchquery as $key => $value) {
		         	  		$linkedinid = $value['linkedinid'];
		         	  		$linkedinUserData = DB::table('users')->where('linkedinid', '=', $linkedinid)->select('id','fname','lname','location','piclink','karmascore','headline','email')->first();
		         	  		if(!empty($linkedinUserData)){
			         	  		$tags = DB::select(DB::raw( "select `name` from `tags` inner join `users_tags` on `tags`.`id` = `users_tags`.`tag_id` where `user_id` = $linkedinUserData->id order by `tags`.`name` = '$search' desc" ));
			         	  		$searchresult[$key]['UserData'] = array();
			         	  		$searchresult[$key]['Tags'] = array();
			         	  		$searchresult[$key]['UserData'] = $linkedinUserData;
			         	  		//$searchresult[$key]['Tags'] = $tags;
		         	  		}	
		         	  	}
					}
					elseif($searchOption == 'Groups'){				
						$searchqueryResult = DB::table('groups')
					        ->join('users_groups', 'groups.id', '=', 'users_groups.group_id')
					        ->join('users', 'users.id', '=', 'users_groups.user_id')
					        ->where('name', '=', $search)
					        ->where('users.userstatus', '=', 'approved')
					        ->where('users.id','<>',$user_id)	
					        ->groupBy('users_groups.user_id')
					        ->orderBy('users.karmascore','DESC')
					        ->select('groups.name', 'groups.id', 'users_groups.user_id', 'users.fname', 'users.lname', 'users.email', 'users.piclink', 'users.linkedinid', 'users.karmascore', 'users.location', 'users.headline')
					        ->get();
					       // print_r($searchqueryResult);die;
					        foreach ($searchqueryResult as $key => $value) {
           						$commonConnectionDataCount=KarmaHelper::commonConnection($user_id,$value->user_id);
           						$getCommonConnectionData=array_unique($commonConnectionDataCount);
           						$commonConnectionDataCount=count($getCommonConnectionData);
					        	$searchquery[$key]['name']=$value->name;
					        	$searchquery[$key]['id']=$value->id;
					    		$searchquery[$key]['user_id']=$value->user_id;
					     		$searchquery[$key]['fname']=$value->fname;
					  			$searchquery[$key]['lname']=$value->lname;
					  			$searchquery[$key]['karmascore']=$value->karmascore;
					  			$searchquery[$key]['location']=$value->location;
					  			$searchquery[$key]['headline']=$value->headline;
					  			$searchquery[$key]['piclink']=$value->piclink;
					  			$searchquery[$key]['linkedinid']=$value->linkedinid;
					  			$dynamic_name=$value->fname.'-'.$value->lname.'/'.$value->user_id;
								$public_profile_url=$siteUrl.'/profile/'.$dynamic_name;
					  			$searchquery[$key]['publicUrl']=$public_profile_url;
					  			$searchquery[$key]['connectionCount']=$commonConnectionDataCount;
					     	
					     }
					          // echo "<pre>";print_r($searchquery);echo "</pre>";die;
		         	  	if(empty($searchquery)){
					  		$searchquery=array();
					  	}
		         	  	foreach ($searchquery as $key => $value) {
		         	  		$linkedinid = $value['linkedinid'];
		         	  		$linkedinUserData = DB::table('users')->where('linkedinid', '=', $linkedinid)->select('id','fname','lname','location','piclink','karmascore','headline','email')->first();
		         	  		if(!empty($linkedinUserData)){
			         	  		$tags = DB::select(DB::raw( "select `name` from `tags` inner join `users_tags` on `tags`.`id` = `users_tags`.`tag_id` where `user_id` = $linkedinUserData->id order by `tags`.`name` = '$search' desc" ));
			         	  		$searchresult[$key]['UserData'] = array();
			         	  		$searchresult[$key]['Tags'] = array();
			         	  		$searchresult[$key]['UserData'] = $linkedinUserData;
			         	  		//$searchresult[$key]['Tags'] = $tags;
		         	  		}	
		         	  	}
					}
					elseif($searchOption == 'Tags'){
						$searchquery = DB::select(DB::raw( 'select * from (select `users`.`fname`, `users`.`lname`, `users`.`piclink`, `users`.`linkedinid`,
												 `users`.`linkedinurl`, `users`.`headline`, `users`.`location`, `users`.`id` from 
												`users` where headline LIKE "%'.$search.'%" and `users`.`id` != '.$user_id.' and
												 `users`.`userstatus` = "approved" union select `connections`.`fname`,
												 `connections`.`lname`, `connections`.`piclink`, `connections`.`networkid`,
												 `connections`.`linkedinurl`, `connections`.`headline`, `connections`.`location`,
												 `users_connections`.`connection_id` from `connections`
												 inner join `users_connections` on `connections`.`id` = `users_connections`.`connection_id`
												 where headline LIKE "%'.$search.'%" and `users_connections`.`user_id` = '.$user_id.') as 
												result group by result.linkedinid order by result.location = "'.$location.'" desc limit 10 offset'.$start ));         	
					    //echo "<pre>";print_r($searchresult);echo "</pre>";die;
		         	  	foreach ($searchquery as $key => $value) {
		         	  		$linkedinid = $value->linkedinid;
		         	  		$linkedinUserData = DB::table('users')->where('linkedinid', '=', $linkedinid)->select('id','fname','lname','location','piclink','karmascore','headline','email')->first();
		         	  		if(!empty($linkedinUserData)){
			         	  		$tags = DB::select(DB::raw( "select `name` from `tags` inner join `users_tags` on `tags`.`id` = `users_tags`.`tag_id` where `user_id` = $linkedinUserData->id order by `tags`.`name` = '$search' desc" ));
			         	  		$searchresult[$key]['UserData'] = array();
			         	  		$searchresult[$key]['Tags'] = array();
			         	  		$searchresult[$key]['UserData'] = $linkedinUserData;
			         	  		//$searchresult[$key]['Tags'] = $tags;
		         	  		}	
		         	  	}
					}else{
						$this->status='Failure';
						$this->message='There is no such category';
						return Response::json(array(
				        	'status'=>$this->status,
				        	'message'=>$this->message
				        	
				    	));	
					}
					if(!empty($searchquery)){
						$this->status='Success';
						return Response::json(array(
				        	'status'=>$this->status,
				        	'searchresult'=>$searchquery
				    	));	
					}else{
						$this->status='Success';
						$this->message='There is no data available';
						return Response::json(array(
				        	'status'=>$this->status,
				        	'message'=>$this->message,
				        	'searchresult'=>$searchquery
				    	));	
					}
					
					//return $searchresult;exit;
				}
				}else{
					$this->status = 'Failure';
	            	$this->message = 'You are not a login user.';	
				}
			
			}else{
				$this->status = 'Failure';
            	$this->message = 'You are not a current user.';
			}
			
		}
		return Response::json(array(
			        	'status'=>$this->status,
			        	'message'=>$this->message
		));	
			
	}
}
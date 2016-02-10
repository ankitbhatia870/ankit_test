<?php

/*
|--------------------------------------------------------------------------
| Application & Route Filters
|--------------------------------------------------------------------------
|
| Below you will find the "before" and "after" events for the application
| which may be used to do any work before or after a request into your
| application. Here you may also register your custom route filters.
|
*/

App::before(function($request)
{
	//
});


App::after(function($request, $response)
{
	//
});

/*
|--------------------------------------------------------------------------
| Authentication Filters
|--------------------------------------------------------------------------
|
| The following filters are used to verify that the user of the current
| session is logged into this application. The "basic" filter easily
| integrates HTTP Basic authentication for quick, simple checking.
|
*/

Route::filter('auth', function()
{
	if (Auth::guest())
	{
		if (Request::ajax())
		{
			return Response::make('Unauthorized', 401);
		}
		else
		{
			return Redirect::guest('/login/linkedin');
		}
	}
});


Route::filter('auth.basic', function()
{
	return Auth::basic();
}); 

/*
|--------------------------------------------------------------------------
| Guest Filter
|--------------------------------------------------------------------------
|
| The "guest" filter is the counterpart of the authentication filters as
| it simply checks that the current user is not logged in. A redirect
| response will be issued if they are, which you may freely change.
|
*/
Route::filter('AfterAuth', function()
{

if (Auth::check()) return Redirect::to('dashboard');

});

Route::filter('guest', function()
{
	if (Auth::check()) return Redirect::to('/');
});

/*
|--------------------------------------------------------------------------
| CSRF Protection Filter
|--------------------------------------------------------------------------
|
| The CSRF filter is responsible for protecting your application against
| cross-site request forgery attacks. If this special token in a user
| session does not match the one given in this request, we'll bail.
|
*/

Route::filter('csrf', function()
{
	if (Session::token() != Input::get('_token'))
	{
		throw new Illuminate\Session\TokenMismatchException;
	}
});

Route::filter('RegistrationCheck', function()
{	
	$url = Request::url();
	if(auth::user()->termsofuse == '0')
	{	
		if($url != URL::to('/')."/register")
		{
			return Redirect::to('register');
		}		
	}
});


Route::filter('statusCheck', function()
{
	$userstatus = auth::user()->userstatus;
	if($userstatus == 'pending' ||$userstatus == 'fetching connection'|| $userstatus == 'ready for approval' || $userstatus == 'hidden')
	{	
			return Redirect::to('status');
				
	}
	if($userstatus == 'TOS not accepted')
	{
		Redirect::to('register');
	}
});
Route::filter('AdminCheck', function()
{
	$userrole = auth::user()->role;
	if($userrole != 'admin')
	{	
			return Redirect::to('/dashboard');
				
	}
});
Route::filter('GroupCheck', function($route)
{
	$Giver_id = Route::input('Giver'); 
	$giverdetail = User::find($Giver_id);
	if($giverdetail->meeting_setting != 'accept from all'){ 
		$ReceiverGroupDetail = Auth::User()->Groups; 
		$giverGroupDetail = User::find($Giver_id)->Groups;
		
		foreach ($giverGroupDetail as $key => $valueGiver) {
			foreach ($ReceiverGroupDetail as $key => $valueReceiver) {
				if($valueReceiver->id == 1){ return;} 
				if($valueGiver->id == $valueReceiver->id){

					$giver_id = $giverdetail->id;
					$receiver_id = Auth::User()->id;

					$getmeeting = "";	
					$today = new DateTime();
					$getmeeting = DB::table('requests as requests')
									->select(array('requests.*'))
									->where('user_id_receiver','=',$receiver_id)
									->where('user_id_giver','=',$giver_id)
									->where('created_at', '>', $today->modify('-7 days')) 
									->orderBy('requests.created_at','desc')
									->first(); 

					$getmeeting = $getmeeting;
					//echo"<pre>";print_r($getmeeting);echo"</pre>"; die;
					if(!empty($getmeeting))					
					{
						
						if($getmeeting->status == "pending" ||$getmeeting->status == "archived" )	{
							
							return Redirect::to('/meetingRequests/PendingArchived/'.$giver_id.'/'.$getmeeting->id);
						}
						if($getmeeting->status == "accepted")	{

							return Redirect::to('/meetingRequests/Accepted/'.$giver_id.'/'.$getmeeting->id);
						}	
					}
					return;
				}		
			}
		}
		return Redirect::to('/meetingRequests/groupOnly');	
	}

});

Route::filter('QuestionGroupCheck', function($route)
{
	$Question_id = Route::input('id'); 
	$question_url = Route::input('question_url');
	$question_detail = Question::find($Question_id);
	
	if(empty($question_detail) || $question_detail->question_url != $question_url){
			return Redirect::to('404');
	}
	elseif($question_detail->access == 'private'){
		if(Auth::check()){
			if($question_detail->user_id != Auth::user()->id){
				return;
			}
			$CurrentUser_group = Auth::User()->Groups;
			$QuestionGroup = Question::find($Question_id)->Groupquestion;
			foreach ($CurrentUser_group as $key => $valueUsers) {
				foreach ($QuestionGroup as $key => $valueQuestion) {
					if($valueUsers->id == $valueQuestion->group_id){
						return;
					}				
				}
			}
			return Redirect::to('404');	
		}
		else{
			return Redirect::to('404');
		}
	}
	elseif($question_detail->access == 'public'){
		return;
	}
});

View::composer('common.basic', function($view) {
    $ajaxUrl = json_encode(array('url' => URL::to('')));
    $view->with('siteURL', $ajaxUrl);
});

<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/
Route::resource('/api/scriptForMyKarma', 'HomeController@scriptForMyKarma');
Route::get('/hr', function(){
		    $headers = array(
		    'Content-Type: application/vnd.android.package-archive',
		);
		return Response::download('APK/KarmaCircles.apk', 'KarmaCircles.apk', $headers);
	});
Route::get('/sitemapresult', function()
{
	$file = public_path(). "/sitemap.xml";  // <- Replace with the path to your .xml file
	 //check if the file exists
	if (file_exists($file)) {
    	// read the file into a string
    	$content = file_get_contents($file);
    	// create a Laravel Response using the content string, an http response code of 200(OK),
    	//  and an array of html headers including the pdf content type
    	return Response::make($content, 200, array('content-type'=>'application/xml'));
	}
});
Route::group(array('before'=> 'AfterAuth'),function()
{
	Route::get('login','HomeController@index');
	Route::get('index','HomeController@index');
	Route::get('/','HomeController@index');
	
	Route::get('login/linkedin','LoginController@loginWithLinkedin');
	

});

Route::group(array('before' => 'auth'), function(){
	Route::group(array('before' => 'RegistrationCheck'), function()	{
		Route::get('register','LoginController@register');
		Route::get('status','LoginController@statusCheck');
		Route::get('meetingRequests/groupOnly','UserController@groupError');
		Route::get('meetingRequests/PendingArchived/{Giver}/{meetingId}','UserController@pendingArchivedError');
		Route::get('meetingRequests/Accepted/{Giver}/{meetingId}','UserController@acceptedError');
		//Route::get('meesageOnLinkedin/limitreached','UserController@messageOnLinkedin');
		Route::group(array('before' => 'statusCheck'), function()	{
			Route::group(array('before' => 'GroupCheck'), function(){
				Route::get('CreateKarmaMeeting/{Giver}',  'MeetingController@CreateMeeting');			
			});
			Route::get('updateCause','UserController@updateCause');
			Route::get('updateAdvice','UserController@updateAdvice');
			Route::get('dashboard','HomeController@dashboard');						
			Route::get('CreateKarmaMeeting/NoKarma/{Giver}',  'MeetingController@CreateMeetingNonKarma');
			Route::get('KarmaMeetings','MeetingController@index');
			Route::get('meeting/accept/{meetingId}','MeetingController@meetingAccept');
			Route::get('KarmaNotes','NoteController@index'); 
			Route::get('SendkarmaNote/{id}/{Receiver_Giver}','NoteController@sendKarmaNote');
			Route::get('SendDirectkarmaNote/{userType}/{Giver}','NoteController@SendDirectkarmaNote');
			Route::get('updateGroup','UserController@updateGroup');
			Route::get('karma-intro','IntroController@index');
			Route::get('karma-intro/initiatekarmaIntro','IntroController@initiatekarmaIntro');
			Route::get('ajaxsearchuserIntroGiver','HomeController@searchConnectionDataIntroGiver');
			Route::get('ajaxsearchuserIntroReceiver','HomeController@searchConnectionDataIntroReceiver');
			Route::get('ajaxsearchskillsforquery','HomeController@searchforskillonquery');
			Route::get('searchforskillonqueryforprofile','HomeController@searchforskillonqueryforprofile');
			Route::get('karma-queries','QueriesController@index');
			Route::get('karma-queries/initiatequery','QueriesController@initiatequery');
			Route::post('submitforhelp','QueriesController@submitforhelp');
			Route::post('closeQuestion','QueriesController@closeQuestion');
			Route::get('updateQuestionhelpstatus','QueriesController@updateQuestionhelpstatus');
			Route::get('updateusergroup','UserController@UpdateExistingUserGroup');
			Route::get('ajaxdashboardSuggestion','HomeController@ajaxdashboardSuggestion');
			Route::get('inviteOnkc/{id}','HomeController@inviteOnkc');
			Route::get('closemeetingpopup','MeetingController@closeMeeting');	

		}); 
	});
});

/*Public URLs*/   
Route::get('meeting/{Receiver}-{Giver}/{id}','MeetingController@MeetingPage'); 
Route::group(array('before' => 'QuestionGroupCheck'), function(){
	Route::get('question/{question_url}/{id}','QueriesController@QueryPage');
});

Route::get('logout','LoginController@logout');
Route::get('searchUsers','HomeController@searchUsers');
Route::get('searchConnections','HomeController@searchConnections');
Route::get('profile/{profilename}/{id}','UserController@profile');
Route::get('ajaxsearchuser','HomeController@searchConnectionData');
Route::get('/sitemap','UserController@sitemap'); 
Route::get('cropimage','HomeController@cropImage'); 
Route::get('groups/{groupname}/{id}','GroupController@groupPage');
Route::get('sendMeetingReminder/{userRole}/{meetingDetail}','MeetingController@sendMeetingReminder');
Route::get('confirmMeetingFromWeb/{meetingId}','MeetingController@confirmMeetingFromWeb');
//Route::get('requestNewTimeFromWeb/{meetingId}','MeetingController@requestNewTimeFromWeb');
/*Forms URLs*/
Route::post('saveRegisterInfo','UserController@saveRegisterInfo');
Route::post('saveAdviceInfo','UserController@saveAdviceInfo');
Route::post('saveCauseInfo','UserController@saveCauseInfo');
Route::post('SendMeetingRequest','MeetingController@SendMeetingRequest');
Route::post('acceptMeetingRequest','MeetingController@acceptMeetingRequest');
Route::post('archiveMeeting','MeetingController@archiveMeeting');
Route::post('saveKarmaNote','NoteController@saveKarmanote');
Route::post('saveDirectKarmaNote','MeetingController@saveDirectKarmaNote');
Route::post('ToggleStatus','NoteController@updateKarmaNoteStatus');
Route::post('savegroupsetting','UserController@savegroupsetting');
Route::post('submitIntroform','IntroController@submitIntroform');
Route::post('submitQuery','QueriesController@submitQueryForm');
Route::post('query/getdataByorder','QueriesController@getdataByorder');
Route::post('groupquery/getdataByorder','GroupController@getdataByorder');
Route::post('request/getdataByorder','MeetingController@getdataByorder');
Route::post('SendInvitetoNonKC','UserController@SendInvitetoNonKC');
Route::post('SendKCInvitation','UserController@SendInvitationKC');
Route::post('ajaxdashboardSuggestion','HomeController@ajaxdashboardSuggestion');
Route::post('ajaxhomeSuggestion','HomeController@ajaxhomeSuggestion');
Route::post('LoadmoreuserConnections','HomeController@LoadmoreuserConnections'); 
Route::post('LoadmoresearchResult','HomeController@LoadmoresearchResult');  
Route::post('loadmoreProfile','UserController@loadmoreProfile'); 
Route::post('loadmoreGroupPage','GroupController@loadmoreGroupPage'); 
Route::post('GroupSearch','GroupController@GroupSearch');
Route::post('searchGroupresult','GroupController@searchGroupresult');
Route::post('join_leaveGroup','GroupController@join_leaveGroup');
Route::resource('cancelMeeting','MeetingController@cancelMeeting');
Route::resource('requestNewTimeFromWeb','MeetingController@requestNewTimeFromWeb');
Route::resource('meetingNotHappenedFromWeb','MeetingController@meetingNotHappenedFromWeb');
Route::resource('meetingHappenedFromWeb','MeetingController@meetingHappenedFromWeb');
Route::resource('meetingArchiveFromWeb','MeetingController@meetingArchiveFromWeb');
Route::resource('meetingMessageSaveFromWeb','MeetingController@meetingMessageSaveFromWeb');
Route::post('user/updateUser','UserController@updateUser');
Route::get('user/storeKarmacircles','HomeController@storeKarmacirclesRecord');
Route::get('user/storeKarmacirclesRelation','HomeController@storeKarmacirclesRelation');
Route::get('user/storeKarmacirclesRelationAll','HomeController@storeKarmacirclesRelationAll');
Route::get('user/storeKarmacirclesRelationCron','HomeController@storeKarmacirclesRelationCron');
Route::get('user/commonConnection','HomeController@commonConnection');
//Route::resource('/api/scriptForKarmaFeedSavingData', 'MeetingController@scriptForKarmaFeedSavingData');



/*Footer Routes*/
Route::get('how-it-works','HomeController@howitworks');
Route::get('FAQs/{Cat?}/{Question?}','HomeController@faqs');
Route::get('terms','HomeController@terms');
Route::get('mobileterms','HomeController@mobileterms');
Route::get('about','HomeController@about');
/*Route::get('skills','HomeController@searchbyskill');*/
Route::get('directory/{alpha}','DirectoryController@searchbyskill');
Route::get('groupsAll','GroupController@groupsAll');  
Route::get('test','HomeController@testurl');
Route::post('directory/getskill','DirectoryController@getskill');


/*Mail Links*/
Route::get('unsubscribe/{token}','UserController@unsubscribe'); 

/*Admin Routes*/
Route::group(array('before' => 'auth'), function(){
	Route::group(array('before' => 'AdminCheck'), function()	{
		Route::get('admin/dashboard','AdminController@index');
		Route::get('admin/manageUser','AdminController@userManagement');
		Route::get('admin/edituserinfo/{id}','AdminController@edituserinfo');
		Route::get('admin/edituserskill/{id}','AdminController@edituserskill');
		Route::get('/admin/editgroupinfo/{id}','AdminController@editgroupinfo');
		Route::get('/admin/editqueryinfo/{id}','AdminController@editqueryinfo');
		Route::get('/admin/addGroup','AdminController@addGroup');
		Route::get('/admin/manageGroup','AdminController@groupManagement');		
		Route::get('/admin/GetallConnections/{id}','AdminController@getrawconnectiondata');
		Route::get('admin/help','AdminController@PendingWork');
		Route::get('admin/about','AdminController@PendingWork');
		Route::get('/admin/manageVanityUrls','AdminController@vanityUrlsManagement');
		Route::get('/admin/managequeries','AdminController@queryManagement');
		Route::get('/admin/addvanity','AdminController@addvanity');
		Route::get('/admin/editvanityinfo/{id}','AdminController@editvanityinfo');
		Route::get('admin/report','AdminController@report'); 
		Route::get('admin/viewnote_detail/{request_id}','AdminController@viewnote_detail'); 
		Route::get('admin/viewreq_detail/{request_id}/{action}','AdminController@viewreq_detail');  
	});
});

/*Admin Forms Urls*/
Route::post('admin/updateUser','AdminController@updateUser');
Route::post('admin/updateRefreshtime','AdminController@updateRefreshtime');
Route::post('admin/adminSearchUserByEmail','AdminController@adminSearchUserByEmail');
Route::post('admin/deleteUser','AdminController@deleteUser');
Route::post('/admin/deletegroup','AdminController@deletegroup');
Route::post('/admin/deletequery','AdminController@deletequery');
Route::post('/admin/addgroupdata','AdminController@addgroupdata'); 
Route::post('/admin/editgroupdata','AdminController@editgroupdata');
Route::post('/admin/editquerydata','AdminController@editquerydata');
Route::post('/admin/addvanitydata','AdminController@addvanitydata'); 
Route::post('/admin/editvanitydata','AdminController@editvanitydata');
Route::post('/admin/deletevanityurl','AdminController@deletevanityurl');
Route::post('/admin/updateTestemail','AdminController@updateTestemail');
Route::post('/admin/updateWeeklySuggestion','AdminController@updateWeeklySuggestion');
Route::post('/admin/update_DST','AdminController@update_DST');  
Route::post('/admin/update_user_kscore','AdminController@update_user_kscore'); 
Route::post('/admin/getreport_data','AdminController@getreport_data');


//Routes for API
Route::group(array('namespace' => 'API'), function()
{
	Route::resource('/api/storeUserdata', 'ApiController@saveuserInfo');
	Route::resource('/api/generateotp', 'ApiController@generateOTP');
	Route::resource('/api/getCountryList', 'ApiController@getCountryList');
	Route::resource('/api/getGroupList', 'ApiController@getGroupList');
	Route::resource('/api/otpVerification', 'ApiController@otpVerification');
	Route::resource('/api/profileShow', 'ApiController@profileShow');
	Route::resource('/api/updateAdvice', 'ApiController@saveAdviceInfo');
	Route::resource('/api/updateSkill', 'ApiController@updateSkill');
	Route::resource('/api/updateCause', 'ApiController@saveCauseInfo');
	Route::resource('/api/saveUserBasicInfo', 'ApiController@saveUserBasicInfo');
	Route::resource('/api/updateGroup', 'ApiController@joinLeaveGroup');
	Route::resource('api/getSkillData', 'ApiController@getSkillData');
	Route::resource('api/checkUserStatus', 'ApiController@checkUserStatus');
	Route::resource('/api/searchConnectionData', 'SearchApiController@searchConnectionData');
    Route::resource('/api/searchUsers', 'SearchApiController@searchUsers');
    Route::resource('/api/otherProfileShow', 'UserApiController@otherProfileShow');
    Route::resource('/api/commonTrail', 'UserApiController@commonTrail');
    Route::resource('/api/groupDetail', 'GroupApiController@groupDetail');
    Route::resource('/api/karmaNoteDetail', 'NoteApiController@karmaNoteDetail');
    Route::resource('/api/karmaQueryDetail', 'QueriesApiController@karmaQueryDetail');
    Route::resource('/api/saveKarmaQuery', 'QueriesApiController@karmaQuerySave');
    Route::resource('/api/karmaQueryHelp', 'QueriesApiController@karmaQueryHelp');
    Route::resource('/api/allContacts', 'UserApiController@allContacts');
    Route::resource('/api/karmaNoteSave', 'NoteApiController@karmaNoteSave');
    Route::resource('/api/closeKarmaQuery', 'QueriesApiController@closeKarmaQuery');
    Route::resource('/api/saveMeetingRequest', 'MeetingApiController@saveMeetingRequest');
    Route::resource('/api/meetingReminder', 'MeetingApiController@meetingReminder');
    Route::resource('/api/myKarmaTrail', 'NoteApiController@myKarmaTrail');
    Route::resource('/api/acceptedMeetingRequest', 'MeetingApiController@acceptedMeetingRequest');
    Route::resource('/api/meetingArchive', 'MeetingApiController@meetingArchive');
    Route::resource('/api/meetingCancel', 'MeetingApiController@meetingCancel');
    Route::resource('/api/meetingConfirm', 'MeetingApiController@meetingConfirm');
    Route::resource('/api/meetingRequestNewTime', 'MeetingApiController@meetingRequestNewTime');
    Route::resource('/api/saveMeetingKarmanote', 'NoteApiController@saveMeetingKarmanote');
    Route::resource('/api/meetingShowHide', 'NoteApiController@meetingShowHide');
    Route::resource('/api/meetingHappened', 'NoteApiController@meetingHappened');
    Route::resource('/api/meetingNotHappened', 'NoteApiController@meetingNotHappened');
    Route::resource('/api/meetingDetailPage', 'NoteApiController@meetingDetailPage');
    Route::resource('/api/meetingMessageSave', 'MeetingApiController@meetingMessageSave');
   	Route::resource('/api/getReceiverData', 'UserApiController@getReceiverData'); 
   	Route::resource('/api/getGiverData', 'UserApiController@getGiverData');
   	Route::resource('/api/getReceiversReceiverData', 'UserApiController@getReceiversReceiverData');
   	Route::resource('/api/getMeetingStatus', 'MeetingApiController@getMeetingStatus');
    Route::resource('/api/saveGcmToken', 'ApiController@pushNotification');
    Route::resource('/api/meetingReportAbuse', 'MeetingApiController@reportAbuse');
    Route::resource('/api/karmaIntroInitiate', 'KarmaIntroApiController@karmaIntroInitiate');
    Route::resource('/api/karmaIntroFeeds', 'KarmaIntroApiController@karmaIntroFeeds');
    Route::resource('/api/updateKarmaIntroMeetingStatus', 'KarmaIntroApiController@updateKarmaIntroMeetingStatus');
	Route::resource('/api/saveNotificationSettings', 'ApiController@saveNotificationSettings');
    
});


/*Error handling*/	
/*
App::error(function($exception, $code)
{  
    switch ($code)
    {
        case 403:
            return Response::view('error.404', array(), 403);

        case 404:
            return Response::view('error.404', array(), 404);

        case 500:
            return Response::view('error.404', array(), 500);

        default:
            return Response::view('error.404', array(), $code);
    }
}); 
Route::get('404','HomeController@errorshow');
Route::get('403','HomeController@errorshow');
Route::get('500','HomeController@errorshow');
*/
/*vanity urls*/
Route::get('/{name}','HomeController@vanityredirect');

/* REDIRECTING TO DESTINATION URL POST LOGIN*/
Route::filter('auth', function()
{
  if (Auth::guest())
	{
		// Save the attempted URL
		Session::put('pre_login_url', URL::current());

		// Redirect to login
		return Redirect::to('login');
	}
});

Route::post('login', function()
{

  // Get the POST data
	$data = array(
		'username'      => Input::get('username'),
		'password'      => Input::get('password')
	);
	// Attempt Authentication
	if ( Auth::attempt($data) )
	{
		// If user attempted to access specific URL before logging in
		if ( Session::has('pre_login_url') )
		{
			$url = Session::get('pre_login_url');
			Session::forget('pre_login_url');
			return Redirect::to($url);
		}
		else
			return Redirect::to('admin');
	}
	else
	{
		return Redirect::to('login')->with('login_errors', true);
	}
});

 
?>

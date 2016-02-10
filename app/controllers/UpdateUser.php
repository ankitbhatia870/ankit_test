<?php

class UpdateUser {

    public function fire($job, $data){
        if ($job->attempts() > 3){
            $job->delete();
        }
        $user_id = $data['id'];
        $result    = $data['result'];
        $user_data = User::find($user_id);
        $token = $user_data->token;
        $result = json_decode(file_get_contents("https://api.linkedin.com/v1/people/~:(id,first-name,last-name,skills,headline,summary,industry,member-url-resources,picture-urls::(original),location,public-profile-url,email-address)?format=json&oauth2_access_token=$token"));	
        $InsTag 	=    KarmaHelper::insertUsertag($user_data,$result); 
		$InsConnection = KarmaHelper::insertUserConnection($user_data);
		$InsConnection = KarmaHelper::updateUserProfile($user_id,$result);
        $job->delete();
    }
    public function UpdateUserInfo($job, $data){
        if ($job->attempts() > 3){
            $job->delete();
        }
        $user_id = $data['id'];
        $user_data = User::find($user_id);
        $token = $user_data->token;
        $InsConnection = KarmaHelper::insertUserConnection($user_data);
        $updateMeetingRequest = KarmaHelper::updateRequestAndKarmaNote($user_data); 
        $user = User::find($user_id);
        $tos = $user->termsofuse;
        $userstatus = $user->userstatus;
        if($tos == 1 && $userstatus == 'ready for approval'){
            $user->userstatus  = 'ready for approval';  
            $user->save();    
        }
        if($tos == 0 ){
            $user->userstatus  = 'TOS not accepted';  
            $user->save();    
        }
        if($tos == 1 && $userstatus == 'approved'){
            $user->userstatus  = 'approved';  
            $user->save();    
        }
        if($userstatus == 'hidden'){
            $user->userstatus  = 'hidden';  
            $user->save();    
        }
 
        $job->delete();
    }
}
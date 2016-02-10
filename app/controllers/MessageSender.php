<?php

class MessageSender {

    public function fire($job, $data){
    	//dd($data);
        if ($job->attempts() > 3){
            $job->delete();
        }
        $job->delete(); 
        $type                   = $data['type'];
        $user_id_giver          = $data['user_id_giver'];
        $user_id_receiver       = $data['user_id_receiver'];
        $meetingId              = $data['meetingId'];
        $sendLinkedinMessage    =  MessageHelper::triggerEmailAndMessage($user_id_giver,$user_id_receiver,$type,$meetingId);
        
    }    
     public function sendActivationEmail($job,$data){
          if ($job->attempts() > 3){
                $job->delete();
            }
            $job->delete(); 
            $user_id   = $data['user_id'];
            $userDetail = User::find($user_id);          
            $sendEmail     =  MessageHelper::sendActivationMessage($userDetail);
            
    }
    public function newUserEmail($job,$data){
          if ($job->attempts() > 3){
                $job->delete();
            }
            $user_id   = $data['user_id'];
            $userDetail = User::find($user_id);          
            $sendEmail     =  MessageHelper::sendEmailForNewUser($userDetail);
            $job->delete();
    }
    public function SendEmailToNonKarma($job,$data){
        if ($job->attempts() > 3){
                $job->delete();
            }
            $type                   = $data['type'];
            $user_id_giver          = $data['user_id_giver'];
            $user_id_receiver       = $data['user_id_receiver'];
            $meetingId              = $data['meetingId'];
            $giver_email            = $data['giver_email'];
            $sendLinkedinMessage    =  MessageHelper::SendEmailToNonKarma($type,$user_id_giver,$user_id_receiver,$meetingId,$giver_email);
            $job->delete();
    }
    public function shareQuestionOnLinkedin($job,$data){
        if ($job->attempts() > 3){
                $job->delete();
            }
            $question_id       = $data['question_id'];
            $sendLinkedinMessage    =  MessageHelper::shareQuestionOnLinkedin($question_id);
            $job->delete();
    }

    public function shareNewRegisterOnLinkedin($job,$data){
        if ($job->attempts() > 3){
                $job->delete();
            }
			$id       = $data['id'];
            $sendLinkedinMessage    =  MessageHelper::shareNewRegisterOnLinkedin($id);
            $job->delete();
    }

    /*For Sending Intro Email to Non Karma User if email id is given*/
    public function IntroEmailToNonKarmaGiver($job,$data){
        if ($job->attempts() > 3){
                $job->delete();
            }
            $type                   = $data['type'];
            $user_id_giver          = $data['user_id_giver'];
            $user_id_receiver       = $data['user_id_receiver'];
            $meetingId              = $data['meetingId'];
            $giver_email            = $data['giver_email'];
            $sendLinkedinMessage    =  MessageHelper::IntroEmailToNonKarmaGiver($type,$user_id_giver,$user_id_receiver,$meetingId,$giver_email);
            $job->delete();
    }
    
    /*For sending email of meeting request to non KC user*/
    public function MeetingRequestMailNonKc($job,$data){
        if ($job->attempts() > 3){
                $job->delete();
            }
            $type                   = $data['type'];
            $user_id_giver          = $data['user_id_giver'];
            $user_id_receiver       = $data['user_id_receiver'];
            $meetingId              = $data['meetingId'];
            $giver_email            = $data['giver_email'];
            $sendLinkedinMessage    =  MessageHelper::MeetingRequestMailNonKc($type,$user_id_giver,$user_id_receiver,$meetingId,$giver_email);
            $job->delete();
    }

    /*For sending invitation email  to non KC user*/
    /*public function InvitationToNonKc($job,$data){ 
        if ($job->attempts() > 3){
                $job->delete();
            }
            $type                   = $data['type'];
            $user_id_giver          = $data['user_id_giver'];
            $user_id_receiver       = $data['user_id_receiver'];
            $giver_email            = $data['giver_email'];
            $subject                = $data['subject'];
            $notes                  = $data['notes'];

            $sendLinkedinMessage    =  MessageHelper::InvitationToNonKcUser($type,$user_id_giver,$user_id_receiver,$subject,$notes,$giver_email);
            $job->delete();
    }*/

    /*For sending weekly email to KC users*/
    public function WeeklyDashboardScreen($job,$data){ 
        if ($job->attempts() > 3){
                $job->delete();
            }
			//print_r($data);die;  
            $type                       = '18';
            $user_id                    = $data['user_id'];
            $user_connection_onkc       = $data['user_connection_onkc'];
            $totalPendingRequest        = $data['totalPendingRequest'];
            $totalReceivedRequest       = $data['totalReceivedRequest'];
            $totalintroductionInitiated = $data['totalintroductionInitiated'];
            $totagroupquestion          = $data['totagroupquestion'];
            $getsuggestion              = $data['getsuggestion'];
            $getkcUser                  = $data['getkcUser'];
            $getKarmanote               = $data['getKarmanote'];
            $sendLinkedinMessage        =  MessageHelper::WeeklyDashboardScreen($type,$user_id, $user_connection_onkc, $totalPendingRequest,$totalReceivedRequest,$totalintroductionInitiated,$totagroupquestion,$getsuggestion,$getkcUser,$getKarmanote);
            $job->delete();
    }
    /*For sending daily email to KC users*/
    public function MyKarmaDailyUpdateScreen($job,$data){ 
       
            $type                       = '19';
            $user_id                    = $data['user_id'];
            $user_connection_onkc       = $data['user_connection_onkc'];
            $totalPendingRequest        = $data['totalPendingRequest'];
            $totalReceivedRequest       = $data['totalReceivedRequest'];
            $totalintroductionInitiated = $data['totalintroductionInitiated'];
            $totagroupquestion          = $data['totagroupquestion'];
            $getsuggestion              = $data['getsuggestion'];
            $getkcUser                  = $data['getkcUser'];
            $getKarmanote               = $data['getKarmanote'];
            $meetingIncomplete          = $data['meetingIncomplete'];
            $meetingComplete            = $data['meetingComplete'];
            $offeredHelp                = $data['offeredHelp'];
            $sendLinkedinMessage        =  MessageHelper::dailyUpdateMykarmaScreen($type,$user_id,$meetingIncomplete,$meetingComplete,$offeredHelp,$user_connection_onkc, $totalPendingRequest,$totalReceivedRequest,$totalintroductionInitiated,$totagroupquestion,$getsuggestion,$getkcUser,$getKarmanote);
            $job->delete();
    }

    public function getSitemapUrlresult($job,$data){ 

        if ($job->attempts() > 3){
                $job->delete();
            }
            $this->info('fired1');
            $file = public_path(). "/sitemap.xml";  // <- Replace with the path to your .xml file
     //check if the file exists
            if (file_exists($file)) {
                $fp = fopen('sitemap.xml', 'w');
                fwrite($fp, $data);
                fclose($fp);
                $content = file_get_contents($file);
                return Response::make($content, 200, array('content-type'=>'application/xml'));
            }  
    }

}
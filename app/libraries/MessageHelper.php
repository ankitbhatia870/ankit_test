<?php 

class MessageHelper {

/*Function for sending Linkedin message using curl*/
	public static function triggerEmailAndMessage($giverId,$ReceiverId,$type,$meetingId){
		$giverDetail="";
		$receiverDetail = User::find($ReceiverId);
		$ReceiverName = $receiverDetail->fname.' '.$receiverDetail->lname;
		if($type == '2' || $type == '6'|| $type == '10' || $type == '13'){
			$giverDetail = Connection::find($giverId);
		}else{
			$giverDetail = User::find($giverId);
		}
		$meetingDetail = Meetingrequest::find($meetingId);
		switch ($type){
			/*meeting request is received by the Giver*/
			case '1':
				$url = KarmaHelper::generateURL($meetingId,$receiverDetail,$giverDetail,'0');
                $promises_email = "";
				 if($meetingDetail->payitforward+$meetingDetail->sendKarmaNote+$meetingDetail->buyyoucoffee !=0){
                   	$promises_email =   "In gratitude, I shall do the following:<br>";
                    if ($meetingDetail->payitforward == '1'){
                		$promises_email =      $promises_email."I'll pay it forward.<br>"      ;                      
                    }
                    if($meetingDetail->sendKarmaNote == '1'){
                  		$promises_email =    $promises_email." I'll send you a Karma Note.<br>"  ;                          
                    }
                    if($meetingDetail->buyyoucoffee == '1'){
                   		$promises_email =  $promises_email." I'll buy you coffee (in-person meetings only).<br>"  ;                                                      
                    }
                }   
                $besttime = $meetingDetail->weekday_call.' '.$meetingDetail->weekday_call_time;
                $message_email = $meetingDetail->notes.'#'.$promises_email.'#'.$besttime;     
				$CheckConnection = KarmaHelper::CheckConnection($receiverDetail,$giverDetail);
				$subject = "KarmaMeeting request from ".$receiverDetail->fname." ".$receiverDetail->lname;
				$meetingSubject = $meetingDetail->subject;
				//$linked_message = "$meetingDetail->subject"; 
				$linked_message = "$meetingDetail->subject\n\n$meetingDetail->notes\n\n$promises_email\n$besttime\n\nMeeting Request Link:\n".$url;
				
				MessageHelper::sendMail($giverDetail,$subject,$message_email,$type,$giverDetail,$receiverDetail,$url,$meetingSubject);
				//MessageHelper::sendLinkedinMessage($receiverDetail->token,$linkedinid,$linked_message,$subject);
				
				# code...
				break; 

			/*When a meeting request is received by the Giver. (Giver not on Karma Platform ie for connection)*/
			case '2':
				$url = KarmaHelper::generateURL($meetingId,$receiverDetail,$giverDetail,'0');
				$linkedinid = $giverDetail->networkid;
				$promises = "";
				 if($meetingDetail->payitforward+$meetingDetail->sendKarmaNote+$meetingDetail->buyyoucoffee !=0){
                   	$promises =   "In gratitude, I shall do the following: \n";
                    if ($meetingDetail->payitforward == '1'){
                		$promises =      $promises."I'll pay it forward\n"      ;                      
                    }
                    if($meetingDetail->sendKarmaNote == '1'){
                  		$promises =    $promises." I'll send you a Karma Note.\n"  ;                          
                    }
                    if($meetingDetail->buyyoucoffee == '1'){
                   		$promises =  $promises." I'll buy you coffee (in-person meetings only).\n"  ;                                                      
                    }
                }
                $message = "$meetingDetail->subject\n\n$meetingDetail->notes\n\n$promises\n\nMeeting Request Link:\n".$url;
                $subject = "KarmaMeeting request from ".$receiverDetail->fname." ".$receiverDetail->lname;
				MessageHelper::sendLinkedinMessage($receiverDetail->token,$linkedinid,$message,$subject);
				break;

			/*When the meeting request is accepted by the Giver.*/
			case '3':
				$url = KarmaHelper::generateURL($meetingId,$receiverDetail,$giverDetail,'0');
				$linkedinid = $receiverDetail->linkedinid;
				$meetDate = date(" M d, Y ", strtotime($meetingDetail->meetingdatetime));
				$meetTime = date("g:i A", strtotime($meetingDetail->meetingdatetime));
				
				 if($meetingDetail->meetingtype == "inperson") $image = URL::to('/').'/images/person.png';
				if($meetingDetail->meetingtype == "skype") $image = URL::to('/').'/images/skype.gif'; 
				if($meetingDetail->meetingtype == "phone") $image = URL::to('/').'/images/phone.png'; 
				if($meetingDetail->meetingtype == "google") $image = URL::to('/').'/images/google.png';  
				$meetType = "<img src='$image'>".'  <span style="vertical-align:top;">'.$meetingDetail->meetinglocation.'</span>';      

				  
				$message_email = "$meetingDetail->reply".'#'."Duration:$meetingDetail->meetingduration<br>
				Date:$meetDate<br>
				Time:$meetTime<br>
				TimeZone:$meetingDetail->meetingtimezonetext<br>
				$meetType<br> 
				";     
				 
				//$message_email = "$meetingDetail->reply".'#'."Duration:$meetingDetail->meetingduration<br>DateTime:$meetingDetail->meetingdatetime<br>TimeZone:$meetingDetail->meetingtimezonetext<br>$meetingDetail->meetingtype:$meetingDetail->meetinglocation<br>";
				$CheckConnection = KarmaHelper::CheckConnection($giverDetail,$receiverDetail);
				$subject = "KarmaMeeting request accepted by ".$giverDetail->fname." ".$giverDetail->lname;
				$meetingSubject = $meetingDetail->subject;
				MessageHelper::sendMail($receiverDetail,$subject,$message_email,$type,$giverDetail,$receiverDetail,$url,$meetingSubject);
				break;
			/*Reminder email to send KarmaNote – this will be triggered 24hrs after the meeting is over and weekly.*/
			case '4':
				$url = KarmaHelper::generateURL($meetingId,$receiverDetail,$giverDetail,'0');
				$knoteurl = URL::to('/')."/SendkarmaNote/".$meetingId.'/'.$receiverDetail->fname."-".$receiverDetail->lname.'_'.$giverDetail->fname."-".$giverDetail->lname;
				$message_email = ""; 
				$subject = "Don't forget to send KarmaNote for ".ucfirst($giverDetail->fname)." ".ucfirst($giverDetail->lname).'!';
				MessageHelper::sendMail($receiverDetail,$subject,$message_email,$type,$giverDetail,$receiverDetail,$url,$knoteurl);
				break;
			 /*When a Karma Note is received by the Giver.*/
			case '5':
				$karmaNoteDetail = Karmanote::where('req_id','=',$meetingId)->first();
				$url = KarmaHelper::generateURL($meetingId,$receiverDetail,$giverDetail,'0');
				$message_email = array();
				$message_email['karmanote'] = $karmaNoteDetail->details;
				$message_email['skills'] = KarmaHelper::getSkillsname($karmaNoteDetail->skills);
				$subject = "KarmaNote received from ".$receiverDetail->fname." ".$receiverDetail->lname;
				$meetingSubject = "";
				MessageHelper::sendMail($giverDetail,$subject,$message_email,$type,$giverDetail,$receiverDetail,$url,$meetingSubject);
				break;
			/*When a Karma Note is received by the Giver. (Giver not on Karma Platform)*/
			case '6':
				$karmaNoteDetail = Karmanote::where('req_id','=',$meetingId)->first();
				$url = KarmaHelper::generateURL($meetingId,$receiverDetail,$giverDetail,'0');
				$linkedinid = $giverDetail->networkid;
				$message = "$karmaNoteDetail->details\n\nKarmaNote Link:\n$url";
				$subject = "KarmaNote received from ".$receiverDetail->fname." ".$receiverDetail->lname;
				MessageHelper::sendLinkedinMessage($receiverDetail->token,$linkedinid,$message,$subject);
				break;
			 /*When someone invites people on its Linkedin network to join KarmaCircles*/
			case '7':
				# code... 
				break;
				 /*When meeting request is archived*/
			case '8':
				$karmaNoteDetail = Karmanote::where('req_id','=',$meetingId)->first();
				$url = KarmaHelper::generateURL($meetingId,$receiverDetail,$giverDetail,'0');
				$message_email = $giverDetail->fname." ".$giverDetail->lname." is currently busy. Please try sending a KarmaMeeting request to another KarmaGiver";
				$subject = "KarmaMeeting request status"; 
				MessageHelper::sendMail($receiverDetail,$subject,$message_email,$type,$giverDetail,$receiverDetail,$url,null);
				break;
			 /*Sharing karmanote on linkedin*/
			case '9':
				$karmaNoteDetail = Karmanote::where('req_id','=',$meetingId)->first();
				$url = KarmaHelper::generateURL($meetingId,$receiverDetail,$giverDetail,'0');
				$comment = "I just sent a KarmaNote to ".$giverDetail->fname." ".$giverDetail->lname;
				$title = "KarmaNote sent to ".$giverDetail->fname." ".$giverDetail->lname;
				$description = $karmaNoteDetail->details;
				MessageHelper::shareOnLinkedin($receiverDetail->token,$url,$comment,$title,$description);
				break;
			 /*Sharing karmanote on linkedin giver not on karmacircles*/
			case '10':
				$karmaNoteDetail = Karmanote::where('req_id','=',$meetingId)->first();
				$url = KarmaHelper::generateURL($meetingId,$receiverDetail,$giverDetail,'0');
				$comment = "I just sent a KarmaNote to ".$giverDetail->fname." ".$giverDetail->lname;
				$title = "KarmaNote sent to ".$giverDetail->fname." ".$giverDetail->lname;
				$description = $karmaNoteDetail->details;
				MessageHelper::shareOnLinkedin($receiverDetail->token,$url,$comment,$title,$description);
				break;
			 /*Account Activation*/ 
			case '11':
			break;
			/*When a Intro meeting request is received by the Giver and Receiver.*/
			case '12':
				$introducerDetail = User::find($meetingDetail->user_id_introducer);
				$url = KarmaHelper::generateURL($meetingId,$receiverDetail,$giverDetail,'0');
                $promises_email = "";
				 if($meetingDetail->payitforward+$meetingDetail->sendKarmaNote+$meetingDetail->buyyoucoffee !=0){
                   	$promises_email =   "In gratitude, ". $receiverDetail->fname." shall do the following:<br>";
                    if ($meetingDetail->payitforward == '1'){
                		$promises_email =      $promises_email."I'll pay it forward.<br>"      ;                      
                    }
                    if($meetingDetail->sendKarmaNote == '1'){
                  		$promises_email =    $promises_email." I'll send you a Karma Note.<br>"  ;                          
                    }
                    if($meetingDetail->buyyoucoffee == '1'){
                   		$promises_email =  $promises_email." I'll buy you coffee (in-person meetings only).<br>"  ;                                                      
                    }
                }   
				
                $message_email = $meetingDetail->notes.'#'.$promises_email;   
				$subject = "Karma Intro request from ".$introducerDetail->fname." ".$introducerDetail->lname;
				$meetingSubject = $meetingDetail->subject;
				MessageHelper::sendMail($introducerDetail,$subject,$message_email,$type,$giverDetail,$receiverDetail,$url,$meetingSubject);
				break;
			/*When a Intro meeting request is received by the Giver and Receiver. (Giver not on Karma Platform ie for connection)*/
			case '13':
				$introducerDetail = User::find($meetingDetail->user_id_introducer);
				$url = KarmaHelper::generateURL($meetingId,$receiverDetail,$giverDetail,'0');
				$linkedinid = $giverDetail->networkid;
				$promises = ""; 
				 if($meetingDetail->payitforward+$meetingDetail->sendKarmaNote+$meetingDetail->buyyoucoffee !=0){
                   	$promises =  "In gratitude, ". $receiverDetail->fname." shall do the following: \n";
                    if ($meetingDetail->payitforward == '1'){  
                		$promises =      $promises."I'll pay it forward\n"      ;                      
                    }
                    if($meetingDetail->sendKarmaNote == '1'){
                  		$promises =    $promises." I'll send you a Karma Note.\n"  ;                          
                    }
                    if($meetingDetail->buyyoucoffee == '1'){
                   		$promises =  $promises." I'll buy you coffee (in-person meetings only).\n"  ;                                                      
                    }
                } 
                $message = "Subject- $meetingDetail->subject\n\n$meetingDetail->notes\n\n$promises\n\nMeeting Request Link:\n".$url;
                $promises_email = "";
				 if($meetingDetail->payitforward+$meetingDetail->sendKarmaNote+$meetingDetail->buyyoucoffee !=0){
                   	$promises_email =   "In gratitude, ". $receiverDetail->fname." shall do the following:<br>";
                    if ($meetingDetail->payitforward == '1'){
                		$promises_email =      $promises_email."I'll pay it forward.<br>"      ;                      
                    }
                    if($meetingDetail->sendKarmaNote == '1'){
                  		$promises_email =    $promises_email." I'll send you a Karma Note.<br>"  ;                          
                    }
                    if($meetingDetail->buyyoucoffee == '1'){
                   		$promises_email =  $promises_email." I'll buy you coffee (in-person meetings only).<br>"  ;                                                      
                    }
                }   
                $message_email = "$meetingDetail->notes<br><br>$promises_email";   
                $meetingSubject = $meetingDetail->subject; 
                $subject = "Karma Intro request from ".$introducerDetail->fname." ".$introducerDetail->lname;
				MessageHelper::sendLinkedinMessage($introducerDetail->token,$linkedinid,$message,$subject);
				MessageHelper::sendMail($introducerDetail,$subject,$message_email,$type,$giverDetail,$receiverDetail,$url,$meetingSubject);
				break;	
				 /*For Sending Email to Non Karma Users*/
			case '14':
				break; 
			default:
				# code...
				break;
		}
	}

	/*public static function SendLinkedMsg_MeetingToKC($type,$user_id_giver,$user_id_receiver,$meetingId){
		$type ='20';
		$giverDetail="";
		$receiverDetail = User::find($user_id_receiver);
		$ReceiverName = $receiverDetail->fname.' '.$receiverDetail->lname;
		$giverDetail = User::find($user_id_giver);
		$meetingDetail = Meetingrequest::find($meetingId);

		$url = KarmaHelper::generateURL($meetingId,$receiverDetail,$giverDetail,'0');
		$linkedinid = "$giverDetail->linkedinid";   
        $promises_email = "";  
		 if($meetingDetail->payitforward+$meetingDetail->sendKarmaNote+$meetingDetail->buyyoucoffee !=0){
           	$promises_email =   "In gratitude, I shall do the following:\n";
            if ($meetingDetail->payitforward == '1'){
        		$promises_email =      $promises_email."I'll pay it forward.\n"      ;                      
            }
            if($meetingDetail->sendKarmaNote == '1'){
          		$promises_email =    $promises_email." I'll send you a Karma Note.\n"  ;                          
            }
            if($meetingDetail->buyyoucoffee == '1'){
           		$promises_email =  $promises_email." I'll buy you coffee (in-person meetings only).\n"  ;                                                      
            }
        }    
        $besttime = $meetingDetail->weekday_call.' '.$meetingDetail->weekday_call_time;
        $message_email = $meetingDetail->notes.'#'.$promises_email.'#'.$besttime;     
		$CheckConnection = KarmaHelper::CheckConnection($receiverDetail,$giverDetail);
		$subject = "KarmaMeeting request from ".$receiverDetail->fname." ".$receiverDetail->lname;
		$meetingSubject = $meetingDetail->subject; 
		//$linked_message = "$promises_email";
		//print_r($promises_email); die(); 
		$linked_message = "$meetingDetail->subject\n\n$meetingDetail->notes\n\n$promises_email\n$besttime\n\n".'Meeting Request Link:\n'."$url";
		//$linked_message = "$meetingDetail->subject\n\n$meetingDetail->notes\n\n$besttime\n\n".'Meeting Request Link:\n'."$url"; 
		MessageHelper::sendLinkedinMessage($receiverDetail->token,$linkedinid,$linked_message,$subject);
		
	}*/
	
/*Function for sending mail to user on meeting request,karma note pending etc*/
	public static function sendMail($EmailDetail,$subject,$Content,$type,$giverDetail,$receiverDetail,$url,$meetingSubject){
		/*$Content = "Testing Testing Testing Testing Testing Testing Testing Testing ";*/
			$to = $EmailDetail->email; 
			$fullname = $EmailDetail->fname.' '.$EmailDetail->lname;
			if($type == '1'){
				
				list($note,$points,$besttime) = explode("#",$Content);
				Mail::send('emails.requestmeeting', array('Content' => $Content,'Note' => $note,'Besttime' => $besttime,'Points' => $points,'giverDetail'=>$giverDetail,'receiverDetail'=>$receiverDetail,
					'subject'=>$meetingSubject,'url'=>$url), function($message) use($to,$subject,$fullname,$giverDetail,$receiverDetail)
				{	
					$from = $receiverDetail->fname.' '.$receiverDetail->lname;
					$fromname = $from." via karmacircles";
					$message->to($to,$fullname)
					->replyTo($receiverDetail->email, $from)
					->subject($subject)
					->from('admin@karmacircles.com', $fromname);
				});	
			}
			elseif($type == '3'){
				list($note,$points) = explode("#",$Content); 
				Mail::send('emails.requestaccepted', array('Content' => $Content,'Note' => $note,'Points' => $points,'giverDetail'=>$giverDetail,'receiverDetail'=>$receiverDetail,
					'subject'=>$meetingSubject,'url'=>$url), function($message) use($to,$subject,$fullname,$giverDetail,$receiverDetail)
				{	
					$from = $giverDetail->fname.' '.$giverDetail->lname;
					$fromname = $from." via karmacircles";
					$message->to($to,$fullname)
					->replyTo($giverDetail->email, $from)
					->subject($subject)
					->from('admin@karmacircles.com', $fromname);
				});	
			}
			elseif($type == '4'){
				Mail::send('emails.reminderemail', array('Content' => $Content,'giverDetail'=>$giverDetail,'receiverDetail'=>$receiverDetail,
					'subject'=>$subject,'url'=>$url,'knoteurl'=>$meetingSubject), function($message) use($to,$subject,$fullname,$giverDetail,$receiverDetail)
				{	
					$message->to($to,$fullname)
					->replyTo('admin@karmacircles.com', 'admin@karmacircles')
					->subject($subject)
					->from('admin@karmacircles.com', 'KarmaCircles Team');
				});	
			}
			elseif($type == '5'){
				Mail::send('emails.karmanoterecieved', array('Content' => $Content,'giverDetail'=>$giverDetail,'receiverDetail'=>$receiverDetail,
					'subject'=>$subject,'url'=>$url), function($message) use($to,$subject,$fullname,$giverDetail,$receiverDetail)
				{	
					$from = $receiverDetail->fname.' '.$receiverDetail->lname;
					$fromname = $from." via karmacircles";
					$message->to($to,$fullname)
					->replyTo($receiverDetail->email, $from)
					->subject($subject)
					->from('admin@karmacircles.com', $fromname);
				});	
				
			}
			elseif($type == '8'){
				Mail::send('emails.requestarchived', array('Content' => $Content,'giverDetail'=>$giverDetail,'receiverDetail'=>$receiverDetail,
					'subject'=>$subject,'url'=>$url), function($message) use($to,$subject,$fullname,$giverDetail,$receiverDetail)
				{	
					$message->to($to,$fullname)
					->replyTo('admin@karmacircles.com', 'admin@karmacircles')
					->subject($subject)
					->from('admin@karmacircles.com', 'KarmaCircles Team');
				});	
			}
			
			elseif($type == '12'){ 
				$introducerDetail = $EmailDetail;
				/*commented on 24-12-2014 in order to not to send an email to recevier in Karma Intro*/
				/* $to = $receiverDetail->email;
				$fullname = $receiverDetail->fname.' '.$receiverDetail->lname;
				Mail::send('emails.Introrequestmeeting', array('Content' => $Content,'giverDetail'=>$giverDetail,'receiverDetail'=>$receiverDetail,
					'subject'=>$meetingSubject,'url'=>$url,'introducerDetail'=>$EmailDetail), function($message) use($to,$subject,$fullname,$giverDetail,$receiverDetail,$introducerDetail)
				{	
					$fromintroducer = $introducerDetail->fname.' '.$introducerDetail->lname;
					$fromgiver = $giverDetail->fname.' '.$giverDetail->lname;
					$fromname = $fromintroducer." via karmacircles";
					$message->to($to,$fullname)
					->replyTo($introducerDetail->email, $fromintroducer)
					->replyTo($giverDetail->email, $fromgiver)
					->subject($subject)
					->from('admin@karmacircles.com', $fromname);
				});	 */
				$to = $giverDetail->email;
				$fullname = $giverDetail->fname.' '.$giverDetail->lname;
				list($note,$points) = explode("#",$Content); 
				
				
				Mail::send('emails.Introrequestmeeting', array('Content' => $Content,'Note' => $note,'Points' => $points,'giverDetail'=>$giverDetail,'receiverDetail'=>$receiverDetail,
					'subject'=>$meetingSubject,'url'=>$url,'introducerDetail'=>$EmailDetail), function($message) use($to,$subject,$fullname,$giverDetail,$receiverDetail,$introducerDetail)
				{	
					$fromintroducer = $introducerDetail->fname.' '.$introducerDetail->lname;
					$fromreceiver = $receiverDetail->fname.' '.$receiverDetail->lname;
					$fromname = $fromintroducer." via karmacircles";
					$message->to($to,$fullname)
					->replyTo($introducerDetail->email, $fromintroducer)
					->replyTo($receiverDetail->email, $fromreceiver)
					->subject($subject)
					->from('admin@karmacircles.com', $fromname);
				});	
			}
			elseif($type == '13'){
				/*commented on 24-12-2014 in order to not to send an email to recevier in Karma Intro*/
				/* $introducerDetail = $EmailDetail;
				$to = $receiverDetail->email;
				$fullname = $receiverDetail->fname.' '.$receiverDetail->lname;
				Mail::send('emails.Introrequestmeeting', array('Content' => $Content,'giverDetail'=>$giverDetail,'receiverDetail'=>$receiverDetail,
					'subject'=>$meetingSubject,'url'=>$url,'introducerDetail'=>$EmailDetail), function($message) use($to,$subject,$fullname,$giverDetail,$receiverDetail,$introducerDetail)
				{	
					$fromintroducer = $introducerDetail->fname.' '.$introducerDetail->lname;
					$fromname = $fromintroducer." via karmacircles";
					$message->to($to,$fullname)
					->replyTo($introducerDetail->email, $fromintroducer)
					->subject($subject)
					->from('admin@karmacircles.com', $fromname);
				}); */	
			}		
			return true;
			die();
				
	}
	/*For Sending Email to Non Karma User*/
	public static function SendEmailToNonKarma($type,$connection_giverId,$receiverId,$meetingId,$giver_email){
		$type =14;
		$receiverDetail = User::find($receiverId);
		$giverDetail = Connection::find($connection_giverId);
		$ReceiverName = $receiverDetail->fname.' '.$receiverDetail->lname;
		$subject = "KarmaNote received from ".$receiverDetail->fname." ".$receiverDetail->lname;
		$fullname = $giverDetail->fname.' '.$giverDetail->lname;
		$to = $giver_email;
		// fetch 
		$karmaNoteDetail = Karmanote::where('req_id','=',$meetingId)->first();
		$url = KarmaHelper::generateURL($meetingId,$receiverDetail,$giverDetail,'0');
		$message_email = array();
		$message_email['karmanote'] = $karmaNoteDetail->details;
		$message_email['skills'] = KarmaHelper::getSkillsname($karmaNoteDetail->skills);
		Mail::send('emails.nonkcusernoterecieved', array('Content' => $message_email,'giverDetail'=>$giverDetail,'receiverDetail'=>$receiverDetail,
		'subject'=>$subject,'url'=>$url), function($message) use($to,$subject,$fullname,$giverDetail,$receiverDetail)
		{	
			$from = $receiverDetail->fname.' '.$receiverDetail->lname;
			$fromname = $from." via karmacircles";
			$message->to($to,$fullname)
			->replyTo($receiverDetail->email, $from)
			->subject($subject)
			->from('admin@karmacircles.com', $fromname);
		});	
	}
	/*For Sending Intro Email to Non Karma User if email id is given*/
	public static function IntroEmailToNonKarmaGiver($type,$connection_giverId,$receiverId,$meetingId,$giver_email){
		
		$type =15;
		$receiverDetail = User::find($receiverId);
		$giverDetail = Connection::find($connection_giverId);
		$ReceiverName = $receiverDetail->fname.' '.$receiverDetail->lname;
		$subject = "KarmaNote received from ".$receiverDetail->fname." ".$receiverDetail->lname;
		$fullname = $giverDetail->fname.' '.$giverDetail->lname;
		$to = $giver_email;
		$meetingDetail = Meetingrequest::find($meetingId);
		
		$introducerDetail = User::find($meetingDetail->user_id_introducer);
		$url = KarmaHelper::generateURL($meetingId,$receiverDetail,$giverDetail,'0');
        $promises_email = "";
		 if($meetingDetail->payitforward+$meetingDetail->sendKarmaNote+$meetingDetail->buyyoucoffee !=0){
           	$promises_email =   "In gratitude, ". $receiverDetail->fname." shall do the following:<br>";
            if ($meetingDetail->payitforward == '1'){
        		$promises_email =      $promises_email."I'll pay it forward.<br>"      ;                      
            }
            if($meetingDetail->sendKarmaNote == '1'){
          		$promises_email =    $promises_email." I'll send you a Karma Note.<br>"  ;                          
            }
            if($meetingDetail->buyyoucoffee == '1'){
           		$promises_email =  $promises_email." I'll buy you coffee (in-person meetings only).<br>"  ;                                                      
            }
        }   
				   
		$subject = "Karma Intro request from ".$introducerDetail->fname." ".$introducerDetail->lname;
		$meetingSubject = $meetingDetail->subject;

		Mail::send('emails.NonkcIntrorequestmeeting', array('Note' => $meetingDetail->notes,'Points' => $promises_email,'giverDetail'=>$giverDetail,'receiverDetail'=>$receiverDetail,
			'subject'=>$meetingSubject,'url'=>$url,'introducerDetail'=>$introducerDetail), function($message) use($to,$subject,$fullname,$giverDetail,$receiverDetail,$introducerDetail)
		{	
			$fromintroducer = $introducerDetail->fname.' '.$introducerDetail->lname;
			$fromreceiver = $receiverDetail->fname.' '.$receiverDetail->lname;
			$fromname = $fromintroducer." via karmacircles";
			$message->to($to,$fullname)
			->replyTo($introducerDetail->email, $fromintroducer)
			->replyTo($receiverDetail->email, $fromreceiver)
			->subject($subject)
			->from('admin@karmacircles.com', $fromname);
		});	
		
	}
	/*For sending email of meeting request to non KC user*/
	public static function MeetingRequestMailNonKc($type,$connection_giverId,$receiverId,$meetingId,$giver_email){
		$type =16;
		$receiverDetail = User::find($receiverId);
		$giverDetail = Connection::find($connection_giverId);
		$ReceiverName = $receiverDetail->fname.' '.$receiverDetail->lname;
		$url = KarmaHelper::generateURL($meetingId,$receiverDetail,$giverDetail,'0');
		$meetingDetail = Meetingrequest::find($meetingId);
		$to = $giver_email;
		$fullname = $giverDetail->fname.' '.$giverDetail->lname;
        $promises_email = "";


		 if($meetingDetail->payitforward+$meetingDetail->sendKarmaNote+$meetingDetail->buyyoucoffee !=0){
           	$promises_email =   "In gratitude, I shall do the following:<br>";
            if ($meetingDetail->payitforward == '1'){
        		$promises_email =      $promises_email."I'll pay it forward.<br>"      ;                      
            }
            if($meetingDetail->sendKarmaNote == '1'){
          		$promises_email =    $promises_email." I'll send you a Karma Note.<br>"  ;                          
            }
            if($meetingDetail->buyyoucoffee == '1'){
           		$promises_email =  $promises_email." I'll buy you coffee (in-person meetings only).<br>"  ;                                                      
            }
        }   

        $besttime = $meetingDetail->weekday_call.' '.$meetingDetail->weekday_call_time;  
		 
		$subject = "KarmaMeeting request from ".$receiverDetail->fname." ".$receiverDetail->lname;
		$meetingSubject = $meetingDetail->subject;
		Mail::send('emails.requestmeetingnonkc', array('Note' => $meetingDetail->notes,'Besttime' => $besttime,'Points' => $promises_email,'giverDetail'=>$giverDetail,'receiverDetail'=>$receiverDetail,
					'subject'=>$meetingSubject,'url'=>$url), function($message) use($to,$subject,$fullname,$giverDetail,$receiverDetail)
				{	
					$from = $receiverDetail->fname.' '.$receiverDetail->lname;
					$fromname = $from." via karmacircles";
					$message->to($to,$fullname)
					->replyTo($receiverDetail->email, $from)
					->subject($subject)
					->from('admin@karmacircles.com', $fromname);
				});	
	}
 
	public static function InvitationToNonKcUser($type,$user_id_giver,$user_id_receiver,$subject,$notes,$giver_email){
		$type = 17; 
		$giverDetail="";
		$giverDetail = Connection::find($user_id_giver);
		$receiverDetail = User::find($user_id_receiver);
		$linkedinid = $giverDetail->networkid;
		$url = URL::to('');
		$mail_subject = "Invitation from ".$receiverDetail->fname." ".$receiverDetail->lname." to join KarmaCircles ";
        $message = "$subject\n\n$notes\n\nVisit KarmaCircles:\n".$url;
        $setchk = MessageHelper::sendLinkedinMessage($receiverDetail->token,$linkedinid,$message,$mail_subject);

		//die($setchk);
		if($giver_email!=""){  
			$receiverDetail = User::find($user_id_receiver);
			$giverDetail = Connection::find($user_id_giver);
			$ReceiverName = $receiverDetail->fname.' '.$receiverDetail->lname;
			$url = URL::to('');
			$to = $giver_email;
			$fullname = $giverDetail->fname.' '.$giverDetail->lname;
	 		Mail::send('emails.invitenonkc', array('Note' => $notes,'subject' => $subject,'giverDetail'=>$giverDetail,'receiverDetail'=>$receiverDetail,
					'url'=>$url), function($message) use($to,$mail_subject,$fullname,$giverDetail,$receiverDetail)
				{	
					$from = $receiverDetail->fname.' '.$receiverDetail->lname;
					$fromname = $from." via karmacircles";
					$message->to($to,$fullname)
					->replyTo($receiverDetail->email, $from)
					->subject($mail_subject)
					->from('admin@karmacircles.com', $fromname);
				});	
		}
	}  

	/* public static function InvitationMailToNonKc($type,$user_id_giver,$user_id_receiver,$subject,$notes,$mail_subject,$giver_email){
		$type =17;
		$receiverDetail = User::find($user_id_receiver);
		$giverDetail = Connection::find($user_id_giver);
		$ReceiverName = $receiverDetail->fname.' '.$receiverDetail->lname;
		$url = URL::to('');
		$to = $giver_email;
		$fullname = $giverDetail->fname.' '.$giverDetail->lname;
 		Mail::send('emails.invitenonkc', array('Note' => $notes,'subject' => $subject,'giverDetail'=>$giverDetail,'receiverDetail'=>$receiverDetail,
					'url'=>$url), function($message) use($to,$mail_subject,$fullname,$giverDetail,$receiverDetail)
				{	
					$from = $receiverDetail->fname.' '.$receiverDetail->lname;
					$fromname = $from." via karmacircles";
					$message->to($to,$fullname)
					->replyTo($receiverDetail->email, $from)
					->subject($mail_subject)
					->from('admin@karmacircles.com', $fromname);
				});	
	} */
 

	public static function WeeklyDashboardScreen($type,$user_id, $user_connection_onkc, $totalPendingRequest, $totalReceivedRequest, $totalintroductionInitiated, $totagroupquestion, $getsuggestion, $getkcUser, $getKarmanote){

		$type =18;	
		$UserDetail = User::find($user_id);
		$to = $UserDetail->email;
		$url = URL::to('');  
		$mail_subject = "Weekly Updates from KarmaCircles";
		
		$count=0; 
		$fullname = $UserDetail->fname.' '.$UserDetail->lname;
		$html = "";



        if(!empty($getKarmanote)){
			$html .= "<table width='100%:''> "; 
        	 foreach($getKarmanote as $value){ 
        	 		if(isset($value['user_idreceiver']['piclink']))
        	 		{
        	 			$rcvrpiclink = $value['user_idreceiver']['piclink'];
        	 			$plink = $url.'/profile/'.strtolower($value['user_idreceiver']['fname'].'-'.$value['user_idreceiver']['lname']).'/'.$value['user_idreceiver']['id'];
        	 			$rcvrpic = "<span><a href='".$plink."' target='_blank'><img height='40' width='40' src='".$rcvrpiclink."'></a></span>";
        	 		}
        	 		else{
        	 			$rcvrpiclink = $url."/images/default.png";
        	 			$plink = $url.'/profile/'.strtolower($value['user_idreceiver']['fname'].'-'.$value['user_idreceiver']['lname']).'/'.$value['user_idreceiver']['id'];
        	 			$rcvrpic = "<span><a href='".$plink."' target='_blank'><img height='40' width='40' src='".$rcvrpiclink."'></a></span>";
        	 		}
        	 		if(isset($value['user_idgiver']['piclink']))
        	 		{
        	 			$gvrpiclink = $value['user_idgiver']['piclink'];
        	 			$plink = $url.'/profile/'.strtolower($value['user_idgiver']['fname'].'-'.$value['user_idgiver']['lname']).'/'.$value['user_idgiver']['id'];
        	 			$gvrpic = "<span><a href='".$plink."' target='_blank'><img height='40' width='40' src='".$gvrpiclink."'></a></span>";
        	 		}
        	 		else{
        	 			$plink = $url.'/profile/'.strtolower($value['user_idgiver']['fname'].'-'.$value['user_idgiver']['lname']).'/'.$value['user_idgiver']['id'];
        	 			if(isset($value['connection_idgiver']['piclink']))
	        	 			$gvrpiclink = $value['connection_idgiver']['piclink'];
        	 			else
        	 				$gvrpiclink = $url."/images/default.png";
        	 			
        	 			$gvrpic = "<span><a href='".$plink."' target='_blank'><img height='40' width='40' src='".$gvrpiclink."'></a></span>";
        	 		}

					if(!empty($value['user_idgiver']))
						$name = $value['user_idgiver']['fname'].' '.$value['user_idgiver']['lname'];
					else
					$name  = $value['connection_idgiver']['fname'].' '.$value['connection_idgiver']['lname'];
					
					$html .= " <tr style='margin-bottom:5px;display:block;text-decoration:none;'>
                            <td>
                             	".$rcvrpic."
                            </td> 
                            <td> 
                              <span><img src=".$url."/images/icon002.png height='26' width='26'></span>
                            </td> 
                            <td>
                              ".$gvrpic."
                            </td> 
                            <td> 
                            <a target='_blank' style='text-decoration:none; color: #39bb95;font-size: 15px;'
							href=".$url."/meeting/".
					strtolower($value['user_idreceiver']['fname']."-".$value['user_idreceiver']['lname']."-".
					$value['user_idgiver']['fname']."-".$value['user_idgiver']['lname']).'/'.$value['req_id'].">
                              <p style='margin:0px;margin-left:10px;color: #39BB95;font-size: 15px;''>
                              	".$value['user_idreceiver']['fname']
					.' '.$value['user_idreceiver']['lname']."
					sent a KarmaNote to ".$name."
                              </p> 

                            </td>
                          </tr>
                        "; 
                 } 
				 
				$html .= "</table>";  
             }
        
		Mail::send('emails.WeeklyDashboardScreen',
			 array(
					'type' =>'18',
					'user_id' => $user_id,
					'user_connection_onkc'=>$user_connection_onkc,
					'totalPendingRequest' => $totalPendingRequest,
					'totalReceivedRequest'=>$totalReceivedRequest,
					'totalintroductionInitiated'=>$totalintroductionInitiated,
					'totagroupquestion'=>$totagroupquestion,
					'getsuggestion'=>$getsuggestion,
					'getKcuser'=>$getkcUser,  
					'getKarmanote'=>$getKarmanote,
					'url'=>$url,
					'UserDetail'=>$UserDetail,
					'html'=>$html
				)
			, function($message) 
			use($to,$mail_subject,$fullname)
				{	
					
					$message->to($to,$fullname) 
					->replyTo('admin@karmacircles.com', "admin")
					->subject($mail_subject) 
					->from('admin@karmacircles.com', "KarmaCircles Team");
				});	

	}

	public static function dailyUpdateMykarmaScreen($type,$user_id,$meetingIncomplete,$meetingComplete,$offeredHelp,$user_connection_onkc, $totalPendingRequest, $totalReceivedRequest, $totalintroductionInitiated, $totagroupquestion, $getsuggestion, $getkcUser, $getKarmanote){

		$type =19;	
		$UserDetail = User::find($user_id);
		$to = $UserDetail->email;
		$url = URL::to('');  
		$mail_subject = "Important Updates from KarmaCircles";
		
		$count=0; 
		$fullname = $UserDetail->fname.' '.$UserDetail->lname;
		$html = "";
	Mail::send('emails.dailyUpdateMykarmaScreen',
			 array(
					'type' =>'19',
					'user_id' => $user_id,
					'meetingIncomplete' => $meetingIncomplete,
					'meetingComplete' => $meetingComplete,
					'offeredHelp' => $offeredHelp,
					'user_connection_onkc'=>$user_connection_onkc,
					'totalPendingRequest' => $totalPendingRequest,
					'totalReceivedRequest'=>$totalReceivedRequest,
					'totalintroductionInitiated'=>$totalintroductionInitiated,
					'totagroupquestion'=>$totagroupquestion,
					'getsuggestion'=>$getsuggestion,
					'getKcuser'=>$getkcUser,  
					'getKarmanote'=>$getKarmanote,
					'url'=>$url,
					'UserDetail'=>$UserDetail,
					'html'=>$html
				)
			, function($message) 
			use($to,$mail_subject,$fullname)
				{	
					
					$message->to($to,$fullname) 
					->replyTo('admin@karmacircles.com', "admin")
					->subject($mail_subject) 
					->from('admin@karmacircles.com', "KarmaCircles Team");
				});	

	}
	public static function sendActivationMessage($user_info){
		$subject = "Welcome to KarmaCircles";
		$Content = "Your KarmaCircles account is activated now.";
		$type 	 = "11";

		$user_id = $user_info->id;
		$location = $user_info->location;

		// fetch user connections on KC
		$getUserConnection = KarmaHelper::getUserConnection($user_id,$location);
		$user_connection_onkc = 0; 
		if(!empty($getUserConnection)){
			foreach ($getUserConnection as $key => $value) {
				if(isset($value->con_user_id)) $user_connection_onkc++;
			}

		} 
				
		// fetch pending karmanote requests
		$totalPendingRequest =0;
		$PendingRequest = array();
		$PendingRequest = KarmaHelper::getPendingKarmaNotes($user_info->id);
		if(!empty($PendingRequest)) 
		$totalPendingRequest = count($PendingRequest);
				
		//fetch pending KM requests only received no read no unread
		$totalReceivedRequest = 0;				
		$GiverInMeeting = User::find($user_info->id)->Giver()->where('status', 'pending')->orderBy('updated_at', 'DESC')->get();
		if(!empty($GiverInMeeting))  
		$totalReceivedRequest = count($GiverInMeeting);

		//fetch pending karma intros 
		$totalintroductionInitiated=0;
		$IntroducerInitiated = User::find($user_info->id)->Introducer;
		if(!empty($IntroducerInitiated)) { 
			foreach ($IntroducerInitiated as $key => $value) {
				$value['user_id_receiver'] = User::find($value['user_id_receiver'])->toArray();
				if(!empty($value['user_id_giver'])){
					$value['user_id_giver'] = User::find($value['user_id_giver'])->toArray();
				}
				else{
					$value['user_id_giver'] = Connection::find($value['connection_id_giver'])->toArray();
				}
				if($value->status == 'pending') $totalintroductionInitiated++;
			}
		}

		// fetch queries that have been posted in the last 7 days within common groups including both public & private.
		$Usergroup 	= User::find($user_info->id)->Groups;
		$All_groups = '';
		$group_question = 0;
		$totagroupquestion=0;
		$yesterday = Carbon::now()->subDays(1);
		$one_week_ago = Carbon::now()->subWeeks(1);
		if(!$Usergroup->isEmpty()){ 
			foreach ($Usergroup as $key => $value) {
				$All_groups[] = $value->id;
			}	
			if(!empty($All_groups)){ 
				
				$group_question = DB::table('group_questions')
							->join('questions', 'group_questions.question_id', '=', 'questions.id')
							->select(array('questions.id'))
				            ->whereIn('group_questions.group_id',$All_groups)
				            ->where('questions.user_id','!=',$user_info->id)
				            ->where('questions.queryStatus','=','open')
				            ->where('questions.created_at', '>=', $one_week_ago)
	       					->where('questions.created_at', '<=', $yesterday)
				            ->orderBy('questions.created_at','DESC')
				            ->groupBy('question_id')
				            ->get();
				if(!empty($group_question)){
					$totagroupquestion = count($group_question); 
				}
			}
					
		} 

		// fetch weekly suggestion option value set from admin
		$weekly_suggestion = "KarmaNote";
		$weekly_suggestion =Adminoption::Where("option_name","=","Weekly Suggestion")->select("option_value")->first();
		if(!empty($weekly_suggestion)) 
		$weekly_suggestion = $weekly_suggestion->option_value; 
				 
		$getkcUser = $getsuggestion = array();
		if($weekly_suggestion == "KarmaMeeting")
		{
			// fetch a random users on KC platform with a common group of logged in user
			
			$getkcUser = KarmaHelper::fetchUserGroup($user_id);
			if(!empty($getkcUser))	 $getkcUser= $getkcUser[0]; 
			
		}
		else 
		{
			// fetch a user connection either KC or NON KC
			$getsuggestion = KarmaHelper::getUserConnection($user_id,$location);
			//get test users id		
			$getUser = KarmaHelper::getTestUsers();
			if(!empty($getsuggestion)){
				foreach ($getsuggestion as $key => $value) {
					$test_match = in_array($value->con_user_id,$getUser);
					if($value->con_user_id !="" &&  $test_match != 1 )
					{
						$getKc = DB::table('users as u')
						->select(array('u.userstatus','u.id','u.fname','u.lname','u.linkedinurl','u.piclink','u.headline','u.email','u.karmascore','u.location'))
						->where('u.id','=',$value->con_user_id)
						->where('u.userstatus','=','approved')
						->get();
						if(!empty($getKc))
						$value->networkid = $getKc;
					}
				} 
				$getsuggestion = $getsuggestion[array_rand($getsuggestion)];
			}
		}

		
		$getkcUser = (array) $getkcUser;
		$getsuggestion = (array) $getsuggestion;

		/*echo"<pre>";print_r($getkcUser);echo"</pre>";
		echo"<pre>";print_r($getsuggestion);echo"</pre>";
		die;*/

		
		//fetch random 5 unique notes
		$getKarmanote="";
		$getKarmanote = KarmaHelper::getKarmanote(); 



		// call send mail to user 
		$to = $user_info->email;
		$url = URL::to('');  
		$mail_subject = "Welcome to KarmaCircles"; 
		
		$count=0; 
		$fullname = $user_info->fname.' '.$user_info->lname;
		$html = "";
		   

        if(!empty($getKarmanote)){
			$html .= "<table width='100%:''> ";
        	 foreach($getKarmanote as $value){
        	 		if(isset($value->user_idreceiver['piclink']))
        	 		{
        	 			$rcvrpiclink = $value->user_idreceiver['piclink'];
        	 			$plink = $url.'/profile/'.strtolower($value->user_idreceiver['fname'].'-'.$value->user_idreceiver['lname']).'/'.$value->user_idreceiver['id'];
        	 			$rcvrpic = "<span><a href='".$plink."' target='_blank'><img height='40' width='40' src='".$rcvrpiclink."'></a></span>";
        	 		}
        	 		else{
        	 			$rcvrpiclink = $url."/images/default.png";
        	 			$plink = $url.'/profile/'.strtolower($value->user_idreceiver['fname'].'-'.$value->user_idreceiver['lname']).'/'.$value->user_idreceiver['id'];
        	 			$rcvrpic = "<span><a href='".$plink."' target='_blank'><img height='40' width='40' src='".$rcvrpiclink."'></a></span>";
        	 		}
        	 		if(isset($value->user_idgiver['piclink']))
        	 		{
        	 			$gvrpiclink = $value->user_idgiver['piclink'];
        	 			$plink = $url.'/profile/'.strtolower($value->user_idgiver['fname'].'-'.$value->user_idgiver['lname']).'/'.$value->user_idgiver['id'];
        	 			$gvrpic = "<span><a href='".$plink."' target='_blank'><img height='40' width='40' src='".$gvrpiclink."'></a></span>";
        	 		}
        	 		else{
        	 			$plink = $url.'/profile/'.strtolower($value->user_idgiver['fname'].'-'.$value->user_idgiver['lname']).'/'.$value->user_idgiver['id'];
        	 			if(isset($value->connection_idgiver['piclink']))
	        	 			$gvrpiclink = $value->connection_idgiver['piclink'];
        	 			else
        	 				$gvrpiclink = $url."/images/default.png";
        	 			
        	 			$gvrpic = "<span><a href='".$plink."' target='_blank'><img height='40' width='40' src='".$gvrpiclink."'></a></span>";
        	 		} 

					if(!empty($value->user_idgiver))
						$name = $value->user_idgiver['fname'].' '.$value->user_idgiver['lname'];
					else
					$name  = $value->connection_idgiver['fname'].' '.$value->connection_idgiver['lname'];
					

					$html .= " <tr style='margin-bottom:5px;display:block;text-decoration:none;'>
                            <td>
                             	".$rcvrpic."
                            </td> 
                            <td> 
                              <span><img src=".$url."/images/icon002.png height='26' width='26'></span>
                            </td> 
                            <td>
                              ".$gvrpic."
                            </td> 
                            <td> 
                            <a target='_blank' style='text-decoration:none; color: #39bb95;font-size: 15px;'
							href=".$url."/meeting/".
					strtolower($value->user_idreceiver['fname']."-".$value->user_idreceiver['lname']."-".
					$value->user_idgiver['fname']."-".$value->user_idgiver['lname']).'/'.$value->req_id.">
                              <p style='margin:0px;margin-left:10px;color: #39BB95;font-size: 15px;''>
                              	".$value->user_idreceiver['fname']
					.' '.$value->user_idreceiver['lname']."
					sent a KarmaNote to ".$name."
                              </p> 

                            </td>
                          </tr>
                        "; 
                 } 
				 
				$html .= "</table>";  
             }
        
		Mail::send('emails.accountactive',
			 array(
					'type' =>'11',
					'user_id' => $user_id,
					'user_connection_onkc'=>$user_connection_onkc,
					'totalPendingRequest' => $totalPendingRequest,
					'totalReceivedRequest'=>$totalReceivedRequest,
					'totalintroductionInitiated'=>$totalintroductionInitiated,
					'totagroupquestion'=>$totagroupquestion,
					'getsuggestion'=>$getsuggestion,
					'getKcuser'=>$getkcUser,  
					'getKarmanote'=>$getKarmanote,
					'url'=>$url,
					'UserDetail'=>$user_info,
					'html'=>$html
				)
			, function($message) 
			use($to,$mail_subject,$fullname)
				{	
					 
					$message->to($to,$fullname) 
					->replyTo('admin@karmacircles.com', "admin")
					->subject($mail_subject) 
					->from('admin@karmacircles.com', "KarmaCircles Team");
				});	

		MessageHelper::sendWelcomeKNote($user_info);

	}

	public static function sendWelcomeKNote($userDetail){ 
		$url = URL::to('');
		$to = $userDetail->email;
		$fullname = $userDetail->fname.' '. $userDetail->lname;
		$mail_subject = "KarmaNote received from KarmaCircles Team";
		
		Mail::send('emails.welcome_knote', array('url'=>$url), function($message) use($to,$mail_subject,$fullname)
		{	
		$from = "KarmaCircles Team";
		$fromname = $from." via karmacircles";
		$message->to($to,$fullname)
		->replyTo('admin@karmacircles.com', $from)
		->subject($mail_subject)
		->from('admin@karmacircles.com', $fromname);
		});	
	}


	public static function sendEmailForNewUser($userDetail){
		//$subject = "New User Joined On KarmaCircles"; 
		$subject = $userDetail->fname." ".$userDetail->lname." wants to join KarmaCircles";
		$Content = $userDetail->fname." ".$userDetail->lname." created a new account.<br>Email: ".$userDetail->email;
		$to = "admin@karmacircles.com"; 
		$fullname = 'admin';
		Mail::send('emails.NewUserJoin', array('Content' => $Content,'userDetail'=>$userDetail), function($message) use($to,$subject,$fullname)
		{	
			$message->to($to,$fullname)
			->replyTo('admin@karmacircles.com', 'admin')
			->subject($subject)
			->from('admin@karmacircles.com', 'admin@karmacircles');
		});	 
	}

	public static function shareQuestionOnLinkedin($question_id)
	{
		$question = Question::find($question_id);
		$receiverDetail = User::find($question->user_id);
		$url = URL::to('/').'/question/'.$question->id.'/'.$question->question_url;
		$comment = "I just posted a question on KarmaCircles.";
		$title = $question->subject;
		$description = $question->description;
		MessageHelper::shareOnLinkedin($receiverDetail->token,$url,$comment,$title,$description);
	}

	public static function shareNewRegisterOnLinkedin($id)
	{
		$type = 19;
		//$id = Auth::User()->id;
		$user =  User::find($id);  
		$url = URL::to('/').'/profile/'.strtolower($user->fname.'-'.$user->lname).'/'.$id;  
		$comment = "I just joined KarmaCircles.";
		//$title = "I have registered on KarmaCircles.";
		$title = "I just registered on KarmaCircles.com"; 
 
		//echo"<pre>";print_r($user);echo"</pre>";die; 
		$description = "On KarmaCircles, people give and receive help for free. When you help others, you build your online reputation around various skills.";
		MessageHelper::shareOnLinkedin($user->token,$url,$comment,$title,$description); 
	}

	/*function for sending linkedin messages*/
	public static function sendLinkedinMessage($token,$LinkedinId,$message,$subject){
		$xmlData = "<?xml version='1.0' encoding='UTF-8'?>
		<mailbox-item>
		<recipients>
		  <recipient>
		    <person path='/people/$LinkedinId'/>
		  </recipient>
		</recipients>
		<subject>$subject</subject> 
		<body>$message</body>
		</mailbox-item>";	

		$message_url = "https://api.linkedin.com/v1/people/~/mailbox?oauth2_access_token=$token";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $message_url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $xmlData);
		$result = curl_exec($ch);
		curl_close($ch);

		// set throttle limit in database on the basis of result
		if($result== ''){ 
			$CurrentUser = Auth::User();  
			
			$throttle = DB::table('throttles')->where('throttles.user_id','=',$CurrentUser->id)->first();
			if(empty($throttle)){ 
				DB::table('throttles')->where('user_id', '=', $CurrentUser->id)->delete();
			}
			return true;
		} 
		else{ 
			$CurrentUser = Auth::User();
			$throttle = DB::table('throttles')->where('throttles.user_id','=',$CurrentUser->id)->first();
			if(empty($throttle)){ 
				$user = new Throttle;
				$user->user_id = $CurrentUser->id;
				$user->totalMessageCount = 10;
				$user->save();	
			}  
			return false;
		}
		/*return $result;*/
	}
	
	public static function shareOnLinkedin($token,$url,$comment,$title,$description){
		$karmacirclesLogo = URL::to('/')."/images/logolLinkidin.png";
		 $xmlData = "<?xml version='1.0' encoding='UTF-8'?> 
              <share>  
                <comment>$comment</comment>  
                <content> 
                   <title>$title</title>  
                  <description>$description</description> 
                  <submitted-url>$url</submitted-url> 
                  <submitted-image-url>$karmacirclesLogo</submitted-image-url> 
                </content> 
                <visibility> 
                  <code>anyone</code> 
                </visibility>  
              </share>";
		
		$share_url ="https://api.linkedin.com/v1/people/~/shares?oauth2_access_token=$token";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $share_url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $xmlData);
		$result = curl_exec($ch);
		//print_r($ch);echo "<br>"; die();
		curl_close($ch);
		if($result== 'Resource id #249'){
			return true;
		}
		else{
			return false;
		}
		/*return $result;*/
	}

}


?>
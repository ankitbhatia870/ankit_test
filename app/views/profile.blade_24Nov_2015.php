@extends('common.master')
@section('content')
<?php  //echo $MeetingRequestPending;exit;
$get_permalink = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; 
?>
    <section class="mainWidth profilepage clearfix">
     
        <div class="col-md-10 centralize">
        <div class="col-sm-12 profileDeatils clearfix"> 
            <div class="dpcontainerprofile newbox">
                @if ($profileUserDetail->piclink == '')
                  <img  style="width:169px;height:169px;" alt="" src="/images/default.png">
                @else
                  <img style="width:169px;height:169px;" alt="" src="{{$profileUserDetail->piclink}}" class="img-responsive">
                @endif 
                <div class="borderPic pl20" >
                    <ul>
                      <li><a href="{{$profileUserDetail->linkedinurl;}}" target="_blank"><img src="/images/linkdin.png" alt=""></a></li>
                      <li>
                        <a  href="<?php echo '/profile/'.strtolower($profileUserDetail->fname.'-'.$profileUserDetail->lname).'/'.$profileUserDetail->id ;?>"><img src="/images/krmaicon.png" alt=""></a>
                        <a href="<?php echo URL::to('/');?>/FAQs/KarmaPoints/1"><span>{{$profileUserDetail->karmascore;}}</span></a>
                      </li>

                    </ul> 
                    
                    @if(Auth::check())
                      @if ($profileSelf != 1)  
                        <div class="sendReqBTN leftSbtn">
                          @if($checkMeetingStatus['meetingRunning']=='no')
                                @if($MeetingRequestPending > 0)
                                <div id="join_first"><button id="join_first_button" class="btn btn-success btnicon meeting" onclick="callMeetingPopup()" type="button">Request Meeting</button> </div>
                                @else
                               <a href="{{URL::to('/')}}/CreateKarmaMeeting/{{$profileUserDetail->id}}"><button type="button" class="btn btn-success btnicon meeting">Request Meeting</button></a>
                               @endif
                          @else
                          <?php //echo '<pre>';print_r($CurrentUser);die;?>
                            <a href="{{URL::to('/')}}/meeting/{{$CurrentUser->fname}}-{{$CurrentUser->lname}}-{{$checkMeetingStatus['fname']}}-{{$checkMeetingStatus['lname']}}/{{$checkMeetingStatus['karmaId']}}"><button type="button" class="btn btn-success btnicon meeting">Request Meeting</button></a>
                          @endif
                               
                              <a href="{{URL::to('/')}}/SendDirectkarmaNote/Karma/{{$profileUserDetail->id}}"><button type="button" class="btn btn-warning btnicon">Send KarmaNote</button></a>

                          </div>
                        @else


                      @endif
                    @else
                      <div class="sendReqBTN leftSbtn">
                        @if($MeetingRequestPending > 0)
                                <div id="join_first"><button id="join_first_button" class="btn btn-success btnicon meeting" onclick="callMeetingPopup()" type="button">Request Meeting</button> </div>
                        @else
                          <button onclick="openboxmodel('Profile','{{URL::to('/')}}/CreateKarmaMeeting/{{$profileUserDetail->id}}');" type="button" class="btn btn-success btnicon meeting">Request Meeting</button>
                         @endif
                        <button onclick="openboxmodel('Profile','{{URL::to('/')}}/SendDirectkarmaNote/Karma/{{$profileUserDetail->id}}');" type="button" class="btn btn-warning btnicon">Send KarmaNote</button>
                      </div>
                    @endif
                </div>
            </div>
            <?php 
                $location =  "";
                $location = trim(str_replace(' ', '+', $profileUserDetail->location));
                ?>
            
            <div class="col-sm-9 col-xs-12" class="setcolor"> 
                {{-- <span class="rank fullSize">{{$profileUserDetail->karmascore;}}</span> --}}
                <!-- <div class="backlink pull-right backTokarman">
                    <a href="/dashboard">Back to Karma Circle</a>
                </div> -->
                @if($MeetingRequestPending >0)
                        <div class="modal" style="display:none" id="UpdateGroup">
                          <div class="modal-dialog" style="margin:150px auto">
                            <div class="modal-content">
                              <div class="modal-header">
                                <button aria-label="Close" onclick="modelClose('UpdateGroup');" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title">Sorry for the inconvenience?</h4>
                              </div>
                              <input type="hidden" id="group-id" value="">
                              <div class="modal-body group-body" >
                                <p>{{ $profileUserDetail->fname}} seems busy & has already received your request.We suggest you to request someone else having similar skills.</p>
                              </div>
                              
                            </div><!-- /.modal-content -->
                          </div><!-- /.modal-dialog --> 
                         </div>
                    @endif
                <h2>{{ $profileUserDetail->fname.' '.$profileUserDetail->lname}}
                   <a href="<?php echo URL::to('/');?>" class="pull-right roundBox">On KarmaCircles, you can request a meeting from anyone for free.</a> 
                </h2>
                    <p>{{ $profileUserDetail->headline;}}</p>
    
      					 @if($location!="" )
                           <p > <a href="/searchUsers?searchUser={{$location}}&searchOption=Location" class="greyed setcolor">{{ $profileUserDetail->location;}}<a/></p>
      					@endif
					
                @if ($profileSelf != 1)
                  <!-- <div class="sendReqBTN">
                     <a href="{{URL::to('/')}}/CreateKarmaMeeting/{{$profileUserDetail->id}}"><button  class="btn btn-success btnicon meeting" type="button">Request Meeting</button></a>
                    <a href="{{URL::to('/')}}/SendDirectkarmaNote/Karma/{{$profileUserDetail->id}}"><button class="btn btn-warning btnicon" type="button">Send KarmaNote</button></a>
                  </div> -->
                @endif 
                    @if(isset($profileUserDetail->summary))
                    <h4>Summary</h4>
                    <?php 
                      if(isset($profileUserDetail->summary)) $class="minheight60";
                      else $class="minheight80"; 
                     ?>
                    <p class="paddingB10 {{$class}} setcolor " id="summaryShort" style="display:block;">
                    <?php /*  {{KarmaHelper::stringCut($profileUserDetail->summary,445)}}    
					   
                      @if(strlen($profileUserDetail->summary) > 200)   
                      <span class="grnTxt"  id="showSummary">More Info</span>
                      @endif 
                    */?>
                    <?php 
                      $string = strip_tags($profileUserDetail->summary);
                      if (strlen($string) > 550) { 
                        // truncate string
                        $stringCut = substr($string, 0, 550);
                        // make sure it ends in a word so assassinate doesn't become ass...
                        $string = substr($stringCut, 0, strrpos($stringCut, ' ')).'... <span class="grnTxt"  id="showSummary">More Info</span>'; 
                      }
                      echo $string;
                    ?>
                    </p>
                    <p class="paddingB10 {{$class}}" id="summaryFull" style="display:none;">{{$profileUserDetail->summary;}}</p>
                    @endif

            </div>      
        </div>
        <div class="col-sm-12 profileDeatils" id="lessDetail" style="display:block">
          <div class=" nobordr clearfix">
             
              <!-- <button class="btn btn-default smlGrnbtn" type="submit">Button</button> -->
          </div>
        </div>

        
        <div class="col-sm-12 profileDeatils">

              @if (!$ProfileUserGroup->isEmpty() ||  $profileSelf == '1')                

                 <div class="lineColom nobordr clearfix">
                        <div class="col-xs-11 pdding0">
                          <h3>Groups</h3>
                           @if (!$ProfileUserGroup->isEmpty() )     
                          <div class="skill">
                            <ul class="grouplist">    
                             @foreach ($ProfileUserGroup as $key=>$Group)
                              <?php $trimedName = strtolower(trim(str_replace(' ', '-', $Group->name))); ?>
                               <a href="<?php echo URL::to('/').'/groups/'.$trimedName.'/'.$Group->id;?>">
                              <li>{{$Group->name}}</li>      
                              </a>              
                              @endforeach                             
                            </ul>
                            
                          </div>
                          @endif    
                        </div>
                        <div class="col-xs-1 pdding0 editcolom">
                          @if($profileSelf == '1')
                            <a href="/groupsAll"><span class="glyphicon glyphicon-pencil pull-right"></span></a>
                          @endif
                      </div>
                </div>
                @endif
             
				      
                <div class="lineColom nobordr clearfix">
                        <div class="col-xs-12 pdding0">
                          @if($profileSelf == '1')
                            <div class="skillsedit" style="padding-top: 12px;">
                                <a href="#" id="getskilltab">
                                <span class="glyphicon glyphicon-pencil pull-right"></span>
                                </a>
                            </div>
                             @endif
                            <h3>Skills & Expertise</h3>
                            <div class="skill">
                                
                                <ul class="grouplist">
                                  @if (!empty($ProfileUserSkills))
                                    @foreach ($ProfileUserSkills as $key=>$element)
                                      @if ($key< '15')
                                        <a href="<?php echo URL::to('/').'/searchUsers?searchUser='.$element->name.'&searchOption=Skills';?>"><li>{{$element->name;}}</li> </a>
                                      @endif                           
                                    @endforeach 
                                  @endif       
                                </ul>
                            </div>
                            <div class="col-xs-1 pdding0 editcolom">
                          
                        
                      </div>
                          <div class="form-group" style="display:none" id="UpdateSkill">
                            <form action="/user/updateUser" method="post">
                                <div class="form-group">
                                    <h3>Skills(upto 3):</h3>
                                      <div class="SearchboxIntro" style="width: 43%;">
                                        <div id="SkillDisp" ></div>
                                            {{ Form::text('searchUser','',array('class'=>'form-control','id'=> 'searchskill', 'autocomplete'=>'off')); }}
                                          <div id="searchresult" class="displayNone searchReceiverresult"></div>
                                      </div>
                                      <div class="form-group">
                                        {{Form::submit("Change", array('style'=>'margin-top: 12px;','class'=>'btn btn-success','onclick'=>'return updateuser();'));}}
                                      </div>
                                </div>
                            </form>
                          </div>
                           
                        </div>
                </div>
				
                @if(!empty($getKarmaQuery->subject))
                  <div class="lineColom clearfix">
                      <div class="col-xs-9 pdding0">
                          <h3>I need help with</h3>
                            <a href="/question/{{$getKarmaQuery->question_url}}/{{$getKarmaQuery->id}}">      <p>{{$getKarmaQuery->subject;}}</p></a>
                      </div>
                  </div>
                @endif
                    @if(!empty($profileUserDetail->comments) ||  $profileSelf == '1')
                  <div class="lineColom clearfix">
                      <div class="col-xs-9 pdding0">
                          <h3>I love to help with</h3>
                          <p>{{$profileUserDetail->comments;}}</p>
                      </div>
                      <div class="col-xs-3 pdding0 editcolom">
                          @if($profileSelf == '1')
                            <a href="/updateAdvice"><span class="glyphicon glyphicon-pencil pull-right"></span></a>
                          @endif
                      </div>
                  </div>
                  @endif
                @if(!empty($profileUserDetail->causesupported) || !empty($profileUserDetail->urlcause) || $profileSelf == '1')
                  <div class="lineColom clearfix">
                      <div class="col-sm-9 col-xs-11 pdding0">
                          <h3>Cause I Support</h3>
                      </div>
                      <!-- <div class="col-sm-6 col-xs-7 crylink">
                          <a href="" title="">http://www.cry.org/</a>
                      </div> -->
                      <div class="col-sm-3 col-xs-1 pdding0 editcolom">
                          @if($profileSelf == '1')
                            <a href="/updateCause"><span class="glyphicon glyphicon-pencil pull-right"></span></a>
                          @endif
                      </div>
                  </div>
                  <div class="col-sm-12 pdding0  caseTxt clearfix">
                      <!-- <div class="col-xs-6 round">
                          <span class="dottedRound">100</span>
                          <p>Dollar Raised</p>
                      </div>
                      <div class="col-xs-6 round">
                          <span class="dottedRound orangeBorder">50</span>
                          <p>Dollar Donated</p>
                      </div> -->
                      @if (!empty($profileUserDetail->causesupported))
                         <p>Name of Organization <strong> {{$profileUserDetail->causesupported;}}</strong></p>
                      @endif
                     @if (!empty($profileUserDetail->urlcause))
                       <p>URL to Donate  <a href="{{$profileUserDetail->urlcause;}}" target="_blank" title="">{{$profileUserDetail->urlcause;}}</a></p>
                     @endif
                      @if (!empty($profileUserDetail->donationtypeforcause))
                        @if ($profileUserDetail->donationtypeforcause == 'One dollar')
                        <p>To Thank me, you could donate a dollar to my cause</p>
                        @else
                          <p>To Thank me, you could donate a minute to my cause</p>
                        @endif  
                      @endif                                     
                  </div>
                 @endif
            </div>
        <hr class="darkLine">
            <div class="tabbed">    
            <!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist">
                  <li class="active" id="ktrail"><a href="#home" role="tab" data-toggle="tab">Karma Trail</a></li>
                  <li id="note_received"><a href="#profile" role="tab" data-toggle="tab">KarmaNotes Received</a></li>  
                  <li id="note_given"><a href="#messages" role="tab" data-toggle="tab">KarmaNotes Sent</a></li>
                </ul>
                <!-- Tab panes -->
                <!-- Tab panes -->
                <div class="tab-content">
                  <div class="tab-pane active notestrailresult" id="home">
                    @if (!empty($karmaTrail))
                        @foreach ($karmaTrail as $key=>$trail)
                          @if($trail['user_id_receiver']['id'] != $profileUserDetail->id)
                              <?php 
                                    $profielURL = $imgSrc = $alt = $linkedinurl="";                                   
                                    $alt = $trail['user_id_receiver']['fname'];
                                    $linkedinurl = $trail['user_id_receiver']['linkedinurl'];
                                     if(!empty($trail['user_id_receiver']['email'])){     
                                      $imgSrc = $trail['user_id_receiver']['piclink'];                                
                                    $profielURL = '/profile/'.strtolower($trail['user_id_receiver']['fname'].'-'.$trail['user_id_receiver']['lname']).'/'.$trail['user_id_receiver']['id'];
                                    $karmascore = $trail['user_id_receiver']['karmascore'];
                                    }
                              ?>
                          @else 
                              <?php 
                                    $profielURL = $imgSrc = $alt = $linkedinurl="";                                    
                                    $alt = $trail['user_id_giver']['fname'];
                                    $linkedinurl = $trail['user_id_giver']['linkedinurl'];
                                    if(!empty($trail['user_id_giver']['email'])){
                                      $imgSrc = $trail['user_id_giver']['piclink'];
                                     $profielURL = '/profile/'.strtolower($trail['user_id_giver']['fname'].'-'.$trail['user_id_giver']['lname']).'/'.$trail['user_id_giver']['id'];
                                      $karmascore = $trail['user_id_giver']['karmascore'];
                                      }
                              ?>
                          @endif      
                          @if ($trail['status'] == 'hidden' && $profileSelf == 0)
                          @else 
                            @if ($countTrail % 2 != 0)
                              <div class="trail send clearfix trailresult">
                            @else
                              <div class="trail clearfix trailresult">
                            @endif                           
                              <div class="col-sm-2 pdding0 borderPic">
                                @if (!empty($imgSrc))
                                  <img src="{{$imgSrc}}" alt="{{$alt}}" title="{{$alt}}">
                                @else 
                                  <img src="/images/default.png" alt="{{$alt}}" title="{{$alt}}">  
                                @endif
                                  
                                  <ul>
                                      <li><a href="{{$linkedinurl }}" target="_blank"><img src="/images/linkdin.png" alt=""></a></li>
                                      @if (!empty($trail['user_id_receiver']['email']))
                                      @if (!empty($profielURL))
                                         <li>
                                          <a href="{{$profielURL}}"><img src="/images/krmaicon.png" alt=""></a>
                                          <a href="<?php echo URL::to('/');?>/FAQs/KarmaPoints/1"><span>{{$karmascore}}</span></a>
                                        </li>
                                      @endif                                       
                                      @endif
                                      
                                  </ul>
                              </div>  
                              <a href="<?php echo '/meeting/'.strtolower($trail['user_id_receiver']['fname'].'-'.$trail['user_id_receiver']['lname'].'-'.$trail['user_id_giver']['fname'].'-'.$trail['user_id_giver']['lname']).'/'.$trail['req_id'];?>"> 
                                <div class="col-sm-10 pdding0 tabtxt">
                                    <h4>Karma Note Sent to {{$trail['user_id_giver']['fname']}} by {{$trail['user_id_receiver']['fname']}} 
                                    <span>(Met on {{$trail['meetingdatetime']}})</span></h4>
                                    <p>{{KarmaHelper::stringCut($trail['karmaNotes'],180)}}</p>
                                    @if (!empty($trail['skills'])) 
                                      <ul class="traillist tag">
                                        @foreach ($trail['skills'] as $Trailskills)
                                           <a href="<?php echo URL::to('/').'/searchUsers?searchUser='.$Trailskills->name.'&searchOption=Skills';?>"><li>{{$Trailskills->name}}</li></a>
                                        @endforeach
                                      </ul>
                                    @endif
                                    </ul>
                                    <div class="action pull-right">
                                      @if ($profileSelf == 1)
                                        <p>{{$trail['status']}}<span class="glyphicon glyphicon-pencil pull-right"></span></p>
                                      @endif
                                      
                                      <p>{{$trail['created_at']}}</p>
                                    </div>
                                </div>
                              </a>
                            </div>
                            <?php $countTrail++ ; ?>
                          @endif  
                        @endforeach
                    @endif
                    @if($countTrail == '0')
                      <div style="margin-left: 33%">
                          <p>No Karma trails yet!!</p>
                      </div>
                    @endif
                      
                  </div>
  
                  <div class="tab-pane notesReceivedresult" id="profile">
                      @if (!empty($karmaReceived))
                        @foreach ($karmaReceived as $received)
                          @if ($received['status'] == 'hidden' && $profileSelf == 0)
                            
                          @else 
                              @if ($countReceived % 2 != 0)
                                <div class="trail send clearfix receivedresult">
                              @else
                                <div class="trail clearfix receivedresult">
                              @endif                             
                                <div class="col-sm-2 pdding0 borderPic">
                                  @if (!empty($received['user_id_receiver']['piclink']))
                                    <img src="{{$received['user_id_receiver']['piclink']}}" alt="{{$received['user_id_receiver']['fname']}}" title="{{$received['user_id_receiver']['fname']}}">
                                  @else 
                                    <img src="/images/default.png" alt="{{$received['user_id_receiver']['fname']. $received['user_id_receiver']['lname']}}" title="{{$received['user_id_receiver']['fname']}}">  
                                  @endif
                                    
                                    <ul>
                                        <li><a href="{{$received['user_id_receiver']['linkedinurl']}}" target="_blank"><img src="/images/linkdin.png" alt=""></a></li>
                                        @if (!empty($received['user_id_receiver']['email']))
                                          <li> 
                                            <a href="<?php echo '/profile/'.strtolower($received['user_id_receiver']['fname'].'-'.$received['user_id_receiver']['lname']).'/'.$received['user_id_receiver']['id'];?>"><img src="/images/krmaicon.png" alt=""></a>
                                            <a href="<?php echo URL::to('/');?>/FAQs/KarmaPoints/1"><span>{{$received['user_id_receiver']['karmascore']}}</span></a>
                                          </li>
                                        @endif 
                                        
                                    </ul>
                                </div>
                                <a href="<?php echo '/meeting/'.strtolower($received['user_id_receiver']['fname'].'-'.$received['user_id_receiver']['lname'].'-'.$received['user_id_giver']['fname'].'-'.$received['user_id_giver']['lname']).'/'.$received['req_id'];?>">
                                  <div class="col-sm-10 pdding0 tabtxt">
                                      <h4>Karma Note Sent to {{$received['user_id_giver']['fname']}} by {{$received['user_id_receiver']['fname']}}
                                      <span>(Met on {{date('F d, Y', strtotime($received['req_detail']['meetingdatetime']))}})</span>
                                      </h4>
                                      <p>{{KarmaHelper::stringCut($received['karmaNotes'],180)}}</p>
                                      @if (!empty($received['skills']))
                                        <ul class=" traillist tag">
                                          @foreach ($received['skills'] as $Receivedskills)
                                             <a href="<?php echo URL::to('/').'/searchUsers?searchUser='.$Receivedskills->name.'&searchOption=Skills';?>"><li>{{$Receivedskills->name}}</li></a>
                                          @endforeach
                                        </ul> 
                                      @endif
                                      </ul>
                                      <div class="action pull-right">
                                        @if ($profileSelf == 1)
                                          <p>{{$received['status']}}<span class="glyphicon glyphicon-pencil pull-right"></span></p>
                                        @endif
                                        
                                        <p>{{$received['created_at']}}</p>
                                      </div>
                                  </div>
                                </a>
                              </div>
                              <?php $countReceived++;?>
                          @endif
                        @endforeach
                      @endif
                      @if($countReceived == '0')
                      <div style="margin-left: 33%">
                          <p>No Karma trails yet!!</p>
                      </div>
                    @endif
                      
                  </div>
         
                  <div class="tab-pane notesGivenresult" id="messages">
                      @if (!empty($karmaSent))
                        @foreach ($karmaSent as $sent)
                           @if ($sent['status'] == 'hidden' && $profileSelf == 0)
                           @else   
                            @if ($countSent % 2 != 0)
                                 <div class="trail send clearfix givenresult">
                              @else
                                 <div class="trail clearfix givenresult">
                              @endif 
                           
                              <div class="col-sm-2 pdding0 borderPic">
                                @if (!empty($sent['user_id_giver']['piclink']) && !empty($sent['user_id_giver']['email']))
                                  <img src="{{$sent['user_id_giver']['piclink']}}" alt="{{$sent['user_id_giver']['fname']}}" title="{{$sent['user_id_giver']['fname']}}"> 
                                @else 
                                  <img src="/images/default.png" alt="{{$sent['user_id_giver']['fname']. $sent['user_id_giver']['lname']}}" title="{{$sent['user_id_giver']['fname']}}">  
                                @endif
                                  
                                  <ul>
                                      <li><a href="{{$sent['user_id_giver']['linkedinurl']}}" target="_blank"><img src="/images/linkdin.png" alt=""></a></li>
                                      @if (!empty($sent['user_id_giver']['email']))
                                        <li>
                                          <a href="<?php echo '/profile/'.strtolower($sent['user_id_giver']['fname'].'-'.$sent['user_id_giver']['lname']).'/'.$sent['user_id_giver']['id'];?>"><img src="/images/krmaicon.png" alt=""></a>
                                          <a href="<?php echo URL::to('/');?>/FAQs/KarmaPoints/1"><span>{{$sent['user_id_giver']['karmascore']}}</span></a>
                                        </li>
                                      @endif  
                                      
                                  </ul> 
                              </div> 

                              <a href="<?php echo '/meeting/'.strtolower($sent['user_id_receiver']['fname'].'-'.$sent['user_id_receiver']['lname'].'-'.$sent['user_id_giver']['fname'].'-'.$sent['user_id_giver']['lname']).'/'.$sent['req_id'];?>">
                                <div class="col-sm-10 pdding0 tabtxt">
                                    <h4>Karma Note Sent to {{$sent['user_id_giver']['fname']}} by {{$sent['user_id_receiver']['fname']}}
                                    <span>(Met on {{date('F d, Y', strtotime($sent['req_detail']['meetingdatetime']))}})</span>
                                    </h4>
                                    <p>{{KarmaHelper::stringCut($sent['karmaNotes'],180)}}</p>  
                                    @if (!empty($sent['skills']))
                                      <ul class=" traillist tag">
                                        @foreach ($sent['skills'] as $Sentskills)
                                           <a href="<?php echo URL::to('/').'/searchUsers?searchUser='.$Sentskills->name.'&searchOption=Skills';?>"><li>{{$Sentskills->name}}</li></a>
                                        @endforeach
                                      </ul>
                                    @endif
                                    </ul>
                                    <div class="action pull-right">
                                      @if ($profileSelf == 1)
                                        <p>{{$sent['status']}}<span class="glyphicon glyphicon-pencil pull-right"></span></p>
                                      @endif
                                      
                                      <p>{{$sent['created_at']}}</p>
                                    </div>
                                </div>
                              </a>
                            </div>
                            <?php $countSent++;?>
                           @endif 
                        @endforeach
                      @endif
                      @if($countSent == '0')
                        <div style="margin-left: 33%">
                            <p>No Karma notes given yet!!</p>
                        </div>
                      @endif
                       
                  </div>
                </div>
                
            </div>
        </div>
    <div class="modal" style="display:none" id="Profile">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button aria-label="Close" onclick="modelClose('Profile');" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Please sign in!</h4>
          </div>
          <div class="modal-body">
            <p>You need to be signed in to perform this action. Please sign in using Linkedin.</p>
          </div>
          <div class="modal-footer">
            <a href="" id="popupUrl"><button data-dismiss="modal" class="btn btn-default linkfullBTN newBluBtn pull-right" type="button">Sign in with Linkedin</button></a>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog --> 
    </div>
    </section> 
  <div class="loderBox">
    <div id="loader"><img src="/images/loader.gif" /></div>
    <div id="scroll"><img src="/images/icon_gototop.png" /></div>
  </div> 
  <input type="hidden" value='' id="hit-KarmaNotesSent"> 
  <input type="hidden" value='15' id="scroller-KarmaNotesSent"> 
  <input type="hidden" value='' id="hit-KarmaNotesReceived"> 
  <input type="hidden" value='15' id="scroller-KarmaNotesReceived"> 
  <input type="hidden" value='' id="hit-KarmaTrail"> 
  <input type="hidden" value='15' id="scroller-KarmaTrail">  
  <input type="hidden" value='{{$id}}' id="user-profile"> 

    <!-- /Main colom -->
      <script type="text/javascript">
      var callonclickfunctionforskill = document.getElementById('getskilltab');
        callonclickfunctionforskill.onclick = callSkillTextBox;
        function callSkillTextBox() {
           
           document.getElementById("UpdateSkill").style.display="block";
          }
          function removeskill(id) {
                  $(".skilldisp_"+id).remove();
          }
          $(document).ready(function() {
            //function for displaying skills 
             $('#searchskill').keydown(function(){
          
           //clearTimeout(timer); 
              timer = setTimeout(callmeSearch, 200)  
            });
 
             var xhrh = null; 
             function callmeSearch(){ 
                  if( xhrh != null ) {
                          xhrh.abort();
                          xhrh = null; 
                  }
                  var keyword = $('#searchskill').val();
                  var optionVal = 'People';
                  $('div.searchReceiverresult').hide();
                 // alert(keyword+'---'+optionVal);
                  if(keyword != ''){
                     var url='<?php echo URL::to('/');?>/searchforskillonqueryforprofile?searchskill='+keyword;
                      //alert(url);
                        xhrh=   $.get(url,function(data) {
                          if(data==""){
                              $('.searchReceiverresult').html('');
                          }
                          else{
                              $('div.searchReceiverresult').show();
                              $('div.searchReceiverresult').focus(); 
                              $("div.searchReceiverresult").html(data);
                          }
                      });    
                  }
                  else{
                    return false;
                  }
              }
              
    //end of skill
            $('#loader-icon').hide();  
              $(window).scroll(function() {
              infinite_scroll_debouncer(infinite_scrolling_profile,200); 
              });
          }); 
		      var isRunning = false;
          function callMeetingPopup() {
           
           document.getElementById("UpdateGroup").style.display="block";
          }
          function gettrailprofile(){
        			if(isRunning){ 
        				return;
  			     }
                
              var currentTab = $('.nav-tabs .active').text().replace(/\s/g, "");
              console.log(currentTab);
              var scroller = $('#scroller-'+currentTab).val(); 
              var hitval =  $('#hit-'+currentTab).val();
              var userProfile = $('#user-profile').val();
              var tabcount;
              if(currentTab == "KarmaTrail")
              tabcount = <?php echo $countTrail;?>;
              if(currentTab == "KarmaNotesReceived")
              tabcount = <?php echo $countReceived;?>;
              if(currentTab == "KarmaNotesSent")
              tabcount = <?php echo $countSent;?>;

              if(hitval !="over" && tabcount > 10 ){  
				        isRunning = true; 
                  $.ajax({
                      url: '<?php echo URL::to('/');?>/loadmoreProfile?hitcount='+scroller+'&action='+currentTab+'&userProfile='+userProfile,  
                      type: "POST", 
                      cache:false,
                      async: true, 
                    beforeSend: function(){
                      $('#loader').show(); 
                      $('#scroll').hide(); 
                    },
                    complete: function(){
                      clearconsole();
                      $('#loader').hide();
                      $('#scroll').hide(); 
                    },
                    success: function(data){
						isRunning = false;  
                        if(data != "")
                        {
                          scroller = (+scroller) + (+10); 
                          $('#scroller-'+currentTab).val(scroller);
                          if(currentTab == 'KarmaTrail')
                          $("#home").append(data); 
                          if(currentTab == 'KarmaNotesReceived')
                          $("#profile").append(data); 
                          if(currentTab == 'KarmaNotesSent')
                          $("#messages").append(data); 
                        }
                        else
                        {  
                           $('#hit-'+currentTab).val('over');
                           $('#scroll').show(); 
                        }
                    }, 
                    error: function(){
                    }           
                   });
            }
            else{ 
              if(tabcount > 10)  
              $('#scroll').show();
              else
                {$('#scroll').hide();$('#loader').hide();}
            }
                
          } 
      $('.nav-tabs').click(function () {
            $('#scroll').hide();$('#loader').hide();
        }); 
      
      </script>
@stop
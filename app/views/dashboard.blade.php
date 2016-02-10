@extends('common.master')
@section('content')  
 <?php  
//print_r($totagroupquestion);die;
 //echo"<pre>----";print_r($CurrentUser);echo"</pre>----------";

?>

   
    {{ Form::hidden('pageIndex','dashboard',array('class'=>'pageIndex')); }}
    <section class="mainWidth suggestSection">  
        <div class="modal" style="display:none" id="UpdateGroup">
            <div class="modal-dialog" style="margin:150px auto">
                <div class="modal-content">
                    <div class="modal-header">
                        <button aria-label="Close" onclick="modelClose('UpdateGroup');" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Sorry for the inconvenience?</h4>
                    </div>
                    <input type="hidden" id="group-id" value="">
                        <div class="modal-body group-body" >
                            <p>That user seems busy & has already received your request.We suggest you to request someone else having similar skills.</p>
                        </div>
                              
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog --> 
        </div>
        <div class=" col-xs-12 centralize greenBg clearfix">
            <div class="col-sm-1">
              <div class="dpcontainer">
             
                @if ($CurrentUser->piclink == '')
                  <img alt="" src="/images/default.png">
                @else
                <img src="{{ $CurrentUser->piclink;}}" class="img-responsive"  alt = "{{$CurrentUser->fname;}}" title = "{{$CurrentUser->fname;}}">
                @endif
              </div>   
            </div>

            <div class="col-sm-1">
               <a href="<?php echo URL::to('/');?>/FAQs/KarmaPoints/1"> <span class="rank">{{$CurrentUser->karmascore;}}</span></a>
            </div>

            <div class="col-sm-3 profileDeatils">
                <h2>{{ $CurrentUser->fname.' '.$CurrentUser->lname;}}</h2>
            </div>

            <div class="col-sm-4 profileDeatils">
                <p>{{ $CurrentUser->headline;}} </p>
                <p>{{ $CurrentUser->location;}} </p>
               <!--  <p>Masovian District, Poland </p> -->
            </div>

            <div class="col-sm-3">
                <a href="profile/<?php echo strtolower($CurrentUser->fname.'-'.$CurrentUser->lname).'/'.$CurrentUser->id;?>">
                 <button class="btn btn-warning viewprofile pull-right" type="button">
                 <span class="glyphicon glyphicon-user"></span>
                Edit Profile</button></a>
            </div>
        </div> 

        @if($user_connection_onkc > 0 || $totalPendingRequest > 0 ||  $totalReceivedRequest > 0 || $totalintroductionInitiated > 0 || $totagroupquestion > 0 )
            <div class=" col-sm-10 centralize networkList"> 
            <h4>Your Updates</h4>
                <ul>
                        @if($user_connection_onkc > 0)
                            <a target='_blank' href="/searchConnections"><li><img src="images/icon001.png"> {{$user_connection_onkc;}} of your LinkedIn connections are on KarmaCircles.</li></a> 
                        @endif

                          @if($totalReceivedRequest > 0)
                            <a target='_blank' href="/KarmaMeetings#profile"><li><img src="images/icon003.png"> You have {{$totalReceivedRequest}} pending Karma Meeting requests.</li></a>
                        @endif

                        @if($totalPendingRequest > 0)
                            <a target='_blank' href="/KarmaNotes"><li><img src="images/icon002.png"> You have {{$totalPendingRequest}} pending Karma Notes to send.</li></a>
                        @endif

                        @if($totalintroductionInitiated > 0 )
                        <a target='_blank' href="/karma-intro"> <li><img src="images/icon004.png">You have {{$totalintroductionInitiated}} pending Karma Intro.</li></a>
                        @endif
                        @if($totagroupquestion > 0)
                            <a target='_blank' href="/karma-queries#messages"> <li><img src="images/icon005.png">
                            There are {{$totagroupquestion}} open queries that have been posted in the last 7 days. 
                            </li></a>
                        @endif
                </ul>
            </div>
            <hr class="darkLine">
        @endif
 
          <!-- meeting request to random KC user-->
           <div class="col-md-2 col-sm-2 col-xs-1 pull-right" id="skip">
                        <a  href="javascript:;" >Suggest More</a> 
                    </div>
        <div id="dash-two">
        @if(!empty($getKcuser))    
            <div id="content-1" class="content-switcher"><div class="sugg-first">
                <div class="col-xs-12 centralize networkList" >
                <div class="row clearfix">
                    <div class="col-md-10 col-sm-10 col-xs-10 pull-left">
                        <h4>Suggestion</h4>
                        <p> 
                            Following people are on KarmaCircles. Consider sending them a meeting request today. 
                        </p>
                    </div>
                   
                </div>

                    <div class="col-md-12 clearfix  listBox mainsuggestBox">
                       <div class="col-sm-4 col-xs-12 noteBox tabtxt suggestBox">
                            <div class="col-xs-4">
                                 @if ($getKcuser->piclink == "" ||$getKcuser->piclink == 'null')
                                        <a  href="profile/<?php echo strtolower($getKcuser->fname.'-'.$getKcuser->lname);?>/{{$getKcuser->id}}"><img alt="" src="/images/default.png" width="82" height="87"></a>
                                    @else
                                        <a  href="profile/<?php echo strtolower($getKcuser->fname.'-'.$getKcuser->lname);?>/{{$getKcuser->id}}"><img src="{{$getKcuser->piclink}}" width="82" height="87"></a>
                                    @endif
                            </div>
                            <div class="col-sm-8 col-xs-8">
                                <a  href="profile/<?php echo strtolower($getKcuser->fname.'-'.$getKcuser->lname);?>/{{$getKcuser->id}}"><h4>{{$getKcuser->fname.' '.$getKcuser->lname }}</h4></a>
                                 <p title="{{KarmaHelper::stringCut($getKcuser->headline,80)}}">{{KarmaHelper::stringCut($getKcuser->headline,80)}}</p>
                                <p>{{$getKcuser->location}}</p>
                            </div>
                            <div class="borderPic pull-left">
                                <ul>
                                 <li>
                                    <a target='_blank' href="{{$getKcuser->linkedinurl}}"><img alt="" src="images/linkdin.png"></a>
                                </li>
                                @if (isset($getKcuser->email))
                                    <li>
                                        <a href="profile/<?php echo strtolower($getKcuser->fname.'-'.$getKcuser->lname);?>/{{$getKcuser->id}}"><img alt="" src="images/krmaicon.png"></a>
                                        <a href="<?php echo URL::to('/');?>/FAQs/KarmaPoints/1"><span class="karmascore">{{$getKcuser->karmascore}}</span></a>
                                    </li>
                                @endif 
                                </ul>
                            </div>
                             <div class="pull-right">
                                @if($checkMeetingStatus['meetingRunning']=='yes' && $checkMeetingStatus['giverId']==$getKcuser->id)
                                    <a href="{{URL::to('/')}}/meeting/{{$CurrentUser->fname}}-{{$CurrentUser->lname}}-{{$checkMeetingStatus['fname']}}-{{$checkMeetingStatus['lname']}}/{{$checkMeetingStatus['karmaId']}}"><button type="button" class="btn btn-success btnicon meeting">Request Meeting</button></a>
                                @else
                                    @if($MeetingRequestPending > 0)
                                        <div id="join_first"><button id="join_first_button" class="btn btn-success btnicon meeting" onclick="callMeetingPopup()" type="button">Request Meeting</button> </div>
                                        @else

                                        <a target='_blank' href="{{URL::to('/')}}/CreateKarmaMeeting/{{$getKcuser->id}}"><button type="button" class="btn btn-success btnicon meeting">Request Meeting</button></a>
                                    @endif
                                @endif 
                                                                    
                            </div>
                        </div>
                        <div class="col-sm-4 col-xs-12 noteBox tabtxt suggestBox" >
                            <div class="col-xs-4">
                                 @if ($getKcuserOne->piclink == "" ||$getKcuserOne->piclink == 'null')
                                        <a  href="profile/<?php echo strtolower($getKcuserOne->fname.'-'.$getKcuserOne->lname);?>/{{$getKcuserOne->id}}"><img alt="" src="/images/default.png" width="82" height="87"></a>
                                    @else
                                        <a  href="profile/<?php echo strtolower($getKcuserOne->fname.'-'.$getKcuserOne->lname);?>/{{$getKcuserOne->id}}"><img src="{{$getKcuserOne->piclink}}" width="82" height="87"></a>
                                    @endif
                            </div>
                            <div class="col-sm-8 col-xs-8">
                                <a  href="profile/<?php echo strtolower($getKcuserOne->fname.'-'.$getKcuserOne->lname);?>/{{$getKcuserOne->id}}"><h4>{{$getKcuserOne->fname.' '.$getKcuserOne->lname }}</h4></a>
                                 <p title="{{KarmaHelper::stringCut($getKcuserOne->headline,80)}}">{{KarmaHelper::stringCut($getKcuserOne->headline,80)}}</p>
                                <p>{{$getKcuserOne->location}}</p>
                            </div>
                            <div class="borderPic pull-left">
                                <ul>
                                 <li>
                                    <a target='_blank' href="{{$getKcuserOne->linkedinurl}}"><img alt="" src="images/linkdin.png"></a>
                                </li>
                                @if (isset($getKcuserOne->email))
                                    <li>
                                        <a href="profile/<?php echo strtolower($getKcuserOne->fname.'-'.$getKcuserOne->lname);?>/{{$getKcuserOne->id}}"><img alt="" src="images/krmaicon.png"></a>
                                        <a href="<?php echo URL::to('/');?>/FAQs/KarmaPoints/1"><span class="karmascore">{{$getKcuserOne->karmascore}}</span></a>
                                    </li>
                                @endif 
                                </ul>
                            </div>
                            <div class="pull-right">
                                @if($checkMeetingStatusOne['meetingRunning']=='yes' && $checkMeetingStatusOne['giverId']==$getKcuserOne->id)
                                    <a href="{{URL::to('/')}}/meeting/{{$CurrentUser->fname}}-{{$CurrentUser->lname}}-{{$checkMeetingStatusOne['fname']}}-{{$checkMeetingStatusOne['lname']}}/{{$checkMeetingStatusOne['karmaId']}}"><button type="button" class="btn btn-success btnicon meeting">Request Meeting</button></a>
                                @else
                                    @if($MeetingRequestPendingOne > 0)
                                        <div id="join_first"><button id="join_first_button" class="btn btn-success btnicon meeting" onclick="callMeetingPopup()" type="button">Request Meeting</button> </div>
                                    @else
                                        <a target='_blank' href="{{URL::to('/')}}/CreateKarmaMeeting/{{$getKcuserOne->id}}"><button type="button" class="btn btn-success btnicon meeting">Request Meeting</button></a>
                                    @endif
                                @endif
                                    
                            </div>
                        </div>
                        <div class="col-sm-4 col-xs-12 noteBox tabtxt suggestBox" style="margin-right: 0px;">
                            <div class="col-xs-4">
                                 @if ($getKcuserTwo->piclink == "" ||$getKcuserTwo->piclink == 'null')
                                        <a  href="profile/<?php echo strtolower($getKcuserTwo->fname.'-'.$getKcuserTwo->lname);?>/{{$getKcuserTwo->id}}"><img alt="" src="/images/default.png" width="82" height="87"></a>
                                    @else
                                        <a  href="profile/<?php echo strtolower($getKcuserTwo->fname.'-'.$getKcuserTwo->lname);?>/{{$getKcuserTwo->id}}"><img src="{{$getKcuserTwo->piclink}}" width="82" height="87"></a>
                                    @endif
                            </div>
                            <div class="col-sm-8 col-xs-8">
                                <a  href="profile/<?php echo strtolower($getKcuserTwo->fname.'-'.$getKcuserTwo->lname);?>/{{$getKcuserTwo->id}}"><h4>{{$getKcuserTwo->fname.' '.$getKcuserTwo->lname }}</h4></a>
                                 <p title="{{KarmaHelper::stringCut($getKcuserTwo->headline,80)}}">{{KarmaHelper::stringCut($getKcuserTwo->headline,80)}}</p>
                                <p>{{$getKcuserTwo->location}}</p>
                            </div>
                            <div class="borderPic pull-left">
                                <ul>
                                 <li>
                                    <a target='_blank' href="{{$getKcuserTwo->linkedinurl}}"><img alt="" src="images/linkdin.png"></a>
                                </li>
                                @if (isset($getKcuserTwo->email))
                                    <li>
                                        <a href="profile/<?php echo strtolower($getKcuserTwo->fname.'-'.$getKcuserTwo->lname);?>/{{$getKcuserTwo->id}}"><img alt="" src="images/krmaicon.png"></a>
                                        <a href="<?php echo URL::to('/');?>/FAQs/KarmaPoints/1"><span class="karmascore">{{$getKcuserTwo->karmascore}}</span></a>
                                    </li>
                                @endif 
                                </ul>
                            </div>
                            <div class="pull-right">
                                @if($checkMeetingStatusTwo['meetingRunning']=='yes' && $checkMeetingStatusTwo['giverId']==$getKcuserTwo->id)
                                    <a href="{{URL::to('/')}}/meeting/{{$CurrentUser->fname}}-{{$CurrentUser->lname}}-{{$checkMeetingStatusTwo['fname']}}-{{$checkMeetingStatusTwo['lname']}}/{{$checkMeetingStatusTwo['karmaId']}}"><button type="button" class="btn btn-success btnicon meeting">Request Meeting</button></a>
                                @else
                                    @if($MeetingRequestPendingTwo > 0)
                                        <div id="join_first"><button id="join_first_button" class="btn btn-success btnicon meeting" onclick="callMeetingPopup()" type="button">Request Meeting</button> </div>
                                        @else    
                                        <a target='_blank' href="{{URL::to('/')}}/CreateKarmaMeeting/{{$getKcuserTwo->id}}"><button type="button" class="btn btn-success btnicon meeting">Request Meeting</button></a>
                                    @endif
                                @endif
                            </div>
                        </div>
                        <div class="clr"></div>
                    </div>
                </div>

               
        </div></div>
        @endif
        
       
        
        


       
        
        <!-- meeting request to random KC user-->
        <!-- kc note to random linkedin connection-->
        

        <!-- kc note to random linkedin connection--> 
    <!-- kc invite to random linkedin connection-->
       
    </div>
<!-- kc invite to random linkedin connection-->
    </div>
        <div class="clr"></div>
        <hr class="darkLine">
        <div class=" col-sm-10 centralize clearfix karmaNotes recentKarma">
        <div class="col-md-12 col-sm-12 noteBox recent_karma recentCol">
                <h3>Recent Karma Notes</h3>
                <ul class="thumbIMGlist">
                  @if(!empty($getKarmanote))
                  <?php $count=0;?>
                @foreach($getKarmanote as $value)
                    @if($count <6 ) 
                    <li class="marginBot">
                        <span class="pull-left mright"> 
                            @if ($value->user_idreceiver['piclink'] == "" ||$value->user_idreceiver['piclink'] == 'null')
                                        <a  href="profile/<?php echo strtolower($value->user_idreceiver['fname'].'-'.$value->user_idreceiver['lname']).'/'.$value->user_idreceiver['id'] ;?>"><img class="big40" alt="" src="/images/default.png" height="40" width="40" ></a>
                                    @else
                                        <a  href="profile/<?php echo strtolower($value->user_idreceiver['fname'].'-'.$value->user_idreceiver['lname']).'/'.$value->user_idreceiver['id'] ;?>"><img class="big40" src="{{$value->user_idreceiver['piclink']}}" height="40" width="40" ></a>
                                    @endif
                            <!-- popup box -->
                            <div class="noteBox tabtxt listpopUp twin">
                                <div class="col-xs-4">
                                    @if ($value->user_idreceiver['piclink'] == "" ||$value->user_idreceiver['piclink'] == 'null')
                                        <a  href="profile/<?php echo strtolower($value->user_idreceiver['fname'].'-'.$value->user_idreceiver['lname']).'/'.$value->user_idreceiver['id'] ;?>"><img alt=""  src="/images/default.png" ></a>
                                    @else
                                        <a  href="profile/<?php echo strtolower($value->user_idreceiver['fname'].'-'.$value->user_idreceiver['lname']).'/'.$value->user_idreceiver['id'] ;?>"><img   src="{{$value->user_idreceiver['piclink']}}"></a>
                                    @endif
                                </div> 
                                <div class="col-sm-8 col-xs-7">
                                    <h4>{{$value->user_idreceiver['fname']." ".$value->user_idreceiver['lname']}}</h4>
                                    <p>{{KarmaHelper::stringCut($value->user_idreceiver['headline'],80)}}</p>
                                    <p>{{$value->user_idreceiver['location']}}</p>
                                </div>
                                <div class="clr"></div>
                                <div class="borderPic">
                                    <ul>
                                      <li><a href="{{$value->user_idreceiver['linkedinurl']}}" target="_blank"><img alt="" src="/images/linkdin.png"></a></a></li>
                                      
                                      @if (isset($value->user_idreceiver['email']))
                                        <li>
                                        <a href="profile/<?php echo strtolower($value->user_idreceiver['fname'].'-'.$value->user_idreceiver['lname']) ;?>/{{$value->user_idreceiver['id']}}"><img alt="" src="/images/krmaicon.png"></a>
                                       <a href="<?php echo URL::to('/');?>/FAQs/KarmaPoints/1"> <span class="karmascore">{{$value->user_idreceiver['karmascore']}}</span></a>
                                        </li>
                                        @endif
                                    </ul>
                                </div>
                                <a href="/CreateKarmaMeeting/{{$value->user_idreceiver['id']}}"><button type="button" class="btn btn-success btnicon meeting pull-right">Request Meeting</button></a>
                            </div>
                            <!-- popup box -->  
                        </span>
                        <span class="pull-left mrightpad"> <img src="images/icon002.png" height="26" width="26"></span>
                        <span class="pull-left mgiver">

                                @if(isset($value->user_idgiver['piclink']))
                                        @if(!empty($value->user_idgiver['email']))
                                        <a href="/profile/<?php echo strtolower($value->user_idgiver['fname'].'-'.$value->user_idgiver['lname']) ;?>/{{$value->user_idgiver['id']}}">
                                        <img class="big40"  src="{{$value->user_idgiver['piclink']}}" height="40" width="40"></a>
                                        @else
                                        <a href="{{$value->user_idgiver['linkedinurl']}}">
                                        <img class="big40"  src="{{$value->user_idgiver['piclink']}}" height="40" width="40"></a>
                                        @endif
                                    @else
                                         @if(!empty($value->user_idgiver['email']))
                                        <a href="profile/<?php echo strtolower($value->user_idgiver['fname'].'-'.$value->user_idgiver['lname']) ;?>/{{$value->user_idgiver['id']}}">
                                        <img class="big40"  src="/images/default.png" height="40" width="40"></a>
                                        @else 
                                        <a href="{{$value->user_idgiver['linkedinurl']}}">
                                       <img class="big40"  src="/images/default.png" height="40" width="40"></a>
                                        @endif
                                    @endif 
                            <!-- popup box -->    

                            <div class="noteBox tabtxt listpopUp twin">
                                <div class="col-xs-4">
                                   
                                   @if(isset($value->user_idgiver['piclink']))
                                        <img src="{{$value->user_idgiver['piclink']}}">
                                    @else
                                        <img src="/images/default.png">
                                    @endif 
                                </div>
                                <div class="col-sm-8 col-xs-7">
                                    <h4>
                                         {{$value->user_idgiver['fname'].' '.$value->user_idgiver['lname']}}
                                    </h4>
                                    <p>
                                          {{KarmaHelper::stringCut($value->user_idgiver['headline'],80)}}
                                    </p>
                                    <p>
                                          {{$value->user_idgiver['location']}}
                                       
                                    </p>
                                </div>
                                <div class="clr"></div>
                                <div class="borderPic">
                                    <ul>
                                      <li>
                                          <a href="{{$value->user_idgiver['linkedinurl']}}"><img src="/images/linkdin.png" alt=""></a>
                                    </li>
                                    @if(!empty($value->user_idgiver['karmascore']))
                                      <li>
                                        <a href="profile/<?php echo strtolower($value->user_idgiver['fname'].'-'.$value->user_idgiver['lname']) ;?>/{{$value->user_idgiver['id']}}"><img src="/images/krmaicon.png" alt=""></a>
                                        <a href="<?php echo URL::to('/');?>/FAQs/KarmaPoints/1"><span class="karmascore">{{$value->user_idgiver['karmascore']}}</span></a>
                                      </li>
                                    @endif 
                                    </ul>
                                </div>
                                @if(!empty($value->user_idgiver))
                                <a href="/CreateKarmaMeeting/{{$value->user_idgiver['id']}}"><button type="button" class="btn btn-success btnicon meeting pull-right">Request Meeting</button></a>
                                @else
                                    <?php 
                                        $user_id  =  $value->connection_idgiver['id'];
                                        $uname =  $value->connection_idgiver['fname'].' '.$value->connection_idgiver['lname'];
                                $click = "window.open('/CreateKarmaMeeting/NoKarma/$user_id','_self')";
                                ?>
                                <button onclick="{{$click}}" class="btn btn-success btnicon meeting" type="button">Request Meeting</button> 

                                    {{-- <a href="{{URL::to('/')}}/CreateKarmaMeeting/NoKarma/{{$value->connection_idgiver['id']}}"><button type="button" class="btn btn-success btnicon meeting pull-right">Request Meeting</button></a> --}}
                                @endif

                            </div>
                            <!-- popup box -->
                        </span>
                       
                         <span class="pull-left "><a class = "homeLink" href="<?php echo '/meeting/'.strtolower($value->user_idreceiver['fname'].'-'.$value->user_idreceiver['lname'].'-'.$value->user_idgiver['fname'].'-'.$value->user_idgiver['lname']).'/'.$value->req_id;?>"> 
                        {{$value->user_idreceiver['fname'].' '.$value->user_idreceiver['lname']}} 
                            sent a  KarmaNote to   
                            @if(!empty($value->user_idgiver))
                             {{$value->user_idgiver['fname'].' '.$value->user_idgiver['lname']}}
                            @else
                             {{$value->connection_idgiver['fname'].' '.$value->connection_idgiver['lname']}}
                            @endif
                        </a></span>

                             <!--GIVER -->

                        
                    </li>
                     @endif
                @endforeach 
                @endif
                    




                </ul>
            </div>
        </div>
    <div class="modal" style="display:none" id="LimitBox">
       <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button aria-label="Close" onclick="modelClose('LimitBox');" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Enter email address!</h4>
          </div>
          <div class="modal-body">
            <p id="Limitboxmsg">Please specify the email address to send this message.</p>
          </div>
          <div class="modal-footer">
           <a href="" id="popupUrl" target="_self"><button data-dismiss="modal" class="btn btn-default linkfullBTN gpBtn pull-right" type="button" onclick="">Continue</button></a>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog --> 
    </div>     
    </section> 

 

  <SCRIPT TYPE="text/javascript">
        function callMeetingPopup() {
           
           document.getElementById("UpdateGroup").style.display="block";
          }
       $(document).ready(function(){
        
        var currlen =1;
        var hicount = 3;
        $('#skip').click(function(){
            setdata(hicount,currlen);
            ajaxcall(currlen,hicount);
            hicount = parseInt(hicount + 3);
            currlen++; 
        }); 

        function ajaxcall(currlen,hicount){ 
            $.ajax({
                url: '<?php echo URL::to('/');?>/ajaxdashboardSuggestion?skipcount='+hicount,
                //global: false,
                type: 'POST',
                //data: {},
                async: true, //blocks window close
                success: function(data) {
                    if(data!=""){
                    $("#dash-two").html(data);
                }
                else{
                     setTimeout(function () {
                        ajaxcall(skipcount);
                    }, 800)      
                }

                }
            });  
        }  

        contentswitche = $('.content-switcher').width();
        function setdata(hicount,currlen){
            /*var first = "content-"+hicount;
            if(hicount <3)
            var second =  "content-"+parseFloat(hicount+1);
            else
            var second =  "content-"+1;*/
            var first = "content-"+currlen;
            var second =  "content-"+parseInt(currlen+1);
            
            $("#"+first)
            .css({left:'0'})  // Set the left to its calculated position
            .animate({"left":"-"+contentswitche}, "2200"); 

            $("#"+second).css('display',"block");  
            $("#"+second).css('left',contentswitche);

            $("#"+second).animate({
            "left":"0",
            },"fast",
            function(){ }
            );
            $("#"+first).remove();
        }
    });
  </SCRIPT> 
@stop
   
 
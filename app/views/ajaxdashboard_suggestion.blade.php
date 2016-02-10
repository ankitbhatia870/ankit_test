<?php  //echo "<pre>======";print_r($getKcuserTwo);echo "</pre>===";die;?> 
 <?php //echo '<pre>';print_r($checkMeetingStatus);die;?>

  <!-- meeting request to random KC user-->
  
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
      
          <div id="dash-two">
            <div   id="content" class="content-switcher">
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
                                <div id="join_third"><button id="join_third_button" class="btn btn-success btnicon meeting" onclick="callMeetingPopup()" type="button">Request Meeting</button> </div>
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
                                    <div id="join_third"><button id="join_third_button" class="btn btn-success btnicon meeting" onclick="callMeetingPopup()" type="button">Request Meeting</button> </div>
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
                                    <div id="join_third"><button id="join_third_button" class="btn btn-success btnicon meeting" onclick="callMeetingPopup()" type="button">Request Meeting</button> </div>
                                    @else
                                    <a target='_blank' href="{{URL::to('/')}}/CreateKarmaMeeting/{{$getKcuserTwo->id}}"><button type="button" class="btn btn-success btnicon meeting">Request Meeting</button></a>
                                    @endif
                                    
                                @endif
                            </div>
                        </div>
                        
                    </div>
                </div>

               
        </div></div>
        <!-- meeting request to random KC user-->
        <!-- kc note to random linkedin connection-->
       
        <!-- kc note to random linkedin connection-->
         <!-- kc invite to random linkedin connection-->
    </div>
</div>
<!-- kc invite to random linkedin connection-->
<script type="text/javascript">
        function callMeetingPopup() {
           document.getElementById("UpdateGroup").style.display="block";
          }
</script>
        
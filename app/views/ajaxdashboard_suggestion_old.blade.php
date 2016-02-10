<?php  
//echo "<pre>";print_r($getKcuser);echo "</pre>";
//echo "<pre>======";print_r($getsuggestion);echo "</pre>===";
//die;?> 

  <!-- meeting request to random KC user-->
          <div id="dash-two">
            <div   id="content-{{$skipcountreq}}" class="content-switcher">
                <div id="content-1" style="height: 305px" class="content-switcher"><div class="sugg-first">
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
                            <a target='_blank' href="{{URL::to('/')}}/CreateKarmaMeeting/{{$getKcuser->id}}"><button type="button" class="btn btn-success btnicon meeting">Request Meeting</button></a>
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
                            <a target='_blank' href="{{URL::to('/')}}/CreateKarmaMeeting/{{$getKcuserOne->id}}"><button type="button" class="btn btn-success btnicon meeting">Request Meeting</button></a>
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
                            <a target='_blank' href="{{URL::to('/')}}/CreateKarmaMeeting/{{$getKcuserTwo->id}}"><button type="button" class="btn btn-success btnicon meeting">Request Meeting</button></a>
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
        
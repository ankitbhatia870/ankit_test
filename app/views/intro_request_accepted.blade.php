@extends('common.master')
@section('content')
    <section class="mainWidth">
        <div class="col-lg-10 col-md-10 col-sm-12 centralize pdding0">
        
       <!--  <div class="backlink pull-right clearfix">
            <a href="/dashboard">Back to Karma Circle</a>
        </div> -->

            <div class="col-md-11 col-sm-12 centralize clearfix pull-left no-pull-md">
                <div class="registrFrm sendnotes regpending col-md-12">
                     <!--Karma person -->
                    <div class="col-sm-5 col-xs-12 noteBox tabtxt centralize">
                        <div class="col-xs-4">
                            @if ($introducerDetail->piclink == '')
                                 <a href="/profile/<?php echo strtolower($introducerDetail->fname.'-'.$introducerDetail->lname).'/'.$introducerDetail->id ;?>"><img height="87" width="82" alt="" src="/images/default.png"></a>
                            @else
                                 <a href="/profile/<?php echo strtolower($introducerDetail->fname.'-'.$introducerDetail->lname).'/'.$introducerDetail->id ;?>"><img height="87" width="82" src="{{ $introducerDetail->piclink;}}"></a>
                            @endif
                        </div>
                        <div class="col-sm-8 col-xs-12">
                            <a href="/profile/<?php echo strtolower($introducerDetail->fname.'-'.$introducerDetail->lname).'/'.$introducerDetail->id ;?>"><h4>{{$introducerDetail->fname." ".$introducerDetail->lname}}</h4></a>
                                <p>{{ $introducerDetail->headline}}</p>
                                <p>{{ $introducerDetail->location}}</p>
                        </div>
                        <div class="clr"></div>
                        <div class="borderPic">
                            <ul>
                                <li><a href="{{$introducerDetail->linkedinurl}}" target="_blank"><img alt="" src="/images/linkdin.png"></a></li> 
                                <li>
                                <a href="/profile/<?php echo strtolower($introducerDetail->fname.'-'.$introducerDetail->lname).'/'.$introducerDetail->id ;?>"><img alt="" src="/images/krmaicon.png"></a>
                                <span>{{$introducerDetail->karmascore}}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <!--Karma person -->

                    <div class="centralize introducerIcon">
                        <img src="/images/introdu001.png" class="img-responsive centralize">
                    </div>
                    
                    <!--Karma person -->
                    <div class="col-sm-11 connectKarma centralize">
                        <div class="col-xs-6 noteBox tabtxt">
                            <div class="col-sm-4">
                                @if ($receiverDetail->piclink == '')
                                     <a href="/profile/<?php echo strtolower($receiverDetail->fname.'-'.$receiverDetail->lname).'/'.$receiverDetail->id ;?>"><img height="87" width="82" alt="" src="/images/default.png"></a>
                                @else
                                     <a href="/profile/<?php echo strtolower($receiverDetail->fname.'-'.$receiverDetail->lname).'/'.$receiverDetail->id ;?>"><img height="87" width="82" src="{{ $receiverDetail->piclink;}}"></a>
                                @endif
                            </div>
                            <div class="col-sm-8">
                               <a href="/profile/<?php echo strtolower($receiverDetail->fname.'-'.$receiverDetail->lname).'/'.$receiverDetail->id ;?>"> <h4>{{$receiverDetail->fname." ".$receiverDetail->lname}}</h4></a>
                                <p>{{ $receiverDetail->headline}}</p>
                                <p>{{ $receiverDetail->location}}</p>
                            </div>
                             <div class="clr"></div>
                            <div class="borderPic">
                                <ul>
                                    <li><a href="{{$receiverDetail->linkedinurl}}" target="_blank"><img alt="" src="/images/linkdin.png"></a></li>
                                    <li>
                                    <a href="/profile/<?php echo strtolower($receiverDetail->fname.'-'.$receiverDetail->lname).'/'.$receiverDetail->id ;?>"><img alt="" src="/images/krmaicon.png"></a>
                                    <span>{{$receiverDetail->karmascore}}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <!--Karma person -->
                        <!--Karma person -->                        
                        <div class="col-xs-6 noteBox tabtxt">
                            <div class="col-sm-4">
                                @if ($giverDetail->piclink == '')
                                    <a href="/profile/<?php echo strtolower($giverDetail->fname.'-'.$giverDetail->lname).'/'.$giverDetail->id ;?>"><img height="87" width="82" alt="" src="/images/default.png"></a>
                                @else
                                  <a href="/profile/<?php echo strtolower($giverDetail->fname.'-'.$giverDetail->lname).'/'.$giverDetail->id ;?>">  <img height="87" width="82" src="{{ $giverDetail->piclink;}}"></a>
                                @endif
                            </div>
                            <div class="col-sm-8">
                                <a href="/profile/<?php echo strtolower($giverDetail->fname.'-'.$giverDetail->lname).'/'.$giverDetail->id ;?>"><h4>{{$giverDetail->fname." ".$giverDetail->lname}}</h4></a>
                        <p>{{ $giverDetail->headline}}</p>
                        <p>{{ $giverDetail->location}}</p>
                            </div>
                             <div class="clr"></div>
                            <div class="borderPic">
                                <ul>
                                  <li><a href="{{$giverDetail->linkedinurl}}" target="_blank"><img alt="" src="/images/linkdin.png"></a></li>
                                      @if (isset($giverDetail->email))
                                          <li>
                                        <a href="/profile/<?php echo strtolower($giverDetail->fname.'-'.$giverDetail->lname).'/'.$giverDetail->id ;?>"><img alt="" src="/images/krmaicon.png"></a>
                                        <span>{{$giverDetail->karmascore}}</span>
                                      </li>
                                      @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!--Karma person -->
                            
                    <div class="clr"></div>        
                     <p class="redPendingtxt bluetxtNp">Accepted</p>            
                 <div class="sendnoteBox">
                        <hr class="darkLine">
                         <h2>Request sent by {{$receiverDetail->fname}} to {{$giverDetail->fname}} (intro via {{$introducerDetail->fname}})
<br>on  {{date('F j, Y', strtotime($meetingDetail->req_createdate))}}<br> Subject: {{$meetingDetail->subject}}</h2>
                        <p class="grayColor">{{$meetingDetail->notes}}</p>
                        <ul class="iconList col-md-11 centralize">
                        @if ($meetingDetail['payitforward']+$meetingDetail['sendKarmaNote']+$meetingDetail['buyyoucoffee'] !=0)
                            <p>In gratitude, I will do the following -</p>
                            @if ($meetingDetail['payitforward'] == '1')
                                 <li>I'll pay it forward.</li>
                            @endif 
                            @if($meetingDetail['sendKarmaNote'] == '1')
                               <li>I'll send you a <a href="/FAQs/KarmaNotes/1" target="_blank">KarmaNote</a>.</li>
                            @endif 
                            @if($meetingDetail['buyyoucoffee'] == '1')
                                <li>I'll buy you coffee (in-person meetings only).</li>
                            @endif                            
                        @endif   
                        </ul>
                            

                        <hr class="darkLine">
                        <h2>Request accepted by  {{$giverDetail->fname}} on  {{date('F j, Y', strtotime($meetingDetail->req_updatedate))}}</h2>

                        <div class="action fullWidth">
                            <ul>
                                <li><span class="glyphicon glyphicon-tasks"></span>{{$meetingDetail->meetingduration}}</li>
                                <li><span class="glyphicon glyphicon-calendar"></span>{{ date('M d, Y',strtotime($meetingDetail->meetingdatetime))}}</li>
                                {{-- <li><span class="glyphicon glyphicon-time"></span>{{ date('g:i A',strtotime($meetingDetail->meetingdatetime))}}, GMT(
                                    @if ($meetingDetail->meetingtimezone >'0')
                                        {{'+'}}
                                    @endif
                                    {{$meetingDetail->meetingtimezone}})</li> --}}
                            </ul>
                        </div>

                        {{-- <div class="mb65 clearfix">
                            <div class="col-xs-12 skype">
                                <h4> @if ($meetingDetail->meetingtype == 'inperson')
                                    {{"In Person"}}
                                @elseif($meetingDetail->meetingtype == 'skype')
                                    {{"Skype"}}
                                @elseif($meetingDetail->meetingtype == 'phone')
                                    {{"Phone"}}
                                @elseif($meetingDetail->meetingtype == 'google')
                                    {{"Google"}}
                                @endif </h4>
                                <p>{{$meetingDetail->meetinglocation}}</p>
                            </div>
                            <div class="col-xs-12 mailID">
                            <span class="glyphicon glyphicon-envelope"></span>
                                <p>{{$giverDetail->email;}}</p>
                            </div>
                        </div>  --}}
                        <div class="mb65 clearfix" style="width:115%">  
                            <div class="col-xs-12 skype" >
                                <h4> <img src="/images/timezone.png" width="26" height="26"></h4> 
                                <p>{{date("g:i A", strtotime($meetingDetail->meetingdatetime)).' '.$meetingDetail->meetingtimezonetext}}</p>

                            </div>
                            <?php 

                            if($meetingDetail->meetingtype == "inperson") $image = '/images/big-person.png';
                            if($meetingDetail->meetingtype == "skype")    $image = '/images/big-skype.png'; 
                            if($meetingDetail->meetingtype == "phone")    $image = '/images/big-phone.png'; 
                            if($meetingDetail->meetingtype == "google")   $image = '/images/big-google.png';  
                            ?>     
                            <div class="col-xs-12 skype"> 
                                <h4>
                                    <img src="<?php  echo $image;?>"  width="22" height="22">
                                </h4>
                                <p>{{$meetingDetail->meetinglocation}}</p>
                            </div>
                            <div class="col-xs-12 mailID">
                            <span class="glyphicon glyphicon-envelope"></span>
                                <p>{{$giverDetail->email;}}</p>
                            </div>
                            <div align="center" class="minBtn">
                            
                            </div>
                        </div>

                        <hr class="darkLine">
                        <h2 class="mleft">Comments:</h2>  
                        <p class="grayColor mleft" > {{$meetingDetail->reply}}</p>
                    </div>
                    @if ($CurrentUser->id == $receiverDetail->id)
                        @if ($meetingDetail->meetingdatetime < $MettingActualCurrentTimeWithZone)
                            <div align="center" class="minBtn">
                                 <a href="/SendkarmaNote/{{$meetingDetail->id}}/{{$receiverDetail->fname.'-'.$receiverDetail->lname.'_'.$giverDetail->fname.'-'.$giverDetail->lname}}">
                            <button class="btn btn-success" type="button">Send Karma Note</button></a>
                            </div>
                        @endif
                    @endif  
                </div>

            </div>
        </div>    
    </section>
    <!-- /Main colom -->
@stop
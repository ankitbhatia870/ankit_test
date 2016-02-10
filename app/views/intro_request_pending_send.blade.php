@extends('common.master')
@section('content')
    <section class="mainWidth">
        <div class="col-lg-10 col-md-10 col-sm-12 centralize pdding0">
        
        <!-- <div class="backlink pull-right clearfix">
            <a href="/dashboard">Back to Karma Circle</a>
        </div> -->

            <div class="col-md-11 col-sm-12 centralize clearfix pull-left no-pull-md">
                <div class="registrFrm sendnotes regpending col-md-12">
                     <!--Karma person -->
                    <div class="col-sm-5 col-xs-12 noteBox tabtxt centralize"> 
                        <div class="col-xs-4">
                            @if ($introducerDetail->piclink == '')
                                 <a href="<?php echo '/profile/'.strtolower($introducerDetail->fname.'-'.$introducerDetail->lname).'/'.$introducerDetail->id ;?>"><img height="87" width="82" alt="" src="/images/default.png"></a>
                            @else
                                <a href="<?php echo '/profile/'.strtolower($introducerDetail->fname.'-'.$introducerDetail->lname).'/'.$introducerDetail->id ;?>">  <img height="87" width="82" src="{{ $introducerDetail->piclink;}}"></a>
                            @endif
                        </div>
                        <div class="col-sm-8 col-xs-12">
                            <a href="<?php echo '/profile/'.strtolower($introducerDetail->fname.'-'.$introducerDetail->lname).'/'.$introducerDetail->id ;?>"><h4>{{$introducerDetail->fname." ".$introducerDetail->lname}}</h4></a>
                                <p>{{ $introducerDetail->headline}}</p>
                                <p>{{ $introducerDetail->location}}</p>
                        </div>
                        <div class="clr"></div>
                        <div class="borderPic">
                            <ul>
                                <li><a href="{{$introducerDetail->linkedinurl}}" target="_blank"><img alt="" src="/images/linkdin.png"></a></li>
                                <li>
                                <a href="<?php echo '/profile/'.strtolower($introducerDetail->fname.'-'.$introducerDetail->lname).'/'.$introducerDetail->id ;?>"><img alt="" src="/images/krmaicon.png"></a>
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
                                     <a href="<?php echo '/profile/'.strtolower($receiverDetail->fname.'-'.$receiverDetail->lname).'/'.$receiverDetail->id ;?>"> <img height="87" width="82" alt="" src="/images/default.png"></a>
                                @else
                                    <a href="<?php echo '/profile/'.strtolower($receiverDetail->fname.'-'.$receiverDetail->lname).'/'.$receiverDetail->id ;?>">  <img height="87" width="82" src="{{ $receiverDetail->piclink;}}">
                                </a>
                                @endif
                            </div>
                            <div class="col-sm-8">
                                 <a href="/profile/{{$receiverDetail->id}}/{{$receiverDetail->fname}}-{{$receiverDetail->lname}}"><h4>{{$receiverDetail->fname." ".$receiverDetail->lname}}</h4></a>
                                <p>{{ $receiverDetail->headline}}</p>
                                <p>{{ $receiverDetail->location}}</p>
                            </div>
                             <div class="clr"></div>
                            <div class="borderPic">
                                <ul>
                                    <li><a href="{{$receiverDetail->linkedinurl}}" target="_blank"><img alt="" src="/images/linkdin.png"></a></li>
                                    <li>
                                    <a href="<?php echo '/profile/'.strtolower($receiverDetail->fname.'-'.$receiverDetail->lname).'/'.$receiverDetail->id ;?>"><img alt="" src="/images/krmaicon.png"></a>
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
                                    @if (isset($giverDetail->email))
                                    <a href="<?php echo '/profile/'.strtolower($giverDetail->fname.'-'.$giverDetail->lname).'/'.$giverDetail->id ;?>"><img height="87" width="82" alt="" src="/images/default.png"></a>
                                    @else
                                         <img height="87" width="82" alt="" src="/images/default.png">
                                    @endif
                                @else
                                    @if (isset($giverDetail->email))
                                        <a href="<?php echo '/profile/'.strtolower($giverDetail->fname.'-'.$giverDetail->lname).'/'.$giverDetail->id ;?>"><img height="87" width="82" src="{{ $giverDetail->piclink;}}"></a>
                                    @else
                                        <img height="87" width="82" src="{{ $giverDetail->piclink;}}">
                                    @endif
                                @endif
                            </div>
                            <div class="col-sm-8">
                                @if (isset($giverDetail->email))
                                 
                                <a href="<?php echo '/profile/'.strtolower($giverDetail->fname.'-'.$giverDetail->lname).'/'.$giverDetail->id ;?>"><h4>{{$giverDetail->fname." ".$giverDetail->lname}}</h4>
                                </a>
                                @else
                                       <h4>{{$giverDetail->fname." ".$giverDetail->lname}}</h4>
                               
                                @endif
                        <p>{{ $giverDetail->headline}}</p>
                        <p>{{ $giverDetail->location}}</p>
                            </div>
                             <div class="clr"></div>
                            <div class="borderPic">
                                <ul>
                                  <li><a href="{{$giverDetail->linkedinurl}}" target="_blank"><img alt="" src="/images/linkdin.png"></a></li>
                                      @if (isset($giverDetail->email))
                                          <li>
                                        <a href="<?php echo '/profile/'.strtolower($giverDetail->fname.'-'.$giverDetail->lname).'/'.$giverDetail->id ;?>"><img alt="" src="/images/krmaicon.png"></a>
                                        <span>{{$giverDetail->karmascore}}</span>
                                      </li>
                                      @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!--Karma person -->
                <div class="clr"></div>
                @if (Auth::check())
                    @if ($CurrentUser->id == $giverDetail->id || $CurrentUser->id == $receiverDetail->id)
                        @if ($meetingDetail->status == 'pending')
                            <p class="redPendingtxt">Pending</p>
                        @elseif($meetingDetail->status == 'archived')
                            <p class="redPendingtxt yeltxt">Archived</p>    
                        @endif
                    @else
                         <p class="redPendingtxt">Pending</p>
                    @endif
                @else
                    <p class="redPendingtxt">Pending</p>
                @endif
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
                    </div>   
                    @if (Auth::check() && ( $meetingDetail->status == 'pending' || $meetingDetail->status == 'archived'))
                        @if ($CurrentUser->id == $giverDetail->id)
                            <div align="center" class="minBtn">
                                @if ($meetingDetail->status == 'pending')
                                      <button type="button" onclick="archiveMeeting({{$meetingDetail->id}})" class="btn btn-warning">Archive</button>
                                @elseif($meetingDetail->status == 'archived')
                                     <a href="{{URL::previous()}}"><button type="button" class="btn btn-warning">Cancel</button></a>
                                @endif                              
                                <a href="/meeting/accept/{{$meetingDetail->id}}"><button type="button" class="btn btn-success">Accept</button></a>
                            </div>
                        @endif
                    @endif   
                </div>

            </div>
        </div>    
    </section>
    <!-- /Main colom -->
@stop
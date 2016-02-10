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
                                 <a href="<?php echo '/profile/'.strtolower($introducerDetail->fname.'-'.$introducerDetail->lname).'/'.$introducerDetail->id ;?>"<img height="87" width="82" src="{{ $introducerDetail->piclink;}}"></a>
                            @endif
                        </div>
                        <div class="col-sm-8 col-xs-12">
                           <a href="<?php echo '/profile/'.strtolower($introducerDetail->fname.'-'.$introducerDetail->lname).'/'.$introducerDetail->id ;?>"> <h4>{{$introducerDetail->fname." ".$introducerDetail->lname}}</h4></a>
                                <p>{{ $introducerDetail->headline}}</p>
                                <p>{{ $introducerDetail->location}}</p>
                        </div>
                        <div class="clr"></div>
                        <div class="borderPic">
                            <ul>
                                <li><a href="{{$introducerDetail->linkedinurl}}" target="_blank"><img alt="" src="/images/linkdin.png"></a></li>
                                <li>
                                <a href="<?php echo '/profile/'.strtolower($introducerDetail->fname.'-'.$introducerDetail->lname).'/'.$introducerDetail->id ;?>"><img alt="" src="/images/krmaicon.png"></a>
                                <a href="<?php echo URL::to('/');?>/FAQs/KarmaPoints/1"><span>{{$introducerDetail->karmascore}}</span></a>
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
                                     <a href="<?php echo '/profile/'.strtolower($receiverDetail->fname.'-'.$receiverDetail->lname).'/'.$receiverDetail->id ;?>"><img height="87" width="82" alt="" src="/images/default.png"></a>
                                @else
                                    <a href="<?php echo '/profile/'.strtolower($receiverDetail->fname.'-'.$receiverDetail->lname).'/'.$receiverDetail->id ;?>"> <img height="87" width="82" src="{{ $receiverDetail->piclink;}}"></a>
                                @endif
                            </div>
                            <div class="col-sm-8">
                                <a href="<?php echo '/profile/'.strtolower($receiverDetail->fname.'-'.$receiverDetail->lname).'/'.$receiverDetail->id ;?>"><h4>{{$receiverDetail->fname." ".$receiverDetail->lname}}</h4></a>
                                <p>{{ $receiverDetail->headline}}</p>
                                <p>{{ $receiverDetail->location}}</p>
                            </div>
                             <div class="clr"></div>
                            <div class="borderPic">
                                <ul>
                                    <li><a href="{{$receiverDetail->linkedinurl}}" target="_blank"><img alt="" src="/images/linkdin.png"></a></li>
                                    <li>
                                    <a href="<?php echo '/profile/'.strtolower($receiverDetail->fname.'-'.$receiverDetail->lname).'/'.$receiverDetail->id ;?>"><img alt="" src="/images/krmaicon.png"></a>
                                    <a href="<?php echo URL::to('/');?>/FAQs/KarmaPoints/1"><span>{{$receiverDetail->karmascore}}</span></a>
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
                                     <a href="<?php echo '/profile/'.strtolower($giverDetail->fname.'-'.$giverDetail->lname).'/'.$giverDetail->id ;?>">
                                        <img height="87" width="82" alt="" src="/images/default.png">
                                    </a>
                                    @else
                                           <img height="87" width="82" alt="" src="/images/default.png">  
                                    @endif
                                @else
                                    @if (isset($giverDetail->email))
                                     <a href="<?php echo '/profile/'.strtolower($giverDetail->fname.'-'.$giverDetail->lname).'/'.$giverDetail->id ;?>">
                                    <img height="87" width="82" src="{{ $giverDetail->piclink;}}">
                                    </a>
                                    @else
                                       <img height="87" width="82" src="{{ $giverDetail->piclink;}}">      
                                    @endif


                                @endif
                            </div>
                            <div class="col-sm-8">
                                @if (isset($giverDetail->email))
                                <a href="<?php echo '/profile/'.strtolower($giverDetail->fname.'-'.$giverDetail->lname).'/'.$giverDetail->id ;?>">
                                <h4>{{$giverDetail->fname." ".$giverDetail->lname}}</h4>
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
                                        <a href="<?php echo URL::to('/');?>/FAQs/KarmaPoints/1"><span>{{$giverDetail->karmascore}}</span></a>
                                      </li>
                                      @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!--Karma person -->
                            
                    <div class="clr"></div>        
                      <p class="redPendingtxt bluetxtNp">Completed</p>  
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
                                    <li><span class="glyphicon glyphicon-calendar"></span>{{ date('d-m-Y',strtotime($meetingDetail->meetingdatetime))}}</li>
                                    <li><span class="glyphicon glyphicon-time"></span>{{ date('g:i A',strtotime($meetingDetail->meetingdatetime))}}, GMT(
                                    @if ($meetingDetail->meetingtimezone > '0')
                                        {{"+"}}
                                    @endif
                                    {{$meetingDetail->meetingtimezone}})</li>
                                </ul>
                            </div>
                            <div class="mb65 clearfix">
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
                            </div>

                            <p class="grayColor">{{$meetingDetail->reply}}</p>
                        </div>
                   
                    <hr class="darkLine">      
                    <div class="col-md-12">
                        <div class="trail clearfix">
                          <div class="col-sm-2 pdding0 borderPic">
                            @if ($receiverDetail->piclink == '')
                               <a href="<?php echo '/profile/'.strtolower($receiverDetail->fname.'-'.$receiverDetail->lname).'/'.$receiverDetail->id ;?>"> <img alt="" src="/images/default.png"></a>
                            @else
                                <a href="<?php echo '/profile/'.strtolower($receiverDetail->fname.'-'.$receiverDetail->lname).'/'.$receiverDetail->id ;?>"><img src="{{ $receiverDetail->piclink;}}" class="img-responsive"  alt = "{{$receiverDetail->fname;}}" title = "{{$receiverDetail->fname;}}"></a>
                            @endif    
                              <ul>
                                  <li><a href="{{$receiverDetail->linkedinurl}}" target="_blank"><img src="/images/linkdin.png" alt=""></a></li>
                                  <li>
                                    <a href="<?php echo '/profile/'.strtolower($receiverDetail->fname.'-'.$receiverDetail->lname).'/'.$receiverDetail->id ;?>">
                                        <img src="/images/krmaicon.png" alt=""></a>
                                    <a href="<?php echo URL::to('/');?>/FAQs/KarmaPoints/1"><span>{{$receiverDetail->karmascore}}</span></a>
                                  </li>
                              </ul>
                          </div>
                          <div class="col-sm-10 pdding0 tabtxt">
                              <h4>Karma Note Sent to {{$giverDetail->fname}} by {{$receiverDetail->fname}}</h4>
                              <p>{{$karmaNoteDetail->details}}</p>
                              @if (!empty($skillSet))
                                <ul class="tag">
                                    @foreach ($skillSet as $element)
                                        <a href="<?php echo URL::to('/').'/searchUsers?searchUser='.$element['name'].'&searchOption=Skills';?>"><li>{{$element['name']}}</li></a>
                                    @endforeach
                                </ul>  
                              @endif
                              <div class="action pull-right">
                                <p></p>
                                <p>{{date('F d, Y', strtotime($karmaNoteDetail->created_at))}}</p>
                              </div>
                          </div>
                        </div>
                    </div>
                    @if(($karmaNoteDetail->user_idreceiver == $CurrentUser->id) || ($karmaNoteDetail->user_idgiver == $CurrentUser->id))
                    <div align="center" class="minBtn">
                        {{ Form::open(array('url' => 'ToggleStatus' , 'method' => '  post')) }}
                        @if ($karmaNoteDetail->user_idgiver != $CurrentUser->id)
                            <p>This KarmaNote is {{$karmaNoteDetail->statusreceiver}} on your  profile.</p>
                            {{ Form::hidden('user_id','receiver'); }}
                            {{ Form::hidden('noteId',$karmaNoteDetail->id); }}
                            {{Form::submit('Hide',array('class'=>'btn btn-warning', 'value'=>'hidden', 'name'=>'status'));}}  
                            {{Form::submit('Show',array('class'=>'btn btn-success', 'value'=>'visible', 'name'=>'status'));}}  
                        @else 
                            <p>This KarmaNote is {{$karmaNoteDetail->statusgiver}} on your  profile.</p>
                            {{ Form::hidden('user_id','giver'); }}
                            {{ Form::hidden('noteId',$karmaNoteDetail->id); }}
                            {{Form::submit('Hide',array('class'=>'btn btn-warning', 'value'=>'hidden', 'name'=>'status'));}}  
                            {{Form::submit('Show',array('class'=>'btn btn-success', 'value'=>'visible', 'name'=>'status'));}}  
                       @endif
                       {{ Form::close() }}                        
                    </div>
                    @endif
                </div>

            </div>
        </div>    
    </section>
    <!-- /Main colom -->
@stop
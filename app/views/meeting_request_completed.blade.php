@extends('common.master')
@section('content')
<?php
$get_permalink = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; 
 //echo  "<pre>";print_r($giverDetail);echo "</pre>---";?>
    <section class="mainWidth">
        <div class="col-lg-10 col-md-10 col-sm-12 centralize pdding0">        
        <!-- <div class="backlink pull-right clearfix">
            <a href="/dashboard">Back to Karma Circle</a>
        </div> -->

            <div class="col-md-11 col-sm-12 centralize clearfix pull-left no-pull-md">
                <div class="registrFrm sendnotes col-md-12">
                    <div class="sendnoteBox">
                        <div class="col-sm-4 col-xs-12 pdding0 borderPic">
                            @if ($receiverDetail->piclink == '')
                             @if (!empty($receiverDetail->email))   
                              <a href="<?php echo '/profile/'.strtolower($receiverDetail->fname.'-'.$receiverDetail->lname).'/'.$receiverDetail->id ;?>"><img alt="" src="/images/default.png"></a>
                              @else
                              <a href="{{$receiverDetail->linkedinurl}}" target="_blank"><img alt="" src="/images/default.png"></a>
                              @endif
                            @else
                                @if (!empty($receiverDetail->email))   
                                <a href="<?php echo '/profile/'.strtolower($receiverDetail->fname.'-'.$receiverDetail->lname).'/'.$receiverDetail->id ;?>">
                                    <img src="{{ $receiverDetail->piclink;}}" class="img-responsive"  alt = "{{$receiverDetail->fname;}}" title = "{{$receiverDetail->fname;}}">
                                </a>
                                @else
                                    <img src="{{ $receiverDetail->piclink;}}" class="img-responsive"  alt = "{{$receiverDetail->fname;}}" title = "{{$receiverDetail->fname;}}">
                                @endif

                            @endif    
                            @if (!empty($receiverDetail->email))                            
                                <a href="<?php echo '/profile/'.strtolower($receiverDetail->fname.'-'.$receiverDetail->lname).'/'.$receiverDetail->id ;?>"><h4>{{$receiverDetail->fname." ".$receiverDetail->lname}}</h4>
                                </a>
                            @else
                                <a href="{{$receiverDetail->linkedinurl}}" target="_blank"><h4>{{$receiverDetail->fname." ".$receiverDetail->lname}}</h4></a>
                               
                            @endif


                                <div class="borderPic midicondetails">
                                    <ul class="clearfix">
                                        <li><a href="{{$receiverDetail->linkedinurl}}" target="_blank"><img src="/images/linkdin.png" alt=""></a></li>
                                        @if (!empty($receiverDetail->email))
                                            <li>
                                                <a href="<?php echo '/profile/'.strtolower($receiverDetail->fname.'-'.$receiverDetail->lname).'/'.$receiverDetail->id ;?>"><img src="/images/krmaicon.png" alt=""></a>
                                                <a href="<?php echo URL::to('/');?>/FAQs/KarmaPoints/1"><span>{{$receiverDetail->karmascore}}</span></a>
                                            </li>
                                        @endif
                                        
                                    </ul>
                                </div>
                            <p>{{$receiverDetail->email}}</p>
                        </div>
                        <div class="col-sm-4 col-xs-12 thumbs">
                            <img alt="" src="/images/thumbsUp.png">
                            <p class="redPendingtxt bluetxt">Completed</p>
                        </div>
                        <div class="col-sm-4 col-xs-12 pdding0 borderPic">
                            @if ($giverDetail->piclink == '')
                                @if (!empty($giverDetail->email))
                                    <a href="<?php echo '/profile/'.strtolower($giverDetail->fname.'-'.$giverDetail->lname).'/'.$giverDetail->id ;?>"><img alt="" src="/images/default.png"><a>
                                @else
                                     <a href="{{$giverDetail->linkedinurl}}" target="_blank"><img alt="" src="/images/default.png"></a>
                                @endif
                            @else
                                @if (!empty($giverDetail->email))
                                <a href="<?php echo '/profile/'.strtolower($giverDetail->fname.'-'.$giverDetail->lname).'/'.$giverDetail->id ;?>">
                                    <img src="{{ $giverDetail->piclink;}}" class="img-responsive"  alt = "{{$giverDetail->fname;}}" title = "{{$giverDetail->fname;}}">
                                </a>
                                @else

                                    <a href="{{$giverDetail->linkedinurl}}" target="_blank"><img src="{{ $giverDetail->piclink;}}" class="img-responsive"  alt = "{{$giverDetail->fname;}}" title = "{{$giverDetail->fname;}}"></a>
                                @endif


                            @endif  
                                @if (!empty($giverDetail->email))
                                    <a href="<?php echo '/profile/'.strtolower($giverDetail->fname.'-'.$giverDetail->lname).'/'.$giverDetail->id ;?>">
                                        <h4>{{$giverDetail->fname." ".$giverDetail->lname}}</h4>
                                    </a>
                                @else

                                        <a href="{{$giverDetail->linkedinurl}}" target="_blank"><h4>{{$giverDetail->fname." ".$giverDetail->lname}}</h4></a>
                                @endif



                                    <div class="borderPic midicondetails">
                                        <ul class="clearfix">
                                            <li><a href="{{$giverDetail->linkedinurl}}" target="_blank"><img src="/images/linkdin.png" alt=""></a></li>
                                            @if (!empty($giverDetail->email))
                                                <li>
                                                    <a href="<?php echo '/profile/'.strtolower($giverDetail->fname.'-'.$giverDetail->lname).'/'.$giverDetail->id ;?>"><img src="/images/krmaicon.png" alt=""></a>
                                                    <a href="<?php echo URL::to('/');?>/FAQs/KarmaPoints/1"><span>{{$giverDetail->karmascore}}</span></a>
                                                </li>
                                            @endif    
                                        </ul>
                                    </div>
                                <p>{{$giverDetail->email}}</p>
                        </div>                   
                        <div class="clr"></div>
                    </div>
                    <hr class="darkLine">      
                    <div class="col-md-12">
                        <div class="trail clearfix">
                          <div class="col-sm-2 pdding0 borderPic">
                            @if ($receiverDetail->piclink == '')
                                <a href="<?php echo '/profile/'.strtolower($receiverDetail->fname.'-'.$receiverDetail->lname).'/'.$receiverDetail->id ;?>"><img alt="" src="/images/default.png"><a/>
                            @else
                               <a href="<?php echo '/profile/'.strtolower($receiverDetail->fname.'-'.$receiverDetail->lname).'/'.$receiverDetail->id ;?>"> <img src="{{ $receiverDetail->piclink;}}" class="img-responsive"  alt = "{{$receiverDetail->fname;}}" title = "{{$receiverDetail->fname;}}"><a/>
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
                            @if($karmaNoteDetail->statusreceiver == 'visible')
                            {{Form::submit('Hide',array('class'=>'btn btn-warning', 'value'=>'hidden', 'name'=>'status'));}}  
                            @endif
                             @if($karmaNoteDetail->statusreceiver == 'hidden')
                            {{Form::submit('Show',array('class'=>'btn btn-success', 'value'=>'visible', 'name'=>'status'));}}  
                            @endif
                        @else 
                            <p>This KarmaNote is {{$karmaNoteDetail->statusgiver}} on your  profile.</p>
                            {{ Form::hidden('user_id','giver'); }}
                            {{ Form::hidden('noteId',$karmaNoteDetail->id); }}

                           

                            @if($karmaNoteDetail->statusgiver == 'visible')
                            {{Form::submit('Hide',array('class'=>'btn btn-warning', 'value'=>'hidden', 'name'=>'status'));}}  
                            @endif
                            @if($karmaNoteDetail->statusgiver == 'hidden')
                            {{Form::submit('Show',array('class'=>'btn btn-success', 'value'=>'visible', 'name'=>'status'));}}  
                            @endif
                       @endif
                       {{ Form::close() }}
                        
                    </div>
                    @endif
                    <hr class="darkLine">
                    {{-- Chat box --}}
                    @if (!empty($meetingTrailData))
                        <div class="massageing">
                            <div class="massageHistory">
                                <?php //echo '<pre>';print_r($meetingTrailData);die;?>
                                @foreach ($meetingTrailData as $meetingData)
                                    @if ($meetingData->message_type=='system')
                                        <p class="meetingNsg">{{ $meetingData->messageText }}</p>
                                    @else ($meetingData->message_type=='user')
                                        <?php $date=date('F d,Y', strtotime($meetingData->created_at)); ?>
                                        
                                            @if($meetingData->sender_id==$CurrentUser['id'] && $userRole='Receiver')
                                            <div class="sent">
                                            <p>{{ $meetingData->messageText }}</p>
                                            <span></span>
                                        </div>
                                        <div class="clr"></div>
                                        <div class="dateSml"><span>{{ $date }}</span> </div>
                                        <div class="clr"></div>
                                        @else
                                        <div class="sender">
                                            <p>{{ $meetingData->messageText }}</p>
                                        </div>
                                        <div class="clr"></div>
                                        <div class="dateSml"><span>{{ $date }}</span></div>
                                        <div class="clr"></div>

                                        @endif
                                        <div class="clr"></div>
                        
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif
                    {{-- Chat box/ --}}
                </div>
            </div>
        </div>    
    </section>
    <!-- /Main colom -->
@stop
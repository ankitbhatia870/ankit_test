@extends('common.master')
@section('content')
<?php //echo  "<pre>";print_r($giverDetail);echo "</pre>---";?>
    <section class="mainWidth">
        <div class="col-lg-10 col-md-10 col-sm-12 centralize pdding0">
        
        <!-- <div class="backlink pull-right clearfix">
            <a href="{{URL::previous()}}">Back to profile</a>
        </div> -->
            <div class="col-md-11 col-sm-12 centralize clearfix pull-left no-pull-md">
                <div class="registrFrm sendnotes col-md-12">
                    <!--Karma person -->
                    <div class="col-sm-5 col-xs-12 noteBox tabtxt centralize">
                        <div class="col-xs-4">
                            @if ($introducerDetail->piclink == '')
                                <a href="/profile/<?php echo strtolower($introducerDetail->fname.'-'.$introducerDetail->lname).'/'.$introducerDetail->id ;?>">
                                 <img height="87" width="82" alt="" src="/images/default.png">
                                </a>
                            @else
                                <a href="/profile/<?php echo strtolower($introducerDetail->fname.'-'.$introducerDetail->lname).'/'.$introducerDetail->id ;?>">
                                 <img height="87" width="82" src="{{ $introducerDetail->piclink;}}">
                                </a>
                            @endif
                        </div>
                        <div class="col-sm-8 col-xs-12">
                           <a href="/profile/<?php echo strtolower($introducerDetail->fname.'-'.$introducerDetail->lname).'/'.$introducerDetail->id ;?>"> <h4>{{$introducerDetail->fname." ".$introducerDetail->lname}}</h4></a>
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
                                    <a href="/profile/<?php echo strtolower($receiverDetail->fname.'-'.$receiverDetail->lname).'/'.$receiverDetail->id ;?>"> <img height="87" width="82" alt="" src="/images/default.png"></a>
                                @else
                                    <a href="/profile/<?php echo strtolower($receiverDetail->fname.'-'.$receiverDetail->lname).'/'.$receiverDetail->id ;?>"> <img height="87" width="82" src="{{ $receiverDetail->piclink;}}"></a>
                                @endif
                            </div>
                            <div class="col-sm-8">
                                <a href="/profile/<?php echo strtolower($receiverDetail->fname.'-'.$receiverDetail->lname).'/'.$receiverDetail->id ;?>"><h4>{{$receiverDetail->fname." ".$receiverDetail->lname}}</h4></a>
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
                                    <a href="/profile/<?php echo strtolower($giverDetail->fname.'-'.$giverDetail->lname).'/'.$giverDetail->id ;?>"><img height="87" width="82" src="{{ $giverDetail->piclink;}}"></a>
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
                     <div class="clr"></div>        
                      <p class="redPendingtxt bluetxtNp">Completed</p>  

                    <!--Karma person -->
                    <hr class="darkLine">      
                    <div class="col-md-12">
                        <div class="trail clearfix">
                          <div class="col-sm-2 pdding0 borderPic">
                            @if ($receiverDetail->piclink == '')
                                <a href="/profile/<?php echo strtolower($receiverDetail->fname.'-'.$receiverDetail->lname).'/'.$receiverDetail->id ;?>"><img alt="" src="/images/default.png"></a>
                            @else
                                <a href="/profile/<?php echo strtolower($receiverDetail->fname.'-'.$receiverDetail->lname).'/'.$receiverDetail->id ;?>"><img src="{{ $receiverDetail->piclink;}}" class="img-responsive"  alt = "{{$receiverDetail->fname;}}" title = "{{$receiverDetail->fname;}}"></a>
                            @endif    
                              <ul>
                                  <li><a href="{{$receiverDetail->linkedinurl}}" target="_blank"><img src="/images/linkdin.png" alt=""></a></li>
                                  <li>
                                    <a href="/profile/<?php echo strtolower($receiverDetail->fname.'-'.$receiverDetail->lname).'/'.$receiverDetail->id ;?>">
                                        <img src="/images/krmaicon.png" alt=""></a>
                                    <span>{{$receiverDetail->karmascore}}</span>
                                  </li>
                              </ul>
                          </div>
                          <div class="col-sm-10 pdding0 tabtxt">
                              <a href="/profile/<?php echo strtolower($receiverDetail->fname.'-'.$receiverDetail->lname).'/'.$receiverDetail->id ;?>"><h4>Karma Note Sent to {{$giverDetail->fname}} by {{$receiverDetail->fname}}</h4></a>
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
                    @if (Auth::check())
                        <div align="center" class="minBtn">
                            {{ Form::open(array('url' => 'ToggleStatus' , 'method' => '  post')) }}
                            @if ($karmaNoteDetail->user_idreceiver == $CurrentUser->id)
                                <p>This KarmaNote is {{$karmaNoteDetail->statusreceiver}} on your  profile.</p>
                                {{ Form::hidden('user_id','receiver'); }}
                                {{ Form::hidden('publicPage','1'); }}
                                {{ Form::hidden('noteId',$karmaNoteDetail->id); }}
                                {{Form::submit('Hide',array('class'=>'btn btn-warning', 'value'=>'hidden', 'name'=>'status'));}}  
                                {{Form::submit('Show',array('class'=>'btn btn-success', 'value'=>'visible', 'name'=>'status'));}}  
                            @elseif($karmaNoteDetail->user_idgiver == $CurrentUser->id) 
                                <p>This KarmaNote is {{$karmaNoteDetail->statusgiver}} on your  profile.</p>
                                {{ Form::hidden('user_id','giver'); }}
                                {{ Form::hidden('publicPage','1'); }}
                                {{ Form::hidden('noteId',$karmaNoteDetail->id); }}
                                {{Form::submit('Hide',array('class'=>'btn btn-warning', 'value'=>'hidden', 'name'=>'status'));}}  
                                {{Form::submit('Show',array('class'=>'btn btn-success', 'value'=>'visible', 'name'=>'status'));}} 
                            @else
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
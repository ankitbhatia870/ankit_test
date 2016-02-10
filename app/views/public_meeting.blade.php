@extends('common.master')
@section('content')
<?php //echo  "<pre>";print_r($giverDetail);echo "</pre>---";?>
    <section class="mainWidth">
        <div class="col-lg-10 col-md-10 col-sm-12 centralize pdding0">
        
      <!--   <div class="backlink pull-right clearfix">
            <a href="{{URL::previous()}}">Back to profile</a>
        </div>
 -->
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
                                    <a href="{{$receiverDetail->linkedinurl}}" target="_blank"><img src="{{ $receiverDetail->piclink;}}" class="img-responsive"  alt = "{{$receiverDetail->fname;}}" title = "{{$receiverDetail->fname;}}"></a>
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
                          
                        </div>
                        <div class="col-sm-4 col-xs-12 thumbs">
                            <div class="roundImgN">
                                <img alt="" src="/images/thumbsUp.png">
                            </div>
                        </div>
                        <div class="col-sm-4 col-xs-12 pdding0 borderPic">
                           @if ($giverDetail->piclink == '')
                                @if (!empty($giverDetail->email))
                                    <a href="<?php echo '/profile/'.strtolower($giverDetail->fname.'-'.$giverDetail->lname).'/'.$giverDetail->id ;?>"><img alt="" src="/images/default.png"><a>
                                @else
                                    <a href="{{$giverDetail->linkedinurl}}" target="_blank"> <img alt="" src="/images/default.png"></a>
                                @endif
                            @else
                                @if (!empty($giverDetail->email))
                                <a href="<?php echo '/profile/'.strtolower($giverDetail->fname.'-'.$giverDetail->lname).'/'.$giverDetail->id ;?>">
                                    <a href="{{$giverDetail->linkedinurl}}" target="_blank"><img src="{{ $giverDetail->piclink;}}" class="img-responsive"  alt = "{{$giverDetail->fname;}}" title = "{{$giverDetail->fname;}}"></a>
                                </a>
                                @else

                                     <a href="{{$giverDetail->linkedinurl}}" target="_blank"> <img src="{{ $giverDetail->piclink;}}" class="img-responsive"  alt = "{{$giverDetail->fname;}}" title = "{{$giverDetail->fname;}}"></a>
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
                              
                        </div>                   
                        <div class="clr"></div>
                    </div> 
                    <hr class="darkLine">      
                    <div class="col-md-12">
                        <div class="trail clearfix">
                          <div class="col-sm-2 pdding0 borderPic">
                            @if ($receiverDetail->piclink == '')
                                <a href="<?php echo '/profile/'.strtolower($receiverDetail->fname.'-'.$receiverDetail->lname).'/'.$receiverDetail->id ;?>"><img alt="" src="/images/default.png"></a>
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
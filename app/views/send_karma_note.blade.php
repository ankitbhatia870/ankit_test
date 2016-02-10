@extends('common.master')
@section('content')
<?php //echo "<pre>"; print_r($userSkills);die;?>
<?php // echo "<pre>"; print_r($meetingDetail->user_id_introducer);echo "</pre>";?>
<?php $meetingDetails = explode(' ', $meetingDetail['meetingdatetime']);
      $meetingDate   = $meetingDetails[0]  ;
      $meetingTime   = $meetingDetails[1];
      $meetingZone   = $meetingDetail['meetingtimezone'];
?>

                    
    <section class="mainWidth">
        <div class="col-lg-10 col-md-10 col-sm-12 centralize pdding0">
        
       <!--  <div class="backlink pull-right clearfix"> 
            <a href="/dashboard">Back to Karma Circle</a>
        </div> -->
            <div class="col-md-11 col-sm-12 centralize clearfix pull-left no-pull-md">
                <div class="registrFrm sendnotes col-md-12">
                    <!-- Form start -->
                    {{ Form::open(array('url' => 'saveKarmaNote' , 'method' => '  post', 'id'=> 'karmaNotes','onsubmit'=>'return validateKCnote();')) }}
                    <div class="sendnoteBox">
                        <div class="col-xs-4 pdding0 borderPic">
                            @if ($receiverDetail->piclink == '') 
                              <img alt="" src="/images/default.png">
                            @else
                            <img src="{{ $receiverDetail->piclink;}}" class="img-responsive"  alt = "{{$receiverDetail->fname;}}" title = "{{$receiverDetail->fname;}}">
                            @endif  
                          <h4>{{$receiverDetail->fname.' '.$receiverDetail->lname}}</h4>
                          <div class="borderPic midicondetails">
                            <ul class="clearfix">
                              <li>
                                <a target="_blank" href="{{$receiverDetail->linkedinurl}}"><img alt="" src="/images/linkdin.png"></a>
                              </li>
                              <li>
                                <a href="<?php echo '/profile/'.strtolower($receiverDetail->fname.'-'.$receiverDetail->lname).'/'.$receiverDetail->id ;?>"><img alt="" src="/images/krmaicon.png"></a>
                              <span>{{$receiverDetail->karmascore}}</span>                               
                              </li>
                            </ul>
                          </div>
                           <span>{{$receiverDetail->email}}</span>
                        </div>
                        <div class="col-xs-4 thumbs">
                          <img alt="" src="/images/thumbsUp.png">
                        </div>
                        <div class="col-xs-4 pdding0 borderPic">
                            @if ($giverDetail->piclink == '')
                              <img alt="" src="/images/default.png">
                            @else
                            <img src="{{ $giverDetail->piclink;}}" class="img-responsive"  alt = "{{$giverDetail->fname;}}" title = "{{$giverDetail->fname;}}">
                            @endif  
                          <h4>{{$giverDetail->fname.' '.$giverDetail->lname}}</h4>
                             <div class="borderPic midicondetails">
                            <ul class="clearfix">
                              <li>
                                <a target="_blank" href="{{$giverDetail->linkedinurl}}"><img alt="" src="/images/linkdin.png"></a>
                              </li>
                              <li>
                                <a href="<?php echo '/profile/'.strtolower($giverDetail->fname.'-'.$giverDetail->lname).'/'.$giverDetail->id ;?>"><img alt="" src="/images/krmaicon.png"></a>
                              <span>{{$giverDetail->karmascore}}</span>                        
                              </li>
                            </ul>
                          </div>
                           <span>{{$giverDetail->email}}</span>
                        </div>
                        <div class="col-sm-11 centralize pull-left">
                        
                        <div class="clr"></div>
                        <hr/ class="darkLine">
                        <div class="col-sm-12 centralize pull-left">
                            @if(!empty($userSkills))  
                              <ul class="grouplist tag checkBoxtag" id = "userSkills">
                                    @foreach ($Skills as $key=>$tag)
										
                                       @if ($key < '10')
                                            <li id = "skillList_{{$tag->id}}">
                                                <label>
                                                    {{$tag->name}}
                                                    <input type="checkbox" class = "skills" id="{{$tag->id}}" value = "{{$tag->id}}" name="skillTags[]">
                                                </label>    
                                            </li>
                                        @endif
										
                                    @endforeach
                                </ul>
                                <span class='error'></span>
                                 
                            @endif    
                      </div>
                      
                      <div class="clr"></div>
                        <hr/ class="darkLine">
                        <div class="action sendAction clearfix">
                            <ul>
                                <li><span class="glyphicon glyphicon-calendar"></span> {{$meetingDate}}</li>
                                <li><span class="glyphicon glyphicon-time"></span> {{date('g:i A',strtotime($meetingDetail->meetingdatetime))}}, {{$meetingZone}}</li>
                                <!-- <li><a href="" title="">Not This Meeting ?</a></li> -->
                            </ul>
                        </div>
                        <h2>Send Karma Note</h2>
                        <!-- <p class="centertxt">Did you donate any time/money to Ivan’s cause? </p>
                        <div class="col-md-7 col-sm-12 centralize ">
                            <div class="select col-xs-4 pdding0">
                            <span class="pointer"></span>
                                <select class="form-control">
                                  <option>Yes</option>
                                  <option>No</option>
                                </select>
                            </div>
                            <div class="col-xs-5">
                                <input type="email" placeholder="" class="form-control">
                            </div>
                            <div class="col-sm-3 pdding0">
                                dollars
                            </div>
                        </div> -->
                        <div class="col-xs-12 notetxt">
                            <!-- <textarea class="form-control" rows="10"></textarea> -->
                            {{ Form::textarea('details','',array('id'=>'karmanotedetail','class'=>'form-control','rows'=>'10')); }}
                            <span class='errordetail'></span>
                            {{$errors->first('details','<span class=error>:message</span>')}}
                        </div>
                    </div>
                    <div class="clr"></div> 
                     <div align="center">
                        {{Form::checkbox('ShareKarmaNote',"1",true)}}
                        <label>Share this Karmanote on Linkedin</label>
                    </div>
                   <div class="clr"></div>
                    <div align="center">
                        {{ Form::hidden('meetingId',$meetingDetail->id, array('class'=>'form-control')); }}
                        {{ Form::hidden('receiverId',$receiverDetail->id, array('class'=>'form-control')); }}
                         {{ Form::hidden('introducerId',$meetingDetail->user_id_introducer, array('class'=>'form-control')); }} 
                        {{ Form::hidden('receiverName',$receiverDetail->fname.'-'.$receiverDetail->lname, array('class'=>'form-control')); }}
                        {{ Form::hidden('giverId',$giverDetail->id, array('class'=>'form-control')); }}
                        {{ Form::hidden('giverName',$giverDetail->fname.'-'.$giverDetail->lname, array('class'=>'form-control')); }}
                         <a href="{{URL::previous()}}">{{Form::button('Cancel',array('class'=>'btn btn-warning'));}}</a>    
                        {{Form::submit('Send',array('class'=>'btn btn-success'));}}  
                    </div>
                    {{ Form::close() }}
                    <!--  Form end -->
                </div>
            </div>
        </div>    
    </section>
    <!-- /Main colom -->
    <script type="text/javascript">
       
    </script>
@stop

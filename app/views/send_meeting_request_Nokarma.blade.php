@extends('common.master')
@section('content')
    <section class="mainWidth">
        <div class="col-lg-10 col-md-10 col-sm-12 centralize pdding0">
            <!-- <div class="backlink pull-right clearfix">
                <a href="/dashboard">Back to Karma Circle</a>
            </div> -->
            <div class="col-md-11 col-sm-12 centralize clearfix pull-left no-pull-md">
                    <div class="registrFrm sendnotes col-md-12">
                        <div class="sendnoteBox">
                            <div class="col-xs-4 pdding0 borderPic">
                                @if ($CurrentUser->piclink == '')
                                 <img alt="" src="/images/default.png">
                                @else
                                 <img alt="" src="{{$CurrentUser->piclink}}">
                                @endif                           
                                <h4>{{$CurrentUser->fname.' '.$CurrentUser->lname}}</h4>
                                <div class="borderPic midicondetails">
                                    <ul class="clearfix">
                                        <li><a target="_blank" href="{{$CurrentUser->linkedinurl}}"><img alt="" src="/images/linkdin.png"></a></li>
                                        <li>
                                            <a href="<?php echo '/profile/'.strtolower($CurrentUser->fname.'-'.$CurrentUser->lname).'/'.$CurrentUser->id ;?>"><img alt="" src="/images/krmaicon.png"></a>
                                            <span>{{$CurrentUser->karmascore}}</span>
                                        </li>
                                    </ul>
                                </div>
                                <p>{{$CurrentUser->email}}</p>
                            </div>
                            <div class="col-xs-4 thumbs">
                                <img alt="" src="/images/meetingIcon.png">
                            </div>
                            <div class="col-xs-4 pdding0 borderPic">
                                @if ($ConnectionDetail->piclink == '')
                                    <img alt="" src="/images/default.png">
                                @else
                                    <img alt="" src="{{$ConnectionDetail->piclink}}">
                                @endif                          
                                <h4>{{$ConnectionDetail->fname.' '.$ConnectionDetail->lname}}</h4>
                                <div class="borderPic midicondetails">
                                    <ul class="clearfix">
                                        <li><a href="{{$ConnectionDetail->linkedinurl}}" target="_blank"><img alt="" src="/images/linkdin.png"></a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="clr"></div>
                        </div>
                        {{ Form::open(array('url' => 'SendMeetingRequest' , 'method' => '  post')) }}
                        <div class="col-md-11 col-sm-12 col-md-pull-1">
                            <div class="col-md-11 col-sm-12 centralize">
                                <div class="margin-bot20">
                                    <div class="select col-xs-3 pdding0">
                                        <p>Subject</p>
                                    </div>
                                    <div class="select col-xs-9 pdding0">                                
                                    <div class="clr"></div>
                                        {{ Form::text('subject','',array('required'=>'required','class'=>'form-control')); }}
                                        {{$errors->first('subject','<span class=error>:message</span>')}}
                                        {{ Form::hidden('user_id_receiver',$CurrentUser->id,array('class'=>'form-control')); }}
                                        {{ Form::hidden('connection_id_giver',$ConnectionDetail->id,array('class'=>'form-control'));}}
                                    </div>
                                </div>
                                <div class="margin-bot20 hAuto clearfix">
                                    <div class="select col-xs-3 pdding0">
                                        <p>Details</p>
                                    </div>
                                    <div class="select col-xs-9 pdding0">
                                        {{ Form::textarea('notes','',array('required'=>'required','class'=>'form-control','rows'=>'6')); }}
                                         {{$errors->first('notes','<span class=error>:message</span>')}}
                                    </div>
                                </div>

                                <div class="margin-bot20">
                                    <div class="select col-xs-2 col-md-offset-1 pdding0">
                                       <!--  <span class="glyphicon glyphicon-time fullIcon"></span> -->
                                       <p class="alignL">Usually</p>
                                    </div>
                                    <div class="select col-xs-3 pdding0">
                                        <span class="pointer"></span>
                                        <select required="required" class="form-control" name="weekday_call">
                                            <option value="Every day">Every day</option>
                                            <option value="Week day">Weekday</option>
                                            <option value="Weekend">Weekend</option>
                                            <option value="Monday">Monday</option>
                                            <option value="Tuesday">Tuesday</option>
                                            <option value="Wednesday">Wednesday</option>
                                            <option value="Thursday">Thursday</option>
                                            <option value="Friday">Friday</option>
                                            <option value="Saturday">Saturday</option>
                                            <option value="Sunday">Sunday</option>
                                        </select>
                                    </div>
                                
                                    <div class="select col-xs-3 paddingR">
                                        <span class="pointer"></span>
                                        <select required="required" class="form-control" name="weekday_call_time">
                                            <option value="Whole day">whole day</option> 
                                            <option value="Morning">Morning</option>
                                            <option value="Afternoon">Afternoon</option>
                                            <option value="Evening">Evening</option>
                                        </select>
                                    </div>
                                    <div class="select col-xs-3 pdding0">
                                       <!--  <span class="glyphicon glyphicon-time fullIcon"></span> -->
                                       <p class="alignL">works for me.</p>
                                    </div>

                                </div>
                                <?php   
                                    $email_text = "Email"; 
                                 
                                ?>
                                <div class="margin-bot20 ">
                                    <div class="select col-xs-12 pdding0">
                                     
                                    <div class="select col-xs-3 pdding0">
                                        <p>{{$email_text}}</p> 
                                    </div>    
                                        <div class="form-group">
                                            <div class="select col-xs-9  pdding0">
                                               
                                                {{Form::email('giver_email', '', array('required'=>'required','class'=>'form-control','placeholder'=>'Please specify email address of '.$ConnectionDetail->fname.' '.$ConnectionDetail->lname))}}
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>    
                            </div>
                        </div>
                        <div class="clr"></div>
                        <div class="sendnoteBox">
                            <ul class="iconList iconListNO col-md-11 centralize">
                                <p>In gratitude, I will do the following -</p>
                                <li>{{Form::checkbox('receiverWR[]', "I'd pay it forward", true); }}I'll pay it forward.</li>
                                <li>{{Form::checkbox('receiverWR[]', "I'd send you a Karma Note", true); }}I'll send you a <a href="/FAQs/KarmaNotes/1" target="_blank">KarmaNote</a>.</li>
                                </li>
                                <li>{{Form::checkbox('receiverWR[]', "I'd buy you coffee (in-person meetings only)", false); }}I'll buy you coffee (in-person meetings only).</li>
                            </ul>
                        </div>
                        <div class="clr"></div>
                        <div align="center" class="minBtn">
                            <a href="{{URL::previous()}}"><button type="button" class="btn btn-warning">Cancel</button></a>
                            {{Form::submit('Request Meeting',array('class'=>'btn btn-success'));}}
                            {{ Form::close() }}
                        </div>
                    </div>
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
            <p id="Limitboxmsg">Please specify the email address of {{$ConnectionDetail->fname}} to send this message.</p>
          </div>
          <div class="modal-footer">
           <button data-dismiss="modal" class="btn btn-default linkfullBTN gpBtn pull-right" type="button" onclick="modelClose('LimitBox');">Continue</button>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog --> 
    </div> 
    </section>
    <script type="text/javascript">
        <?php if($checkMsgLimit != 1) { ?>
        openboxmodel('LimitBox','');
        <?php } ?>
    </script>
    <!-- /Main colom -->
@stop




@extends('common.master')
@section('content')
    <section class="mainWidth">
        <div class="col-lg-10 col-md-10 col-sm-12 centralize pdding0">
           <!--  <div class="backlink pull-right clearfix">
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
                                            <a href="/profile/<?php echo strtolower($CurrentUser->fname.'-'.$CurrentUser->lname).'/'.$CurrentUser->id;?>
"><img alt="" src="/images/krmaicon.png"></a>
                                            <span>{{$CurrentUser->karmascore}}</span>
                                        </li>
                                    </ul>
                                </div>
                                <p>{{$CurrentUser->email}}</p>
                            </div>
                            <div class="col-xs-4 thumbs">
                               <img alt="" src="/images/iconM.png">
                            </div> 
                            <div class="col-xs-4 pdding0 borderPic">
                                @if ($GiverInfo->piclink == '')
                                    <img alt="" src="/images/default.png">
                                @else
                                    <img alt="" src="{{$GiverInfo->piclink}}">
                                @endif                          
                                <h4>{{$GiverInfo->fname.' '.$GiverInfo->lname}}</h4>
                                <div class="borderPic midicondetails">
                                    <ul class="clearfix">
                                        <li><a href="{{$GiverInfo->linkedinurl}}" target="_blank"><img alt="" src="/images/linkdin.png"></a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="clr"></div>
                        </div>
                        {{ Form::open(array('url' => 'SendKCInvitation' , 'method' => '  post')) }}
                        <div class="col-md-11 col-sm-12 col-md-pull-1">
                            <div class="col-md-11 col-sm-12 centralize">
                                <div class="margin-bot20">
                                    <div class="select col-xs-3 pdding0">
                                        <p>Subject</p>
                                    </div>
                                    <div class="select col-xs-9 pdding0">                                
                                    <div class="clr"></div>
                                        {{ Form::text('subject','I would like you to join KarmaCircles',array('required'=>'required','class'=>'form-control')); }}
                                        
                                        {{ Form::hidden('user_id_receiver',$CurrentUser->id,array('class'=>'form-control')); }}
                                        {{ Form::hidden('user_id_giver',$GiverInfo->id,array('class'=>'form-control')); }}
                                    </div>
                                </div>
                                
                                <div style="height:auto" class="margin-bot20 clearfix">
                                    <div class="select col-xs-3 pdding0">
                                        <p>Message</p>
                                    </div>
                                    <div class="select col-xs-9 pdding0">  
                                         
                                    <textarea rows="17" cols="50" name = "notes" required='required' class ='form-control'> Hi {{$GiverInfo->fname}},

I would like to invite you to join a new platform called KarmaCircles (KC). On KC, people give and receive help for free. One can find an expert, request for an online/in-person meeting and then thank them for their help. When you help others, you build your online reputation around various skills. You can see a typical KC profile at http://www.karmacircles.com/deepak.

You can sign up using Linkedin and start requesting meetings from others.

Thanks 
{{$CurrentUser->fname}} 
{{$receiver_vanity_url}}
                                    </textarea>

                                    </div>
                                 </div>
                                <?php if($checkMsgLimit != 1)  
                                    $email_text = "Email"; 
                                else 
                                    $email_text = "Email (Optional)"; 
                                ?>
                                <div class="margin-bot20 ">
                                    <div class="select col-xs-12 pdding0">
                                        <div class="select col-xs-3 pdding0">
                                            <p>{{$email_text}}</p> 
                                        </div>    
                                        <div class="form-group">
                                            <div class="select col-xs-9  pdding0">
                                                @if($checkMsgLimit != 1) 
                                                {{Form::email('giver_email', '', array('required'=>'required','class'=>'form-control','placeholder'=>'Please specify email address of '.$GiverInfo->fname.' '.$GiverInfo->lname))}}
                                                @else
                                                {{Form::email('giver_email', '', array('class'=>'form-control','placeholder'=>'Please specify email address of '.$GiverInfo->fname.' '.$GiverInfo->lname))}}
                                                @endif 
                                            </div>
                                        </div> 
                                    </div>
                                </div>    
                            </div>
                        </div>
                        
                        
                        <div class="clr"></div>
                        <div align="center" class="minBtn">
                            <a href="{{URL::previous()}}"><button type="button" class="btn btn-warning">Cancel</button></a>
                            {{Form::submit('Send invite',array('class'=>'btn btn-success'));}}
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
            <p id="Limitboxmsg">Please specify the email address of {{$GiverInfo->fname.' '.$GiverInfo->lname}} to send this message.</p>
          </div>
          <div class="modal-footer">
           <button data-dismiss="modal" class="btn btn-default linkfullBTN gpBtn pull-right" type="button" onclick="modelClose('LimitBox');">Continue</button>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog --> 
    </div>     
    </section>
    <SCRIPT TYPE="text/javascript">
        <?php if($checkMsgLimit != 1) { ?>
        openboxmodel('LimitBox','');
        <?php } ?>
    </SCRIPT>
    <!-- /Main colom -->
@stop




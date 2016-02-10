@extends('common.master')
@section('content')

<?php $timeSlot = KarmaHelper::halfHourTimes();?>
    <section class="mainWidth"> 
        <div class="col-lg-10 col-md-10 col-sm-12 centralize pdding0">
        <!-- <div class="backlink pull-right clearfix">
            <a href="/dashboard">Back to Karma Circle</a>
        </div> -->
            <div class="col-md-11 col-sm-12 centralize clearfix pull-left no-pull-md">
                <div class="registrFrm sendnotes col-md-12 clearfix">
                    <!-- Form start -->
                    {{ Form::open(array('url' => 'saveDirectKarmaNote' , 'method' => '  post','onsubmit'=>'return validateKCnote();')) }}
                    <div class="sendnoteBox">
                        <div class="col-xs-4 pdding0 borderPic">
                          @if ($CurrentUser->piclink == '')
                           <img alt="" src="/images/default.png">
                          @else
                          <img alt="{{$CurrentUser->fname}}" src="{{$CurrentUser->piclink}}" title="{{$CurrentUser->fname}}">
                          @endif                            
                          <h4>{{$CurrentUser->fname}}</h4>
                          <div class="borderPic midicondetails">
                            <ul class="clearfix">
                              <li>
                                <a target="_blank" href="{{$CurrentUser->linkedinurl}}"><img alt="" src="/images/linkdin.png"></a>
                              </li>
                              <li>
                                <a href="/profile/<?php echo strtolower($CurrentUser->fname.'-'.$CurrentUser->lname).'/'.$CurrentUser->id;?>"><img alt="" src="/images/krmaicon.png"></a>
                              <a href="<?php echo URL::to('/');?>/FAQs/KarmaPoints/1"><span>{{$CurrentUser->karmascore}}</span></a>
                              </li>
                            </ul>
                          </div>
                          {{$CurrentUser->email}}
                        </div>
                        <div class="col-xs-4 thumbs">
                          <img alt="" src="/images/thumbsUp.png">
                        </div>
                        <div class="col-xs-4 pdding0 borderPic">
                        @if (!empty($ConnectionDetail))
                           @if ($ConnectionDetail->piclink == '')
                             <img alt="" src="/images/default.png">
                            @else
                             <img src="{{$ConnectionDetail->piclink}}" alt="{{$ConnectionDetail->fname}}" title="$ConnectionDetail->fname">
                            @endif   
                            <h4>{{$ConnectionDetail->fname}}</h4>
                        <div class="borderPic midicondetails">
                            <ul class="clearfix">
                                <li><a target="_blank" href="{{$ConnectionDetail->linkedinurl}}"><img alt="" src="/images/linkdin.png"></a></li>
                            </ul>
                        </div>
                        @elseif (!empty($UserDetail))                           
                            @if ($UserDetail['piclink'] == '')
                             <img alt="" src="/images/default.png">
                            @else
                             <img src="{{$UserDetail['piclink']}}" alt="{{$UserDetail['fname']}}" title="$UserDetail['fname']">
                            @endif   
                           <h4>{{$UserDetail['fname']}}</h4>
                           <div class="borderPic midicondetails">
                            <ul class="clearfix">
                                <li><a target="_blank" href="{{$UserDetail['linkedinurl']}}"><img alt="" src="/images/linkdin.png"></a></li>
                                <li>
                                    <a href="/profile/<?php echo strtolower($UserDetail['fname'].'-'.$UserDetail['lname']).'/'.$UserDetail['id'];?>"><img alt="" src="/images/krmaicon.png"></a>
                                    <a href="<?php echo URL::to('/');?>/FAQs/KarmaPoints/1"><span>{{$UserDetail['karmascore']}}</span></a>
                                </li>
                            </ul>
                        </div>
                        @endif    
                        </div>

                         <div class="clr"></div>
                           <br>
                        <div class="col-sm-12 centralize pull-left">

                            @if(!empty($Skills))  
                                @if (!empty($ConnectionDetail))
                                    <p class="centertxt">You can endorse {{$ConnectionDetail->fname}}  for up to three skills.</p>
                                @elseif(!empty($UserDetail))
                                    <p class="centertxt">You can endorse {{$UserDetail['fname']}} for up to three skills.</p>
                                @endif
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
                            <input type="hidden" class = "skillscount" id="skillscount" value = "{{count($Skills)}}" name="skillscount">
                        </div>
                        <div class="clr"></div>
                        <hr/ class="darkLine">
                        <h2>When did you meet ?</h2>
                        <p class="centertxt">If you do’nt remember the exact day, please pick an  approximate day & time. </p>
                        <div class="col-md-11 col-sm-12 centralize">
                            <div class="margin-bot20 ">
                                <div class="select col-xs-12 pdding0">
								
                                <div class="select col-sm-3 col-sm-12 pdding0">
                                    <p >Date</p>
                                </div>    
                                    <div class="form-group">
                                        <div class='input-group date' id='datetimepicker5'>
                                            <input required='required' name='meetingdate' type='text' class="form-control" data-date-format="YYYY-MM-DD"/>
                                            <span class="input-group-addon">
                                                <span class="glyphicon glyphicon-calendar"></span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>      
                            {{Form::hidden('meetingtimezone', '-8', array('id'=>'meetingtimezone','class'=>'form-control'))}}
                             {{Form::hidden('meetingtime', '09:00', array('class'=>'form-control'))}}
                          
                            <?php /*
                            <div class="margin-bot20">
                                <div class="select col-sm-3 col-xs-12  pdding0">
                                   <!--  <span class="glyphicon glyphicon-time fullIcon"></span> -->
                                   <p >Time</p>
                                </div>
                                <div class="select col-xs-12 col-sm-9 pdding0">
                                <span class="pointer"></span>
                                    <!--<span class="pointer"></span>
                                    <div class='input-group date' id='datetimepicker4'>
                                       <input name='meetingtime'  type='text' class="form-control" required='required'/>
                                         <span class="input-group-addon"><span class="glyphicon glyphicon-time"></span> -->
                                        <select name="meetingtime" class="form-control" required='required'>
                                          @foreach ($timeSlot as $slots)
                                            <option>{{$slots}}</option>
                                          @endforeach
                                        </select>
                                    </div>
                                </div> 
                            
                              
                           <div class="margin-bot20">  
                                <div class="select col-sm-3 col-xs-12 pdding0">
                                    <p>Time Zone</p>
                                </div>
                                 <div class="select col-sm-9 col-xs-12  pdding0">
                                <span class="pointer"></span>
                                    <select name='meetingtimezone' class="form-control" id='meetingtimezone'>
                                        <option timeZoneId="1" gmtAdjustment="GMT-12:00" useDaylightTime="0" value="-12">International Date Line West (GMT-12:00) </option>
                                        <option timeZoneId="2" gmtAdjustment="GMT-11:00" useDaylightTime="0" value="-11"> Midway Island, Samoa (GMT-11:00)</option>
                                        <option timeZoneId="3" gmtAdjustment="GMT-10:00" useDaylightTime="0" value="-10"> Hawaii(GMT-10:00)</option>
                                        <option timeZoneId="4" gmtAdjustment="GMT-09:00" useDaylightTime="1" value="-9">Alaska (GMT-09:00) </option>
                                        <option timeZoneId="5" gmtAdjustment="GMT-08:00" useDaylightTime="1" value="-8"> Pacific Time (US & Canada) (GMT-08:00)</option>
                                        <option timeZoneId="6" gmtAdjustment="GMT-08:00" useDaylightTime="1" value="-8">Tijuana, Baja California (GMT-08:00) </option>
                                        <option timeZoneId="7" gmtAdjustment="GMT-07:00" useDaylightTime="0" value="-7">Arizona (GMT-07:00) </option>
                                        <option timeZoneId="8" gmtAdjustment="GMT-07:00" useDaylightTime="1" value="-7">Chihuahua, La Paz, Mazatlan (GMT-07:00) </option>
                                        <option timeZoneId="9" gmtAdjustment="GMT-07:00" useDaylightTime="1" value="-7"> Mountain Time (US & Canada) (GMT-07:00)</option>
                                        <option timeZoneId="10" gmtAdjustment="GMT-06:00" useDaylightTime="0" value="-6"> Central America (GMT-06:00)</option>
                                        <option timeZoneId="11" gmtAdjustment="GMT-06:00" useDaylightTime="1" value="-6"> Central Time (US & Canada) (GMT-06:00)</option>
                                        <option timeZoneId="12" gmtAdjustment="GMT-06:00" useDaylightTime="1" value="-6"> Guadalajara, Mexico City, Monterrey (GMT-06:00)</option>
                                        <option timeZoneId="13" gmtAdjustment="GMT-06:00" useDaylightTime="0" value="-6"> Saskatchewan (GMT-06:00)</option>
                                        <option timeZoneId="14" gmtAdjustment="GMT-05:00" useDaylightTime="0" value="-5"> Bogota, Lima, Quito, Rio Branco (GMT-05:00)</option>
                                        <option timeZoneId="15" gmtAdjustment="GMT-05:00" useDaylightTime="1" value="-5"> Eastern Time (US & Canada) (GMT-05:00)</option>
                                        <option timeZoneId="16" gmtAdjustment="GMT-05:00" useDaylightTime="1" value="-5"> Indiana (East) (GMT-05:00)</option>
                                        <option timeZoneId="17" gmtAdjustment="GMT-04:00" useDaylightTime="1" value="-4"> Atlantic Time (Canada) (GMT-04:00)</option>
                                        <option timeZoneId="18" gmtAdjustment="GMT-04:00" useDaylightTime="0" value="-4"> Caracas, La Paz (GMT-04:00)</option>
                                        <option timeZoneId="19" gmtAdjustment="GMT-04:00" useDaylightTime="0" value="-4"> Manaus (GMT-04:00) </option>
                                        <option timeZoneId="20" gmtAdjustment="GMT-04:00" useDaylightTime="1" value="-4"> Santiago (GMT-04:00)</option>
                                        <option timeZoneId="21" gmtAdjustment="GMT-03:30" useDaylightTime="1" value="-3.5"> Newfoundland (GMT-03:30)</option>
                                        <option timeZoneId="22" gmtAdjustment="GMT-03:00" useDaylightTime="1" value="-3"> Brasilia(GMT-03:00)</option>
                                        <option timeZoneId="23" gmtAdjustment="GMT-03:00" useDaylightTime="0" value="-3">Buenos Aires, Georgetown (GMT-03:00) </option>
                                        <option timeZoneId="24" gmtAdjustment="GMT-03:00" useDaylightTime="1" value="-3"> Greenland (GMT-03:00)</option>
                                        <option timeZoneId="25" gmtAdjustment="GMT-03:00" useDaylightTime="1" value="-3">Montevideo (GMT-03:00) </option>
                                        <option timeZoneId="26" gmtAdjustment="GMT-02:00" useDaylightTime="1" value="-2"> Mid-Atlantic (GMT-02:00)</option>
                                        <option timeZoneId="27" gmtAdjustment="GMT-01:00" useDaylightTime="0" value="-1"> Cape Verde Is. (GMT-01:00)</option>
                                        <option timeZoneId="28" gmtAdjustment="GMT-01:00" useDaylightTime="1" value="-1"> Azores(GMT-01:00)</option>
                                        <option timeZoneId="29" gmtAdjustment="GMT+00:00" useDaylightTime="0" value="0"> Casablanca, Monrovia, Reykjavik (GMT+00:00)</option>
                                        <option timeZoneId="30" gmtAdjustment="GMT+00:00" useDaylightTime="1" value="0"> Greenwich Mean Time : Dublin, Edinburgh, Lisbon, London (GMT+00:00)</option>
                                        <option timeZoneId="31" gmtAdjustment="GMT+01:00" useDaylightTime="1" value="1">Amsterdam, Berlin, Bern, Rome, Stockholm, Vienna (GMT+01:00) </option>
                                        <option timeZoneId="32" gmtAdjustment="GMT+01:00" useDaylightTime="1" value="1"> Belgrade, Bratislava, Budapest, Ljubljana, Prague (GMT+01:00)</option>
                                        <option timeZoneId="33" gmtAdjustment="GMT+01:00" useDaylightTime="1" value="1"> Brussels, Copenhagen, Madrid, Paris (GMT+01:00)</option>
                                        <option timeZoneId="34" gmtAdjustment="GMT+01:00" useDaylightTime="1" value="1">Sarajevo, Skopje, Warsaw, Zagreb (GMT+01:00) </option>
                                        <option timeZoneId="35" gmtAdjustment="GMT+01:00" useDaylightTime="1" value="1">West Central Africa(GMT+01:00) </option>
                                        <option timeZoneId="36" gmtAdjustment="GMT+02:00" useDaylightTime="1" value="2">Amman(GMT+02:00) </option>
                                        <option timeZoneId="37" gmtAdjustment="GMT+02:00" useDaylightTime="1" value="2"> Athens, Bucharest, Istanbul(GMT+02:00)</option>
                                        <option timeZoneId="38" gmtAdjustment="GMT+02:00" useDaylightTime="1" value="2"> Beirut (GMT+02:00)</option>
                                        <option timeZoneId="39" gmtAdjustment="GMT+02:00" useDaylightTime="1" value="2"> Cairo (GMT+02:00)</option>
                                        <option timeZoneId="40" gmtAdjustment="GMT+02:00" useDaylightTime="0" value="2"> Harare, Pretoria (GMT+02:00)</option>
                                        <option timeZoneId="41" gmtAdjustment="GMT+02:00" useDaylightTime="1" value="2"> Helsinki, Kyiv, Riga, Sofia, Tallinn, Vilnius (GMT+02:00)</option>
                                        <option timeZoneId="42" gmtAdjustment="GMT+02:00" useDaylightTime="1" value="2"> Jerusalem (GMT+02:00)</option>
                                        <option timeZoneId="43" gmtAdjustment="GMT+02:00" useDaylightTime="1" value="2"> Minsk (GMT+02:00)</option>
                                        <option timeZoneId="44" gmtAdjustment="GMT+02:00" useDaylightTime="1" value="2"> Windhoek (GMT+02:00)</option>
                                        <option timeZoneId="45" gmtAdjustment="GMT+03:00" useDaylightTime="0" value="3"> Kuwait, Riyadh, Baghdad (GMT+03:00)</option>
                                        <option timeZoneId="46" gmtAdjustment="GMT+03:00" useDaylightTime="1" value="3"> Moscow, St. Petersburg, Volgograd (GMT+03:00)</option>
                                        <option timeZoneId="47" gmtAdjustment="GMT+03:00" useDaylightTime="0" value="3"> Nairobi (GMT+03:00)</option>
                                        <option timeZoneId="48" gmtAdjustment="GMT+03:00" useDaylightTime="0" value="3"> Tbilisi (GMT+03:00)</option>
                                        <option timeZoneId="49" gmtAdjustment="GMT+03:30" useDaylightTime="1" value="3.5"> Tehran (GMT+03:30)</option>
                                        <option timeZoneId="50" gmtAdjustment="GMT+04:00" useDaylightTime="0" value="4"> Abu Dhabi, Muscat (GMT+04:00)</option>
                                        <option timeZoneId="51" gmtAdjustment="GMT+04:00" useDaylightTime="1" value="4"> Baku (GMT+04:00)</option>
                                        <option timeZoneId="52" gmtAdjustment="GMT+04:00" useDaylightTime="1" value="4"> Yerevan (GMT+04:00)</option>
                                        <option timeZoneId="53" gmtAdjustment="GMT+04:30" useDaylightTime="0" value="4.5"> Kabul (GMT+04:30)</option>
                                        <option timeZoneId="54" gmtAdjustment="GMT+05:00" useDaylightTime="1" value="5"> Yekaterinburg (GMT+05:00)</option>
                                        <option timeZoneId="55" gmtAdjustment="GMT+05:00" useDaylightTime="0" value="5"> Islamabad, Karachi, Tashkent (GMT+05:00)</option>
                                        <option timeZoneId="56" gmtAdjustment="GMT+05:30" useDaylightTime="0" value="5.5"> Sri Jayawardenapura (GMT+05:30)</option>
                                        <option timeZoneId="57" gmtAdjustment="GMT+05:30" useDaylightTime="0" value="5.5"> Chennai, Kolkata, Mumbai, New Delhi (GMT+05:30)</option>
                                        <option timeZoneId="58" gmtAdjustment="GMT+05:45" useDaylightTime="0" value="5.75"> Kathmandu (GMT+05:45)</option>
                                        <option timeZoneId="59" gmtAdjustment="GMT+06:00" useDaylightTime="1" value="6"> Almaty, Novosibirsk (GMT+06:00)</option>
                                        <option timeZoneId="60" gmtAdjustment="GMT+06:00" useDaylightTime="0" value="6"> Astana, Dhaka (GMT+06:00)</option>
                                        <option timeZoneId="61" gmtAdjustment="GMT+06:30" useDaylightTime="0" value="6.5">Yangon (Rangoon) (GMT+06:30) </option>
                                        <option timeZoneId="62" gmtAdjustment="GMT+07:00" useDaylightTime="0" value="7"> Bangkok, Hanoi, Jakarta (GMT+07:00)</option>
                                        <option timeZoneId="63" gmtAdjustment="GMT+07:00" useDaylightTime="1" value="7"> Krasnoyarsk (GMT+07:00)</option>
                                        <option timeZoneId="64" gmtAdjustment="GMT+08:00" useDaylightTime="0" value="8"> Beijing, Chongqing, Hong Kong, Urumqi (GMT+08:00)</option>
                                        <option timeZoneId="65" gmtAdjustment="GMT+08:00" useDaylightTime="0" value="8">Kuala Lumpur, Singapore (GMT+08:00) </option>
                                        <option timeZoneId="66" gmtAdjustment="GMT+08:00" useDaylightTime="0" value="8"> Irkutsk, Ulaan Bataar (GMT+08:00)</option>
                                        <option timeZoneId="67" gmtAdjustment="GMT+08:00" useDaylightTime="0" value="8"> Perth (GMT+08:00)</option>
                                        <option timeZoneId="68" gmtAdjustment="GMT+08:00" useDaylightTime="0" value="8"> Taipei (GMT+08:00)</option>
                                        <option timeZoneId="69" gmtAdjustment="GMT+09:00" useDaylightTime="0" value="9"> Osaka, Sapporo, Tokyo (GMT+09:00)</option>
                                        <option timeZoneId="70" gmtAdjustment="GMT+09:00" useDaylightTime="0" value="9"> Seoul (GMT+09:00)</option>
                                        <option timeZoneId="71" gmtAdjustment="GMT+09:00" useDaylightTime="1" value="9"> Yakutsk (GMT+09:00)</option>
                                        <option timeZoneId="72" gmtAdjustment="GMT+09:30" useDaylightTime="0" value="9.5"> Adelaide (GMT+09:30)</option>
                                        <option timeZoneId="73" gmtAdjustment="GMT+09:30" useDaylightTime="0" value="9.5"> Darwin (GMT+09:30)</option>
                                        <option timeZoneId="74" gmtAdjustment="GMT+10:00" useDaylightTime="0" value="10"> Brisbane (GMT+10:00)</option>
                                        <option timeZoneId="75" gmtAdjustment="GMT+10:00" useDaylightTime="1" value="10"> Canberra, Melbourne, Sydney (GMT+10:00)</option>
                                        <option timeZoneId="76" gmtAdjustment="GMT+10:00" useDaylightTime="1" value="10"> Hobart (GMT+10:00)</option>
                                        <option timeZoneId="77" gmtAdjustment="GMT+10:00" useDaylightTime="0" value="10"> Guam, Port Moresby (GMT+10:00)</option>
                                        <option timeZoneId="78" gmtAdjustment="GMT+10:00" useDaylightTime="1" value="10"> Vladivostok (GMT+10:00)</option>
                                        <option timeZoneId="79" gmtAdjustment="GMT+11:00" useDaylightTime="1" value="11"> Magadan, Solomon Is., New Caledonia (GMT+11:00)</option>
                                        <option timeZoneId="80" gmtAdjustment="GMT+12:00" useDaylightTime="1" value="12"> Auckland, Wellington (GMT+12:00)</option>
                                        <option timeZoneId="81" gmtAdjustment="GMT+12:00" useDaylightTime="0" value="12"> Fiji, Kamchatka, Marshall Is. (GMT+12:00)</option>
                                        <option timeZoneId="82" gmtAdjustment="GMT+13:00" useDaylightTime="0" value="13"> Nuku'alofa (GMT+13:00)</option>
                                    </select>
                                </div>
                            </div>  
                            */ ?> 
							 
							
                                <?php  
                                 if($userType == 'NoKarma')
                                    $email_text = "Email"; 
                                
                                     
                                ?>

								<div class="margin-bot20 ">
									<div class="select col-xs-12 pdding0">
									
									<div class="select col-sm-3 col-sm-12 pdding0">
                                        @if($userType == 'NoKarma')
										<p>{{$email_text}}</p>
                                        @endif 
									</div>    
										<div class="form-group">
											<?php 
                                            $user_name =" ";
                                            if(!empty($ConnectionDetail))
                                            $user_name = $ConnectionDetail->fname.' '.$ConnectionDetail->lname;
                                            else
                                            $user_name = $UserDetail['fname'].' '.$UserDetail['lname'];
                                            ?>
                                            <div class="select col-xs-9  pdding0">
                                                 @if($userType == 'NoKarma')
                                                {{Form::email('giver_email', '', array('required'=>'required','class'=>'form-control','placeholder'=>'Please specify email address of '.$user_name))}}
                                                @endif
                                            </div>
										</div>
									</div>
								</div>    
						
                            </div>   
                             <div class="col-xs-12 notetxt">
                        <h2>Send KarmaNote </h2> 
                            <!-- <textarea class="form-control" rows="10"></textarea> -->
                            {{ Form::textarea('details','',array('class'=>'form-control','rows'=>'10','id'=>'karmanotedetail')); }}
                            <span class='errordetail'></span>
                            {{$errors->first('details','<span class=error>:message</span>')}}
                        </div>
                        </div>
                       
                    </div>
                    <div align="center">
                        {{Form::checkbox('ShareKarmaNote[]',"1",true)}}
                        <label>Share this Karmanote on Linkedin</label>
                    </div>
                   <div class="clr"></div>

                    <div align="center">
                        {{ Form::hidden('user_id_receiver',$CurrentUser->id, array('class'=>'form-control')); }}
                        @if (!empty($ConnectionDetail))
                            {{ Form::hidden('connection_id_giver', $ConnectionDetail->id, array('class'=>'form-control')); }}
                        @endif
                        @if (!empty($UserDetail))
                            {{ Form::hidden('user_id_giver', $UserDetail['id'], array('class'=>'form-control')); }}
                        @endif
                        
                         <a href="{{URL::previous()}}">{{Form::button('Cancel',array('class'=>'btn btn-warning'));}} </a>   
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
        <?php if($checkMsgLimit != 1) { ?>
        openboxmodel('LimitBox','');
        <?php } ?>
        function get_time_zone_offset() {
            var current_date = new Date();
            return -current_date.getTimezoneOffset() / 60;
        }
        var timze = get_time_zone_offset();
        // var timer = timze.split("GMT "); 
        //alert(timze);
        $("#meetingtimezone").val( timze );  
        var timezone = $('#meetingtimezone option:selected').text();
        
        $(function () {
            $('#datetimepicker5').datetimepicker({
                maxDate:new Date(),
                pickTime: false
            });
              $('#datetimepicker4').datetimepicker({
                pickDate: false
            });
        });

        
    </script>  
@stop

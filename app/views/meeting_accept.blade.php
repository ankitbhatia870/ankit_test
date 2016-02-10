@extends('common.master')
@section('content') 
<?php 
$receiverName=strtolower($receiverDetail->fname.'-'.$receiverDetail->lname);
$giverName=strtolower($giverDetail->fname.'-'.$giverDetail->lname);

$timeSlot = KarmaHelper::halfHourTimes();?>
    <section class="mainWidth">
        <div class="col-lg-10 col-md-10 col-sm-12 centralize pdding0">
        <!-- <div class="backlink pull-right clearfix">
            <a href="">Back to Karma Circle</a>
        </div> -->
            <div class="col-md-11 col-sm-12 centralize clearfix pull-left no-pull-md">
                <div class="registrFrm sendnotes col-md-12">
                    <div class="sendnoteBox">
                        <div class="col-xs-4 pdding0 borderPic">
                        @if ($receiverDetail->piclink == '')
                             <a href="<?php echo '/profile/'.strtolower($receiverDetail->fname.'-'.$receiverDetail->lname).'/'.$receiverDetail->id ;?>"><img alt="" src="/images/default.png"></a>
                        @else
                            <a href="<?php echo '/profile/'.strtolower($receiverDetail->fname.'-'.$receiverDetail->lname).'/'.$receiverDetail->id ;?>"><img alt="" src="{{ $receiverDetail->piclink;}}"></a>
                        @endif
                         
                          <a href="<?php echo '/profile/'.strtolower($receiverDetail->fname.'-'.$receiverDetail->lname).'/'.$receiverDetail->id ;?>"><h4>{{$receiverDetail->fname." ".$receiverDetail->lname}}</h4></a>
                          <div class="borderPic midicondetails">
                                <ul class="clearfix">
                                    <li><a target="_blank" href="{{$receiverDetail->linkedinurl}}" ><img alt="" src="/images/linkdin.png"></a></li>
                                    <li>
                                        <a href="<?php echo '/profile/'.strtolower($receiverDetail->fname.'-'.$receiverDetail->lname).'/'.$receiverDetail->id ;?>"><img alt="" src="/images/krmaicon.png"></a>
                                       <a href="<?php echo URL::to('/');?>/FAQs/KarmaPoints/1"> <span>{{$receiverDetail->karmascore}}</span></a>

                                    </li>

                                </ul>

                            </div>
                          <p>{{$receiverDetail->email}}</p>
                        </div>
                        <div class="col-xs-4 thumbs">
                          <img alt="" src="/images/handshak.png">
                        </div>
                        <div class="col-xs-4 pdding0 borderPic">
                            @if ($giverDetail->piclink == "" || $giverDetail->piclink == 'null')
                             <a href="<?php echo '/profile/'.strtolower($giverDetail->fname.'-'.$giverDetail->lname).'/'.$giverDetail->id ;?>"><img alt="" src="/images/default.png"></a>
                            @else
                               <a href="<?php echo '/profile/'.strtolower($giverDetail->fname.'-'.$giverDetail->lname).'/'.$giverDetail->id ;?>"> <img alt="" src="{{ $giverDetail->piclink;}}"></a>
                            @endif
                         
                          <a href="<?php echo '/profile/'.strtolower($giverDetail->fname.'-'.$giverDetail->lname).'/'.$giverDetail->id ;?>"><h4>{{$giverDetail->fname." ".$giverDetail->lname}}</h4></a>
                           <div class="borderPic midicondetails">
                                <ul class="clearfix">
                                    <li><a target="_blank" href="{{$giverDetail->linkedinurl}}" ><img alt="" src="/images/linkdin.png"></a></li>
                                    <li>
                                        <a href="<?php echo '/profile/'.strtolower($giverDetail->fname.'-'.$giverDetail->lname).'/'.$giverDetail->id ;?>"><img alt="" src="/images/krmaicon.png"></a>
                                        <a href="<?php echo URL::to('/');?>/FAQs/KarmaPoints/1"><span>{{$giverDetail->karmascore}}</span></a>
                                    </li>
                                </ul>
                            </div>
                          <p>{{$giverDetail->email}}</p>
                        </div>
                        {{ Form::open(array('url' => 'acceptMeetingRequest' , 'method' => '  post')) }}
                        {{ Form::hidden('meetingId',$meetingDetail->id,array('class'=>'form-control')); }}
                        {{ Form::hidden('giverName',$giverName,array('class'=>'form-control')); }}
                        {{ Form::hidden('receiverName',$receiverName,array('class'=>'form-control')); }}
                        
                        <div class="clr"></div>
                        <div class="col-md-11 col-sm-12 centralize meetingFrm">
                            <div class="margin-bot20">
                                <div class="select col-xs-3 pdding0">
                                    <p>Duration</p>
                                </div>
                                 <div class="select col-xs-4 pdding0">
                                <span class="pointer"></span>
                                 {{ Form::select('meetingduration', array('15 Minutes' => '15 Minutes','30 Minutes' => '30 Minutes','45 Minutes' => '45 Minutes','1 Hour' => '1 Hour'),'15 Minutes',array('class'=>'form-control')); }}
                                </div>
                            </div>
                            <div class="margin-bot20">
                                <div class="select col-xs-3 pdding0">
                                    <span class="glyphicon glyphicon-calendar fullIcon"></span>
                                </div>
                                
                                <div class="select col-xs-4 pdding0">                                   
                                    <div class="form-group">
                                        <div class='input-group date' id='datetimepicker5'>
                                            <input required='required' name='meetingdate' type='text' class="form-control" data-date-format="YYYY-MM-DD"/>
                                            <span class="input-group-addon">
                                                <span class="glyphicon glyphicon-calendar"></span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                           <!--  <div class="input-group date" id="dp3"  data-date-format="mm-dd-yyyy">
                              <input  style="background-color:#ffffff" class="form-control" type="text" readonly="" value="Date()">
                              <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                            </div> -->
                            </div>      
                            <div class="margin-bot20 ">
                                <div class="select col-xs-3 pdding0">
                                    <span class="glyphicon glyphicon-time fullIcon"></span>
                                </div>
                                 <div class="select col-xs-4 pdding0">
                                    <!-- <span class="pointer"></span>
                                    <div class='input-group date' id='datetimepicker4'>
                                        <input name='meetingtime'  type='text' class="form-control" required='required'/>
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-time"></span>
                                        </span>
                                    </div> -->
                                     <span class="pointer"></span>
                                    <select name="meetingtime" id="meetingtime" class="form-control" required='required'>
                                          @foreach ($timeSlot as $slots)
                                            <option>{{$slots}}</option>
                                          @endforeach
                                    </select>
                                </div>
                            </div>    
                            <div class="margin-bot20">
                                <div class="select col-xs-3 pdding0">
                                    <p>Time Zone</p>
                                </div>
                                 <div class="select col-xs-9 pdding0">
                                <span class="pointer"></span>
                                    <select name='meetingtimezone' id='meetingtimezone' class="form-control">
                                    <option timeZoneId="1" gmtAdjustment="GMT-12:00" useDaylightTime="0" value="-12">International Date Line West</option>
                                    <option timeZoneId="2" gmtAdjustment="GMT-11:00" useDaylightTime="0" value="-11"> Midway Island, Samoa</option>
                                    <option timeZoneId="3" gmtAdjustment="GMT-10:00" useDaylightTime="0" value="-10"> Hawaii(GMT-10:00)</option>
                                    <option timeZoneId="4" gmtAdjustment="GMT-09:00" useDaylightTime="1" value="-9">Alaska (GMT-09:00) </option>
                                    <option timeZoneId="5" gmtAdjustment="GMT-08:00" useDaylightTime="1" value="-8"> Pacific Time (US & Canada)</option>  
                                   {{--  <option timeZoneId="6" gmtAdjustment="GMT-08:00" useDaylightTime="1" value="-8">Tijuana, Baja California</option> --}}
                                   {{--  <option timeZoneId="7" gmtAdjustment="GMT-07:00" useDaylightTime="0" value="-7">Arizona (GMT-07:00) </option> --}}
                                {{--     <option timeZoneId="8" gmtAdjustment="GMT-07:00" useDaylightTime="1" value="-7">Chihuahua, La Paz, Mazatlan</option> --}}
                                    <option timeZoneId="9" gmtAdjustment="GMT-07:00" useDaylightTime="1" value="-7"> Mountain Time (US & Canada)</option>
                                   {{--  <option timeZoneId="10" gmtAdjustment="GMT-06:00" useDaylightTime="0" value="-6"> Central America</option> --}}
                                    <option timeZoneId="11" gmtAdjustment="GMT-06:00" useDaylightTime="1" value="-6"> Central Time (US & Canada)</option>
                                   {{--  <option timeZoneId="12" gmtAdjustment="GMT-06:00" useDaylightTime="1" value="-6"> Guadalajara, Mexico City, Monterrey</option> --}}
                                    {{-- <option timeZoneId="13" gmtAdjustment="GMT-06:00" useDaylightTime="0" value="-6"> Saskatchewan</option> --}}
                                  {{--   <option timeZoneId="14" gmtAdjustment="GMT-05:00" useDaylightTime="0" value="-5"> Bogota, Lima, Quito, Rio Branco </option> --}}
                                    <option timeZoneId="15" gmtAdjustment="GMT-05:00" useDaylightTime="1" value="-5"> Eastern Time (US & Canada)</option>
                                    <option timeZoneId="16" gmtAdjustment="GMT-05:00" useDaylightTime="1" value="-5"> Indiana (East)</option>
                                    <option timeZoneId="17" gmtAdjustment="GMT-04:00" useDaylightTime="1" value="-4"> Atlantic Time (Canada)</option>
                                    {{-- <option timeZoneId="18" gmtAdjustment="GMT-04:00" useDaylightTime="0" value="-4"> Caracas, La Paz</option> --}}
                                    <option timeZoneId="19" gmtAdjustment="GMT-04:00" useDaylightTime="0" value="-4"> Manaus </option>
                                    <option timeZoneId="20" gmtAdjustment="GMT-04:00" useDaylightTime="1" value="-4"> Santiago </option>
                                    <option timeZoneId="21" gmtAdjustment="GMT-03:30" useDaylightTime="1" value="-3.5"> Newfoundland</option>
                                    <option timeZoneId="22" gmtAdjustment="GMT-03:00" useDaylightTime="1" value="-3"> Brasilia</option>
                                    <option timeZoneId="23" gmtAdjustment="GMT-03:00" useDaylightTime="0" value="-3">Buenos Aires, Georgetown </option>
                                    <option timeZoneId="24" gmtAdjustment="GMT-03:00" useDaylightTime="1" value="-3"> Greenland</option>
                                    <option timeZoneId="25" gmtAdjustment="GMT-03:00" useDaylightTime="1" value="-3">Montevideo</option>
                                    <option timeZoneId="26" gmtAdjustment="GMT-02:00" useDaylightTime="1" value="-2"> Mid-Atlantic</option>
                                    <option timeZoneId="27" gmtAdjustment="GMT-01:00" useDaylightTime="0" value="-1"> Cape Verde Is</option>
                                    <option timeZoneId="28" gmtAdjustment="GMT-01:00" useDaylightTime="1" value="-1"> Azores</option>
                                    <option timeZoneId="29" gmtAdjustment="GMT+00:00" useDaylightTime="0" value="0"> Casablanca, Monrovia, Reykjavik</option>
                                    <option timeZoneId="30" gmtAdjustment="GMT+00:00" useDaylightTime="1" value="0"> Greenwich Mean Time : Dublin, Edinburgh, Lisbon, London</option>
                                    <option timeZoneId="31" gmtAdjustment="GMT+01:00" useDaylightTime="1" value="1">Amsterdam, Berlin, Bern, Rome, Stockholm, Vienna</option>
                                    <option timeZoneId="32" gmtAdjustment="GMT+01:00" useDaylightTime="1" value="1"> Belgrade, Bratislava, Budapest, Ljubljana, Prague</option>
                                    <option timeZoneId="33" gmtAdjustment="GMT+01:00" useDaylightTime="1" value="1"> Brussels, Copenhagen, Madrid, Paris</option>
                                    <option timeZoneId="34" gmtAdjustment="GMT+01:00" useDaylightTime="1" value="1">Sarajevo, Skopje, Warsaw, Zagreb </option>
                                    <option timeZoneId="35" gmtAdjustment="GMT+01:00" useDaylightTime="1" value="1">West Central Africa</option>
                                    <option timeZoneId="36" gmtAdjustment="GMT+02:00" useDaylightTime="1" value="2">Amman </option>
                                    <option timeZoneId="37" gmtAdjustment="GMT+02:00" useDaylightTime="1" value="2"> Athens, Bucharest, Istanbul</option>
                                    <option timeZoneId="38" gmtAdjustment="GMT+02:00" useDaylightTime="1" value="2"> Beirut </option>
                                    <option timeZoneId="39" gmtAdjustment="GMT+02:00" useDaylightTime="1" value="2"> Cairo</option>
                                    <option timeZoneId="40" gmtAdjustment="GMT+02:00" useDaylightTime="0" value="2"> Harare, Pretoria</option>
                                    <option timeZoneId="41" gmtAdjustment="GMT+02:00" useDaylightTime="1" value="2"> Helsinki, Kyiv, Riga, Sofia, Tallinn, Vilnius</option>
                                    <option timeZoneId="42" gmtAdjustment="GMT+02:00" useDaylightTime="1" value="2"> Jerusalem</option>
                                    <option timeZoneId="43" gmtAdjustment="GMT+02:00" useDaylightTime="1" value="2"> Minsk</option>
                                    <option timeZoneId="44" gmtAdjustment="GMT+02:00" useDaylightTime="1" value="2"> Windhoek</option>
                                    <option timeZoneId="45" gmtAdjustment="GMT+03:00" useDaylightTime="0" value="3"> Kuwait, Riyadh, Baghdad</option>
                                    <option timeZoneId="46" gmtAdjustment="GMT+03:00" useDaylightTime="1" value="3"> Moscow, St. Petersburg, Volgograd</option>
                                    <option timeZoneId="47" gmtAdjustment="GMT+03:00" useDaylightTime="0" value="3"> Nairobi</option>
                                    <option timeZoneId="48" gmtAdjustment="GMT+03:00" useDaylightTime="0" value="3"> Tbilisi </option>
                                    <option timeZoneId="49" gmtAdjustment="GMT+03:30" useDaylightTime="1" value="3.5"> Tehran</option>
                                    <option timeZoneId="50" gmtAdjustment="GMT+04:00" useDaylightTime="0" value="4"> Abu Dhabi, Muscat</option>
                                    <option timeZoneId="51" gmtAdjustment="GMT+04:00" useDaylightTime="1" value="4"> Baku</option>
                                    <option timeZoneId="52" gmtAdjustment="GMT+04:00" useDaylightTime="1" value="4"> Yerevan </option>
                                    <option timeZoneId="53" gmtAdjustment="GMT+04:30" useDaylightTime="0" value="4.5"> Kabul</option>
                                    <option timeZoneId="54" gmtAdjustment="GMT+05:00" useDaylightTime="1" value="5"> Yekaterinburg</option>
                                    <option timeZoneId="55" gmtAdjustment="GMT+05:00" useDaylightTime="0" value="5"> Islamabad, Karachi, Tashkent</option>
                                    <option timeZoneId="56" gmtAdjustment="GMT+05:30" useDaylightTime="0" value="5.6"> Sri Jayawardenapura</option>
                                    <option timeZoneId="57" gmtAdjustment="GMT+05:30" useDaylightTime="0" value="5.5"> Chennai, Kolkata, Mumbai, New Delhi</option>
                                    <option timeZoneId="58" gmtAdjustment="GMT+05:45" useDaylightTime="0" value="5.75"> Kathmandu </option>
                                    <option timeZoneId="59" gmtAdjustment="GMT+06:00" useDaylightTime="1" value="6"> Almaty, Novosibirsk </option>
                                    <option timeZoneId="60" gmtAdjustment="GMT+06:00" useDaylightTime="0" value="6"> Astana, Dhaka</option>
                                    <option timeZoneId="61" gmtAdjustment="GMT+06:30" useDaylightTime="0" value="6.5">Yangon (Rangoon)  </option>
                                    <option timeZoneId="62" gmtAdjustment="GMT+07:00" useDaylightTime="0" value="7"> Bangkok, Hanoi, Jakarta</option>
                                    <option timeZoneId="63" gmtAdjustment="GMT+07:00" useDaylightTime="1" value="7"> Krasnoyarsk</option>
                                    <option timeZoneId="64" gmtAdjustment="GMT+08:00" useDaylightTime="0" value="8"> Beijing, Chongqing, Hong Kong, Urumqi </option>
                                    <option timeZoneId="65" gmtAdjustment="GMT+08:00" useDaylightTime="0" value="8">Kuala Lumpur, Singapore  </option>
                                    <option timeZoneId="66" gmtAdjustment="GMT+08:00" useDaylightTime="0" value="8"> Irkutsk, Ulaan Bataar</option>
                                    <option timeZoneId="67" gmtAdjustment="GMT+08:00" useDaylightTime="0" value="8"> Perth</option>
                                    <option timeZoneId="68" gmtAdjustment="GMT+08:00" useDaylightTime="0" value="8"> Taipei </option>
                                    <option timeZoneId="69" gmtAdjustment="GMT+09:00" useDaylightTime="0" value="9"> Osaka, Sapporo, Tokyo</option>
                                    <option timeZoneId="70" gmtAdjustment="GMT+09:00" useDaylightTime="0" value="9"> Seoul </option>
                                    <option timeZoneId="71" gmtAdjustment="GMT+09:00" useDaylightTime="1" value="9"> Yakutsk </option>
                                    <option timeZoneId="72" gmtAdjustment="GMT+09:30" useDaylightTime="0" value="9.5"> Adelaide </option>
                                    <option timeZoneId="73" gmtAdjustment="GMT+09:30" useDaylightTime="0" value="9.5"> Darwin </option>
                                    <option timeZoneId="74" gmtAdjustment="GMT+10:00" useDaylightTime="0" value="10"> Brisbane</option>
                                    <option timeZoneId="75" gmtAdjustment="GMT+10:00" useDaylightTime="1" value="10"> Canberra, Melbourne, Sydney </option>
                                    <option timeZoneId="76" gmtAdjustment="GMT+10:00" useDaylightTime="1" value="10"> Hobart </option>
                                    <option timeZoneId="77" gmtAdjustment="GMT+10:00" useDaylightTime="0" value="10"> Guam, Port Moresby </option>
                                    <option timeZoneId="78" gmtAdjustment="GMT+10:00" useDaylightTime="1" value="10"> Vladivostok </option>
                                    <option timeZoneId="79" gmtAdjustment="GMT+11:00" useDaylightTime="1" value="11"> Magadan, Solomon Is., New Caledonia </option>
                                    <option timeZoneId="80" gmtAdjustment="GMT+12:00" useDaylightTime="1" value="12"> Auckland, Wellington</option>
                                    <option timeZoneId="81" gmtAdjustment="GMT+12:00" useDaylightTime="0" value="12"> Fiji, Kamchatka, Marshall Is.</option>
                                    <option timeZoneId="82" gmtAdjustment="GMT+13:00" useDaylightTime="0" value="13"> Nuku'alofa</option>
                                    </select> 
                                    {{Form::hidden('meetingtimezonetext', 'International Date Line West (GMT-12:00)', array('id'=>'meetingtimezonetext','class'=>'form-control'))}}
                                </div>   
                            </div>
                            <div class="margin-bot20">
                                <div class="select col-xs-3 pdding0">
                                    <p>Location</p>
                                </div>
                                 <div class="select col-xs-4 pdding0">
                                <span class="pointer"></span>
                                    <select id="meetingtype" onchange="CheckmeetingType()" name='meetingtype' class="form-control">
                                        <option value="inperson">In Person</option>
                                        <option value="skype">Skype</option>
                                        <option value="phone">Phone</option>
                                        <option value="google">Google</option>
                                    </select>
                                </div>
                            </div>
                            <div class="margin-bot20 col-xs-9 col-xs-offset-3 pdding0 mobFull">
                                <textarea id="meetinglocation" placeholder='Enter meeting Location' name='meetinglocation' rows="1" class="form-control fixH35" required></textarea>
                            </div> 

                            <div class="margin-bot20">
                                <div class="select col-xs-3 pdding0">
                                    <p>Comments</p>
                                </div>
                                <div class="select col-xs-9 pdding0">
                                    <textarea name='reply' rows="6" class="form-control" id="comments"></textarea>    
                                </div>
                            </div>
                        </div>
                  
                    <div class="clr"></div>
                   
                    <div align="center" class="minBtn">
                        <a href="{{URL::previous()}}"><button type="button" class="btn btn-warning">Cancel</button></a>
                        {{Form::submit('Confirm',array('class'=>'btn btn-success'));}}
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
         <script type="text/javascript">
            function get_time_zone_offset() {
                var current_date = new Date();
                return -current_date.getTimezoneOffset() / 60;
            }
            var timze = get_time_zone_offset();
            <?php if($dst_value < 0) {?>
                if(timze==5.5 || timze==4){
                    timze = parseFloat(timze);
                }else{
                    timze = parseInt(timze)+parseInt(<?php echo $dst_value;?>);        
                }
            
            <?php }?>
           
            // var timer = timze.split("GMT "); 
            $("#meetingtimezone").val(timze);  
            var timezone = $('#meetingtimezone option:selected').text();
            $('#meetingtimezonetext').val(timezone); 


            $('#meetingtimezone').change(function(event) {
                var timezone = $('#meetingtimezone option:selected').text();
               $('#meetingtimezonetext').val(timezone);
            });

            $(function () {
                $('#datetimepicker5').datetimepicker({
                    minDate:new Date(),
                    pickTime: false
                });
                  $('#datetimepicker4').datetimepicker({
                    pickDate: false
                });
            });

          

        </script>  
    </section>
    <!-- /Main colom --> 
@stop
@extends('common.master')
@section('content')
   <?php //echo '<pre>';print_r($dst_value->option_value);?>
    <section class="mainWidth">
        <div class="col-lg-10 col-md-10 col-sm-12 centralize pdding0">
        
       <!--  <div class="backlink pull-right clearfix">
            <a href="/dashboard">Back to Karma Circle</a>
        </div> -->
            <div class="col-md-11 col-sm-12 centralize clearfix pull-left no-pull-md">
                <div class="registrFrm sendnotes col-md-12">
                    <div class="sendnoteBox">
                        <div class="col-sm-4 col-xs-12 pdding0 borderPic">
                            @if ($receiverDetail->piclink == '')
                                <a href="<?php echo '/profile/'.strtolower($receiverDetail->fname.'-'.$receiverDetail->lname).'/'.$receiverDetail->id ;?>"><img alt="" src="/images/default.png"><a/>
                            @else
                                <a href="<?php echo '/profile/'.strtolower($receiverDetail->fname.'-'.$receiverDetail->lname).'/'.$receiverDetail->id ;?>"><img src="{{ $receiverDetail->piclink;}}" class="img-responsive"  alt = "{{$receiverDetail->fname;}}" title = "{{$receiverDetail->fname;}}"></a>
                            @endif                            
                             <a href="<?php echo '/profile/'.strtolower($receiverDetail->fname.'-'.$receiverDetail->lname).'/'.$receiverDetail->id ;?>"><h4>{{$receiverDetail->fname." ".$receiverDetail->lname}}</h4></a>
                                <div class="borderPic midicondetails">
                                    <ul class="clearfix">
                                        <li><a target="_blank" href="{{$receiverDetail->linkedinurl}}"><img src="/images/linkdin.png" alt=""></a></li>
                                        <li>
                                            <a href="<?php echo '/profile/'.strtolower($receiverDetail->fname.'-'.$receiverDetail->lname).'/'.$receiverDetail->id ;?>"><img src="/images/krmaicon.png" alt=""></a>
                                            <a href="<?php echo URL::to('/');?>/FAQs/KarmaPoints/1"><span>{{$receiverDetail->karmascore}}</span></a>
                                        </li>
                                    </ul>
                                </div>
                            <p>{{$receiverDetail->email}}</p>
                        </div>
                        <div class="col-sm-4 col-xs-12 thumbs">
                            <img alt="" src="/images/handshak.png">
                            <p class="redPendingtxt bluetxt">Accepted</p>
                        </div>
                        <div class="col-sm-4 col-xs-12 pdding0 borderPic">
                            @if ($giverDetail->piclink == '')
                                <a href="<?php echo '/profile/'.strtolower($giverDetail->fname.'-'.$giverDetail->lname).'/'.$giverDetail->id ;?>"><img alt="" src="/images/default.png"><a/>
                            @else
                                <a href="<?php echo '/profile/'.strtolower($giverDetail->fname.'-'.$giverDetail->lname).'/'.$giverDetail->id ;?>"><img src="{{ $giverDetail->piclink;}}" class="img-responsive"  alt = "{{$giverDetail->fname;}}" title = "{{$giverDetail->fname;}}"></a>
                            @endif  
                            
                                <a href="<?php echo '/profile/'.strtolower($giverDetail->fname.'-'.$giverDetail->lname).'/'.$giverDetail->id ;?>"><h4>{{$giverDetail->fname." ".$giverDetail->lname}}</h4></a>
                                    <div class="borderPic midicondetails">
                                        <ul class="clearfix">
                                            <li><a target="_blank" href="{{$giverDetail->linkedinurl}}"><img src="/images/linkdin.png" alt=""></a></li>
                                            <li>
                                                <a href="<?php echo '/profile/'.strtolower($giverDetail->fname.'-'.$giverDetail->lname).'/'.$giverDetail->id ;?>"><img src="/images/krmaicon.png" alt=""></a>
                                                <a href="<?php echo URL::to('/');?>/FAQs/KarmaPoints/1"><span>{{$giverDetail->karmascore}}</span></a>
                                            </li>
                                        </ul>
                                    </div>
                                <p>{{$giverDetail->email}}</p>
                        </div>                   
                        <div class="clr"></div>
                    </div>
                    <div class="sendnoteBox">
                        <hr class="darkLine">
                        <h2>Request sent by {{$receiverDetail->fname}} to {{$giverDetail->fname}} <br>on  {{date('F j, Y', strtotime($meetingDetail->req_createdate))}}<br> Subject: {{$meetingDetail->subject}}</h2>
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
                                <li><span class="glyphicon glyphicon-calendar"></span>{{ date('M d, Y',strtotime($meetingDetail->meetingdatetime))}}</li>
                                <li><p onclick="googleAd();" class="googleBtn"><img border="0" alt="" src="https://www.google.com/calendar/images/ext/gc_button6.gif"></p></li>
                                <!-- <li><span class="glyphicon glyphicon-time"></span>{{ date('g:i A',strtotime($meetingDetail->meetingdatetime))}}, GMT(
                                    @if ($meetingDetail->meetingtimezone >'0')
                                        {{'+'}} 
                                    @endif
                                    {{$meetingDetail->meetingtimezone}})</li> -->
                            </ul>
                        </div>

                        <div class="mb65 clearfix" style="width:115%">  
                            <div class="col-xs-12 skype" >
                                <h4> <img src="/images/timezone.png" width="26" height="26"></h4> 
                                <p>{{date("g:i A", strtotime($meetingDetail->meetingdatetime)).' '.$meetingDetail->meetingtimezonetext}}</p>

                            </div>
                            <?php 

                            if($meetingDetail->meetingtype == "inperson") $image = '/images/big-person.png';
                            if($meetingDetail->meetingtype == "skype")    $image = '/images/big-skype.png'; 
                            if($meetingDetail->meetingtype == "phone")    $image = '/images/big-phone.png'; 
                            if($meetingDetail->meetingtype == "google")   $image = '/images/big-google.png';  
                            ?>     
                            <div class="col-xs-12 skype"> 
                                <h4>
                                    <img src="<?php  echo $image;?>"  width="22" height="22">
                                </h4>
                                <p>{{$meetingDetail->meetinglocation}}</p>
                            </div>
                            <div class="col-xs-12 mailID">
                            <span class="glyphicon glyphicon-envelope"></span>
                                <p>{{$giverDetail->email;}}</p>
                            </div>
                            <div align="center" class="minBtn">
                            
                            </div>
                        </div>
                        <hr class="darkLine">
                        <h2 class="mleft">Comments:</h2>  
                        <p class="grayColor mleft" > {{$meetingDetail->reply}}</p>

                    </div>
                    @if ($CurrentUser->id == $receiverDetail->id)
                        @if ($meetingDetail->meetingdatetime < $MettingActualCurrentTimeWithZone)
                            <div align="center" class="minBtn">
                                 <a href="/SendkarmaNote/{{$meetingDetail->id}}/{{$receiverDetail->fname.'-'.$receiverDetail->lname.'_'.$giverDetail->fname.'-'.$giverDetail->lname}}">
                            <button class="btn btn-success" type="button">Send Karma Note</button></a>
                            </div>
                        @endif
                    @endif
                    
                      
                </div>
            </div>
        </div>    

    </section>

{{--              <a href="http://www.google.com/calendar/event?
action=TEMPLATE
&text=title
&dates=20150320T113000Z/20150329T053000Z
&details=details
&location=Dehradun
&trp=false
&sprop=
&ctz= Kathmandu
&sprop=name:rashi" 
target="_blank" rel="nofollow">Add to my calendar</a> --}}
    <SCRIPT TYPE="text/javascript">
//Add google calander to meeting request accept page
      function googleAd(){
                var timeduration = parseInt("<?php echo $meetingDetail->meetingduration;?>");
                if(timeduration < 15){
                    timeduration=timeduration*60;
                }
                var type = "<?php echo $meetingDetail->meetingtype;?>";
                var dst_value = "<?php echo $dst_value->option_value;?>";
                if(type == "inperson" )
                    type = "In Person" ;
                if(type == "skype" )
                    type = "Skype" ;
                if(type == "phone" )
                    type = "Phone" ;
                if(type == "google" )
                    type = "Google" ;
                var date_timezone = "<?php echo $meetingDetail->meetingtimezone;?>";
                var meetingLink ="<?php echo URL::to('').'/meeting/'.strtolower($receiverDetail->fname.'-'.$receiverDetail->lname.'-'.$giverDetail->fname.'-'.$giverDetail->lname).'/'.$meetingDetail->id ;?>";
                    var date_time = "<?php echo date(' Y-m-d H:i:s', strtotime($meetingDetail->meetingdatetime));?>";
                    var date_meeting = "<?php echo date('Y-m-d', strtotime($meetingDetail->meetingdatetime));?>";
                    var getDateHours=sqlToJsDate(date_time);
                    date_timezone_numeric=parseFloat(date_timezone);
                    var dst_value=parseInt(dst_value);
                    if(date_timezone_numeric ==5.5){
                       getUtcHour=getDateHours - date_timezone_numeric;

                    }else{
                        getUtcHour=getDateHours - date_timezone_numeric+dst_value;    
                    }
					if(getUtcHour >24){
                        var sqlDateArr = date_time.split("-");
                        var sqlDateArrDate = sqlDateArr[2].split(" ");
                        var sDay = sqlDateArrDate[0];
                        sDay=parseInt(sDay);
                        date_update_value_day=sDay+1;
                        var sYear = sqlDateArr[0];
                        var sMonth = (Number(sqlDateArr[1])).toString();
                        var sqlDateArrTime = sqlDateArrDate[1].split(":");
                        var getMinuteUpdate = getUtcHour.toString().split(".")[1];
                        if(getMinuteUpdate==5){
                            var sMinute = parseInt(sqlDateArrTime[1]);
                            var sMinute=sMinute+30;
                            if(sMinute > 60){
                                var hours = Math.floor( sMinute / 60);
                                var sMinute = sMinute % 60;
                                getUtcHour=getUtcHour+hours;
                            }
                            var getUtcHour=parseFloat(getUtcHour);
                            getUtcHour=getUtcHour-.5;
                        }else{
                            var sMinute = sqlDateArrTime[1];
                        }
                       var sqlDateArrSecond = sqlDateArrTime[2].split(".");
                        var sSecond = sqlDateArrSecond[0];
                        var getUtcHour=getUtcHour-24;
                    }else if(getUtcHour < 0){
                        var sqlDateArr = date_time.split("-");
                        var sqlDateArrDate = sqlDateArr[2].split(" ");
                        var sDay = sqlDateArrDate[0];
                        sDay=parseInt(sDay);
                        date_update_value_day=sDay-1;
                        var sYear = sqlDateArr[0];
                        var sMonth = (Number(sqlDateArr[1])).toString();
                        var sqlDateArrTime = sqlDateArrDate[1].split(":");
                        var getMinuteUpdate = getUtcHour.toString().split(".")[1];
                        if(getMinuteUpdate==5){
                            var sMinute = parseInt(sqlDateArrTime[1]);
                            var sMinute=sMinute+30;
                            if(sMinute >= 60){
                                var hours = Math.floor( sMinute / 60);
                                var sMinute = sMinute % 60;
                                getUtcHour=getUtcHour+hours;
                            }
                            var getUtcHour=parseFloat(getUtcHour);
                            getUtcHour=getUtcHour-.5;
                        }else{
                            var sMinute = sqlDateArrTime[1];
                        }
                        var sqlDateArrSecond = sqlDateArrTime[2].split(".");
                        var sSecond = sqlDateArrSecond[0];
                        var getUtcHour=getUtcHour+24;
                    }else{
                        var sqlDateArr = date_time.split("-");
                        var sqlDateArrDate = sqlDateArr[2].split(" ");
                        var sDay = sqlDateArrDate[0];
                        sDay=parseInt(sDay);
                        date_update_value_day=sDay;
                        var sYear = sqlDateArr[0];
                        var sMonth = (Number(sqlDateArr[1])).toString();
                        var sqlDateArrTime = sqlDateArrDate[1].split(":");
                        var getMinuteUpdate = getUtcHour.toString().split(".")[1];
                        if(getMinuteUpdate==5){
                            var sMinute = parseInt(sqlDateArrTime[1]);
                            var sMinute=sMinute+30;
                            if(sMinute >= 60){
                                var hours = Math.floor( sMinute / 60);
                                var sMinute = sMinute % 60;
                                getUtcHour=getUtcHour+hours;
                            }
                            var getUtcHour=parseFloat(getUtcHour);
                            getUtcHour=getUtcHour-.5;
                        }else{
                            var sMinute = sqlDateArrTime[1];
                        }
                        var sqlDateArrSecond = sqlDateArrTime[2].split(".");
                        var sSecond = sqlDateArrSecond[0];

                    }

                    if(sMonth.toString().length==1){
                            sMonth='0'+sMonth;
                         }
                         if(sMonth.toString().length==1){
                            sMonth='0'+sMonth;
                         }
                         if(date_update_value_day.toString().length==1){
                            date_update_value_day='0'+date_update_value_day;
                         }
                    var date=sYear + '' + sMonth + '' + date_update_value_day;
					if(getUtcHour.toString().length==1){
                        getUtcHour='0'+getUtcHour;
                    }
                    if(sMinute.toString().length==1){
                        sMinute='0'+sMinute;
                    }
                    if(sSecond.toString().length==1){
                        sSecond='0'+sSecond;
                    }
                    var getDateTo=getUtcHour + '' + sMinute + '' + sSecond;

                    getHourFrom=getUtcHour;
                    sMinute=parseInt(sMinute);
                    getMinuteFrom=sMinute+timeduration;
                    getSecondFrom=sSecond;
                    if(getMinuteFrom > 60){
                        var hours = Math.floor( getMinuteFrom / 60);
                        var getMinuteFrom = getMinuteFrom % 60;
						getHourFrom=parseInt(getHourFrom);
                        getHourFrom=getHourFrom+hours;
                    }
                    if(getHourFrom.toString().length==1){
                        getHourFrom='0'+getHourFrom;
                    }
                    if(getMinuteFrom.toString().length==1){
                        getMinuteFrom='0'+getMinuteFrom;

                    }
                    if(getSecondFrom.toString().length==1){
                        getSecondFrom='0'+getSecondFrom;
                    }
                    var getDateFrom = (getHourFrom+''+ getMinuteFrom +''+ getSecondFrom);
                    var comments = '<?php echo $meetingDetail->reply;?>';
                        comments = comments.replace(/ /g, '+'); 
                        comments =  comments+"%0A"+"%0A"+meetingLink; 
                    date = date+'T'+getDateTo+'Z/'+date.trim()+'T'+getDateFrom+'Z';
                    var date_value=date.toString();
                    var text = "KarmaMeeting between "+'<?php echo $receiverDetail->fname;?>'+" and "+'<?php echo $giverDetail->fname;?>';
                    text = text.replace(/ /g, '+'); 
                    if(type == "inperson" ){
                        type =  type+': '+'<?php echo $meetingDetail->meetingtimezonetext;?> ';    
                    }
                    
                    type =  type+': '+'<?php echo $meetingDetail->meetinglocation;?> ';
                    url = "https://www.google.com/calendar/render?action=TEMPLATE&text="+text+"&dates="+date_value.trim()+"&details="+comments+"&location="+type+"&sf=true&output=xml#g";
                    window.open(url,'_blank');
            }

    //Function to convert a SQL date to Js Date according to google calander

    function sqlToJsDate(sqlDate){

        //sqlDate in SQL DATETIME format ("yyyy-mm-dd hh:mm:ss.ms")
        var sqlDateArr1 = sqlDate.split("-");
        //format of sqlDateArr1[] = ['yyyy','mm','dd hh:mm:ms']
        var sYear = sqlDateArr1[0];
        var sMonth = (Number(sqlDateArr1[1]) - 1).toString();
        var sqlDateArr2 = sqlDateArr1[2].split(" ");
        //format of sqlDateArr2[] = ['dd', 'hh:mm:ss.ms']
        var sDay = sqlDateArr2[0];
        var sqlDateArr3 = sqlDateArr2[1].split(":");
        //format of sqlDateArr3[] = ['hh','mm','ss.ms']
        var sHour = sqlDateArr3[0];
        var sMinute = sqlDateArr3[1];
        var sqlDateArr4 = sqlDateArr3[2].split(".");
        //format of sqlDateArr4[] = ['ss','ms']
        var sSecond = sqlDateArr4[0];
        //var sMillisecond = sqlDateArr4[1];
        
        return sHour;
    }



    </SCRIPT>
    <!-- /Main colom -->
@stop

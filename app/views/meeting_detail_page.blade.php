@extends('common.master')
@section('content')
<?php //echo '<pre>';print_r($meetingTrailData);die;?>
<section class="mainWidth">
    <div class="col-lg-10 col-md-10 col-sm-12 centralize pdding0">        
        <div class="backlink pull-right clearfix">
            <a href="">Back to Karma Circle</a>
        </div>
            <div class="col-md-11 col-sm-12 centralize clearfix pull-left no-pull-md">
                <div class="registrFrm sendnotes col-md-12 regpending meetingupdate">
                <!-- karma profile-->
                    <header class="clearfix">
                        <input type="hidden" value="{{$meetingDetail->id}}" id="meetingId">
                        <input type="hidden" value="{{$userRole}}" id="userRole">
                        <div class="userImg">
                            @if ($giverDetail->piclink == '')
                                <img  alt="" src="/images/default.png">
                            @else
                                @if($userRole=='Receiver')
                                    <img alt="" src="{{$giverDetail->piclink}}" class="img-responsive">
                                @else
                                    <img alt="" src="{{$receiverDetail->piclink}}" class="img-responsive">
                                @endif    
                            @endif
                            
                        </div>
                        <div class="userDetail">
                            @if($userRole=='Receiver')
                                <h2>{{$giverDetail->fname}} {{$giverDetail->lname}}</h2>
                            @else
                                <h2>{{$receiverDetail->fname}} {{$receiverDetail->lname}}</h2>
                            @endif
                            @if ($meetingStatusText == '')
                                
                            @else
                                <p>{{ $meetingStatusText }}</p>
                            @endif
                            
                        </div>
                        @if(($meetingDetail->status == 'happened') && ($userRole =='Giver'))
                        @else
                            @if($meetingDetail->status != 'archived')
                                @if($meetingDetail->status != 'cancelled')
                                    <div class="headNav headNav2">
                                        <input type="button" value="" class="newwwww">
                                        <ul class="navList">
                                            @if ($meetingDetail->status == 'pending' && $userRole=='Receiver')
                                                <?php $giverName=$giverDetail->fname.'-'.$giverDetail->lname;
                                                    $receiverName=$receiverDetail->fname.'-'.$receiverDetail->lname;
                                                ?>
                                                <li><a href="/sendMeetingReminder/{{$userRole }}/{{$meetingDetail->id}}">Send Reminder</a></li>
                                                <li><a  data-action='cancelRequest' class='openPopup' onclick='openPopup(this, "meetingCalledForCancelRequest()")'>Cancel Request</a></li>
                                            @elseif($meetingDetail->status == 'pending' && $userRole=='Giver')
                                            <li><a href="#" data-toggle="modal" data-target=".bs-example-modal-md" >Send Message</a></li>
                                            <li><a href="/meeting/accept/{{$meetingDetail->id}}" class='openPopup'>Schedule Meeting</a></li>
                                            <li><a  data-action='archiveRequest' class='openPopup' onclick='openPopup(this, "meetingArchivePopup()")'>Archive Request</a></li>
                                            @elseif($meetingDetail->status == 'responded' && $userRole=='Receiver')
                                            <li><a href="#" data-toggle="modal" data-target=".bs-example-modal-md" >Send Message</a></li>
                                            <li><a href="/SendkarmaNote/{{$meetingDetail->id}}/{{$receiverDetail->fname}}-{{$receiverDetail->lname}}_{{$giverDetail->fname}}-{{$giverDetail->lname}}">Send KarmaNote</a></li>
                                            <li><a  data-action='cancelRequest' class='openPopup' onclick='openPopup(this, "meetingCalledForCancelRequest()")'>Cancel Request</a></li>
                                            @elseif($meetingDetail->status == 'responded' && $userRole=='Giver')
                                            <li><a href="#" data-toggle="modal" data-target=".bs-example-modal-md" >Send Message</a></li>
                                            <li><a href="/meeting/accept/{{$meetingDetail->id}}" class='openPopup'>Schedule Meeting</a></li>
                                            <li><a  data-action='archiveRequest' class='openPopup' onclick='openPopup(this, "meetingArchivePopup()")'>Archive Request</a></li>
                                            @elseif($meetingDetail->status == 'scheduled' && $userRole=='Receiver')
                                            <li><a href="#" data-toggle="modal" data-target=".bs-example-modal-md" >Send Message</a></li>
                                            <li><a href="/confirmMeetingFromWeb/{{$meetingDetail->id}}">Confirm Meeting</a></li>
                                            <li><a  data-action='requestNewTime' class='openPopup' onclick='openPopup(this, "requestNewTimePopup()")'>Request New Time</a></li>
                                            @elseif($meetingDetail->status == 'scheduled' && $userRole=='Giver')
                                            <li><a href="#" data-toggle="modal" data-target=".bs-example-modal-md" >Send Message</a></li>
                                            <li><a href="/meeting/accept/{{$meetingDetail->id}}" class='openPopup'>ReSchedule Meeting</a></li>
                                            <li><a  data-action='cancelRequest' class='openPopup' onclick='openPopup(this, "meetingCalledForCancelRequest()")'>Cancel Meeting</a></li>
                                            @elseif($meetingDetail->status == 'confirmed' && $userRole=='Receiver')
                                            <li><a href="#" data-toggle="modal" data-target=".bs-example-modal-md" >Send Message</a></li>
                                            <li><a href="/SendkarmaNote/{{$meetingDetail->id}}/{{$receiverDetail->fname}}-{{$receiverDetail->lname}}_{{$giverDetail->fname}}-{{$giverDetail->lname}}">Send KarmaNote</a></li>
                                            <li><a  data-action='requestNewTime' class='openPopup' onclick='openPopup(this, "requestNewTimePopup()")'>Request New Time</a></li>
                                            @elseif($meetingDetail->status == 'confirmed' && $userRole=='Giver')
                                            <li><a href="#" data-toggle="modal" data-target=".bs-example-modal-md" >Send Message</a></li>
                                             <li><a href="/meeting/accept/{{$meetingDetail->id}}" class='openPopup'>ReSchedule Meeting</a></li>
                                            <li><a  data-action='cancelRequest' class='openPopup' onclick='openPopup(this, "meetingCalledForCancelRequest()")'>Cancel Meeting</a></li>
                                            @elseif($meetingDetail->status == 'over' && $userRole=='Receiver')
                                            <li><a  data-action='meetingNotHappen' class='openPopup' onclick='openPopup(this, "meetingNotHappenPopup()")'>Meeting didn’t happen</a></li>
                                            <li><a href="/SendkarmaNote/{{$meetingDetail->id}}/{{$receiverDetail->fname}}-{{$receiverDetail->lname}}_{{$giverDetail->fname}}-{{$giverDetail->lname}}">Send KarmaNote</a></li>
                                            @elseif($meetingDetail->status == 'over' && $userRole=='Giver')
                                            <li><a  data-action='meetingNotHappen' class='openPopup' onclick='openPopup(this, "meetingNotHappenPopup()")'>Meeting didn’t happen</a></li>
                                            
                                            <li><a  data-action='meetingHappen' class='openPopup' onclick='openPopup(this, "meetingHappenPopup()")'>Meeting happened</a></li>
                                            @elseif($meetingDetail->status == 'completed' && $userRole=='Receiver')
                                            <li><a href="#">Share KarmaNote</a></li>
                                            @elseif($meetingDetail->status == 'completed' && $userRole=='Giver')
                                            <li><a href="#">Share KarmaNote</a></li>
                                            @else($meetingDetail->status == 'Happened' && $userRole=='Receiver')
                                            <li><a href="/SendkarmaNote/{{$meetingDetail->id}}/{{$receiverDetail->fname}}-{{$receiverDetail->lname}}_{{$giverDetail->fname}}-{{$giverDetail->lname}}">Send KarmaNote</a></li>
                                            @endif
                                            
                                        </ul>
                                    </div>
                                @endif
                            @endif
                        @endif    
                    </header>
                <!-- karma profile-->
                <div class="clr"></div>
                    
                     <div class="">
                        <div class="massageing">
                            @if ($meetingDetail->status == 'scheduled' || $meetingDetail->status == 'confirmed')
                                <div class="meetingPersonDetail">
                                    <div class="action fullWidth">
                                        <ul>
                                            <li><span class="glyphicon glyphicon-tasks"></span>  {{$meetingDetail->meetingduration}}</li>
                                            <?php $date=date('Y-m-d', strtotime($meetingDetail->meetingdatetime));
                                            $time=date('H', strtotime($meetingDetail->meetingdatetime));
                                            ?> 
                                            <li><span class="glyphicon glyphicon-calendar"></span> {{$date}}</li>
                                            <li><span class="glyphicon glyphicon-time"></span> {{ date('g:i A',strtotime($meetingDetail->meetingdatetime))}}, GMT(
                                            @if ($meetingDetail->meetingtimezone > '0')
                                                {{"+"}}
                                            @endif
                                            {{$meetingDetail->meetingtimezone}})</li>
                                        </ul>
                                    </div>

                                    <div class=" clearfix">
                                        <div class="col-xs-6 skype">
                                            @if($meetingDetail->meetingtype=='skype')
                                            <h4>Skype</h4>
                                            <p>{{$meetingDetail->meetinglocation}}</p>
                                            @endif
                                            @if($meetingDetail->meetingtype=='inperson')
                                            <h4>Inperson</h4>
                                            <p>{{$meetingDetail->meetinglocation}}</p>
                                            @endif
                                            @if($meetingDetail->meetingtype=='phone')
                                            <h4>Phone Number</h4>
                                            <p>{{$meetingDetail->meetinglocation}}</p>
                                            @endif
                                            @if($meetingDetail->meetingtype=='google')
                                            <h4>Google</h4>
                                            <p>{{$meetingDetail->meetinglocation}}</p>
                                            @endif
                                        </div>
                                        <div class="col-xs-6 mailID">
                                        <span class="glyphicon glyphicon-envelope"></span>
                                            <p>{{$CurrentUser->email}}</p>
                                        </div>
                                    </div>
                                    <div class="googleBTN">
                                       <p onclick="googleAd();" class="googleBtn"><img border="0" alt="" src="https://www.google.com/calendar/images/ext/gc_button6.gif"></p>
                                    </div>
                                </div>
                            @endif
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
                        </div>
                    <hr class="darkLine"/>    
                </div>
                <div class="modal" style="display:none" id="openSendReminderPopup">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <button aria-label="Close" onclick="modelClose('openSendReminderPopup');" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">&times;</span></button>
                          <h4 class="modal-title">KarmaCircles Alert</h4>
                        </div>
                        <input type="hidden" id="group-id" value="">
                        <div class="modal-body group-body" >
                          <p id='getTextData'></p>
                        </div>
                        <div class="modal-footer">  
                         
                          <button data-dismiss="modal" class="btn btn-default linkfullBTN gpBtn pull-right" id = "noButton" type="button" onclick="cancelcalledMeetingPopup();">No</button> 
                           <button data-dismiss="modal" class="btn btn-default linkfullBTN gpBtn pull-right" id = "yesButton" type="button" onclick="">Yes</button>
                        </div>
                      </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog --> 
                </div>  
            </div>
            <div class="closeNav"></div>
        </div>    
</section>
<div class="closeNav"></div>
<!-- /Main colom -->
<!-- Small modal -->

<div class="modal fade bs-example-modal-md messagesentBox" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
        <div class="modal-header">
          <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
          <h4 id="myLargeModalLabel" class="modal-title">Send 
            @if ($CurrentUser->id==$receiverDetail->id)
                {{$giverDetail->fname}} {{$giverDetail->lname}}
            @else($CurrentUser->id==$giverDetail->id)
                {{$receiverDetail->fname}} {{$receiverDetail->lname}}
            @endif
            a Message</h4>
        </div>
        <div class="modal-body">
          <textarea name="" id="message"></textarea>
           <div class="modal-footer">
            <button data-dismiss="modal" class="btn btn-default" type="button" class="close">Cancel</button>
            <button class="btn btn-primary sendBTN" type="button" onclick=callMessageData()>Send Message</button>
          </div>
        </div>

      </div>
  </div>
</div>

<script type="text/javascript">

    function openPopup(obj,noAction) {
        var action = $(obj).data('action');
        var text = '';
        if(action == 'cancelRequest'){
            text = 'Cancel request? Please confirm!';
        } else if(action == 'archiveRequest') {
            text = 'Very busy? Archive request!';
        } else if(action == 'requestNewTime') {
            text = 'Cant make it? Request new time!';
        } else if(action == 'meetingNotHappen') {
            text = 'Did this meeting not happen?';
        } else if(action == 'meetingHappen') {
            text = 'Has this meeting happened?';
        }
        $('#getTextData').html(text);
        $('#yesButton').attr('onclick', noAction);
        openPopupForAlert();
        
    }

    function callMessageData(){
        var message=$('#message').val();
        var meetingId=$('#meetingId').val();
        var userRole=$('#userRole').val();
        $.ajax({ 
            url: siteURL.url+'/meetingMessageSaveFromWeb',
            type: "post", 
            cache:false,
            data : { meetingId : meetingId, userRole : userRole,message : message},
            async: true,
            success: function(data){
                location.reload();
            },
            error: function (data) {
                location.reload();
            }
        });
    }
    function openPopupForAlert() {
        document.getElementById('openSendReminderPopup').style.display = 'block';
    }
</script>
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

@stop
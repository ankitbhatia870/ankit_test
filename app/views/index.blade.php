
@extends('common.basic')
@section('body')
<?php 
// echo '<pre>';print_r($getKcuserOne);
// echo '<pre>';print_r($getKcuserTwo);
//  echo '<pre>';print_r($getKcuser);die;
$searchVal = ''; $searchOption = 'Skills' ;
      $CurrentUser = Auth::user();
    if(isset($_GET['searchUser'])) $searchVal = $_GET['searchUser']; 
    if(isset($_GET['searchOption'])) $searchOption = $_GET['searchOption']; 
?>
<header>
    <div class="mainWidth">
		<div class="col-sm-4 col-xs-12 logo"> 
			<a href="/"><img src="/images/logo1.png"></a>
		</div>  
        <div style="top:0" class="col-sm-5 col-xs-12 pull-right headlink">
                <ul>
					<li><a href="/">Home</a></li>     
					<li>|</li>
					<li><a href="/directory/skills-a">Skills</a></li>    
					<li>|</li>
					<li><a href="/FAQs">FAQs</a></li>    
					<li>|</li>
					<li><a href="/groupsAll">Groups</a></li>   
					<li>|</li>
					<li><a href="/how-it-works">How it works</a></li>    
                </ul>    
               
                <div class="clr"></div>
                <div class="action">
                  <p><a href="login/linkedin"><img src="/images/login_button.jpg" class="img-responsive" alt=""></a><p>
                  {{-- <p><a href="login/linkedin">Sign in with Linkedin</a></p> --}}
                </div>
        </div>
        <div class="clr"></div>
    </div>
</header>

<div class="mainWidth suggestSection suggestImage">
    <img src="/images/homepage_image1.jpg" class="img-responsive" alt="">
</div>

<div class="col-md-12 greenborder searchSkills">
    <section class="mainWidth banner">
     <!-- Search Panel -->
        <div class="col-md-10 col-sm-11 col-xs-11 centralize searchPanel pdding0">
            <div class="col-xs-1 searcati">
               <span id="displayIcon" class="glyphicon glyphicon-align-user"></span>
               <i class="glyphicon glyphicon-chevron-down"></i>
            </div>
            {{ Form::open(array('url' => 'searchUsers' , 'method' => 'get','onsubmit'=>'return validateSearch()')) }}
            <div class="col-sm-10 col-xs-9">
                 {{ Form::text('searchUser',$searchVal,array('class'=>'form-control searchBox','placeholder' => 'Search for a name', 'id'=> 'searchKeyword', 'onkeyup'=>'searchResult()', 'autocomplete'=>'off')); }}
                {{ Form::hidden('searchOption',$searchOption ,array('id'=>'searchOption')); }}
            </div>
            <div class="col-xs-1 pull-right searchIconbtn">
                {{Form::submit('',array('class'=>'searchBTN'));}}          
            </div>
              {{ Form::close() }} 
              <div id="searchresult" class="displayNone"></div>
        </div>

        <div class="searchlink">
            <ul>
                <!--  <li onclick="searchOption('All')"><a href=""><span class="glyphicon glyphicon-align-justify"></span> All</a></li> -->
                <li onclick="searchOption('People')"><a href=""><span class="glyphicon glyphicon-user"></span> People</a></li>
                <li onclick="searchOption('Skills')"><a href=""><span class="glyphicon glyphicon-certificate"></span> Skills</a></li>
                <li onclick="searchOption('Industry')"><a href=""><span class="glyphicon glyphicon-tower"></span> Industry</a></li>
                <li onclick="searchOption('Location')"><a href=""><span class="glyphicon glyphicon-globe"></span> Location</a></li>               
                <li onclick="searchOption('Groups')" style="position:relative">
                    <a href="">
                        <span class="groupIcon">
                          <i class="glyphicon glyphicon-user group"></i>
                          <i class="glyphicon glyphicon-user group"></i>
                          <i class="glyphicon glyphicon-user group"></i>
                        </span>
                        <b style="text-indent:28px;font-weight:normal;display:inline-block">Group</b>
                     </a>
                </li>
                <!-- <li onclick="searchOption('Groups')"><a href=""><span class="glyphicon glyphicon-tag"></span> Groups</a></li> -->
               <!--  <li onclick="searchOption('Tags')"><a href=""><span class="glyphicon glyphicon-tag"></span> Tags</a></li> -->
            </ul>
        </div>
        <!-- Search Panel /-->
       
    </section>
</div>
<!-- Banner section -->

<!-- Banner section/ -->
<!-- ThreeColom -->
<section class="mainWidth">
<div class="clr"></div>
<h3 class="top15"> 
KarmaCircles makes it easy for you to give and receive help for free.
</h3>
<h3 class="homeHeading" style=" max-width: 90%;">
Find a person, request for an online or in-person meeting and thank them for their help.
</h3>
<h3 class="homeHeading" style=" max-width: 90%;">
Help others to build your online reputation around various skills. <a href="/deepak" class="f14">(<u>See example</u>)</a>
</h3>
<hr class="darkLine">
</section>
<section class="mainWidth suggestSection">
 <!-- meeting request to random KC user-->
        <div class="col-md-2 col-sm-2 col-xs-1 pull-right" id="skip" style="margin-top: 0px;">
          <a  href="javascript:;" >Suggest More</a> 
        </div>
        
        <div id="dash-two">
          
        @if(!empty($getKcuser))   
        <?php $i=1;?> 
         
            <?php if($i==2) $style = "style='display:none'"; else $style = "";?>
            <div id="content-{{$i}}" <?php echo $style;?>  class="content-switcher"><div class="sugg-first">
                <div class=" col-xs-12 centralize networkList" >
                <div class="row clearfix">
                    <div class="col-md-10 col-sm-10 col-xs-10 pull-left">
                        
                        <p> 
                            Following people are on KarmaCircles. Consider sending them a meeting request today.
                        </p>
                       
                    </div>
                </div>
                    <div class="col-md-12 clearfix  listBox mainsuggestBox">
                       <div class="col-sm-4 col-xs-12 noteBox tabtxt suggestBox">
                            <div class="col-xs-4">
                                 @if ($getKcuser->piclink == "" ||$getKcuser->piclink == 'null')
                                        <a  href="profile/<?php echo strtolower($getKcuser->fname.'-'.$getKcuser->lname);?>/{{$getKcuser->id}}"><img alt="" src="/images/default.png" width="82" height="87"></a>
                                    @else
                                        <a  href="profile/<?php echo strtolower($getKcuser->fname.'-'.$getKcuser->lname);?>/{{$getKcuser->id}}"><img src="{{$getKcuser->piclink}}" width="82" height="87"></a>
                                    @endif
                            </div>
                            <div class="col-sm-8 col-xs-8">
                                <a  href="profile/<?php echo strtolower($getKcuser->fname.'-'.$getKcuser->lname);?>/{{$getKcuser->id}}"><h4>{{$getKcuser->fname.' '.$getKcuser->lname }}</h4></a>
                                 <p title="{{KarmaHelper::stringCut($getKcuser->headline,80)}}">{{KarmaHelper::stringCut($getKcuser->headline,80)}}</p>
                                <p>{{$getKcuser->location}}</p>
                            </div>
                            <div class="borderPic pull-left">
                                <ul>
                                 <li>
                                    <a target='_blank' href="{{$getKcuser->linkedinurl}}"><img alt="" src="images/linkdin.png"></a>
                                </li>
                                @if (isset($getKcuser->email))
                                    <li>
                                        <a href="profile/<?php echo strtolower($getKcuser->fname.'-'.$getKcuser->lname);?>/{{$getKcuser->id}}"><img alt="" src="images/krmaicon.png"></a>
                                        <a href="<?php echo URL::to('/');?>/FAQs/KarmaPoints/1"><span class="karmascore">{{$getKcuser->karmascore}}</span></a>
                                    </li>
                                @endif 
                                </ul>
                            </div>
                            <div class="pull-right">
                            <a target='_blank' href="{{URL::to('/')}}/CreateKarmaMeeting/{{$getKcuser->id}}"><button type="button" class="btn btn-success btnicon meeting">Request Meeting</button></a>
                            </div>
                        </div>
                        <div class="col-sm-4 col-xs-12 noteBox tabtxt suggestBox">
                            <div class="col-xs-4">
                                 @if ($getKcuserOne->piclink == "" ||$getKcuserOne->piclink == 'null')
                                        <a  href="profile/<?php echo strtolower($getKcuserOne->fname.'-'.$getKcuserOne->lname);?>/{{$getKcuserOne->id}}"><img alt="" src="/images/default.png" width="82" height="87"></a>
                                    @else
                                        <a  href="profile/<?php echo strtolower($getKcuserOne->fname.'-'.$getKcuserOne->lname);?>/{{$getKcuserOne->id}}"><img src="{{$getKcuserOne->piclink}}" width="82" height="87"></a>
                                    @endif
                            </div>
                            <div class="col-sm-8 col-xs-">
                                <a  href="profile/<?php echo strtolower($getKcuserOne->fname.'-'.$getKcuserOne->lname);?>/{{$getKcuserOne->id}}"><h4>{{$getKcuserOne->fname.' '.$getKcuserOne->lname }}</h4></a>
                                 <p title="{{KarmaHelper::stringCut($getKcuserOne->headline,80)}}">{{KarmaHelper::stringCut($getKcuserOne->headline,80)}}</p>
                                <p>{{$getKcuserOne->location}}</p>
                            </div>
                            <div class="borderPic pull-left">
                                <ul>
                                 <li>
                                    <a target='_blank' href="{{$getKcuserOne->linkedinurl}}"><img alt="" src="images/linkdin.png"></a>
                                </li>
                                @if (isset($getKcuserOne->email))
                                    <li>
                                        <a href="profile/<?php echo strtolower($getKcuserOne->fname.'-'.$getKcuserOne->lname);?>/{{$getKcuserOne->id}}"><img alt="" src="images/krmaicon.png"></a>
                                        <a href="<?php echo URL::to('/');?>/FAQs/KarmaPoints/1"><span class="karmascore">{{$getKcuserOne->karmascore}}</span></a>
                                    </li>
                                @endif 
                                </ul>
                            </div>
                            <div class="pull-right">
                            <a target='_blank' href="{{URL::to('/')}}/CreateKarmaMeeting/{{$getKcuserOne->id}}"><button type="button" class="btn btn-success btnicon meeting">Request Meeting</button></a>
                            </div>
                        </div>
                        <div class="col-sm-4 col-xs-12 noteBox tabtxt suggestBox" style="margin-right: 0px;">
                            <div class="col-xs-4">
                                 @if ($getKcuserTwo->piclink == "" ||$getKcuserTwo->piclink == 'null')
                                        <a  href="profile/<?php echo strtolower($getKcuserTwo->fname.'-'.$getKcuserTwo->lname);?>/{{$getKcuserTwo->id}}"><img alt="" src="/images/default.png" width="82" height="87"></a>
                                    @else
                                        <a  href="profile/<?php echo strtolower($getKcuserTwo->fname.'-'.$getKcuserTwo->lname);?>/{{$getKcuserTwo->id}}"><img src="{{$getKcuserTwo->piclink}}" width="82" height="87"></a>
                                    @endif
                            </div>
                            <div class="col-sm-8 col-xs-">
                                <a  href="profile/<?php echo strtolower($getKcuserTwo->fname.'-'.$getKcuserTwo->lname);?>/{{$getKcuserTwo->id}}"><h4>{{$getKcuserTwo->fname.' '.$getKcuserTwo->lname }}</h4></a>
                                 <p title="{{KarmaHelper::stringCut($getKcuserTwo->headline,80)}}">{{KarmaHelper::stringCut($getKcuserTwo->headline,80)}}</p>
                                <p>{{$getKcuserTwo->location}}</p>
                            </div>
                            <div class="borderPic pull-left">
                                <ul>
                                 <li>
                                    <a target='_blank' href="{{$getKcuserTwo->linkedinurl}}"><img alt="" src="images/linkdin.png"></a>
                                </li>
                                @if (isset($getKcuserTwo->email))
                                    <li>
                                        <a href="profile/<?php echo strtolower($getKcuserTwo->fname.'-'.$getKcuserTwo->lname);?>/{{$getKcuserTwo->id}}"><img alt="" src="images/krmaicon.png"></a>
                                        <a href="<?php echo URL::to('/');?>/FAQs/KarmaPoints/1"><span class="karmascore">{{$getKcuserTwo->karmascore}}</span></a>
                                    </li>
                                @endif 
                                </ul>
                            </div>
                            <div class="pull-right">
                            <a target='_blank' href="{{URL::to('/')}}/CreateKarmaMeeting/{{$getKcuserTwo->id}}"><button type="button" class="btn btn-success btnicon meeting">Request Meeting</button></a>
                            </div>
                        </div>
                      
                    </div>
                </div>

               
          </div>
        </div>
        <?php $i++;?>
       
        @endif
        <!-- meeting request to random KC user-->
    </div>
<div class="clr"></div>
<hr class="darkLine">
</section>

<section class="mainWidth homeBox">
  <div class="col-sm-4"> 
    <h2>Request KarmaMeeting</h2> 
    <div class="col-md-12">
      <img src="/images/img00657.png" class="img-responsive" alt="">
      <p>Search for a <a href = "searchUsers?searchUser=Start-ups&searchOption=Skills" target="_blank">KarmaGiver</a> by name, skills, industry or 
      location and send a <a href="FAQs/KarmaMeetings/1" target="_blank">KarmaMeeting</a> request specifying the topic that you need help on.</p>
    </div>
  </div>
   
   
  <div class="col-sm-4">
    <h2>Receive Good Karma</h2>
    <div class="col-md-12">
      <img src="/images/img003.png" class="img-responsive" alt="">
      <p>Receive good karma from the KarmaGiver via Skype, Google Hangout, phone call or an in-person meeting.</p>
    </div>
  </div>
  
  <div class="col-sm-4">
    <h2>Thank By Sending KarmaNote</h2>
    <div class="col-md-12">
      <img src="/images/img00456.png" class="img-responsive" alt=""> 
      <p>Thank the KarmaGiver for their time by sending them a <a href="FAQs/KarmaNotes/1" target="_blank">KarmaNote</a>. You can also endorse them for specific skills.</p>
    </div> 
  </div>
</section>

<script type="text/javascript">
    var xhr = null;
   function searchResult(){
        if( xhr != null ) {
                xhr.abort();
                xhr = null;
        }
        var keyword = $('#searchKeyword').val();
        var optionVal = $('#searchOption').val();
        $('div#searchresult').hide();
       // alert(keyword+'---'+optionVal);
        if((optionVal == 'People' || optionVal == 'Groups' || optionVal == 'Skills') && keyword != ''){
           var url='<?php echo URL::to('/');?>/ajaxsearchuser?searchUsers='+keyword+'&searchCat='+optionVal;
            //alert(url);
              xhr=   $.get(url,function(data) {
                if(data==""){
                    $('#searchresult').html('');
                }
                else{
                    $('div#searchresult').show();
                    $("div#searchresult").html(data);
                }
            });    
        }
        else{
          return false;
        }
    }
    function validateSearch(){
          var keyword = $('#searchKeyword').val();
          if(keyword == ''){
            return false;
          }
    }
</script>
 <SCRIPT TYPE="text/javascript">
       $(document).ready(function(){
        var currlen =1;
        var hicount = 2;
        window.setInterval(function(){
            setdata(hicount,currlen);
            ajaxcall(currlen,hicount);
            hicount = parseInt(hicount + 2);
            currlen++; 
        }, 8000); 

        $('#skip').click(function(){
            setdata(hicount,currlen);
            ajaxcall(currlen,hicount);
            hicount = parseInt(hicount + 2);
            currlen++; 
        }); 

        

        contentswitche = $('.content-switcher').width();
        function setdata(hicount,currlen){
            /*var first = "content-"+hicount;
            if(hicount <3)
            var second =  "content-"+parseFloat(hicount+1);
            else
            var second =  "content-"+1;*/
            var first = "content-"+currlen;
            var second =  "content-"+parseInt(currlen+1);
            
            $("#"+first)
            .css({left:'0'})  // Set the left to its calculated position
            .animate({"left":"-"+contentswitche}, "2200"); 

            $("#"+second).css('display',"block");  
            $("#"+second).css('left',contentswitche);

            $("#"+second).animate({
            "left":"0",
            },"fast",
            function(){ }
            );
            $("#"+first).remove();
        }
        function ajaxcall(currlen,hicount){ 
         
            $.ajax({
                url: '<?php echo URL::to('/');?>/ajaxhomeSuggestion?skipcount='+hicount,
                //global: false,
                type: 'POST',
                //data: {},
                async: true, //blocks window close
                success: function(data) {
                  
                    if(data!=""){
                    $("#dash-two").html(data);
                }
                else{
                     setTimeout(function () {
                        ajaxcall(skipcount);
                    }, 800)      
                }

                }
            });  
        }  
    });
  </SCRIPT> 

<!-- ThreeColom/ -->

    <!-- Footer -->
@include('common.footer')
    <!-- Footer/ -->
@stop


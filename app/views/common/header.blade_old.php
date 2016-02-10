<?php 
    $searchVal = ''; 
    if(Request::is('groups/*') || Request::is('groupsAll')) {
     $searchOption = 'Groups' ;
    }
    else
      $searchOption = 'Skills' ;

    $CurrentUser = Auth::user();
    if(isset($_GET['searchUser'])) $searchVal = $_GET['searchUser']; 
    if(isset($_GET['searchOption'])) $searchOption = $_GET['searchOption']; 
?>
<header> 
    <div class="mainWidth">
        <div class="col-sm-4 col-xs-12 logo">
            @if(Auth::check())
			<a href="/dashboard"><img src="/images/Logo_grey.png"></a>
            @else
            <a href="/"><img src="/images/Logo_grey.png"></a>
            @endif
        </div> 
        <div class="col-sm-5 col-xs-12 pull-right headlink" style="top:0">
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
                    @if (Auth::check())
                        <ul>
                        @if(Auth::user()->role == 'admin')
                            <li><a href="/admin/manageUser">Admin Panel </a></li>
                             <li>|</li>
                        @endif
                        @if (Auth::user()->termsofuse == '1' && Auth::user()->userstatus == 'approved')
                             <li><a href="/profile/<?php echo strtolower($CurrentUser->fname.'-'.$CurrentUser->lname).'/'.$CurrentUser->id;?>">Welcome {{$CurrentUser->fname;}}</a></li>
                        @else
                            <li><a href="">Welcome {{$CurrentUser->fname;}}</a></li>
                        @endif
                        <li>|</li>
                        <li><a href="/logout">Logout</a></li>    
                        </ul> 
                    @else
                       <div class="clr"></div>
                <div class="action">
                  <p><a href="/login/linkedin"><img src="/images/login_button.jpg" class="img-responsive" alt=""></a><p>
                  {{-- <p><a href="login/linkedin">Sign in with Linkedin</a></p> --}}
                </div> 
                    @endif
   
        </div>
        <div class="clr"></div>
    </div>
</header>
<!-- /Header -->
<!-- Main colom -->
@if (Auth::check())
    <section class="mainWidth">
    <!-- Search Panel -->
        <div class="col-md-10 col-sm-11 col-xs-11 centralize searchPanel pdding0">
            <div class="col-xs-1 searcati">
               <span id="displayIcon" class="glyphicon glyphicon-align-user"></span>
               <i class="glyphicon glyphicon-chevron-down"></i>
            </div>
             {{ Form::open(array('url' => 'searchUsers' , 'method' => 'get','onsubmit'=>'return validateSearch()')) }}
            <div class="col-sm-10 col-xs-9">
               
                {{ Form::text('searchUser',$searchVal,array('class'=>'form-control searchBox','placeholder' => 'Search for a name', 'id'=> 'searchKeyword', 'autocomplete'=>'off')); }}
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
                <!-- <li onclick="searchOption('Tags')"><a href=""><span class="glyphicon glyphicon-tag"></span> Tags</a></li> -->
            </ul>
        </div>
        <!-- Search Panel -->

    <div class=" col-md-10 centralize nav">
        <div class="mobMenu">
            <img src="/images/navIcon.png" alt="">
            <!-- <a href="/dashboard">Back to Karma Circle</a> -->
        </div>
        <ul>
            <li id="dashboard">
                <a href="/dashboard">
                    <img src="/images/icon001.png" >
                    Karma Circle
                </a>
            </li>
            <li id="karmameeting" >
                @if (Auth::check())
                  <span class="rank_notification" id='notification_karmameeting'>0</span>        
                @endif  
                <a href="/KarmaMeetings">
                    <img src="/images/icon003.png">
                    Karma Meetings
                </a>
            </li>
            <li id="karmanote">
                @if (Auth::check())
                      <span class="rank_notification" id='notification_karmanote'>0</span>        
                @endif              
                <a href="/KarmaNotes">
                    <img src="/images/icon002.png" >
                    Karma Notes
                </a>
            </li>
            <li id="karmaIntro">
                <a href="/karma-intro">
                    <img src="/images/icon004.png">
                    Karma Intro
                </a>
            </li>
            <li id="karmaevent">
                <a href="/karma-queries"> 
                    <img src="/images/icon0022.png">
                    Karma Queries
                </a>
            </li>
        </ul>
    </div>
     
</section>
@else
<section class="mainWidth">

       <!-- Search Panel -->
        <div class="col-md-10 col-sm-11 col-xs-11 centralize searchPanel pdding0">
            <div class="col-xs-1 searcati">
               <span id="displayIcon" class="glyphicon glyphicon-align-user"></span>
               <i class="glyphicon glyphicon-chevron-down"></i>
            </div>
            {{ Form::open(array('url' => 'searchUsers' , 'method' => 'get','onsubmit'=>'return validateSearch()')) }}
            <div class="col-sm-10 col-xs-9">
                 {{ Form::text('searchUser',$searchVal,array('class'=>'form-control searchBox','placeholder' => 'Search for a name', 'id'=> 'searchKeyword', 'autocomplete'=>'off')); }}
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
                <!-- <li onclick="searchOption('All')"><a href=""><span class="glyphicon glyphicon-align-justify"></span> All</a></li> -->
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
        <!-- Search Panel -->
    {{-- <div class=" col-md-10 centralize nav">
        <div class="mobMenu">            
            <a href="/dashboard">Back to Karma Circle</a>
        </div>
        <div>&nbsp;
        </div>
    </div> --}}
  <div class="modal" style="display:none" id="header"> 
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button aria-label="Close" onclick="modelClose('header');" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Please sign in!</h4>
          </div>
          <div class="modal-body">
            <p>You need to be signed in to perform this action. Please sign in using Linkedin.</p>
          </div>
          <div class="modal-footer">
            <a href="/dashboard"><button data-dismiss="modal" class="btn btn-default linkfullBTN newBluBtn pull-right" type="button">Sign in with Linkedin</button></a>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog --> 
    </div>
</section>
@endif

<script type="text/javascript">
    var timer = null;
    $('#searchKeyword').keydown(function(){
           clearTimeout(timer); 
           timer = setTimeout(callmeSearch, 200)
            
    });

   var xhr = null;
   function callmeSearch(){ 
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
                    $('div#searchresult').focus();
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
<?php if(Auth::check()){?>
<script type="text/javascript">
$(document).ready(function(){  
  var   authCheck = <?php if(Auth::check()){ echo "1";}else{echo "0";}?>;
    if(authCheck == '1'){
        var notification_karmanote = <?php echo KarmaHelper::UnreadKarmaNote();?>;
        var notification_karmameeting = <?php echo KarmaHelper::UnreadMeetingRequest();?>;
    $("#notification_karmanote").html(notification_karmanote);
    $("#notification_karmameeting").html(notification_karmameeting);
      }
     
    });

</script>
<?php } ?>
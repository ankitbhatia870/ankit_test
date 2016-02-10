<?php $searchVal = ''; $searchOption = 'People' ;
      $CurrentUser = Auth::user();
    if(isset($_GET['searchUser'])) $searchVal = $_GET['searchUser']; 
    if(isset($_GET['searchOption'])) $searchOption = $_GET['searchOption']; 
?>
<header>
    <div class="mainWidth">
        <div class="col-sm-4 col-xs-12 logo">
            <a href="/dashboard"><img src="/images/logo1.png"></a>
        </div>
        <div class="col-sm-4 col-xs-12 pull-right headlink">
            @if (Auth::check())
                <ul>
                @if(Auth::user()->role == 'admin')
                    <li><a href="/admin/dashboard">Admin Panel </a></li>
                     <li>|</li>
                @endif
                <li><a href="/profile/<?php echo strtolower($CurrentUser->fname.'-'.$CurrentUser->lname);?>/{{$CurrentUser->id}}">Welcome {{$CurrentUser->fname;}}</a></li>
                <li>|</li>
                <li><a href="/logout">Logout</a></li>    
                </ul> 
            @else
                <ul>
                <li>Welcome</a></li>
                <li>|</li>
                <li><a href="/index">Login</a></li>    
                </ul> 
            @endif
   
        </div>
        <div class="clr"></div>
    </div>
</header>
<!-- /Header -->
<!-- Main colom -->
@if (Auth::check())
    <section class="mainWidth">


    <div class=" col-md-10 centralize nav">
        <div class="mobMenu">
            <img src="/images/navIcon.png" alt="">
            <a href="dashboard">Back to Karma Circle</a>
        </div>
        <ul>
            <li id="AdminDashboard">
                <a href="/admin/dashboard">
                    <img src="/images/icon001.png" >
                   Admin Dashboard
                </a>
            </li>
            <li id="UserManagement" >
                <a href="/admin/manageUser">
                    <img src="/images/icon002.png" >
                   User Management
                </a>
            </li>
            <li id=" GroupManagement">
                <a href="/admin/manageGroup">
                    <img src="/images/icon003.png" >
                    Group Management
                </a>
            </li>
            <li id="karmameeting" >
                <a href="/admin/manageVanityUrls">
                    <img src="/images/icon004.png">
                   Vanity Url Management
                </a>
            </li>
            <li id="karmaIntro">
                 
                <a href="/admin/report">
                    <img src="/images/icon005.png">
                   Report
                </a>
            </li>
             <li id="karmaevent">
                <a href="/admin/managequeries">
                    <img src="/images/icon004.png">
                    Query Managment
                </a>
            </li>
        </ul>
    </div>
</section>
@else
<section class="mainWidth">

    <div class=" col-md-10 centralize nav">
        <div class="mobMenu">
            <img src="/images/navIcon.png" alt="">
            <a href="dashboard">Back to Karma Circle</a>
        </div>
        <div>&nbsp;
        </div>
    </div>

</section>
@endif

<script type="text/javascript">
    function searchResult(){
        var keyword = $('#searchKeyword').val();
        var optionVal = $('#searchOption').val();
        $('div#searchresult').hide();
       // alert(keyword+'---'+optionVal);
        if(optionVal == 'People'){
           var url='<?php echo URL::to('/');?>/ajaxsearchuser?searchUsers='+keyword;
            //alert(url);
                $.get(url,function(data) {
                if(data==""){
                    $('#searchresult').html('');
                }
                else{
                    $('div#searchresult').show();
                    $("div#searchresult").html(data);
                }
            });    
        }else{
          return false;
        }
    }

</script>
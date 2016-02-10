@extends('common.basic')
@section('body')
 <header>
 	 <div class="mainWidth">
 	<div class="col-sm-4 col-xs-12 logo">
            <img src="/images/logo1.png">
    </div>
     <div class="col-sm-4 col-xs-12 pull-right headlink">
            @if (Auth::check())
                <ul>
                <li><a href="">Welcome {{$CurrentUser->fname;}}</a></li>
                <li>|</li>
                <li><a href="/logout">Logout</a></li>    
                </ul> 
            @else
                <ul>
                <li><a href="">Welcome</a></li>
                <li>|</li>
                <li><a href="/index">Login</a></li>    
                </ul> 
            @endif
   
        </div>
    <div class="clr"></div>
    </div>
 </header>
 <section class="mainWidth">
 	<div class="errorPage" >
    <img src="/images/logo_notification.png">
    <div class='errorText'>
        <p>Your KarmaCircles account is currently pending approval.</p>
        <p>If you have any question, please contact</p>
        <a href="mailto:help@karmacircles.com">help@karmacircles.com</a>
    </div>
    </div>
 </section>


@include('common.footer')
    <!-- Footer/ -->
@stop
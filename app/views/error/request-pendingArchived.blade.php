@extends('common.masternomenu')
@section('content')
 <section class="mainWidth">
 	<div class="errorPage" >
    <img src="/images/logo_notification.png">
    <div class='errorText'>
        <p>You have already sent a meeting request to {{$name}}. Please wait to hear back from them. If it has been a while, please search for another KarmaGiver and request a meeting from them.</p>
    </div>
    </div>
 </section> 
@stop

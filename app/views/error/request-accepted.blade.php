@extends('common.masternomenu')
@section('content')
 <section class="mainWidth">
 	<div class="errorPage" >
    <img src="/images/logo_notification.png">
    <div class='errorText'>
        <p>Your last meeting request was accepted by {{$name}}. Please write a KarmaNote for them by going to <a href="{{$url}}">KarmaNotes</a>. You will then be allowed to request them for another meeting.</p>
    </div>
    </div>
 </section> 
@stop

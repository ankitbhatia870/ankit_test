@extends('common.master')
@section('content')
	 {{ Form::hidden('pageIndex','karmaIntro',array('class'=>'pageIndex')); }}
	<section class="mainWidth profilepage clearfix">
		<div class="col-md-10 centralize">
			<div class="errorPage" >
				<img src="/images/logo_notification.png">
				<div class='errorText'>
					<p>KarmaIntro feature will be availabe soon.</p>
					<p>If you would like to know more about this feature,please contact</p>
					<a href="mailto:help@karmacircles.com">help@karmacircles.com</a>
				</div>
			</div>
		</div>
	</section>
 <!-- /Main colom -->
@stop
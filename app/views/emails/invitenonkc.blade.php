<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">   
    <meta name="author" content="Mukul Kant">
    <meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">
    <link rel="shortcut icon" href="{{URL::to('/')}}/images/favicon.png">
    <title>Welcome to Karma Circle</title>
    <link href="{{URL::to('/')}}/css/font.css" rel="stylesheet">
</head>
<body>
	<div style="width:640px;margin:50px auto 0;border:1px solid #e9e9e9;background:#f4f4f4;color:#626262;padding:0 5px 5px;font-family:arial;font-size:13px">
	<!-- Header -->
		<table width="100%" style="text-align:left;padding:8px 10px;color: #626262;border-bottom:1px solid #ccc;margin-bottom:5px">
            <tbody>
            	<tr>
                <th style="color:#626262;font-weight:normal">
                	<a href="{{URL::to('/')}}" target="_blank">
                	<img src="{{URL::to('/')}}/images/logo.png" style="max-width:230px;display:block">
                </a>
                </th>
                <th style="color:#626262;font-size: 14px;text-align: center;">
                	 Invitation from {{$receiverDetail->fname." ".$receiverDetail->lname}} to join KarmaCircles
                	 </th>
	                </th>
	            </tr>
	        </tbody>
        </table>
        <!-- /Header -->

		<div style="width:280px;background:#fff;border:1px solid #ccc;margin:0 auto;padding:5px">
			<div style="width:90px;float:left">
				@if ($receiverDetail->piclink == '')
					<img  height="87px" width="82px" alt="" src="{{URL::to('/')}}/images/default.png">
				@else
					<img height="87px" width="82px" alt="" src="{{$receiverDetail->piclink}}">
				@endif 		
			</div>
			<div>
				<h4 style="color: #38bb95;font-size: 16px;font-weight: normal;margin: 0 0 4px;">{{$receiverDetail->fname." ".$receiverDetail->lname}}</h4>
				<p style="margin:0 0 6px">{{ $receiverDetail->headline;}}</p>
				<p style="margin:0 0 6px">{{ $receiverDetail->location;}}</p>
			</div>
			<div style="clear:both"></div>
			<div class="borderPic">
				<ul style="padding:0">
					<li style="list-style:none;float:left;margin-right:4px">
						<a target="_blank" href="{{$receiverDetail->linkedinurl}}"><img src="{{URL::to('/')}}/images/linkdin.png" alt="" height="20px"; width="20px"></a>
					</li>
					<li style="list-style:none;float:left;margin-right:4px;position:relative">
						<a href="{{URL::to('/')}}/profile/<?php echo strtolower($receiverDetail->fname.'-'.$receiverDetail->lname).'/'.$receiverDetail->id ;?>"><img src="{{URL::to('/')}}/images/krmaicon.png" height="20px" width="20px" alt=""></a>
						
					</li>
				</ul>
			</div>
			<div style="clear:both"></div>
		</div>
		 	<hr style="height:1px;border:none;border-bottom:1px solid #898989;margin:10px 10%;">
			<p style="width:380px;margin:0 auto;color: #626262; font-weight: bold;padding-left: 85px;">
				Subject: <span style="font-weight: normal;"> {{$subject}} <span>
			</p> 
			<p style="width:380px;margin:0 auto;padding-left: 85px; ">
				{{$Note}} 
			</p> 
			
			<hr style="height:1px;border:none;border-bottom:1px solid #898989;margin:10px 10%;">
			  
			<div style="width:380px;margin:0 auto; text-align:center; "> 
                       <a href="{{$url}}" style="text-decoration:none" target="blank"><input type="submit" name="" value="Visit KarmaCircles" style="background:#39bb95;color:#fff;font-size:15px;border:none;border-radius:6px;height:38px;width:170px;box-shadow:3px 3px 0px #6acdb1;margin:10px 0 10px 0;cursor:pointer"></a>
            </div>   
	</div>	
</body>
</html>	

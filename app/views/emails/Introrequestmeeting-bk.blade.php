<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">   
    <meta name="author" content="Mukul Kant">
    <link rel="shortcut icon" href="{{URL::to('/')}}/images/favicon.png">
    <title>Welcome to Karma Circle</title>
    <link href="{{URL::to('/')}}/css/font.css" rel="stylesheet">
  </head>
<body>
    <div style="width:650px;margin:50px auto 0;border:1px solid #e9e9e9; ;font-weight:normal;background:#f4f4f4;color:#626262">
        <table width="100%" style="text-align:left;padding:8px 10px;color: #626262;">
            <tr >
                <th style="font-weight:normal">
                  <a href="{{URL::to('/')}}" target="_blank">
                  <img src="{{URL::to('/')}}/images/logo.png" style="max-width:230px;display:block;color: #626262;">
                </a>
                </th>
                <th style="color: #626262;font-size: 14px;text-align: center;">You have received a Karma Intro request from {{$introducerDetail->fname." ".$introducerDetail->lname}}</th>
              <!--   <th style="font-weight:normal; font-family:'ubuntuReg';font-size:15px"></th> -->
            </tr>
        </table>
         <div style="color: #626262;background:#fff;padding-top:20px">
            <table width="100%">
                <tr>
                    <td width="20%" style="color: #626262;vertical-align:top;" align="center">
            						@if ($receiverDetail->piclink == '')
            							<img  height="87px" width="82px" alt="" src="{{URL::to('/')}}/images/default.png">
            						@else
            							<img height="87px" width="82px" alt="" src="{{$receiverDetail->piclink}}">
            						@endif 
                        <br style="clear:both"/>  
                            <span style="float: left;color: #626262;padding:0 0 0 33px"><a href=" {{$receiverDetail->linkedinurl}}"><img style="width:22px;height:22px" src="{{URL::to('/')}}/images/linkdin.png"></a></span>
                            <span style="float: left;color: #626262;padding:0 0 0 2px"><a href="{{URL::to('/')}}/profile/<?php echo strtolower($receiverDetail->fname.'-'.$receiverDetail->lname).'/'.$receiverDetail->id ;?>"><img src="{{URL::to('/')}}/images/krmaicon.png" style="width:22px;height:22px"></a></span>
							 
					</td>
                    <td width="80%">
                       <h4 style="color: #626262;padding:0;margin:0 0 4px;">Request Sent by {{$receiverDetail->fname." ".$receiverDetail->lname}}  (intro via {{$introducerDetail->fname}}) </h4>  
                       <p style="color: #626262;margin:0 0 4px">{{ $receiverDetail->headline;}}</p>
                       <p style="color: #626262;margin:0 0 10px;display: inline-block;">{{ $receiverDetail->location;}}</p>
                       <p><span style="color: #626262;font-weight: 600;">{{$subject}}:</span><br>
                       {{$Content}} </p>
                       <a href="{{$url}}"><input type="submit" name="" value="View Details" style="background:#39bb95;color:#fff;font-size:15px;border:none;border-radius:6px;height:38px;width:170px;box-shadow:3px 3px 0px #6acdb1;margin:10px 0 10px 0;cursor:pointer"></a>
                    </td>
                </tr>
            </table>

         </div>
    </div>
</body>
</html>
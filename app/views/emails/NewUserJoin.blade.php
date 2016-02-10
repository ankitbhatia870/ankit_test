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
    <div style="width:650px;margin:50px auto 0;border:1px solid #e9e9e9; font-weight:normal;background:#f4f4f4;color:#626262">
        <table width="100%" style="text-align:left;padding:8px 10px;color:#626262;">
            <tr >
                <th style="font-weight:normal;color:#626262;">
                  <a href="{{URL::to('/')}}" target="_blank">
                  <img src="{{URL::to('/')}}/images/logo.png" style="max-width:230px;display:block">
                </a>
                </th>
                <th style="font-size: 14px;text-align: center;color:#626262;">{{$userDetail->fname." ".$userDetail->lname}} created account on KarmaCircles</th>
                <th style="font-weight:normal; font-size:15px;color:#626262;"></th>
            </tr>
        </table>
         <div style="background:#fff;padding-top:20px;color:#626262;">
            <table width="100%">
                <tr>
                    <td width="80%">
                      <!--  <h4 style="font-family: 'Ubuntubold';padding:0;margin:0 0 4px;">Account Activation</h4>   -->
                       <p style="color:#626262;">{{$Content}}</p>	
                    </td>
                </tr>
            </table>
         </div>
    </div>
</body>
</html>
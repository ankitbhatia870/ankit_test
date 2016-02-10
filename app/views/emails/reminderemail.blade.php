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

    <div style="width:650px;margin:50px auto 0;border:1px solid #e9e9e9;font-weight:normal;background:#f4f4f4;color:#626262">
        <table width="100%" style="color:#626262;text-align:left;padding:8px 10px;">
            <tr >
                <th style="color:#626262;font-weight:normal">
                  <a href="{{URL::to('/')}}" target="_blank">
                  <img src="{{URL::to('/')}}/images/logo.png" style="max-width:230px;display:block">
                </a>
                </th>
                <th style="color:#626262;font-size: 14px;text-align: center;">Gentle reminder to send KarmaNote</th>
            </tr>
        </table>
         <div style="color:#626262;background:#fff;padding-top:20px">
            <table width="100%">
                <tr>
                    <td width="80%">
                       <h4 style="color:#626262;padding:0;margin:0 0 4px;font-weight:bold;">Please send KarmaNote for {{ucfirst($giverDetail->fname)." ".ucfirst($giverDetail->lname)}}!</h4>  
                       <p style="color:#626262;">{{$Content}}</p>
                       <p style="color:#626262;">{{ucfirst($giverDetail->fname)." ".ucfirst($giverDetail->lname)}} took time out of their busy schedule for the KarmaMeeting. Please consider sending them a KarmaNote (message of appreciation) for helping you.</p>

                       <p style="color:#626262;">It takes 30 seconds to write a KarmaNote and goes a long way to build your credibility with them. You also get 2 Karma points for sending a KarmaNote.</p>

                      <p style="color:#626262;">
                      If you want some inspiration, you can see a sample KarmaNote at <br>
                      <a href="http://www.karmacircles.com/meeting/Kelly-Sherman-Deepak-Goel/28" target="
                      _blank">http://www.karmacircles.com/meeting/Kelly-Sherman-Deepak-Goel/28</a>
                    </p>
                      <p style="color:#626262;">Thank you!</p>
                      <p style="color:#626262;">KarmaCircles Team</p>
                       <a href="{{$url}}" style="text-decoration:none;"><input type="submit" name="" value="Send KarmaNote" style=" background:#39bb95;color:#fff;font-size:15px;border:none;border-radius:6px;height:38px;width:170px;box-shadow:3px 3px 0px #6acdb1;margin:10px 0 10px 0;cursor:pointer"></a>
                    </td>
                </tr>
            </table>
         </div>
    </div>
</body>
</html>
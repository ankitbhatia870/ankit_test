<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">   
    <meta name="author" content="Mukul Kant">
    <link rel="shortcut icon" href="images/favicon.png">
    <title>Welcome to Karma Circle</title>
    <link href="css/font.css" rel="stylesheet">
  </head>

<body style="font-family: 'ubuntuReg';">

<?php 
//echo"<pre>+++++";print_r($getsuggestion);echo"</pre>++++++";die;
//echo"<pre>+++++";print_r($getKcuser->fname);echo"</pre>++++++"; die;
//echo"<pre>+++++";print_r($html);echo"</pre>++++++"; die;
 ?>

    <div style="width:860px;margin:50px auto 0;border:1px solid #e9e9e9; font-family:'ubuntuReg';font-weight:normal;background:#fff;color:#626262">
        <table width="100%" style="text-align:left;padding:8px 10px;background:#f4f4f4">
            <tr style="font-family: 'Ubuntubold';">
                <th style="font-weight:normal">
                  <a href="{{URL::to('/')}}" target="_blank">
                  <img src="{{URL::to('/')}}/images/logo1.png" style="max-width:230px;display:block">
                </a>
                </th>
                <th style="font-weight:bold;font-size:17px">Karma Updates</th>
                <th style="font-weight:normal; font-family:'ubuntuReg';font-size:15px">Important Updates from KarmaCircles</th>
            </tr>
        </table>
         <div style="background:#fff;padding:20px;width:750px;margin:0px auto;">
            <table width="100%">
                @if($meetingIncomplete > 0 || $meetingComplete > 0 ||  $offeredHelp > 0 )
                <tr style="border-bottom:1px solid #000;display:block; font-family: 'ubuntuReg'">
                    <td>
                        <h4 style="color: #555555;font-family: 'Ubuntubold';font-size: 18px;">Your Updates</h4> 
                        <table width="100%:">
                            @if($meetingIncomplete > 0)
                              <tr style="margin-bottom:5px;display:block">
                                <td style="margin:0px;padding:0px;width:20px;" valign="middle">
                                  <img src="{{URL::to('/')}}/images/prof.png" alt="">
                                </td>
                                <td width="100%" align="left" valign="middle">
                                    
                                        <a target='_blank' style="text-decoration:none; color: #626262; font-family: ubuntuReg;font-weight: normal;font-size: 16px;" href="{{URL::to('/')}}/KarmaMeetings"><p style="margin:0px;margin-left:10px; font-family: 'ubuntuReg';font-size: 14px;">You have updates on  {{$meetingIncomplete;}}  KarmaMeetings.</p></a>
                                   
                                </td>
                                </tr>
                            @endif

                            @if($meetingComplete > 0)
                              <tr style="margin-bottom:5px;display:block">
                                <td>
                                  <span><img src="{{URL::to('/')}}/images/icon002.png" alt=""></span>
                                </td>
                                <td>
                                   <a target='_blank' style="text-decoration:none; color: #626262; font-family: ubuntuReg;font-weight: normal;font-size: 14px;" href="{{URL::to('/')}}/KarmaNotes"><p style="margin:0px;margin-left:10px;font-family: 'ubuntuReg';font-size: 14px; "> You have received  {{$meetingComplete}}  new KarmaNotes.</p></a>
                                </td>
                              </tr>
                            @endif

                            @if($offeredHelp > 0)
                              <tr style="margin-bottom:5px;display:block">
                                <td>
                                  <span><img src="{{URL::to('/')}}/images/icon003.png" alt=""></span>
                                </td>
                                <td>
                                  <a target='_blank' style="text-decoration:none; color: #626262; font-family: ubuntuReg;font-weight: normal;font-size: 14px;" href="{{URL::to('/')}}/karma-queries"><p style="margin:0px;margin-left:10px; "><p style="margin:0px;margin-left:10px;font-size: 14px;font-family: 'ubuntuReg'">New users have offered help on your queries.</p></a>
                                </td>
                              </tr>
                            @endif
                        </table>
                    </td>
                </tr>
                @endif
  
                
                 @if(!empty($getKcuser))    
                 <tr style="border-bottom:1px solid #000;display:block">
                    <td style="width:100%;">
                        <h4 style="color: #555555;font-family: 'Ubuntubold';font-size: 18px;">Suggestion</h4>
                        <table width="100%">
                          <tr>
                            <td><p style="margin:0px;font-family: 'ubuntuReg';font-size: 16px;">
                             {{$getKcuser->fname.' '.$getKcuser->lname }} is on KarmaCircles. Consider sending them a meeting request today.
                             </p></td>
                          </tr>
                          <tr style="display:block;margin-bottom:30px;">
                            <td>
                              <div style="background:#F4F4F4;border:1px solid #a2a2a2;margin-top:10px;width:92%;padding:30px;">
                                <table>
                                  <tr>
                                    <td width="50%;" align="left">
                                      <table style="width:350px;padding:15px 15px 10px;background:#fff;border:1px solid #A2A2A2;">
                                       <tr>
                                         <td valign="top" style="width:30%;">
                                           <!-- <img src="images/ivan.png" alt=""> --> 
                                              @if ($getKcuser->piclink == "" || $getKcuser->piclink == 'null')
                                              <a  href="{{URL::to('/')}}/profile/<?php echo strtolower($getKcuser->fname.'-'.$getKcuser->lname).'/'.$getKcuser->id ;?>"><img alt="" src="{{URL::to('/')}}/images/default.png" width="82" height="87"></a>
                                              @else
                                              <a  href="{{URL::to('/')}}/profile/<?php echo strtolower($getKcuser->fname.'-'.$getKcuser->lname).'/'.$getKcuser->id ;?>"><img src="{{$getKcuser->piclink}}" width="82" height="87"></a>
                                              @endif
                        
                        <a target='_blank' href="{{$getKcuser->linkedinurl}}" ><img src="{{URL::to('/')}}/images/in.png" alt=""></a> 
                      
                        <a  href="{{URL::to('/')}}/profile/<?php echo strtolower($getKcuser->fname.'-'.$getKcuser->lname).'/'.$getKcuser->id ;?>"><img alt="" src="{{URL::to('/')}}/images/krmaicon.png"></a> 
                        
                        
                                         </td>
                                         <td valign="top" style="width:70%;">
                                           <h4 style="margin:0px;margin-top:5px;color:#37bb94;font-family: 'ubuntuReg';font-size: 16px;">{{$getKcuser->fname.' '.$getKcuser->lname }}</h4>
                                           <p style="margin:0px;font-size:14px;font-family: 'ubuntuReg'">{{KarmaHelper::stringCut($getKcuser->headline,80)}}</p>
                                         
                                           <p style="margin:0px;font-size:14px;margin-top:10px;">{{$getKcuser->location}}</p>

                                          

                                         </td>
                                       </tr>
                                      </table>
                                    </td>  
                                    <td width="50%;" align="right">
                                      <table align="right" width="250px;">
                                       <tr>
                                         <td valign="top">

                                          

                                           <a target='_blank' href="{{URL::to('/')}}/CreateKarmaMeeting/{{$getKcuser->id}}">
                                                <button style="border:none;background:#39bb95;border-radius:5px;margin-bottom:10px;vertical-align:middle;font-size:16px;color:#fff;cursor:pointer;width:85%;font-family: 'ubuntuReg'"><img src="{{URL::to('/')}}/images/icon003w.png" alt="" style="margin-right:8px; vertical-align:middle;padding:5px 8px;">Request Meeting</button>
                                            </a>

                                          <a target='_blank' href="{{URL::to('/')}}/SendDirectkarmaNote/Karma/{{$getKcuser->id}}">
                                                <button style="border:none;background:#39bb95;border-radius:5px;margin-bottom:10px;vertical-align:middle;font-size:16px;color:#fff;cursor:pointer;width:85%;font-family: 'ubuntuReg'"><img src="{{URL::to('/')}}/images/send.png" alt="" style="margin-right:8px; vertical-align:middle;padding:5px 8px;">Send Karma Note</button>
                                            </a>
                                         </td>
                                       </tr>
                                      </table>
                                    </td>
                                  </tr>
                                </table>
                              </div>
                            </td>
                          </tr>
                        </table>
                    </td>
                </tr>
                @endif 
 
 


               

            </table>
         </div>
    </div>
</body>
</html>
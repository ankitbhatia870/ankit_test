
@extends('common.master')
@section('content')
     {{ Form::hidden('pageIndex','karmaqueries',array('class'=>'pageIndex')); }}
     {{ Form::hidden('currentTab','myquestion',array('class'=>'currentTab')); }}
<?php  //echo $CurrentUser->id ;
//echo"<pre>";print_r($allquestion);echo"</pre>";
//echo"<pre>";print_r($myquestion);echo"</pre>";
 ?>
    <section class="mainWidth">
        <div class="col-md-10 centralize profilepage pdding0 clearfix note">
             <div class="backlink clearfix subTopLink">
                
                <!-- <p class="pull-right">
                    <a href="/dashboard">Back to Karma Circle</a>
                </p> -->

                <p class="pull-left grnBtn">
                      <a href="/karma-queries/initiatequery">Post a Query</a>
                </p>

            </div>
            <?php 
               @$valArray = array();
               foreach ($myquestion as $key => $question) {
                  $valArray[] = $question->user_id->email;
               }
           
                if(!empty($group_question)) {
                  $valueGroupQuestion = 1;
                } else {
                  $valueGroupQuestion = 0;
                } 
                if(!empty($valArray)) {
                  ?>
                  <input type="hidden" value="1" id="primaryValue">
                  <?php 
                  $valueGroupQuestion1 = 1;
                } else {
                  $valueGroupQuestion1 = 0;
                }
                
                if(!empty($allquestion)) {
                  $valueGroupQuestion2 = 1;
                } else {
                  $valueGroupQuestion2 = 0;
                } 
            ?>
                <div class="tabbed">    
                <!-- Nav tabs -->
                    <ul class="nav nav-tabs" role="tablist">
                      <li class="active" onclick="changeTab('myquestion','<?php echo $valueGroupQuestion1; ?>')"><a href="#home" role="tab" data-toggle="tab">My Queries</a></li>
                      <li onclick="changeTab('groupquestion', '<?php echo $valueGroupQuestion; ?>')"><a href="#profile" role="tab" data-toggle="tab">KarmaCircles Queries</a></li>
                      <li onclick="changeTab('allQuestion','<?php echo $valueGroupQuestion2; ?>')"><a href="#messages" role="tab" data-toggle="tab">All Queries</a></li>
                    </ul>
                        <div class="backlink clearfix subTopLink" id="rightTabs">
                            <p class="pull-right tab3">
                                <a id="Recent" onclick="changeorder('Recent')" >Recent</a>
                                <a id="Populer" onclick="changeorder('Populer')" >Popular</a>
                                <a id="Unanswered" onclick="changeorder('Unanswered')" >Unanswered</a>
                            </p>
                        </div>
                    <!-- Tab panes --> 
                    <div class="tab-content">
                        <!--1st-->
                        <div class="tab-pane active question myquestion_div" id="home" >
                            <!-- MY QUESRTION -->
                            <?php $myqyes = 0;?>
                                @if (!empty($myquestion))
                                    @foreach ($myquestion as $question)
                                        <div class="questiionBlock clearfix">
                                            <div class="col-sm-1 newimgbox">
                                                @if($question->user_id->piclink != '')
                                                    @if (isset($question->user_id->email))
                                                    <a href="/profile/<?php echo strtolower($question->user_id->fname.'-'.$question->user_id->lname);?>/{{$question->user_id->id}}">
                                                    <img height='50' width='45' src="{{$question->user_id->piclink}}"></a>
                                                    @else
                                                        <img height='50' width='45' src="{{$question->user_id->piclink}}">
                                                    @endif
                                                @else
                                                    @if (isset($question->user_id->email))
                                                    <a href="/profile/<?php echo strtolower($question->user_id->fname.'-'.$question->user_id->lname);?>/{{$question->user_id->id}}">   
                                                    <img  height='50' width='45' src="/images/default.png">
                                                    </a>
                                                    @else
                                                       <img  height='50' width='45' src="/images/default.png">      
                                                    @endif
                                                @endif
                                                    <!-- List popup -->
                                                    <div class="noteBox tabtxt listpopUp w280">
                                                        <div class="col-xs-4">                                                        
                                                            @if ($question->user_id->piclink == "" || $question->user_id->piclink == 'null')
                                                                    @if (isset($question->user_id->email))
                                                                        <a href="/profile/<?php echo strtolower($question->user_id->fname.'-'.$question->user_id->lname);?>/{{$question->user_id->id}}">   
                                                                         <img  height='50' width='45' src="/images/default.png">
                                                                        </a>
                                                                    @else
                                                                        <img  height='50' width='45' src="/images/default.png">      
                                                                    @endif
                                                            @else
                                                                @if (isset($question->user_id->email))
                                                                <a href="/profile/<?php echo strtolower($question->user_id->fname.'-'.$question->user_id->lname);?>/{{$question->user_id->id}}">
                                                                <img height='50' width='45' src="{{$question->user_id->piclink}}"></a>
                                                                @else
                                                                <img height='50' width='45' src="{{$question->user_id->piclink}}">
                                                                @endif
                                                            @endif
                                                        </div>
                                                        <div class="col-sm-8 col-xs-7">
                                                            @if (isset($question->user_id->email))
                                                                <a href="/profile/<?php echo strtolower($question->user_id->fname.'-'.$question->user_id->lname);?>/{{$question->user_id->id}}"><h4>{{$question->user_id->fname." ".$question->user_id->lname}}</h4></a>
                                                            @else
                                                                <h4>{{$question->user_id->fname." ".$question->user_id->lname}}</h4>    
                                                            @endif
                                                            <p>{{KarmaHelper::stringCut($question->user_id->headline,80)}}</p>
                                                            <p>{{$question->user_id->location}}</p>
                                                        </div>
                                                        <div class="clr"></div>
                                                        <div class="borderPic">
                                                        <ul>
                                                            <li><a href="{{$question->user_id->linkedinurl}}" target="_blank"><img alt="" src="/images/linkdin.png"></a></li>
                                                            @if (isset($question->user_id->email))
                                                            <li>
                                                            <a href="/profile/<?php echo strtolower($question->user_id->fname.'-'.$question->user_id->lname);?>/{{$question->user_id->id}}"><img alt="" src="/images/krmaicon.png"></a>
                                                            <a href="<?php echo URL::to('/');?>/FAQs/KarmaPoints/1"><span>{{$question->user_id->karmascore}}</span></a>
                                                            </li>
                                                            @endif
                                                        </ul>
                                                        </div>
                                                        @if($question->user_id->id != $CurrentUser->id)
                                                        <a href="/CreateKarmaMeeting/{{$question->user_id->id}}"><button class="btn btn-success btnicon meeting smlBtnNew" type="button">Request Meeting</button></a>
                                                        @endif
                                                    </div>
                                                    <!-- List popup -->

                                            </div>

                                            <div class="col-sm-5"> 
                                                <a href="/question/{{$question->question_url}}/{{$question->id}}"><h4>{{$question->subject}}</h4></a>
                                                <div>
                                                    <span class="simTxt">{{date('F d, Y', strtotime($question->created_at))}}</span>
                                                    <ul class="tag">
                                                        @if($question->skills != '')
                                                            @foreach ($question->skills as $skills)
                                                             <a href="<?php echo URL::to('/').'/searchUsers?searchUser='.$skills->name.'&searchOption=Skills';?>"><li>{{$skills->name}}</li></a>
                                                            @endforeach  
                                                        @endif        
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                @if($question->queryStatus == 'open')   

                                                
                                                {{ Form::open(array('url' => 'closeQuestion' , 'method' => 'post','onsubmit'=>'return closecheck()')) }}
                                                {{Form::hidden('question_id', $question->id)}}                  
                                                {{Form::submit('Close',array('class'=>'btn btn-warning qrypag'));}}
                                                {{Form::close()}} 

                                                @elseif($question->queryStatus == 'closed')
                                                          <div  class="btn btn-success toggleBtn pending  btn-warning qrypag">Closed</div>
                                                @endif 
                                            </div>
                                            <div class="col-sm-3">
                                                <ul class="thumbIMGlist">
                                                    @if(!empty($question->giver_Info)) 
                                                        @foreach ($question->giver_Info as $giver_Info)
                                                            <li>
                                                                @if ($giver_Info->user_id->piclink == "" || $giver_Info->user_id->piclink == 'null')
                                                                    @if (isset($giver_Info->user_id->email))
                                                                        <a href="/profile/<?php echo strtolower($giver_Info->user_id->fname.'-'.$giver_Info->user_id->lname);?>/{{$giver_Info->user_id->id}}">    
                                                                            <img alt="" src="/images/default.png" >
                                                                        </a>
                                                                    @else
                                                                        <img alt="" src="/images/default.png" >
                                                                    @endif
                                                                @else
                                                                     @if (isset($giver_Info->user_id->email))
                                                                        <a href="/profile/<?php echo strtolower($giver_Info->user_id->fname.'-'.$giver_Info->user_id->lname);?>/{{$giver_Info->user_id->id}}">       
                                                                            <img src="{{$giver_Info->user_id->piclink}}" >
                                                                        </a>
                                                                    @else
                                                                         <img src="{{$giver_Info->user_id->piclink}}" >   
                                                                    @endif
                                                                @endif
                                                                <!-- List popup -->
                                                                <div class="noteBox tabtxt listpopUp">
                                                                    <div class="col-xs-4">                                  
                                                                        @if ($giver_Info->user_id->piclink == "" || $giver_Info->user_id->piclink == 'null')
                                                                            <img alt="" src="/images/default.png" >
                                                                        @else
                                                                            <img src="{{$giver_Info->user_id->piclink}}" >
                                                                        @endif
                                                                    </div>
                                                                    <div class="col-sm-8 col-xs-7">
                                                                        @if (isset($giver_Info->user_id->email))
                                                                            <a href="/profile/<?php echo strtolower($giver_Info->user_id->fname.'-'.$giver_Info->user_id->lname);?>/{{$giver_Info->user_id->id}}">   
                                                                                <h4>{{$giver_Info->user_id->fname." ".$giver_Info->user_id->lname}}</h4>
                                                                            </a>
                                                                            @else
                                                                                <h4>{{$giver_Info->user_id->fname." ".$giver_Info->user_id->lname}}</h4>
                                                                            @endif
                                                                        <p>{{KarmaHelper::stringCut($giver_Info->user_id->headline,80)}}</p>
                                                                        <p>{{$giver_Info->user_id->location}}</p>
                                                                    </div>
                                                                    <div class="clr"></div>
                                                                    <div class="borderPic">
                                                                    <ul>
                                                                        <li><a href="{{$giver_Info->user_id->linkedinurl}}" target="_blank"><img alt="" src="/images/linkdin.png"></a></li>
                                                                        @if (isset($giver_Info->user_id->email))
                                                                        <li>
                                                                        <a href="/profile/<?php echo strtolower($giver_Info->user_id->fname.'-'.$giver_Info->user_id->lname);?>/{{$giver_Info->user_id->id}}"><img alt="" src="/images/krmaicon.png"></a>
                                                                        <a href="<?php echo URL::to('/');?>/FAQs/KarmaPoints/1"><span>{{$giver_Info->user_id->karmascore}}</span></a>
                                                                        </li>
                                                                        @endif
                                                                    </ul>
                                                                    </div>
                                                                    @if($giver_Info->user_id->id != $CurrentUser->id)
                                                                    <a href="/CreateKarmaMeeting/{{$giver_Info->user_id->id}}"><button class="btn btn-success btnicon meeting smlBtnNew" type="button">Request Meeting</button></a>
                                                                    @endif
                                                                </div>
                                                                <!-- List popup --> 
                                                            </li>
                                                        @endforeach
                                                   
                                                    @endif   
                                                </ul>
                                            </div>
                                        </div>
                                        <?php $myqyes++;?>
                                @endforeach
                                 @else
                                                                         
                            @endif
                            @if($myqyes == 0)
                             <li>
                                        <div class="centerText">
                                                <p>No Query yet!</p>
                                        </div>
                                    </li>  
                            @endif

                            <!-- MY QUESRTION -->
                        </div>
                        <!--1st-->
                         <!--2nd-->
                        <div class="tab-pane" id="profile" >
                           
                            <div class="tab-pane active question groupquestion_div" id="home">
                           <!-- GROUP QUESRTION -->
                            @if (!empty($group_question))
                                @foreach ($group_question as $question)
                                    <div class="questiionBlock clearfix">
                                         <div class="col-sm-1 newimgbox">
                                            @if($question->user_id->piclink != '')
                                                @if (isset($question->user_id->email))
                                                    <a href="/profile/<?php echo strtolower($question->user_id->fname.'-'.$question->user_id->lname);?>/{{$question->user_id->id}}"><img height='50' width='45' src="{{$question->user_id->piclink}}">
                                                    </a>
                                                @else
                                                    <img height='50' width='45' src="{{$question->user_id->piclink}}">
                                                @endif 
                                            @else
                                                 @if (isset($question->user_id->email))
                                                    <a href="/profile/<?php echo strtolower($question->user_id->fname.'-'.$question->user_id->lname);?>/{{$question->user_id->id}}">
                                                        <img  height='50' width='45' src="/images/default.png">
                                                    </a>
                                                @else
                                                    <img  height='50' width='45' src="/images/default.png">
                                                @endif
                                            @endif
                                            <!-- List popup -->
                                                    <div class="noteBox tabtxt listpopUp w280">
                                                        <div class="col-xs-4">                                                        
                                                            @if ($question->user_id->piclink == "" || $question->user_id->piclink == 'null')
                                                                <img alt="" src="/images/default.png" >
                                                            @else
                                                                <img src="{{$question->user_id->piclink}}" >
                                                            @endif
                                                        </div>
                                                        <div class="col-sm-8 col-xs-7"> 
                                                            @if (isset($question->user_id->email))
                                                                <a href="/profile/<?php echo strtolower($question->user_id->fname.'-'.$question->user_id->lname);?>/{{$question->user_id->id}}">
                                                                    <h4>{{$question->user_id->fname." ".$question->user_id->lname}}</h4>
                                                                </a> 
                                                            @else

                                                                    <h4>{{$question->user_id->fname." ".$question->user_id->lname}}</h4>
                                                                   
                                                            @endif

                                                            <p>{{KarmaHelper::stringCut($question->user_id->headline,80)}}</p>
                                                            <p>{{$question->user_id->location}}</p>
                                                        </div>
                                                        <div class="clr"></div>
                                                        <div class="borderPic">
                                                        <ul>
                                                            <li><a href="{{$question->user_id->linkedinurl}}" target="_blank"><img alt="" src="/images/linkdin.png"></a></li> 
                                                            @if (isset($question->user_id->email))
                                                            <li>
                                                            <a href="/profile/<?php echo strtolower($question->user_id->fname.'-'.$question->user_id->lname);?>/{{$question->user_id->id}}"><img alt="" src="/images/krmaicon.png"></a> 
                                                            <a href="<?php echo URL::to('/');?>/FAQs/KarmaPoints/1"><span>{{$question->user_id->karmascore}}</span></a>
                                                            </li>
                                                            @endif
                                                        </ul>
                                                        </div>
                                                        @if($question->user_id->id != $CurrentUser->id)
                                                        <a href="/CreateKarmaMeeting/{{$question->user_id->id}}"><button class="btn btn-success btnicon meeting smlBtnNew" type="button">Request Meeting</button></a>
                                                        @endif
                                                    </div>
                                                    <!-- List popup -->
                                         </div>
                                        <div class="col-sm-6">
                                            
                                              <a href="/question/{{$question->question_url}}/{{$question->id}}"><h4>{{$question->subject}}</h4></a>
                                            <div>
                                                <span class="simTxt">{{date('F d, Y', strtotime($question->created_at))}}</span>
                                                <ul class="tag">
                                                    @if($question->skills != '')
                                                        @foreach ($question->skills as $skills)
                                                            <a href="<?php echo URL::to('/').'/searchUsers?searchUser='.$skills->name.'&searchOption=Skills';?>"><li>{{$skills->name}}</li></a>
                                                            @endforeach  
                                                    @endif      
                                                </ul>
                                            </div>
                                        </div>
                                        
                                        <div class="col-sm-2" id="group_helpbutton_{{$question->id}}">

                                          @if(!empty($question->giver_Info))
                                        @foreach ($question->giver_Info as $giver_Info)
                                            @if($giver_Info->user_id->id == $CurrentUser->id && $question->queryStatus != 'closed') 
                                                <div class="newimgbox" id="midhelpimg"> 
                                                     @if($giver_Info->user_id->piclink != '')
                                                        @if (isset($giver_Info->user_id->email))
                                                            <a href="/profile/<?php echo strtolower($giver_Info->user_id->fname.'-'.$giver_Info->user_id->lname);?>/{{$giver_Info->user_id->id}}"><img height='50' width='45' src="{{$giver_Info->user_id->piclink}}"></a>
                                                        @else
                                                            <img height='50' width='45' src="{{$giver_Info->user_id->piclink}}">
                                                        @endif

                                                    @else
                                                        @if (isset($giver_Info->user_id->email))
                                                            <a href="/profile/<?php echo strtolower($giver_Info->user_id->fname.'-'.$giver_Info->user_id->lname);?>/{{$giver_Info->user_id->id}}"> <img  height='50' width='45' src="/images/default.png"></a>
                                                        @else
                                                           <img  height='50' width='45' src="/images/default.png">     
                                                        @endif

                                                    @endif
                                                    <!-- List popup -->
                                                                <div class="noteBox tabtxt listpopUp w280">
                                                                    <div class="col-xs-4">                        
                                                                        @if ($giver_Info->user_id->piclink == "" || $giver_Info->user_id->piclink == 'null')
                                                                             @if (isset($giver_Info->user_id->email))
                                                                                <a href="/profile/{{$giver_Info->user_id->id}}/{{$giver_Info->user_id->fname}}-{{$giver_Info->user_id->lname}}"><img alt="" src="/images/default.png" ></a>
                                                                            @else
                                                                                <img alt="" src="/images/default.png" >
                                                                            @endif

                                                                        @else
                                                                          @if (isset($giver_Info->user_id->email))  
                                                                            <a href="/profile/<?php echo strtolower($giver_Info->user_id->fname.'-'.$giver_Info->user_id->lname);?>/{{$giver_Info->user_id->id}}">  <img src="{{$giver_Info->user_id->piclink}}" ></a>
                                                                            @else
                                                                                <img src="{{$giver_Info->user_id->piclink}}" >   
                                                                            @endif
                                                                        @endif
                                                                    </div>
                                                                    <div class="col-sm-8 col-xs-7">
                                                                        @if (isset($giver_Info->user_id->email))  
                                                                            <a href="/profile/<?php echo strtolower($giver_Info->user_id->fname.'-'.$giver_Info->user_id->lname);?>/{{$giver_Info->user_id->id}}"><h4>{{$giver_Info->user_id->fname." ".$giver_Info->user_id->lname}}</h4></a>
                                                                        @else
                                                                            <h4>{{$giver_Info->user_id->fname." ".$giver_Info->user_id->lname}}</h4>
                                                                        @endif
                                                                        <p>{{KarmaHelper::stringCut($giver_Info->user_id->headline,80)}}</p>
                                                                        <p>{{$giver_Info->user_id->location}}</p>
                                                                    </div> 
                                                                    <div class="clr"></div>
                                                                    <div class="borderPic">
                                                                    <ul>
                                                                        <li><a href="{{$giver_Info->user_id->linkedinurl}}" target="_blank"><img alt="" src="/images/linkdin.png"></a></li>
                                                                        @if (isset($giver_Info->user_id->email))
                                                                        <li>
                                                                        <a href="/profile/<?php echo strtolower($giver_Info->user_id->fname.'-'.$giver_Info->user_id->lname);?>/{{$giver_Info->user_id->id}}"><img alt="" src="/images/krmaicon.png"></a>
                                                                        <a href="<?php echo URL::to('/');?>/FAQs/KarmaPoints/1"><span>{{$giver_Info->user_id->karmascore}}</span></a>
                                                                        </li>
                                                                        @endif
                                                                    </ul>
                                                                    </div> 
                                                                    @if($giver_Info->user_id->id != $CurrentUser->id)
                                                                    <a href="/CreateKarmaMeeting/{{$giver_Info->user_id->id}}"><button class="btn btn-success btnicon meeting smlBtnNew" type="button">Request Meeting</button></a>
                                                                    @endif
                                                                </div>
                                                                <!-- List popup -->
                                        </div>     
                                            @endif
                                        @endforeach
                                    @endif
                                   
                                        @if($question->answered == '0' && $question->queryStatus == 'open')
                                            <div  class="btn btn-success minBtn" id="group_query_help_{{$question->id}}" onclick="getHelperImage({{$question->id}},'group');" >Help</div>
                                        @elseif($question->queryStatus == 'closed')
                                            <div  class=" btn btn-success toggleBtn pending  btn-warning qrypag">Closed</div>
                                        @endif   
                                       
            
                                        </div>
                                        <div id="group_helper_{{$question->id}}">
                                        </div>
                                        
                                          <div class="col-sm-3" id="group_query_help_pic_{{$question->id}}" >
                                      
                                            <ul class="thumbIMGlist" id="groupquestion_{{$question->id}}">
                                                @if(!empty($question->giver_Info))
                                                    @foreach ($question->giver_Info as $giver_Info)
                                                        @if($giver_Info->user_id->id != $CurrentUser->id)
                                                        <li>
                                                            @if ($giver_Info->user_id->piclink == "" || $giver_Info->user_id->piclink == 'null')
                                                                @if (isset($giver_Info->user_id->email))
                                                                   <a href="/profile/<?php echo strtolower($giver_Info->user_id->fname.'-'.$giver_Info->user_id->lname);?>/{{$giver_Info->user_id->id}}"> <img alt="" src="/images/default.png" ></a>
                                                                @else
                                                                    <img alt="" src="/images/default.png" >
                                                                @endif
                                                                
                                                            @else
                                                                @if (isset($giver_Info->user_id->email))
                                                                   <a href="/profile/<?php echo strtolower($giver_Info->user_id->fname.'-'.$giver_Info->user_id->lname);?>/{{$giver_Info->user_id->id}}"> 
                                                                <img src="{{$giver_Info->user_id->piclink}}" ></a>
                                                                @else
                                                                    <img src="{{$giver_Info->user_id->piclink}}" >
                                                                @endif
                                                            @endif
                                                            <!-- List popup -->
                                                            <div class="noteBox tabtxt listpopUp">
                                                                <div class="col-xs-4">                                                        
                                                                    @if ($giver_Info->user_id->piclink == "" || $giver_Info->user_id->piclink == 'null')
                                                                        @if (isset($giver_Info->user_id->email))
                                                                   <a href="/profile/<?php echo strtolower($giver_Info->user_id->fname.'-'.$giver_Info->user_id->lname);?>/{{$giver_Info->user_id->id}}"> 
                                                                        <img alt="" src="/images/default.png" ></a>
                                                                        @else
                                                                            <img alt="" src="/images/default.png" >
                                                                        @endif
                                                                    @else
                                                                     @if (isset($giver_Info->user_id->email))
                                                                   <a href="/profile/<?php echo strtolower($giver_Info->user_id->fname.'-'.$giver_Info->user_id->lname);?>/{{$giver_Info->user_id->id}}"> 
                                                                        <img src="{{$giver_Info->user_id->piclink}}" ></a>
                                                                        @else
                                                                             <img src="{{$giver_Info->user_id->piclink}}" >
                                                                        @endif
                                                                    @endif
                                                                </div>
                                                                <div class="col-sm-8 col-xs-7">
                                                                    @if (isset($giver_Info->user_id->email))
                                                                   <a href="/profile/<?php echo strtolower($giver_Info->user_id->fname.'-'.$giver_Info->user_id->lname);?>/{{$giver_Info->user_id->id}}"> 
                                                                        <h4>{{$giver_Info->user_id->fname." ".$giver_Info->user_id->lname}}</h4>
                                                                    </a>
                                                                    @else
                                                                        <h4>{{$giver_Info->user_id->fname." ".$giver_Info->user_id->lname}}</h4>
                                                                    @endif

                                                                    <p>{{KarmaHelper::stringCut($giver_Info->user_id->headline,80)}}</p>
                                                                    <p>{{$giver_Info->user_id->location}}</p>
                                                                </div>
                                                                <div class="clr"></div>
                                                                <div class="borderPic">
                                                                <ul>
                                                                    <li><a href="{{$giver_Info->user_id->linkedinurl}}" target="_blank"><img alt="" src="/images/linkdin.png"></a></li>
                                                                    @if (isset($giver_Info->user_id->email))
                                                                    <li>
                                                                    <a href="/profile/<?php echo strtolower($giver_Info->user_id->fname.'-'.$giver_Info->user_id->lname);?>/{{$giver_Info->user_id->id}}"><img alt="" src="/images/krmaicon.png"></a>
                                                                    <a href="<?php echo URL::to('/');?>/FAQs/KarmaPoints/1"><span>{{$giver_Info->user_id->karmascore}}</span></a>
                                                                    </li>
                                                                    @endif
                                                                </ul>
                                                                </div>
                                                                @if($giver_Info->user_id->id != $CurrentUser->id)
                                                                <a href="/CreateKarmaMeeting/{{$giver_Info->user_id->id}}"><button class="btn btn-success btnicon meeting smlBtnNew" type="button">Request Meeting</button></a>
                                                                @endif
                                                            </div>
                                                            <!-- List popup --> 
                                                        </li>
                                                        @endif
                                                    @endforeach
                                                @endif   
                                                
                                            </ul>    
                                        </div> 
                                    </div>
                                @endforeach
                                 @else 
                                        <li>
                                        <div class="centerText">
                                                <p>No Query yet!</p>
                                        </div>
                                    </li>
                            @endif
                            <!-- GROUP QUESRTION -->
                        </div>
                        </div>
                        
                        <!--2nd-->
                        <!--3rd -->
                        <!--3rd-->
                        <div class="tab-pane question allquestion_div" id="messages">
                            <!-- ALL QUESRTION -->
                            @if (!empty($allquestion))
                                @foreach ($allquestion as $question)
                                    <div class="questiionBlock clearfix">
                                    <div class="col-sm-1 newimgbox">
                                         @if($question->user_id->piclink != '')
                                            @if (isset($question->user_id->email))
                                                <a href="/profile/<?php echo strtolower($question->user_id->fname.'-'.$question->user_id->lname);?>/{{$question->user_id->id}}">
                                                <img height='50' width='45' src="{{$question->user_id->piclink}}"></a>
                                            @else

                                                <img height='50' width='45' src="{{$question->user_id->piclink}}">
                                            @endif
                                        @else
                                             @if (isset($question->user_id->email))
                                                <a href="/profile/<?php echo strtolower($question->user_id->fname.'-'.$question->user_id->lname);?>/{{$question->user_id->id}}">
                                                    <img  height='50' width='45' src="/images/default.png">
                                                </a>
                                            @else
                                                <img  height='50' width='45' src="/images/default.png">
                                            @endif

                                        @endif
                                        <!-- List popup -->
                                                    <div class="noteBox tabtxt listpopUp w280">
                                                        <div class="col-xs-4">                                                        
                                                            @if ($question->user_id->piclink == "" || $question->user_id->piclink == 'null')
                                                                @if (isset($question->user_id->email))
                                                <a href="/profile/<?php echo strtolower($question->user_id->fname.'-'.$question->user_id->lname);?>/{{$question->user_id->id}}">
                                                                <img alt="" src="/images/default.png" ></a>
                                                                @else
                                                                    <img alt="" src="/images/default.png" >    
                                                                @endif
                                                            @else
                                                                @if (isset($question->user_id->email))
                                                <a href="/profile/<?php echo strtolower($question->user_id->fname.'-'.$question->user_id->lname);?>/{{$question->user_id->id}}">
                                                                <img src="{{$question->user_id->piclink}}" ></a>
                                                                @else
                                                                    <img src="{{$question->user_id->piclink}}" >
                                                                @endif
                                                            @endif
                                                        </div>
                                                        <div class="col-sm-8 col-xs-7">
                                                            @if (isset($question->user_id->email))
                                                <a href="/profile/<?php echo strtolower($question->user_id->fname.'-'.$question->user_id->lname);?>/{{$question->user_id->id}}">
                                                            <h4>{{$question->user_id->fname." ".$question->user_id->lname}}</h4>
                                                        </a>
                                                        @else
                                                             <h4>{{$question->user_id->fname." ".$question->user_id->lname}}</h4>
                                                        @endif
                                                            <p>{{KarmaHelper::stringCut($question->user_id->headline,80)}}</p>
                                                            <p>{{$question->user_id->location}}</p>
                                                        </div> 
                                                        <div class="clr"></div>
                                                        <div class="borderPic">
                                                        <ul>
                                                            <li><a href="{{$question->user_id->linkedinurl}}" target="_blank"><img alt="" src="/images/linkdin.png"></a></li>
                                                            @if (isset($question->user_id->email))
                                                            <li>
                                                            <a href="/profile/<?php echo strtolower($question->user_id->fname.'-'.$question->user_id->lname);?>/{{$question->user_id->id}}"><img alt="" src="/images/krmaicon.png"></a>
                                                           <a href="<?php echo URL::to('/');?>/FAQs/KarmaPoints/1"> <span>{{$question->user_id->karmascore}}</span></a>
                                                            </li>
                                                            @endif
                                                        </ul>
                                                        </div> 
                                                        @if($question->user_id->id != $CurrentUser->id)
                                                        <a href="/CreateKarmaMeeting/{{$question->user_id->id}}"><button class="btn btn-success btnicon meeting smlBtnNew" type="button">Request Meeting</button></a>
                                                        @endif
                                                    </div>
                                                    <!-- List popup -->
                                    </div>
                                <div class="col-sm-6">
                                      <a href="/question/{{$question->question_url}}/{{$question->id}}"><h4>{{$question->subject}}</h4></a>
                                    <div>
                                          <span class="simTxt">{{date('F d, Y', strtotime($question->created_at))}}</span>
                                        <ul class="tag">
                                            @if($question->skills != '')
                                                @foreach ($question->skills as $skills)
                                                    <a href="<?php echo URL::to('/').'/searchUsers?searchUser='.$skills->name.'&searchOption=Skills';?>"><li>{{$skills->name}}</li></a>
                                                @endforeach  
                                            @endif      
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-sm-2" id="all_helpbutton_{{$question->id}}">
                                    @if(!empty($question->giver_Info)) 
                                        @foreach ($question->giver_Info as $giver_Info)
                                            @if($giver_Info->user_id->id == $CurrentUser->id && $question->queryStatus != 'closed')
                                                <div class="newimgbox" id="midhelpimg">
                                                     @if($giver_Info->user_id->piclink != '')
                                                        @if (isset($giver_Info->user_id->email))
                                                            <a href="/profile/<?php echo strtolower($giver_Info->user_id->fname.'-'.$giver_Info->user_id->lname);?>/{{$giver_Info->user_id->id}}">
                                                                <img height='50' width='45' src="{{$giver_Info->user_id->piclink}}">
                                                            </a>
                                                        @else
                                                            <img height='50' width='45' src="{{$giver_Info->user_id->piclink}}">
                                                        @endif
                                                    @else
                                                        @if (isset($giver_Info->user_id->email))
                                                            <a href="/profile/<?php echo strtolower($giver_Info->user_id->fname.'-'.$giver_Info->user_id->lname);?>/{{$giver_Info->user_id->id}}">
                                                        <img  height='50' width='45' src="/images/default.png"></a>
                                                        @else
                                                            <img  height='50' width='45' src="/images/default.png">
                                                        @endif
                                                    @endif
                                                    <!-- List popup -->
                                                                <div class="noteBox tabtxt listpopUp w280">
                                                                    <div class="col-xs-4">                                                        
                                                                        @if ($giver_Info->user_id->piclink == "" || $giver_Info->user_id->piclink == 'null')
                                                                            @if (isset($giver_Info->user_id->email))
                                                            <a href="/profile/<?php echo strtolower($giver_Info->user_id->fname.'-'.$giver_Info->user_id->lname);?>/{{$giver_Info->user_id->id}}">
                                                                            <img alt="" src="/images/default.png" ></a>
                                                                            @else
                                                                                <img alt="" src="/images/default.png" >
                                                                            @endif
                                                                        @else
                                                                             @if (isset($giver_Info->user_id->email))
                                                            <a href="/profile/<?php echo strtolower($giver_Info->user_id->fname.'-'.$giver_Info->user_id->lname);?>/{{$giver_Info->user_id->id}}">
                                                                            <img src="{{$giver_Info->user_id->piclink}}" >
                                                                        </a>
                                                                        @else
                                                                            <img src="{{$giver_Info->user_id->piclink}}" >
                                                                        @endif
                                                                        @endif
                                                                    </div>
                                                                    <div class="col-sm-8 col-xs-7">
                                                                         @if (isset($giver_Info->user_id->email))
                                                            <a href="/profile/<?php echo strtolower($giver_Info->user_id->fname.'-'.$giver_Info->user_id->lname);?>/{{$giver_Info->user_id->id}}"><h4>{{$giver_Info->user_id->fname." ".$giver_Info->user_id->lname}}</h4></a>
                                                            @else
                                                                <h4>{{$giver_Info->user_id->fname." ".$giver_Info->user_id->lname}}</h4>
                                                            @endif

                                                                        <p>{{KarmaHelper::stringCut($giver_Info->user_id->headline,80)}}</p>
                                                                        <p>{{$giver_Info->user_id->location}}</p>
                                                                    </div> 
                                                                    <div class="clr"></div>
                                                                    <div class="borderPic">
                                                                    <ul>
                                                                        <li><a href="{{$giver_Info->user_id->linkedinurl}}" target="_blank"><img alt="" src="/images/linkdin.png"></a></li>
                                                                        @if (isset($giver_Info->user_id->email))
                                                                        <li>
                                                                        <a href="/profile/<?php echo strtolower($giver_Info->user_id->fname.'-'.$giver_Info->user_id->lname);?>/{{$giver_Info->user_id->id}}"><img alt="" src="/images/krmaicon.png"></a>
                                                                        <a href="<?php echo URL::to('/');?>/FAQs/KarmaPoints/1"><span>{{$giver_Info->user_id->karmascore}}</span></a>
                                                                        </li>
                                                                        @endif
                                                                    </ul>
                                                                    </div> 
                                                                    @if($giver_Info->user_id->id != $CurrentUser->id)
                                                                    <a href="/CreateKarmaMeeting/{{$giver_Info->user_id->id}}"><button class="btn btn-success btnicon meeting smlBtnNew" type="button">Request Meeting</button></a>
                                                                    @endif
                                                                </div>
                                                                <!-- List popup -->
                                        </div>     
                                            @endif
                                          @endforeach 
                                    @endif     
                                    @if($question->answered == '0' && $question->queryStatus == 'open')
                                                       <!--  {{ Form::open(array('url' => 'submitforhelp' , 'method' => 'post')) }}
                                                        {{Form::hidden('question_id', $question->id)}}
                                                        {{Form::hidden('giver_id', Auth::user()->id)}}
                                                        {{Form::submit('Helps',array('class'=>'btn btn-success minBtn'));}}
                                                        {{Form::close()}}   -->
                                                        <div  class="btn btn-success minBtn" id="all_query_help_{{$question->id}}" onclick="getHelperImage({{$question->id}},'all');" >Help</div>
                                                    @elseif($question->queryStatus == 'closed')
                                                        <div  class="btn btn-success toggleBtn pending  btn-warning qrypag">Closed</div>
                                    @endif 

                                </div>
                                <div id="all_helper_{{$question->id}}">
                                </div>

                                <div class="col-sm-3" id="all_query_help_pic_{{$question->id}}">
                                    <ul class="thumbIMGlist" id="allquestion_{{$question->id}}">
                                        @if(!empty($question->giver_Info)) 
                                            @foreach ($question->giver_Info as $giver_Info) 
                                                @if($giver_Info->user_id->id != $CurrentUser->id)
                                                <li>
                                                    @if ($giver_Info->user_id->piclink == "" || $giver_Info->user_id->piclink == 'null')
                                                        @if (isset($giver_Info->user_id->email))
                                                            <a href="/profile/<?php echo strtolower($giver_Info->user_id->fname.'-'.$giver_Info->user_id->lname);?>/{{$giver_Info->user_id->id}}">
                                                                <img alt="" src="/images/default.png" >
                                                            </a>
                                                        @else
                                                             <img alt="" src="/images/default.png" >
                                                        @endif
                                                    @else
                                                        @if (isset($giver_Info->user_id->email))
                                                            <a href="/profile/<?php echo strtolower($giver_Info->user_id->fname.'-'.$giver_Info->user_id->lname);?>/{{$giver_Info->user_id->id}}">
                                                                <img src="{{$giver_Info->user_id->piclink}}" >
                                                            </a>
                                                        @else
                                                            <img src="{{$giver_Info->user_id->piclink}}" >
                                                        @endif
                                                    @endif
                                                    <!-- List popup -->
                                                    <div class="noteBox tabtxt listpopUp ">
                                                        <div class="col-xs-4">                                                        
                                                            @if ($giver_Info->user_id->piclink == "" || $giver_Info->user_id->piclink == 'null')
                                                                @if (isset($giver_Info->user_id->email))
                                                                    <a href="/profile/<?php echo strtolower($giver_Info->user_id->fname.'-'.$giver_Info->user_id->lname);?>/{{$giver_Info->user_id->id}}">
                                                                        <img alt="" src="/images/default.png" >
                                                                    </a>
                                                                @else
                                                                      <img alt="" src="/images/default.png" >
                                                                @endif
                                                            @else
                                                                 @if (isset($giver_Info->user_id->email))
                                                                    <a href="/profile/{{$giver_Info->user_id->id}}/{{$giver_Info->user_id->fname}}-{{$giver_Info->user_id->lname}}">
                                                                        <img src="{{$giver_Info->user_id->piclink}}" >
                                                                    </a>
                                                                @else
                                                                    <img src="{{$giver_Info->user_id->piclink}}" >
                                                                @endif
                                                            @endif
                                                        </div>
                                                        <div class="col-sm-8 col-xs-7">
                                                           @if (isset($giver_Info->user_id->email))
                                                                    <a href="/profile/<?php echo strtolower($giver_Info->user_id->fname.'-'.$giver_Info->user_id->lname);?>/{{$giver_Info->user_id->id}}"> <h4>{{$giver_Info->user_id->fname." ".$giver_Info->user_id->lname}}</h4></a>
                                                                @else
                                                                    <h4>{{$giver_Info->user_id->fname." ".$giver_Info->user_id->lname}}</h4>
                                                                @endif

                                                            <p>{{KarmaHelper::stringCut($giver_Info->user_id->headline,80)}}</p>
                                                            <p>{{$giver_Info->user_id->location}}</p>
                                                        </div>
                                                        <div class="clr"></div>
                                                        <div class="borderPic">
                                                        <ul>
                                                            <li><a href="{{$giver_Info->user_id->linkedinurl}}" target="_blank"><img alt="" src="/images/linkdin.png"></a></li>
                                                            @if (isset($giver_Info->user_id->email))
                                                            <li>
                                                            <a href="/profile/<?php echo strtolower($giver_Info->user_id->fname.'-'.$giver_Info->user_id->lname);?>/{{$giver_Info->user_id->id}}"><img alt="" src="/images/krmaicon.png"></a>
                                                            <a href="<?php echo URL::to('/');?>/FAQs/KarmaPoints/1"><span>{{$giver_Info->user_id->karmascore}}</span></a>
                                                            </li>
                                                            @endif
                                                        </ul>
                                                        </div>
                                                        @if($giver_Info->user_id->id != $CurrentUser->id)
                                                        <a href="/CreateKarmaMeeting/{{$giver_Info->user_id->id}}"><button class="smlBtnNew btn btn-success btnicon meeting" type="button">Request Meeting</button></a>
                                                        @endif
                                                    </div>
                                                    <!-- List popup --> 
                                                </li>
                                                @endif
                                            @endforeach
                                        @endif   
                                    </ul>    
                                </div>
                            </div>
                                @endforeach
                                 @else
                                        <li>
                                        <div class="centerText">
                                                <p>No Query yet!</p>
                                        </div>
                                    </li>
                            @endif
                           

                            
                            <!-- ALL QUESRTION -->
                        </div>
                        <!--3rd-->
                        <!--3rd-->
                        
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /Main colom -->
    <script type="text/javascript">
    $(document).ready(function(){
      
      if($("#primaryValue").val() == 1) {
        $("#rightTabs").show();
      } else {
        $("#rightTabs").hide();
      }
      
    });
    function  changeTab (tabName, value) {
      $('.currentTab').val(tabName);
    
      if(tabName == "groupquestion" && value == 0) {
        $("#rightTabs").hide();
      }else if(tabName == "myquestion" && value == 0) {
        $("#rightTabs").hide();
      } else if(tabName == "allQuestion" && value == 0) {
        $("#rightTabs").hide();
      } else {
        $("#rightTabs").show();
      }
     
                                 
    }
    function  changeorder (setting) { 
        $(".tab3").find("a").removeAttr("style");
        var currentTab =  $('.currentTab').val();

        $.post('/query/getdataByorder',{ currentTab: currentTab,setting: setting}, function(data, textStatus, xhr) {
            if(currentTab == 'myquestion'){
                $('.myquestion_div').html('');
                $('.myquestion_div').html(data);
            }
            else if(currentTab == 'groupquestion'){
                $('.groupquestion_div').html('');
                $('.groupquestion_div').html(data);
            }
            else if(currentTab == 'allQuestion'){
                $('.allquestion_div').html('');
                $('.allquestion_div').html(data);
            }
          
            $('#'+setting).css('background','#ededed');  
        });

    }

    function  closecheck () {
        if (confirm("Are you sure that you want to close this question?") == true) {
                return;
            } else {
               return false;
            }
    }
    var xhrh = null;
    function getHelperImage(quesId,action){
         if( xhrh != null ) {
                xhrh.abort(); 
                xhrh = null;
        }
        if(quesId) {
            var url='<?php echo URL::to('/');?>/updateQuestionhelpstatus?quesId='+quesId;
              xhrh=   $.get(url,function(data) {
                $('#'+action+'_helpbutton_'+quesId).hide();
                $('#'+action+'_helper_'+quesId).html(data);
                //  $('#'+action+'question_'+quesId).append(data); 
            }); 
        }
    }

    var url = window.location.href;
    var chk = url.split("#"); 
    if(chk[1] == 'messages')
        activaTab('messages');

    function activaTab(tab){
        $('.nav-tabs a[href="#' + tab + '"]').tab('show');
    };
    </script>
 
@stop
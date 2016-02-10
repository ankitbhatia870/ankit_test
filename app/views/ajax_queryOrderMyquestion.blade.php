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
                                                            <span>{{$question->user_id->karmascore}}</span>
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
                                                    @if(!$question->giver_Info->isEmpty())
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
                                                                        <span>{{$giver_Info->user_id->karmascore}}</span>
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
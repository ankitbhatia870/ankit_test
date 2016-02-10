@if (!empty($group_question))
                                @foreach ($group_question as $question)
                                    @if(isset($question->id)) 
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
                                                            <span>{{$question->user_id->karmascore}}</span>
                                                            </li>
                                                            @endif
                                                        </ul>
                                                        </div>
                                                        @if(Auth::check())    
                                                            @if($question->user_id->id != $CurrentUserId)
                                                            <a href="/CreateKarmaMeeting/{{$question->user_id->id}}"><button class="btn btn-success btnicon meeting smlBtnNew" type="button">Request Meeting</button></a>
                                                            @endif
                                                        @else
                                                            <button onclick="openboxmodel('GroupPage','/CreateKarmaMeeting/{{$question->user_id->id}}');" class="btn btn-success btnicon meeting smlBtnNew" type="button">Request Meeting</button>
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
                                
                                           @if(!$question->giver_Info->isEmpty())
                                        @foreach ($question->giver_Info as $giver_Info)
                                            @if($giver_Info->user_id->id == $CurrentUserId)
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
                                                                                <a href="/profile/<?php echo strtolower($giver_Info->user_id->fname.'-'.$giver_Info->user_id->lname);?>/{{$giver_Info->user_id->id}}"><img alt="" src="/images/default.png" ></a>
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
                                                                        <span>{{$giver_Info->user_id->karmascore}}</span>
                                                                        </li>
                                                                        @endif
                                                                    </ul>
                                                                    </div> 
                                                                    @if(Auth::check())
                                                                        @if($giver_Info->user_id->id != $CurrentUserId)
                                                                        <a href="/CreateKarmaMeeting/{{$giver_Info->user_id->id}}"><button class="btn btn-success btnicon meeting smlBtnNew" type="button">Request Meeting</button></a>
                                                                        @endif
                                                                    @else
                                                                        <button onclick="openboxmodel('GroupPage','/CreateKarmaMeeting/{{$giver_Info->user_id->id}}');" class="btn btn-success btnicon meeting smlBtnNew" type="button">Request Meeting</button>

                                                                    @endif 


                                                                </div>
                                                                <!-- List popup -->
                                        </div>     
                                            @endif
                                        @endforeach
                                    @endif
                                      <?php 
                                       $giver_userId = 0;
                                      if(isset($giver_Info->user_id->id))
                                      {
                                        $giver_userId = $giver_Info->user_id->id;
                                      } 
                                      ?>

                                       
                                            @if(Auth::check() && $question->answered == '0')
                                                <div  class="btn btn-success minBtn" id="group_query_help_{{$question->id}}" onclick="getHelperImage({{$question->id}},'group');" >Help</div>
                                            @endif

                                        </div>
 
                                
                                        <div id="group_helper_{{$question->id}}">
                                        </div>

                                        <div class="col-sm-3" id="group_query_help_pic_{{$question->id}}" >
                                      
                                            <ul class="thumbIMGlist" id="groupquestion_{{$question->id}}">
                                                @if(!$question->giver_Info->isEmpty())
                                                    @foreach ($question->giver_Info as $giver_Info)
                                                        @if($giver_Info->user_id->id != $CurrentUserId)
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
                                                                    <span>{{$giver_Info->user_id->karmascore}}</span>
                                                                    </li>
                                                                    @endif
                                                                </ul>
                                                                </div>
                                                                @if(Auth::check())
                                                                    @if($giver_Info->user_id->id != $CurrentUserId)
                                                                    <a href="/CreateKarmaMeeting/{{$giver_Info->user_id->id}}"><button class="btn btn-success btnicon meeting smlBtnNew" type="button">Request Meeting</button></a>
                                                                    @endif
                                                                @else
                                                                    <button onclick="openboxmodel('GroupPage','/CreateKarmaMeeting/{{$giver_Info->user_id->id}}');" class="btn btn-success btnicon meeting smlBtnNew" type="button">Request Meeting</button>
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
                                    <?php $coutTopQuery ; ?>
                                    @endif
                                @endforeach
                            @endif
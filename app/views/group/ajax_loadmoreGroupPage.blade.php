<?php 
   if(!empty($CurrentUser)) $CurrentUserId = $CurrentUser->id;
   else $CurrentUserId =0;
 ?> 

        @if(!empty($toppers)) 
                    @foreach ($toppers as $giver_Infoset)
                        <?php  
                          // if($topperCount%2 == 1 ) $class="fRight"; else $class="";
                           $giver_Info = $giver_Infoset['UserData'];
                           $skill = $giver_Infoset['Tags'];
                        //   echo"<pre>";print_r($giver_Info);echo"</pre>";
                        //   echo"<pre>";print_r($skill);echo"</pre>";
                        ?>
                         <div class="dataGenerate">
                            <div class="groupsFullInfo clearfix">
                                <div class="row">
                                     <div class="col-md-7 pdding0 col-xs-12 col-sm-8">
                                        <div class="top-giver">
                                            <div class="topGiverInfo clearfix">
                                                <div class="col-xs-3 pdding0 givepic">
                                                    @if ($giver_Info->piclink == "" || $giver_Info->piclink == 'null')
                                                        <a  href="/profile/<?php echo strtolower($giver_Info->fname.'-'.$giver_Info->lname).'/'.$giver_Info->id ;?>">
                                                            <img alt="" src="/images/default.png" >
                                                        </a>
                                                    @else
                                                    <a  href="/profile/<?php echo strtolower($giver_Info->fname.'-'.$giver_Info->lname).'/'.$giver_Info->id ;?>"><img src="{{$giver_Info->piclink}}" ></a>
                                                    @endif 
                                                </div>
                                                 <div class="col-xs-8 pdding0">
                                                   <a  href="/profile/<?php echo strtolower($giver_Info->fname.'-'.$giver_Info->lname).'/'.$giver_Info->id ;?>"><h4>{{$giver_Info->fname." ".$giver_Info->lname}}</h4></a>
                                                   <p>{{KarmaHelper::stringCut($giver_Info->headline,80)}}</p>
                                                    <p>{{$giver_Info->location}}</p>
                                                </div>
                                                 <div class="borderPic col-xs-12 pdding0 col-xs-12">
                                                    <ul>
                                                  
                                          <li><a href="{{$giver_Info->linkedinurl}}" target="_blank"><img alt="" src="/images/linkdin.png"></a></li>
                                   
                                    
                                    @if (!empty($giver_Info->email))
                                            <li>
                                            <a href="/profile/<?php echo strtolower($giver_Info->fname.'-'.$giver_Info->lname);?>/{{$giver_Info->id}}"><img alt="" src="/images/krmaicon.png"></a>
                                            <span>{{$giver_Info->karmascore}}</span>
                                            </li>
                                            @endif
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                  <div class="col-xs-12 col-sm-4 col-md-5 pdding0">

                                        @if(Auth::check())
                                            
                                             <a href="/CreateKarmaMeeting/{{$giver_Info->id}}"><button type="button" class="btn btn-success btnicon meeting ">Request Meeting</button></a>
                                            
                                             <a href="{{URL::to('/')}}/SendDirectkarmaNote/Karma/{{$giver_Info->id}}"><button class="btn btn-success btnicon" type="button">Send KarmaNote</button></a>
                                    
                                        @else
                                             <button type="button" onclick="openboxmodel('GroupPage','/CreateKarmaMeeting/{{$giver_Info->id}}');" class="btn btn-success btnicon meeting ">Request Meeting</button>
                                           
                                             <button class="btn btn-success btnicon" onclick="openboxmodel('GroupPage','{{URL::to('/')}}/SendDirectkarmaNote/Karma/{{$giver_Info->id}}');" type="button">Send KarmaNote</button>
                                        @endif
 
                                    </div>
                                    <div class="clr"></div> 
                                    <div class="tagList-new">
                                        @if (!empty($skill))
                                        <ul class="tag checkBoxtag"> 
                                       <?php $count = 0;?>
                        @foreach ($skill as $element)  
                          <?php if($count < 20) {?>
                            <a href="<?php echo URL::to('/').'/searchUsers?searchUser='.$element['name'].'&searchOption=Skills';?>"> <li>
                            <label>{{$element['name']}}</label>
                            <!-- <input type="checkbox" class="tagsCheck" value="{{$element['name']}}"> -->
                            </li></a>
                          <?php } else break;?>
                          <?php $count++;?> 
                        @endforeach                     
                                                        
                             
                            </ul>  
                             @endif
                                    </div>

                                </div>
                              
                            </div>
                           
                            
                        <!-- Group Info -->
                        </div>    

                        <?php $topperCount++;?>
                        @endforeach
                    @endif
 

@if (!empty($group_question))
@foreach ($group_question as $question)
     @if(isset($question->id))
    <div class="questiionBlock clearfix question">
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
                            @if (!empty($question->user_id))
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
                                            <a href="/profile/<?php echo strtolower($giver_Info->user_id->fname.'-'.$giver_Info->user_id->lname);?>/{{$giver_Info->user_id->id}}
">  <img src="{{$giver_Info->user_id->piclink}}" ></a>
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
                                        @if (!empty($giver_Info->user_id))  
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
        
        @if($question->answered == '0' && $question->queryStatus == 'open' &&  $giver_Info->user_id->id != $CurrentUserId)
            @if(Auth::check())
                <div  class="btn btn-success minBtn" id="group_query_help_{{$question->id}}" onclick="getHelperImage({{$question->id}},'group');" >Help</div>
            @endif
        @elseif($question->queryStatus == 'closed') 
            <div  class=" btn btn-success toggleBtn pending  btn-warning qrypag">Closed</div> 
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
                                   <a href="/profile/{{$giver_Info->user_id->id}}/{{$giver_Info->user_id->fname}}-{{$giver_Info->user_id->lname}}"> <img alt="" src="/images/default.png" ></a>
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
    <?php $coutTopQuery++ ; ?> 
    @endif
@endforeach
@endif

  @if(!empty($group_search))
             @foreach ($group_search as $value)         
                <div class="dataGenerate">
                            <div class="groupsFullInfo clearfix">
                                <div class="row">
                                   <div class="col-md-7 pdding0 col-xs-12 col-sm-8">
                                        <div class="top-giver">
                                            <div class="topGiverInfo clearfix">
                                                <div class="col-xs-3 pdding0 givepic">
                                                    @if ($value['piclink'] == '')
                                  @if( !empty($value['UserData']) && $value['UserData']->userstatus == "approved") 
                                    <a  href="profile/<?php echo strtolower($value['UserData']->fname.'-'.$value['UserData']->lname).'/'.$value['UserData']->id ;?>">
                                      <img style="width: 80px;" alt="" src="/images/default.png">
                                    </a>
                                  @else
                                    <img style="width: 80px;" alt="" src="/images/default.png">
                                  @endif
                                @else
                                @if(!empty($value['UserData']) && $value['UserData']->userstatus == "approved") 
                                    <a  href="profile/<?php echo strtolower($value['UserData']->fname.'-'.$value['UserData']->lname).'/'.$value['UserData']->id ;?>">
                                      <img style="width: 80px;" src="{{$value['piclink']}}">
                                    </a> 
                                  @else
                                      <img style="width: 80px;" src="{{$value['piclink']}}">
                                  @endif
                                @endif     
                                                </div>
                                                 <div class="col-xs-8 pdding0">
                                                    @if(!empty($value['UserData']) && $value['UserData']->userstatus == "approved") 
                                    <a  href="profile/<?php echo strtolower($value['UserData']->fname.'-'.$value['UserData']->lname).'/'.$value['UserData']->id ;?>"><h4>{{$value['fname']." ".$value['lname'] }}</h4></a>
                                @else
                                  <h4>{{$value['fname']." ".$value['lname'] }}</h4>
                                @endif
                                                   <p>{{$value['headline']}}</p>
                                <p>{{$value['location']}}</p>
                                                </div>
                                                <div class="borderPic col-xs-12 pdding0 col-xs-12">
                                                    <ul>
                                                   @if (empty($value['UserData']))
                                          <li><a target="_blank" href="{{$value['linkedinurl']}}" ><img src="/images/linkdin.png" alt=""></a></li>
                                    @else
                                    <li><a target="_blank" href="{{$value['UserData']->linkedinurl}}"><img src="/images/linkdin.png" alt=""></a></li>
                                    @endif
                                    
                                    @if($value['UserData']->userstatus == "approved")
                                      <li>
                                          <a  href="profile/<?php echo strtolower($value['UserData']->fname.'-'.$value['UserData']->lname).'/'.$value['UserData']->id ;?>">
                                          <img src="/images/krmaicon.png" alt=""></a><span>{{$value['UserData']->karmascore}}</span>
                                      </li>
                                    
                                    @endif
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-4 col-md-5 pdding0">
                                        

                                        @if(Auth::check())
                                            
                                             <a href="{{URL::to('/')}}/CreateKarmaMeeting/{{$value['UserData']->id}}"><button  class="btn btn-success btnicon meeting" type="button">Request Meeting</button></a>  
                                            
                                              <a href="{{URL::to('/')}}/SendDirectkarmaNote/Karma/{{$value['UserData']->id}}"><button class="btn btn-success btnicon" type="button">Send KarmaNote</button></a>
                                    
                                        @else
                                             <button onclick="openboxmodel('GroupPage','{{URL::to('/')}}/CreateKarmaMeeting/{{$value['UserData']->id}}');"
                                              class="btn btn-success btnicon meeting" type="button">Request Meeting</button>  
                                           
                                              <button onclick="openboxmodel('GroupPage','{{URL::to('/')}}/SendDirectkarmaNote/Karma/{{$value['UserData']->id}}');" class="btn btn-success btnicon" type="button">Send KarmaNote</button>
                                        @endif

                                   
                       
                                    </div>
                                    <div class="clr"></div>
                                    <div class="tagList-new">
                                        @if (!empty($value['Tags']))
                                        <ul class="tag checkBoxtag"> 
                                       <?php $count = 0;?>
                        @foreach ($value['Tags'] as $element)  
                          <?php if($count < 20) {?>
                            <a href="<?php echo URL::to('/').'/searchUsers?searchUser='.$element['name'].'&searchOption=Skills';?>"> <li>
                            <label>{{$element['name']}}</label>
                            <!-- <input type="checkbox" class="tagsCheck" value="{{$element['name']}}"> -->
                            </li></a>
                          <?php } else break;?>
                          <?php $count++;?> 
                        @endforeach                     
                                                        
                             
                            </ul> 
                             @endif
                                    </div>

                                </div>
                              
                            </div>
                           
                            
                        <!-- Group Info -->
                        </div>
                            @endforeach
            
            @endif

@extends('common.master')
@section('content')
	 {{ Form::hidden('pageIndex','karmaqueries',array('class'=>'pageIndex')); }}
     {{ Form::hidden('currentTab','myquestion',array('class'=>'currentTab')); }}
<?php  
   if(!empty($CurrentUser)) $CurrentUserId = $CurrentUser->id;
   else $CurrentUserId =0;
 ?> 
<section class="mainWidth">
    <div class="col-md-10 centralize profilepage pdding0 clearfix note">
        <div class="col-md-12 pZero centralize  "> 
            <div class="row clearfix">
               <h4 class="col-md-6">{{$groupDetail->name}}</h4>
               <div class="members_btn col-md-6">
                    

                    @if($CurrentUserId != 0  && $userinGroup ==0)
                    <div id="join-{{$groupDetail->id}}"><button class="btn btn-success pull-right"  onclick="updategroup({{$groupDetail->id}},'join');" type="button">Join</button></div>
                    @endif
                    @if($userinGroup !=0 && $CurrentUserId != 0 )
                    <div id="leave-{{$groupDetail->id}}"><button class="btn btn-success pull-right" onclick="updategroup({{$groupDetail->id}},'leave');"  id="leave-{{$groupDetail->id}}" type="button">Leave</button></div>
                    @endif
                    @if( $CurrentUserId == 0 )
                    <button onclick="openboxmodel('GroupPage','/updateGroup');" class="btn btn-success">Join</button>
                    @endif 
                   

               </div>
           </div>
           <p> <a href="{{$groupDetail->url}}"><?php echo $groupDetail->url;?></a></p> 
            <p>{{$groupDetail->description}}<p> 
        </div>  

        <div class="backlink clearfix subTopLink"></div>

        <div class="tabbed">    
        <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist">
              <li class="active" onclick="changeTab('topgivers')"><a href="#home" role="tab" data-toggle="tab">Top Givers</a></li>
              <li onclick="changeTab('topquery')"><a href="#profile" role="tab" data-toggle="tab">Top Queries</a></li>
              <li onclick="changeTab('search')"><a href="#messages" role="tab" data-toggle="tab">Search</a></li> 
            </ul>
             <div class="backlink clearfix subTopLink">
                <p class="pull-right tab3">
                    <a id="Recent" onclick="changeorder('Recent')" >Recent</a>
                    <a id="Populer" onclick="changeorder('Populer')" >Popular</a>
                    <a id="Unanswered" onclick="changeorder('Unanswered')" >Unanswered</a>
                </p>
            </div>

                
            <!-- Tab panes --> 
            <div class="tab-content">
                <!--1st-->
                <div class="tab-pane active topgiver_div " id="home" >
                    @if(!empty($toppers)) 
                    @foreach ($toppers as $giver_Infoset)
                        <?php  
                           if($topperCount%2 == 1 ) $class="fRight"; else $class="";

                           $giver_Info = $giver_Infoset['UserData'];
                           $skill = $giver_Infoset['Tags'];
                          //echo"<pre>";print_r($giver_Infoset);echo"</pre>";
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
                                                    <a  href="/profile/<?php echo strtolower($giver_Info->fname.'-'.$giver_Info->lname).'/'.$giver_Info->id ;?>">
                                                    <img src="{{$giver_Info->piclink}}" >
                                                    </a>
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
                                           
                                             <button onclick="openboxmodel('GroupPage','{{URL::to('/')}}/SendDirectkarmaNote/Karma/{{$giver_Info->id}}');" class="btn btn-success btnicon" type="button">Send KarmaNote</button>
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
                        @else
                        <div class="centerText">  
                                <p>No Top Givers</p>
                        </div>
                    @endif

                </div> 
                <!--1st-->

                <!--2nd-->
                <div class="tab-pane" id="profile" >
                    <div class="tab-pane active question groupquestion_div" id="home">
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
                                                            <?php //echo '<pre>';print_r($question->user_id->fname);die;?>
                                                             @if (!empty($question->user_id->email))
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
                                                                        @if (!empty($giver_Info->email))
                                                                        <li>
                                                                        <a href="/profile/<?php echo strtolower($giver_Info->fname.'-'.$giver_Info->lname);?>/{{$giver_Info->user_id->id}}"><img alt="" src="/images/krmaicon.png"></a>
                                                                        <span>{{$giver_Info->karmascore}}</span>
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
                                                                    @if (!empty($giver_Info->user_id->email))
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
                                 @else
                                    <li>
                                        <div class="centerText">
                                            <p>No Query yet!</p>
                                        </div>
                                    </li>
                            @endif
                           
                        </div>
        
                </div>
                <!--2nd-->
 
                <!--3rd--> 
                <?php $searchGroupVal = $GroupChoosen = ''; $searchGroupOption = 'People' ;?>
                <div id="messages" class="tab-pane ">
                    <!-- Search Panel -->
                        <div class="col-md-12 col-sm-11 col-xs-12 centralize searchPanel pdding0">
                            <div class="col-xs-1 seargroupcati">
                                <span class="glyphicon glyphicon-user" id="displayGroupIcon"></span>
                                <i class="glyphicon glyphicon-chevron-down"></i>
                            </div>
                            {{ Form::open(array('id' => 'groupSearchFrm' ,'url' => '' , 'method' => 'post')) }} 
                            <div class="col-sm-10 col-xs-9">
                                {{ Form::text('searchGroup',$searchGroupVal,array('class'=>'form-control searchBox','placeholder' => 'Search for a name', 'id'=> 'searchGroupKeyword', 'autocomplete'=>'off')); }}
                            {{ Form::hidden('searchGroupOption',$searchGroupOption ,array('id'=>'searchGroupOption')); }}
                            {{ Form::hidden('GroupChoosen',$groupId ,array('id'=>'GroupChoosen')); }}
                            </div>
                            <div class="col-xs-1 pull-right searchIconbtn">
                                 {{Form::submit('',array('class'=>'searchBTN', 'id'=>'groupsubmit'));}}  
                            </div>
                             {{ Form::close() }} 
                        </div>

                        <div class="searchGrouplink" id="">
                            <ul>
                                <li onclick="searchGroupOption('People')"><a href=""><span class="glyphicon glyphicon-user group" id="GPeople"></span>People</a></li>
                                <li onclick="searchGroupOption('Skills')"><a href=""><span class="glyphicon glyphicon-certificate group"></span>Skills</a></li>
                                <li onclick="searchGroupOption('Industry')"><a href=""><span class="glyphicon glyphicon-tower group"></span>Industry</a></li>
                                <li onclick="searchGroupOption('Location')"><a href=""><span class="glyphicon glyphicon-globe group"></span>Location</a></li>
                                <li onclick="searchGroupOption('Groups')" style="position:relative">
                    <a href="">
                        <span class="groupIcon">
                          <i class="glyphicon glyphicon-user group"></i>
                          <i class="glyphicon glyphicon-user group"></i>
                          <i class="glyphicon glyphicon-user group"></i>
                        </span>
                        <b style="text-indent:28px;font-weight:normal;display:inline-block">Group</b>
                     </a>
                </li>
                            </ul>
                        </div>
                        <!-- Search Panel -->
                        <div id="searchgroupresult" class="displayNone"></div>
                        <div id="searchgroupdata" class="displayNone"></div>
                </div>


                <!--3rd-->
            </div>
        </div>
    </div>
    <div class="modal" style="display:none" id="GroupPage">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button aria-label="Close" onclick="modelClose('GroupPage');" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Please sign in!</h4>
          </div>
          <div class="modal-body">
            <p>You need to be signed in to perform this action. Please sign in using Linkedin.</p>
          </div>
          <div class="modal-footer">
            <a href="" id="popupUrl"><button data-dismiss="modal" class="btn btn-default linkfullBTN newBluBtn pull-right" type="button">Sign in with Linkedin</button></a>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog --> 
    </div>
     <div class="modal" style="display:none" id="oneGroup">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button aria-label="Close" onclick="modelClose('oneGroup');" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">&times;</span></button>
            <h4>You have to be a part of atleast one group.</h4>
          </div>
          <div class="modal-footer">  
              <button data-dismiss="modal" class="btn btn-default linkfullBTN gpBtn pull-right" type="button" onclick="modelClose('oneGroup');">Close</button> 
            </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div>
        <input type="hidden" value="" id="gselect">
    <input type="hidden" value="page" id="gpage">
    <input type="hidden" value="" id="gaction">  
    <div class="modal" style="display:none" id="GroupLeave">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button aria-label="Close" onclick="modelClose('GroupLeave');" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Please sign in!</h4>
          </div>
          <div class="modal-body">
            <p>You need to be signed in to perform this action. Please sign in using Linkedin.</p>
          </div>
          <div class="modal-footer">
            <a href="" id="popupUrl"><button data-dismiss="modal" class="btn btn-default linkfullBTN newBluBtn pull-right" type="button">Sign in with Linkedin</button></a>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div>

    
    <div class="modal" style="display:none" id="UpdateGroup">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button aria-label="Close" onclick="modelClose('UpdateGroup');" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">Are you sure?</h4>
            </div>
            <input type="hidden" id="group-id" value="{{$groupId}}"> 
            <div class="modal-body group-body" >
              <p>Please join this group only if you belong to this group. If you don't belong to this group, your membership may be cancelled. Are you sure that you want to join?</p>
            </div>
            <div class="modal-footer">  
             
              <button data-dismiss="modal" class="btn btn-default linkfullBTN gpBtn pull-right" type="button" onclick="cancelcalled();">No</button> 
               <button data-dismiss="modal" class="btn btn-default linkfullBTN gpBtn pull-right" type="button" onclick="okcalled();">Yes</button>
            </div>
          </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog --> 
      </div>
</section>
<div class="loderBox">
<div id="loader"><img src="/images/loader.gif" /></div>
<div id="scroll"><img src="/images/icon_gototop.png" /></div>
</div> 
<input type="hidden" value='' id="hit-TopGivers"> 
<input type="hidden" value='15' id="scroller-TopGivers"> 
<input type="hidden" value='' id="hit-TopQueries"> 
<input type="hidden" value='15' id="scroller-TopQueries">   
<input type="hidden" value='' id="hit-Search"> 
<input type="hidden" value='15' id="scroller-Search">   
<input type="hidden" value='{{$groupId}}' id="group-id">
<input type="hidden" value='0' id="group-searchd-id">
<input type="hidden" value='' id="recent">      
    <!-- /Main colom --> 
    <script type="text/javascript">
    function  changeTab (tabName) {  
        $('#recent').val('');
        $('.currentTab').val(tabName);
        if(tabName == 'topquery') $('.subTopLink').show();
        else $('.subTopLink').hide();
    } 

    function  changeorder (setting) { 
        $('#recent').val('recent');
        $(".tab3").find("a").removeAttr("style");
        var currentTab =  $('.currentTab').val();
        var groupId =  $('#group-id').val(); 
        $.post('/groupquery/getdataByorder',{ currentTab: currentTab,setting: setting,groupId: groupId}, function(data, textStatus, xhr) {
            $('.groupquestion_div').html('');
            $('.groupquestion_div').html(data);
            $('#'+setting).css('background','#ededed');  
        });
    }

    var xhrh = null;
    function getHelperImage(quesId,action){
        if(<?php echo  $CurrentUserId;?> == 0){
                location.href="/index"; 
        }    

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
 
    $(document).ready(function() {
        $('#loader').hide(); 
        $('#scroll').hide();
        $('.subTopLink').hide();
      $(window).scroll(function() {
        infinite_scroll_debouncer(scrolling_top_groupqueries,200);  
      });
    });

    var isRunning = false;
        function gettrailgroup(){  
            if(isRunning){ 
                return;
            }
            
              var currentTab = $('.nav-tabs .active').text().replace(/\s/g, "");
              var scroller = $('#scroller-'+currentTab).val(); 
              var hitval =  $('#hit-'+currentTab).val();
              var groupId = $('#group-id').val();
			  var groupSearchd = $('#group-searchd-id').val(); 
               
              var tabcount;
              var recent =  $('#recent').val();
              if(currentTab == "TopGivers") 
                tabcount = <?php echo $topperCount;?>; 
              if(currentTab == "TopQueries")
                tabcount = <?php echo $coutTopQuery;?>;
              if(currentTab == "Search") 
                tabcount = <?php echo $countSearchQuery;?>;

              if(hitval !="over" && tabcount > 10 && recent!='recent'){    
                isRunning = true; 
                  $.ajax({
                      url: '<?php echo URL::to('/');?>/loadmoreGroupPage?hitcount='+scroller+'&action='+currentTab+'&groupId='+groupId+'&groupSearchd='+groupSearchd,   
                      type: "POST", 
                      cache:false,
                      async: true, 
                    beforeSend: function(){
                      $('#loader').show(); 
                      $('#scroll').hide(); 
                    },
                    complete: function(){
                     // clearconsole();
                      $('#loader').hide();
                      $('#scroll').hide(); 
                    },
                    success: function(data){
                        isRunning = false;  
                        if(data != "")
                        {
                          scroller = (+scroller) + (+10); 
                          $('#scroller-'+currentTab).val(scroller);
                          if(currentTab == 'TopGivers')
                          $("#home").append(data); 
                          if(currentTab == 'TopQueries')
                          $("#profile").append(data); 
                          if(currentTab == 'Search')
                          {
                            $('#searchgroupdata').show();
                            $('#searchgroupdata').html(data);
                            // $("#messages div#searchgroupdata").append(data); 
                          }
                        }
                        else
                        {  
                           $('#hit-'+currentTab).val('over');
                           $('#scroll').show(); 
                           $('#loader').hide();
                        }
                    }, 
                    error: function(){ 
                    }           
                   });
            }
            else{
              if(tabcount > 7)  
              $('#scroll').show();
              else
                {$('#scroll').hide();$('#loader').hide();}
            }
                
          } 
      $('.nav-tabs').click(function () {
            $('#scroll').hide();$('#loader').hide();
        }); 

     
   
      $('#groupsubmit').click(function () {
        $('div#searchgroupresult').hide(); 
        $('div#searchgroupresult').removeAttr('style');
        var keyword = $('#searchGroupKeyword').val();
        var optionVal = $('#searchGroupOption').val();
        var groupId = $('#group-id').val();

        if(keyword == ''){
            return false;
        }
        else{
            $.ajax({ 
                    url: '<?php echo URL::to('/');?>/GroupSearch',
                    type: "post", 
                    cache:false,
                    data : { keyword : keyword, groupId : groupId, optionVal : optionVal },
                    async: true, 
                    beforeSend: function(){
                    },
                    complete: function(){
                     $('#searchgroupdata').show();
                    },
                    success: function(data){
                        $('#searchgroupdata').html(data);
                    }, 
                    error: function(){
                    }           
                   });
        }
        return false;
    });



    
    var timer = null;
    $('#searchGroupKeyword').keydown(function(){
       
           clearTimeout(timer); 
           timer = setTimeout(callmeSearchGroup, 200)
    });

   var xhr = null;
   function callmeSearchGroup(){ 
        $('#searchgroupresult').show();
        $('div#searchgroupresult').focus();     
        var keyword = $('#searchGroupKeyword').val();
        var optionVal = $('#searchGroupOption').val();
        var groupId = $('#group-id').val();
        if((optionVal == 'People' || optionVal == 'Skills' || optionVal == 'Groups') && keyword != ''){
            $.ajax({ 
                    url: '<?php echo URL::to('/');?>/searchGroupresult?keyword='+keyword+'&optionVal='+optionVal+'&groupId='+groupId,    
                    type: "post", 
                    cache:false,
                    async: true, 
                    beforeSend: function(){
                    },
                    complete: function(){
                        $('div#searchgroupresult').focus(); 
                    },
                    success: function(data){
                        if(data==""){
                            $('div#searchgroupresult').html(' ');
                        }
                        else{
                            $('div#searchgroupresult').show(); 
                            $('div#searchgroupresult').focus(); 
                            $("div#searchgroupresult").html(data);
                        }
                    }, 
                    error: function(){
                    }           
                   });
        }  
        else{ 
            $('div#searchgroupresult').hide(); 
          return false;
        }
    }

    
    </script>
 
@stop
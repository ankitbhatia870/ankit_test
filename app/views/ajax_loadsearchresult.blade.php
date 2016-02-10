 @foreach ($searchresult as $value)   

                <div class="result">  
                    <div class="col-sm-8 col-xs-7">
                        <div class="col-sm-9 col-xs-12 noteBox tabtxt">
                            <div class="col-xs-4">
                                @if ($value['piclink'] == '')
                                  @if( !empty($value['UserData']) && $value['UserData']->userstatus == "approved") 
                                    <a  href="<?php echo "/profile/".strtolower($value['UserData']->fname.'-'.$value['UserData']->lname).'/'.$value['UserData']->id;?>">
                                      <img style="width: 80px;" alt="" src="/images/default.png">
                                    </a>
                                  @else
                                    <img style="width: 80px;" alt="" src="/images/default.png">
                                  @endif
                                @else
                                @if(!empty($value['UserData']) && $value['UserData']->userstatus == "approved") 
                                    <a  href="<?php echo '/profile/'.strtolower($value['UserData']->fname.'-'.$value['UserData']->lname).'/'.$value['UserData']->id ;?>">
                                      <img style="width: 80px;" src="{{$value['piclink']}}">
                                    </a> 
                                  @else
                                      <img style="width: 80px;" src="{{$value['piclink']}}">
                                  @endif
                                @endif                                 
                            </div>
                            <div class="col-sm-8 col-xs-7">
                                @if(!empty($value['UserData']) && $value['UserData']->userstatus == "approved")  
                                    <a  href="<?php echo '/profile/'.strtolower($value['UserData']->fname.'-'.$value['UserData']->lname).'/'.$value['UserData']->id;?>"><h4>{{$value['fname']." ".$value['lname'] }}</h4></a>
                                @else
                                  <h4>{{$value['fname']." ".$value['lname'] }}</h4>
                                @endif
                                <p>{{$value['headline']}}</p>
                                <p>{{$value['location']}}</p>
                            </div>
                            <div class="borderPic pull-left">
                                <ul>
                                    @if (empty($value['UserData']))
                                          <li><a target="_blank" href="{{$value['linkedinurl']}}" ><img src="images/linkdin.png" alt=""></a></li>
                                    @else
                                    <li><a target="_blank" href="{{$value['UserData']->linkedinurl}}"><img src="images/linkdin.png" alt=""></a></li>
                                    @if($value['UserData']->userstatus == "approved")
                                      <li>
                                          <a  href="<?php echo "/profile/".strtolower($value['UserData']->fname.'-'.$value['UserData']->lname).'/'.$value['UserData']->id;?>">
                                          <img src="images/krmaicon.png" alt=""></a><span>{{$value['UserData']->karmascore}}</span>
                                      </li>
                                    @endif


                                    @endif
                                 
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4 col-xs-3"> 
                      @if(Auth::check())
                        @if (empty($value['UserData']))
                             <?php
                              $user_id  =  $value['id'];
                              $uname =  $value['fname'].' '.$value['lname'];
                              $click = "window.open('/CreateKarmaMeeting/NoKarma/$user_id','_self')";
                                ?>
                                <button onclick="{{$click}}" class="btn btn-success btnicon meeting" type="button">Request Meeting</button> 
                        @else
                            <a href="{{URL::to('/')}}/CreateKarmaMeeting/{{$value['UserData']->id}}"><button  class="btn btn-success btnicon meeting" type="button">Request Meeting</button></a>                            
                        @endif
                        <!-- <button class="btn btn-success btnicon intro" type="button">Initiate Intro</button> -->
                        @if (empty($value['UserData']))
                          <?php 
                            $user_id = $value['id'];
                            $uname = $value['fname'].' '.$value['lname'];
                            $click = "window.open('/SendDirectkarmaNote/NoKarma/$user_id','_self')";
                          ?>
                          <button onclick="{{$click}}" class="btn btn-success btnicon" type="button">Send KarmaNote</button>
                        @else
                          <a href="{{URL::to('/')}}/SendDirectkarmaNote/Karma/{{$value['UserData']->id}}"><button class="btn btn-success btnicon" type="button">Send KarmaNote</button></a>
                        @endif  
                        @if (empty($value['UserData']))
                        <?php  
                          if(empty($value['UserData']))
                             $user_id = 'inviteOnkc/'.$value['id'];
                          else 
                             $user_id = $value['UserData']->id; 
                        
                         $click = "window.open('$user_id','_self')";
                        
                        ?> 
                        <button onclick="{{$click}}" class="btn btn-success btnicon inviteKC" type="button">Invite to KC</button>
                        @endif
                      @else
                        @if (empty($value['UserData']))
                            <?php 
                            $user_id  =  $value['id'];
                            $uname =  $value['fname'].' '.$value['lname'];
                            $click = "window.open('/CreateKarmaMeeting/NoKarma/$user_id','_self')";
                                ?>
                                <button onclick="{{$click}}" class="btn btn-success btnicon meeting" type="button">Request Meeting</button> 
                        @else
                            <button onclick="openboxmodel('SearchResultPage','{{URL::to('/')}}/CreateKarmaMeeting/{{$value['UserData']->id}}');"  class="btn btn-success btnicon meeting" type="button">Request Meeting</button>                             
                        @endif
                        <!-- <button class="btn btn-success btnicon intro" type="button">Initiate Intro</button> -->
                        @if (empty($value['UserData']))
                          <?php 
                            $user_id = $value['id'];
                            $uname = $value['fname'].' '.$value['lname'];
                            $click = "window.open('/SendDirectkarmaNote/NoKarma/$user_id','_self')";
                          ?>
                          <button onclick="{{$click}}" class="btn btn-success btnicon" type="button">Send KarmaNote</button>
                        @else
                          <button onclick="openboxmodel('SearchResultPage','{{URL::to('/')}}/SendDirectkarmaNote/Karma/{{$value['UserData']->id}}');" class="btn btn-success btnicon" type="button">Send KarmaNote</button>
                        @endif  
                        @if (empty($value['UserData']))
                       <?php  
                          if(empty($value['UserData']))
                             $user_id = 'inviteOnkc/'.$value['id'];
                          else 
                             $user_id = $value['UserData']->id; 
                      
                         $click = "window.open('$user_id','_self')";
                        
                        ?>  
                        <button onclick="{{$click}}" class="btn btn-success btnicon inviteKC" type="button">Invite to KC</button>
                        @endif
                      @endif  
                    
                    </div>
                    <div class="clr"></div> 
                    
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
            @endforeach
   
  
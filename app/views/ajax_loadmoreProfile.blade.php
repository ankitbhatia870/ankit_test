
                    @if (!empty($karmaTrail))
                        @foreach ($karmaTrail as $key=>$trail)
                          @if($trail['user_id_receiver']['id'] != $profileUserDetail->id)
                              <?php 
                                    $profielURL = $imgSrc = $alt = $linkedinurl="";                                   
                                    $alt = $trail['user_id_receiver']['fname'];
                                    $linkedinurl = $trail['user_id_receiver']['linkedinurl'];
                                     if(!empty($trail['user_id_receiver']['email'])){     
                                      $imgSrc = $trail['user_id_receiver']['piclink'];                                
                                    $profielURL = '/profile/'.strtolower($trail['user_id_receiver']['fname'].'-'.$trail['user_id_receiver']['lname']).'/'.$trail['user_id_receiver']['id'];
                                    $karmascore = $trail['user_id_receiver']['karmascore'];
                                    }
                              ?>
                          @else 
                              <?php 
                                    $profielURL = $imgSrc = $alt = $linkedinurl="";                                    
                                    $alt = $trail['user_id_giver']['fname'];
                                    $linkedinurl = $trail['user_id_giver']['linkedinurl'];
                                    if(!empty($trail['user_id_giver']['email'])){
                                      $imgSrc = $trail['user_id_giver']['piclink'];
                                     $profielURL = '/profile/'.strtolower($trail['user_id_giver']['fname'].'-'.$trail['user_id_giver']['lname']).'/'.$trail['user_id_giver']['id'];
                                      $karmascore = $trail['user_id_giver']['karmascore'];
                                      }
                              ?>
                          @endif      
                          @if ($trail['status'] == 'hidden' && $profileSelf == 0)
                          @else 
                            @if ($countTrail % 2 != 0)
                              <div class="trail send clearfix trailresult">
                            @else
                              <div class="trail clearfix trailresult">
                            @endif                           
                              <div class="col-sm-2 pdding0 borderPic">
                                @if (!empty($imgSrc))
                                  <img src="{{$imgSrc}}" alt="{{$alt}}" title="{{$alt}}">
                                @else 
                                  <img src="/images/default.png" alt="{{$alt}}" title="{{$alt}}">  
                                @endif
                                  
                                  <ul>
                                      <li><a href="{{$linkedinurl }}" target="_blank"><img src="/images/linkdin.png" alt=""></a></li>
                                      @if (!empty($trail['user_id_receiver']['email']))
                                      @if (!empty($profielURL))
                                         <li>
                                          <a href="{{$profielURL}}"><img src="/images/krmaicon.png" alt=""></a>
                                          <span>{{$karmascore}}</span>
                                        </li>
                                      @endif                                       
                                      @endif
                                      
                                  </ul>
                              </div>
                              <a href="<?php echo '/meeting/'.strtolower($trail['user_id_receiver']['fname'].'-'.$trail['user_id_receiver']['lname'].'-'.$trail['user_id_giver']['fname'].'-'.$trail['user_id_giver']['lname']).'/'.$trail['req_id'];?>">
                                <div class="col-sm-10 pdding0 tabtxt">
                                    <h4>Karma Note Sent to {{$trail['user_id_giver']['fname']}} by {{$trail['user_id_receiver']['fname']}} 
                                    <span>(Met on {{$trail['meetingdatetime']}})</span></h4>
                                    <p>{{KarmaHelper::stringCut($trail['karmaNotes'],180)}}</p>
                                    @if (!empty($trail['skills'])) 
                                      <ul class="traillist tag">
                                        @foreach ($trail['skills'] as $Trailskills)
                                           <a href="<?php echo URL::to('/').'/searchUsers?searchUser='.$Trailskills->name.'&searchOption=Skills';?>"><li>{{$Trailskills->name}}</li></a>
                                        @endforeach
                                      </ul>
                                    @endif
                                    </ul>
                                    <div class="action pull-right">
                                      @if ($profileSelf == 1)
                                        <p>{{$trail['status']}}<span class="glyphicon glyphicon-pencil pull-right"></span></p>
                                      @endif
                                      
                                      <p>{{$trail['created_at']}}</p>
                                    </div>
                                </div>
                              </a>
                            </div>
                            <?php $countTrail++ ; ?>
                          @endif  
                        @endforeach
                    @endif
                    
                      
     
   
                      @if (!empty($karmaReceived))
                        @foreach ($karmaReceived as $received)
                          @if ($received['status'] == 'hidden' && $profileSelf == 0)
                            
                          @else 
                              @if ($countReceived % 2 != 0)
                                <div class="trail send clearfix receivedresult">
                              @else
                                <div class="trail clearfix receivedresult">
                              @endif                             
                                <div class="col-sm-2 pdding0 borderPic">
                                  @if (!empty($received['user_id_receiver']['piclink']))
                                    <img src="{{$received['user_id_receiver']['piclink']}}" alt="{{$received['user_id_receiver']['fname']}}" title="{{$received['user_id_receiver']['fname']}}">
                                  @else 
                                    <img src="/images/default.png" alt="{{$received['user_id_receiver']['fname']. $received['user_id_receiver']['lname']}}" title="{{$received['user_id_receiver']['fname']}}">  
                                  @endif
                                    
                                    <ul>
                                        <li><a href="{{$received['user_id_receiver']['linkedinurl']}}" target="_blank"><img src="/images/linkdin.png" alt=""></a></li>
                                        @if (!empty($received['user_id_receiver']['email']))
                                          <li>
                                            <a href="<?php echo '/profile/'.strtolower($received['user_id_receiver']['fname'].'-'.$received['user_id_receiver']['lname']).'/'.$received['user_id_receiver']['id'];?>"><img src="/images/krmaicon.png" alt=""></a>
                                            <span>{{$received['user_id_receiver']['karmascore']}}</span>
                                          </li>
                                        @endif
                                        
                                    </ul>
                                </div>
                                <a href="<?php echo '/meeting/'.$received['user_id_receiver']['fname'].'-'.$received['user_id_receiver']['lname'].'-'.$received['user_id_giver']['fname'].'-'.$received['user_id_giver']['lname'].'/'.$received['req_id'];?>">
                                  <div class="col-sm-10 pdding0 tabtxt">
                                      <h4>Karma Note Sent to {{$received['user_id_giver']['fname']}} by {{$received['user_id_receiver']['fname']}}
                                      <span>(Met on {{date('F d, Y', strtotime($received['req_detail']['meetingdatetime']))}})</span>
                                      </h4>
                                      <p>{{KarmaHelper::stringCut($received['karmaNotes'],180)}}</p>
                                      @if (!empty($received['skills']))
                                        <ul class=" traillist tag">
                                          @foreach ($received['skills'] as $Receivedskills)
                                             <a href="<?php echo URL::to('/').'/searchUsers?searchUser='.$Receivedskills->name.'&searchOption=Skills';?>"><li>{{$Receivedskills->name}}</li></a>
                                          @endforeach
                                        </ul> 
                                      @endif
                                      </ul>
                                      <div class="action pull-right">
                                        @if ($profileSelf == 1)
                                          <p>{{$received['status']}}<span class="glyphicon glyphicon-pencil pull-right"></span></p>
                                        @endif
                                        
                                        <p>{{$received['created_at']}}</p>
                                      </div>
                                  </div>
                                </a>
                              </div>
                              <?php $countReceived++;?>
                          @endif
                        @endforeach
                      @endif
                       
                   @if (!empty($karmaSent))
                        @foreach ($karmaSent as $sent)
                           @if ($sent['status'] == 'hidden' && $profileSelf == 0)
                           @else   
                            @if ($countSent % 2 != 0)
                                 <div class="trail send clearfix givenresult">
                              @else
                                 <div class="trail clearfix givenresult">
                              @endif 
                           
                              <div class="col-sm-2 pdding0 borderPic">
                                @if (!empty($sent['user_id_giver']['piclink']) && !empty($sent['user_id_giver']['email']))
                                  <img src="{{$sent['user_id_giver']['piclink']}}" alt="{{$sent['user_id_giver']['fname']}}" title="{{$sent['user_id_giver']['fname']}}">
                                @else 
                                  <img src="/images/default.png" alt="{{$sent['user_id_giver']['fname']. $sent['user_id_giver']['lname']}}" title="{{$sent['user_id_giver']['fname']}}">  
                                @endif
                                  
                                  <ul>
                                      <li><a href="{{$sent['user_id_giver']['linkedinurl']}}" target="_blank"><img src="/images/linkdin.png" alt=""></a></li>
                                      @if (!empty($sent['user_id_giver']['email']))
                                        <li>
                                          <a href="<?php echo '/profile/'.strtolower($sent['user_id_giver']['fname'].'-'.$sent['user_id_giver']['lname']).'/'.$sent['user_id_giver']['id'];?>"><img src="/images/krmaicon.png" alt=""></a>
                                          <span>{{$sent['user_id_giver']['karmascore']}}</span>
                                        </li>
                                      @endif  
                                      
                                  </ul>
                              </div>
                              <a href="<?php echo '/meeting/'.strtolower($sent['user_id_receiver']['fname'].'-'.$sent['user_id_receiver']['lname'].'-'.$sent['user_id_giver']['fname'].'-'.$sent['user_id_giver']['lname']).'/'.$sent['req_id'];?>">
                                <div class="col-sm-10 pdding0 tabtxt">
                                    <h4>Karma Note Sent to {{$sent['user_id_giver']['fname']}} by {{$sent['user_id_receiver']['fname']}}
                                    <span>(Met on {{date('F d, Y', strtotime($sent['req_detail']['meetingdatetime']))}})</span>
                                    </h4>
                                    <p>{{KarmaHelper::stringCut($sent['karmaNotes'],180)}}</p>  
                                    @if (!empty($sent['skills']))
                                      <ul class=" traillist tag">
                                        @foreach ($sent['skills'] as $Sentskills)
                                           <a href="<?php echo URL::to('/').'/searchUsers?searchUser='.$Sentskills->name.'&searchOption=Skills';?>"><li>{{$Sentskills->name}}</li></a>
                                        @endforeach
                                      </ul>
                                    @endif
                                    </ul>
                                    <div class="action pull-right">
                                      @if ($profileSelf == 1)
                                        <p>{{$sent['status']}}<span class="glyphicon glyphicon-pencil pull-right"></span></p>
                                      @endif
                                      
                                      <p>{{$sent['created_at']}}</p>
                                    </div>
                                </div>
                              </a>
                            </div>
                            <?php $countSent++;?>
                           @endif 
                        @endforeach
                      @endif
                      
                      
             
     
                


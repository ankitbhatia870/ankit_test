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
                                    <a  href="/profile/<?php echo strtolower($value['UserData']->fname.'-'.$value['UserData']->lname).'/'.$value['UserData']->id ;?>">
                                      <img style="width: 80px;" alt="" src="/images/default.png">
                                    </a>
                                  @else
                                    <img style="width: 80px;" alt="" src="/images/default.png">
                                  @endif
                                @else
                                @if(!empty($value['UserData']) && $value['UserData']->userstatus == "approved") 
                                    <a  href="/profile/<?php echo strtolower($value['UserData']->fname.'-'.$value['UserData']->lname).'/'.$value['UserData']->id ;?>">
                                      <img style="width: 80px;" src="{{$value['piclink']}}">
                                    </a> 
                                  @else
                                      <img style="width: 80px;" src="{{$value['piclink']}}">
                                  @endif
                                @endif     
                                                </div> 
                                                 <div class="col-xs-8 pdding0">
                                                    @if(!empty($value['UserData']) && $value['UserData']->userstatus == "approved") 
                                    <a  href="/profile/<?php echo strtolower($value['UserData']->fname.'-'.$value['UserData']->lname).'/'.$value['UserData']->id ;?>"><h4>{{$value['fname']." ".$value['lname'] }}</h4></a>
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
                                          <a  href="/profile/<?php echo strtolower($value['UserData']->fname.'-'.$value['UserData']->lname).'/'.$value['UserData']->id ;?>">
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
                                             <button onclick="openboxmodel('GroupPage','{{URL::to('/')}}/CreateKarmaMeeting/{{$value['UserData']->id}}');"  class="btn btn-success btnicon meeting" type="button">Request Meeting</button>
                                           
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
            @else
               <div class="result"> No results found for "{{$searchFor}}" in {{$searchCat}} category. </div>
            @endif
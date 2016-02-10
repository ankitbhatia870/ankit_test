@extends('common.master')
@section('content')
    
   <?php //echo "<pre>";print_r($searchresult);echo"</pre>";?> 
    <section class="mainWidth">
        <div class="col-lg-9 col-md-10 col-sm-12 centralize pdding0">
            <!-- <div class="backlink clearfix">  
                <span class="pull-left" style="display:iblock">{{$totalResult}} results for "{{$searchFor}}" in {{$searchCat}} category.</span>
                <a href="/dashboard" class="pull-right">Back to Karma Circle</a>
            </div> -->
        </div>    
        <div class="clr"></div>
        <div class="searchresult borderColom">
            <!-- Result colom -->
            @if(!empty($searchresult))
             @foreach ($searchresult as $value)            
                <div class="result">
                    <div class="col-sm-8 col-xs-7">
                        <div class="col-sm-9 col-xs-12 noteBox tabtxt">
                            <div class="col-xs-4">
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
                            <div class="col-sm-8 col-xs-7">
                                @if(!empty($value['UserData']) && $value['UserData']->userstatus == "approved") 
                                    <a  href="profile/<?php echo strtolower($value['UserData']->fname.'-'.$value['UserData']->lname).'/'.$value['UserData']->id ;?>"><h4>{{$value['fname']." ".$value['lname'] }}</h4></a>
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
                                          <a  href="profile/<?php echo strtolower($value['UserData']->fname.'-'.$value['UserData']->lname).'/'.$value['UserData']->id ;?>">
                                          <img src="images/krmaicon.png" alt=""></a><a href="<?php echo URL::to('/');?>/FAQs/KarmaPoints/1"><span>{{$value['UserData']->karmascore}}</span></a>
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
                              $clickmeet = "window.open('/CreateKarmaMeeting/NoKarma/$user_id','_self')";
                                ?>
                                <button onclick="{{$clickmeet}}" class="btn btn-success btnicon meeting" type="button">Request Meeting</button> 
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
                                $clickmeet = "window.open('/CreateKarmaMeeting/NoKarma/$user_id','_self')";
                                ?>
                                <button onclick="{{$clickmeet}}" class="btn btn-success btnicon meeting" type="button">Request Meeting</button> 

                            {{--  <button onclick="openboxmodel('SearchResultPage','{{URL::to('/')}}/CreateKarmaMeeting/NoKarma/{{$value['id']}}');"  class="btn btn-success btnicon meeting" type="button">Request Meeting</button> --}}

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
                         {{--  <button onclick="openboxmodel('SearchResultPage','{{URL::to('/')}}/SendDirectkarmaNote/NoKarma/{{$value['id']}}');" class="btn btn-success btnicon" type="button">Send KarmaNote</button> --}}
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
            @else
               <div class="result"> No results found for "{{$searchFor}}" in {{$searchCat}} category. </div>
            @endif
            <!-- Result colom -->
             
        </div>
        <div class="modal" style="display:none" id="SearchResultPage">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button aria-label="Close" onclick="modelClose('SearchResultPage');" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">&times;</span></button>
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
 
    </section>
    <input type="hidden" value='' id="hit"> 
    <input type="hidden" value='10' id="scroller"> 
    <div class="loderBox">
      <div id="loader-icon"><img src="images/loader.gif" /></div>
      <div id="scroll-top"><img src="images/icon_gototop.png" /></div>
    </div>

    <!-- /Main colom -->
    <script type="text/javascript">
      var isRunning = false;
       $(document).ready(function() {
          $('#loader-icon').hide();   
          $('#scroll-top').hide(); 
          $(window).scroll(function() {  
          infinite_scroll_debouncer(infinite_scrolling_searchResult,200);       
        });  
      });
   
      
      function getsearchresult() {   
       if(isRunning){ 
        return;
      }
          var scroller = $('#scroller').val(); 
          var hitval =  $('#hit').val();
          var date = new Date(); 
          var timestamp = date.getTime();
          if(hitval !="over" ){
        isRunning = true; 
                  $.ajax({
                      url: '<?php echo URL::to('/');?>/LoadmoresearchResult?hitcount='+scroller+'&time='+timestamp+'&searchUser='+'<?php echo $searchFor;?>'+'&searchOption='+'<?php echo  $searchCat;?>',  
                      type: "POST", 
                      cache:false, 
                      async: true, 
                    beforeSend: function(){
                      $('#loader-icon').show(); 
                      $('#scroll-top').hide(); 
                    },
                    complete: function(){
                      //clearconsole();   
                      $('#loader-icon').hide();
                      $('#scroll-top').hide(); 
                    },
                    success: function(data){
                        isRunning = false;  
                        if(data != "")
                        {
                          scroller = (+scroller) + (+10); 
                          $('#scroller').val(scroller);
                          $(".searchresult").append(data);
                        }
                        else
                        {
                           $('#hit').val('over');
                           $('#scroll-top').show(); 
                        }
                    }, 
                    error: function(){ 
                    }           
                   });
            }
            else
                $('#scroll-top').show();
          }
    
    </script>
@stop
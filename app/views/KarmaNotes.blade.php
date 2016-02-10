@extends('common.master')
@section('content')
<?php 
//echo "<pre>";print_r($PendingRequest);echo "</pre>string"; 
//echo KarmaHelper::currentDate();
?>
     {{ Form::hidden('pageIndex','karmanote',array('class'=>'pageIndex')); }}
	<section class="mainWidth">
        <div class="col-md-10 centralize profilepage pdding0 clearfix note">
          @if(empty($PendingRequest) && empty($ReceivedRequest) && empty($sentRequest))
            <div class="backlink ">
            KarmaNote is a message of appreciation (thank you note) for someone who took the time to help you. You can send KarmaNote to anyone who you are grateful to.
            </br>
            To see who all can you request KarmaNote from, click here <a href="http://www.karmacircles.com/searchConnections">http://www.karmacircles.com/searchConnections</a>
            </div>
          @else
                <div class="tabbed">     
                <!-- Nav tabs -->
                    <ul class="nav nav-tabs" role="tablist">
                      <li class="active"><a href="#home" role="tab" data-toggle="tab">Pending</a></li>
                      <li><a href="#profile" role="tab" data-toggle="tab">Received</a></li>
                      <li><a href="#messages" role="tab" data-toggle="tab">Sent</a></li>
                    </ul>
                    <!-- Tab panes -->
                    <div class="tab-content">
                        <!--1st-->
                        <div class="tab-pane active notesPendingresult" id="home">
                        @if (!empty($PendingRequest))
                            @foreach ($PendingRequest as $pending)
                                @if ($pending->meetingdatetime < KarmaHelper::currentDate())
                                <!--pending list-->
                        
                    <div class="col-sm-12 centralize clearfix introresult" >
                            <!-- single tab -->
                              <div class="borderContainer mintopM0 col-xs-12">
                                  <div class="col-xs-12 col-sm-5 pdding0 ">
                                      <div class="noteBox tabtxt">
                                          <div class="col-xs-5">
                                            @if (!empty($pending->piclink))
                                                    <a href="<?php echo '/profile/'.strtolower($pending->fname.'-'.$pending->lname).'/'.$pending->user_id;?>"><img src="{{$pending->piclink}}" alt="{{$pending->fname}}" title="{{$pending->lname}}"  width="82" height="87"></a>
                                                @else 
                                                    <a href="<?php echo '/profile/'.strtolower($pending->fname.'-'.$pending->lname).'/'.$pending->user_id;?>"><img src="/images/default.png"  width="82" height="87"></a>
                                                @endif
                                          </div>
                                          <div class="col-xs-7">
                                            <a href="<?php echo '/profile/'.strtolower($pending->fname.'-'.$pending->lname).'/'.$pending->user_id;?>"><h4>{{$pending->fname.' '.$pending->lname}}</h4></a>
                                            <p title="{{$pending->headline}}">{{$pending->headline}}</p><p><span></span></p>
                                          </div>
                                          <div class="clr"></div>
                                            <div class="borderPic">
                                              <ul>
                                                <li><a href="{{$pending->linkedinurl}}"><img alt="" src="images/linkdin.png"></a></li>
                                                @if (!empty($pending->email))
                                                              <li>
                                                                <a href="<?php echo '/profile/'.strtolower($pending->fname.'-'.$pending->lname).'/'.$pending->user_id;?>"><img alt="" src="images/krmaicon.png"></a>
                                                               <a href="<?php echo URL::to('/');?>/FAQs/KarmaPoints/1"> <span>{{$pending->karmascore}}</span></a>
                                                        </li>
                                                        @endif
                                              </ul>
                                            </div>
                                      </div>
                                  </div>
                                  <div class="col-sm-7 col-xs-12 textR">
                                    <h4>{{ date('F d,Y',strtotime($pending->updated_at))}}</h4>
                                    <p>{{$pending->location}}</p>
                                    <div class="clr"></div>
                                    <!-- <div class="recive">
                                      <button class="btn btn-warning toggleBtn completed" type="button">hidden</button>
                                    </div> -->
                                    <div class="">       
                                       <a href="/meeting/{{$CurrentUser->fname.'-'.$CurrentUser->lname.'-'.$pending->fname.'-'.$pending->lname}}/{{$pending->id}}"><button type="button" class="btn btn-success btnicon">Send KarmaNote</button></a> 
                                    </div>
                                  </div>
                                  <div class="clr"></div>
                                  <div class="tabtxt">
                                    <p class="np statusN"> {{KarmaHelper::stringCut($pending->subject,70)}}</p>
                                    <p class="np">{{KarmaHelper::stringCut($pending->notes,100)}}</p>
                                  </div>
                            </div>
                          
                <!-- single tab/ -->
                      </div>
                                <!--pending list-->
                                <?php $countPen++; ?>
                                @endif
                            @endforeach
                        @endif
                         @if($countPen == '0')
                            <div class="centerText">  
                                <p>No KarmaNotes pending!</p>
                            </div> 
                         @endif
                            @if($totalPendingRequest > NOTESPERPAGE)
                                <div class="clr"></div>
                                <div class="loadMore" id ="showMore">
                                    <span>Load More</span>
                                </div>
                            @endif 
                        </div>
                        <!--1st-->
                        <!--2nd-->
                        <div class="tab-pane notesReceivedresult" id="profile" >
                            @if (!empty($ReceivedRequest)) 
                                @foreach ($ReceivedRequest as $received)
                                    <!--Received list-->
                                     
                                  <div class="col-sm-12 centralize clearfix introresult" >
                            <!-- single tab -->
                              <div class="borderContainer mintopM0 col-xs-12">
                                  <div class="col-xs-12 col-sm-5 pdding0 ">
                                      <div class="noteBox tabtxt">
                                          <div class="col-xs-5">
                                           @if (!empty($received['user_id_receiver']['piclink']))
                                                       <a href="<?php echo '/profile/'.strtolower($received['user_id_receiver']['fname'].'-'.$received['user_id_receiver']['lname']).'/'.$received['user_id_receiver']['id'];?>"> <img src="{{$received['user_id_receiver']['piclink']}}" alt="{{$received['user_id_receiver']['fname']}}" title="{{$received['user_id_receiver']['fname']}}" width="82" height="87"></a>
                                                    @else 
                                                       <a href="<?php echo '/profile/'.strtolower($received['user_id_receiver']['fname'].'-'.$received['user_id_receiver']['lname']).'/'.$received['user_id_receiver']['id'];?>"> <img src="/images/default.png" width="82" height="87"></a>
                                                    @endif
                                          </div>
                                          <div class="col-xs-7">  
                                            <a href="<?php echo '/profile/'.strtolower($received['user_id_receiver']['fname'].'-'.$received['user_id_receiver']['lname']).'/'.$received['user_id_receiver']['id'];?>">
                                              <h4>{{$received['user_id_receiver']['fname'].' '.$received['user_id_receiver']['lname']}}</h4>
                                            </a>
                                            <p title="{{$received['user_id_receiver']['headline']}}">{{$received['user_id_receiver']['headline']}}</p><p><span></span></p>
                                          </div>
                                          <div class="clr"></div>
                                            <div class="borderPic">
                                              <ul>
                                                <li><a href="{{$received['user_id_receiver']['linkedinurl']}}"><img alt="" src="images/linkdin.png"></a></li>
                                                @if (!empty($received['user_id_receiver']['email']))
                                                                <li>
                                                                   <a href="<?php echo '/profile/'.strtolower($received['user_id_receiver']['fname'].'-'.$received['user_id_receiver']['lname']).'/'.$received['user_id_receiver']['id'];?>">
                                                                        <img alt="" src="images/krmaicon.png"></a>
                                                                  <a href="<?php echo URL::to('/');?>/FAQs/KarmaPoints/1"> <span>{{$received['user_id_receiver']['karmascore']}}</span></a>
                                                                </li>
                                                          @endif
                                              </ul>
                                            </div>
                                      </div>
                                  </div>
                                  <div class="col-sm-7 col-xs-12 textR">
                                    <h4>{{ date('F d,Y',strtotime($received['created_at']))}}</h4>
                                    <p>{{$received['user_id_receiver']['location']}}</p>
                                    <div class="clr"></div>
                                    <div class="recive">
                                     
                                      @if ($received['status'] == 'visible')
                                                 <button type="button" class="btn btn-success toggleBtn" >{{$received['status']}}</button>
                                            @else
                                               <button type="button" class="btn btn-warning toggleBtn completed" >{{$received['status']}}</button>
                                            @endif 
                                    </div> 
                                    <div class="">       
                         <a href="<?php echo '/meeting/'.strtolower($received['user_id_receiver']['fname'].'-'.$received['user_id_receiver']['lname'].'-'.$received['user_id_giver']['fname'].'-'.$received['user_id_giver']['lname']).'/'.$received['meetingId'];?>"><button type="button" class="btn btn-success smlGrnbtn">View Details</button></a>
                          </div>
                                  </div>
                                  <div class="clr"></div>
                                  <div class="tabtxt">
                                    <p class="np statusN"></p>
                                    <p class="np">{{KarmaHelper::stringCut($received['details'],200)}}</p>
                                  </div>
                            </div>
                          
                <!-- single tab/ -->
                      </div>
                                    <!--Received list-->
                                     <?php $countRec++; ?>
                                @endforeach
                            @endif
                            @if($countRec == '0')
                                <div class="centerText">
                                    <p>No KarmaNotes received yet!</p>
                                </div>
                            @endif
                            @if($totalReceivedRequest > NOTESPERPAGE)
                            <div class="clr"></div>
                            <div class="loadMore" id ="showReceivedMore">
                                <span>Load More</span>
                            </div>
                            @endif 
                        </div>
                        <!--2nd-->
                        <!--3rd-->
                        <div class="tab-pane notesresult" id="messages">
                            @if (!empty($sentRequest))
                                @foreach ($sentRequest as $sent)
                                    <!--Sent list-->
                                    <?php 
                                      $profileURL = "";
                                      if (!empty($sent['giver_detail']['email']))
                                      $profileURL = '/profile/'.strtolower($sent['giver_detail']['fname'].'-'.$sent['giver_detail']['lname']).'/'.$sent['giver_detail']['id'];

                                    ?>
                                     
                                  <div class="col-sm-12 centralize clearfix introresult" > 
                            <!-- single tab -->
                              <div class="borderContainer mintopM0 col-xs-12">
                                  <div class="col-xs-12 col-sm-5 pdding0 ">
                                      <div class="noteBox tabtxt">
                                          <div class="col-xs-5">
                                              @if (!empty($sent['giver_detail']['piclink']))
                                               
                                                @if(!empty($sent['giver_detail']['email'])) 
                                                <a href="{{$profileURL}}">
                                                  <img src="{{$sent['giver_detail']['piclink']}}" alt="{{$sent['giver_detail']['fname']}}" title="{{$sent['giver_detail']['lname']}}" width="82" height="87">
                                                </a>
                                                @else
                                                  <img src="{{$sent['giver_detail']['piclink']}}" alt="{{$sent['giver_detail']['fname']}}" title="{{$sent['giver_detail']['lname']}}" width="82" height="87">
                                                @endif
                                              @else 
                                                @if(!empty($sent['giver_detail']['email'])) 
                                                  <a href="{{$profileURL}}">
                                                  <img src="/images/default.png" width="82" height="87">
                                                  </a>
                                                @else
                                                  <img src="/images/default.png" width="82" height="87">
                                                @endif
                                              @endif
                                          </div>
                                          <div class="col-xs-7">
                                            @if(!empty($sent['giver_detail']['email'])) 
                                              <a href="{{$profileURL}}"><h4>
                                              {{$sent['giver_detail']['fname'].' '.$sent['giver_detail']['lname']}}
                                              </h4></a>
                                            @else
                                             <h4>
                                              {{$sent['giver_detail']['fname'].' '.$sent['giver_detail']['lname']}}
                                              </h4>
                                            @endif


                                            <p title="{{$sent['giver_detail']['headline']}}">{{$sent['giver_detail']['headline']}}</p><p><span></span></p>
                                          </div>
                                          <div class="clr"></div>
                                            <div class="borderPic">
                                              <ul>
                                                <li><a href="{{$sent['giver_detail']['linkedinurl']}}"><img alt="" src="images/linkdin.png"></a></li>
                                                 @if (!empty($sent['giver_detail']['email']))
                                                              <li>
                                                              <a href="<?php echo '/profile/'.strtolower($sent['giver_detail']['fname'].'-'.$sent['giver_detail']['lname']).'/'.$sent['giver_detail']['id'];?>"><img alt="" src="images/krmaicon.png"></a>
                                                              @if (!empty($sent['giver_detail']['karmascore']))
                                                                 <a href="<?php echo URL::to('/');?>/FAQs/KarmaPoints/1"> <span>{{$sent['giver_detail']['karmascore']}}</span></a>
                                                              @endif                                                            
                                                              </li>
                                                @endif     
                                              </ul>
                                            </div>
                                      </div>
                                  </div>
                                    <div class="col-sm-7 col-xs-12 textR">
                                    <h4>{{ date('F d,Y',strtotime($sent['created_at']))}}</h4>

                                    <p>{{$sent['giver_detail']['location']}}</p>
                                    <div class="clr"></div>
                                    <div class="recive">
                                     
                                      @if ($sent['status'] == 'visible')
                                                 <button type="button" class="btn btn-success toggleBtn ">{{$sent['status']}}</button>
                                            @else
                                               <button type="button" class="btn btn-warning toggleBtn completed">{{$sent['status']}}</button>
                                            @endif 
                                    </div> 
                                    <div class="">       
                      <a href="<?php echo '/meeting/'.strtolower($sent['receiver_detail']['fname'].'-'.$sent['receiver_detail']['lname'].'-'.$sent['giver_detail']['fname'].'-'.$sent['giver_detail']['lname']).'/'.$sent['meetingId'];?>"><button type="button" class="btn btn-success smlGrnbtn">View Details</button></a>
                          </div>
                                  </div>
                                  <div class="clr"></div>
                                  <div class="tabtxt">
                                    <p class="np statusN"></p>
                                    <p class="np">{{KarmaHelper::stringCut($sent['karmanotedetail'],200)}}</p>
                                  </div>
                            </div>
                          
                <!-- single tab/ -->
                      </div>

                                    <!--Sent list-->
                                     <?php $countSent++; ?>
                                @endforeach
                            @endif
                            @if($countSent == '0')
                                <div class="centerText">
                                    <p>No KarmaNotes sent yet!</p>
                                </div>
                            @endif
                            @if($totalSentRequest > NOTESPERPAGE)
                            <div class="clr"></div>
                            <div class="loadMore" id ="showMore">
                                <span>Load More</span>
                            </div>
                            @endif 
                        </div>
                            
                        <!--3rd-->
                    </div>
                </div>
                @endif
            </div>
        </div>    
    </section>
        <script type="text/javascript">
          var itemsCount = 0,
              itemsRecCount = 0,
              itemsPenCount = 0,
              itemsMax = $('.notesresult div.results').length;
              itemsReceivedMax = $('.notesReceivedresult div.receivedresult').length;
              itemsPendingMax = $('.notesPendingresult div.pendingresult').length;
              //alert(itemsReceivedMax);
          $('.notesresult div.results').hide();
          $('.notesReceivedresult div.receivedresult').hide();
          $('.notesPendingresult div.pendingresult').hide();

          function showNextItems() {
              var pagination = <?php echo NOTESPERPAGE; ?>;
              
              for (var i = itemsCount; i < (itemsCount + pagination); i++) {
                  $('.notesresult div.results:eq(' + i + ')').show();
              }

              itemsCount += pagination;
              
              if (itemsCount > itemsMax) {
                  $('#showMore').hide();
              }
          };

          function showNextReceivedItems() {
              var pagination = <?php echo NOTESPERPAGE; ?>;
              
              for (var i = itemsRecCount; i < (itemsRecCount + pagination); i++) {
                  $('.notesReceivedresult div.receivedresult:eq(' + i + ')').show();
              }

              itemsRecCount += pagination;
              
              if (itemsRecCount > itemsReceivedMax) {
                  $('#showReceivedMore').hide();
              }
          };

          function showNextPendingItems() {
              var pagination = <?php echo NOTESPERPAGE; ?>;
              
              for (var i = itemsPenCount; i < (itemsPenCount + pagination); i++) {
                  $('.notesPendingresult div.pendingresult:eq(' + i + ')').show();
              }

              itemsPenCount += pagination;
              
              if (itemsPenCount > itemsPendingMax) {
                  $('#showPendingMore').hide();
              }
          };
            showNextItems();
            showNextReceivedItems();
            showNextPendingItems();

          $('#showMore').on('click', function (e) {
              e.preventDefault();
              showNextItems();
          });
          $('#showReceivedMore').on('click', function (e) {
              e.preventDefault();
              showNextReceivedItems();
          });
          $('#showPendingMore').on('click', function (e) {
              e.preventDefault();
              showNextPendingItems();
          });

         

        </script>

@stop
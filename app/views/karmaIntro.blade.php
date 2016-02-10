@extends('common.master')
@section('content')
	 {{ Form::hidden('pageIndex','karmaIntro',array('class'=>'pageIndex')); }}
	    <section class="mainWidth">
        <div class="col-lg-10 col-md-10 col-sm-12 centralize pdding0">

       <!--  <div class="backlink pull-right clearfix">
            <a href="/dashboard">Back to Karma Circle</a>
        </div> -->
        <div class="clr"></div>
        <div class="clearfix scheduleBtn">
            <a href="/karma-intro/initiatekarmaIntro"><button class="btn btn-success smlGrnbtn pull-right" type="button">Initiate Karma Intro</button></a>
        </div>

         @foreach ($IntroducerInitiated as $key => $element)
          <!--Received list-->
        <div class="col-sm-12 centralize clearfix introresult">
            <!-- single tab -->
            <div class="borderContainer mintopM col-xs-12">
                <div class="col-xs-5 pdding0 fixWidth270">
                    <div class="noteBox tabtxt">
                        <div class="col-xs-5">
                            @if ($element->user_id_receiver->piclink == "" || $element->user_id_receiver->piclink== 'null')
                                <a href="profile/<?php echo strtolower($element->user_id_receiver->fname.'-'.$element->user_id_receiver->lname).'/'.$element->user_id_receiver->id ;?>"><img alt="" src="/images/default.png" width="82" height="87"></a>
                            @else
                               <a href="profile/<?php echo strtolower($element->user_id_receiver->fname.'-'.$element->user_id_receiver->lname).'/'.$element->user_id_receiver->id ;?>"> <img src="{{$element->user_id_receiver->piclink}}" width="82" height="87"></a>
                            @endif
                        </div>
                        <div class="col-xs-7">
                            <a href="profile/<?php echo strtolower($element->user_id_receiver->fname.'-'.$element->user_id_receiver->lname).'/'.$element->user_id_receiver->id ;?>"><h4>{{$element->user_id_receiver->fname." ".$element->user_id_receiver->lname}}</h4></a>
                            <p title="{{$element->user_id_receiver->headline}}">{{KarmaHelper::stringCut($element->user_id_receiver->headline,80)}}<p><span>{{$element->user_id_receiver->location}}</span></p></p>
                        </div>
                        <div class="clr"></div>
                        <div class="borderPic">
                            <ul>
                                <li><a href="{{$element->user_id_receiver->linkedinurl}}" target="_blank"><img alt="" src="images/linkdin.png"></a></li>
                                @if (isset($element->user_id_receiver->email))
                                <li>
                                <a href="profile/<?php echo strtolower($element->user_id_receiver->fname.'-'.$element->user_id_receiver->lname).'/'.$element->user_id_receiver->id ;?>"><img alt="" src="images/krmaicon.png"></a>
                               <a href="<?php echo URL::to('/');?>/FAQs/KarmaPoints/1"> <span>{{$element->user_id_receiver->karmascore}}</span></a>
                                </li>
                                @endif 
                            </ul> 
                        </div>
                    </div>
                </div>
                <div class="col-xs-2 fixWidth233">
                     <span>{{ date('F d,Y',strtotime($element->updated_at))}}</span>
                    @if ($element->status == 'pending' )
                        <button type="button" class="btn btn-success toggleBtn pending">Pending</button>
                    @elseif($element->status == 'archived')
                         <button type="button" class="btn btn-success toggleBtn">Archived</button>
                    @elseif($element->status == 'accepted')
                        <button type="button" class="btn btn-success toggleBtn accept">Accepted</button>
                    @elseif($element->status == 'completed')
                        <button type="button" class="btn btn-success toggleBtn completed">Completed</button>
                    @endif      
                </div>
                <div class="col-xs-5 pdding0 fixWidth270">
                    <div class="noteBox tabtxt">
                        <div class="col-xs-5">
                           @if ($element->user_id_giver->piclink == "" || $element->user_id_giver->piclink == 'null')
                                @if (isset($element->user_id_giver->email))
                                <a href="profile/<?php echo strtolower($element->user_id_giver->fname.'-'.$element->user_id_giver->lname).'/'.$element->user_id_giver->id ;?>">
                                <img alt="" src="/images/default.png" width="82" height="87">
                              </a>
                              @else
                                <img alt="" src="/images/default.png" width="82" height="87">
                                @endif
                            @else
                              @if (isset($element->user_id_giver->email))
                                <a href="profile/<?php echo strtolower($element->user_id_giver->fname.'-'.$element->user_id_giver->lname).'/'.$element->user_id_giver->id ;?>">
                                 <img src="{{$element->user_id_giver->piclink}}" width="82" height="87">
                                </a>
                                @else
                                   <img src="{{$element->user_id_giver->piclink}}" width="82" height="87">
                                @endif

                            @endif
                        </div>
                        <div class="col-xs-7">
                            <h4>{{$element->user_id_giver->fname." ".$element->user_id_giver->lname}}</h4>
                            <p title="{{$element->user_id_giver->headline}}">{{KarmaHelper::stringCut($element->user_id_giver->headline,80)}}<p><span>{{$element->user_id_giver->location}}</span></p></p>
                        </div>
                        <div class="clr"></div>
                        <div class="borderPic">
                            <ul>
                                <li><a href="{{$element->user_id_giver->linkedinurl}}" target="_blank"><img alt="" src="images/linkdin.png"></a></li>
                                @if (isset($element->user_id_giver->email))
                                <li>
                                <a href="profile/<?php echo strtolower($element->user_id_giver->fname.'-'.$element->user_id_giver->lname).'/'.$element->user_id_giver->id ;?>"><img alt="" src="images/krmaicon.png"></a>
                                <a href="<?php echo URL::to('/');?>/FAQs/KarmaPoints/1"><span>{{$element->user_id_giver->karmascore}}</span></a>
                                </li>
                                @endif    
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="clr"></div>
                <div class="tabtxt">
                    <p class="np statusN"> {{KarmaHelper::stringCut($element->subject,70)}}</p>
                    <p class="np">{{KarmaHelper::stringCut($element->notes,100)}}</p>
                </div>
            </div>
            <div class="recive">          
            <a href="/meeting/<?php echo strtolower($element->user_id_receiver->fname.'-'.$element->user_id_receiver->lname.'-'.$element->user_id_giver->fname.'-'.$element->user_id_giver->lname);?>/{{$element->id}}"><button type="button" class="btn btn-success smlGrnbtn MT10">View Details</button></a> 
            </div>
            <!-- single tab/ -->
            </div>
         <?php $countIntro++; ?>
         @endforeach
          @if($countIntro == '0')
        <div style="margin-left: 33%">
            <p>Your karma Intro tab is empty.</p>
        </div>
        @endif   
        @if($countIntro > INTROPERPAGE)
            <div class="clr"></div>
            <div class="loadMore" id ="showMore">
                <span>Load More</span>
            </div>
        @endif  
                                   
            
        </div>  
    </section>
    <!-- /Main colom -->
    <script type="text/javascript">
          var itemsSentCount = 0,
              itemsSentMax = $('div.introresult').length;
              //alert(itemsSentMax);
          $('div.introresult').hide();

          function showNextItems() {
              var pagination = <?php echo INTROPERPAGE; ?>;
              for (var i = itemsSentCount; i < (itemsSentCount + pagination); i++) {
                  $('div.introresult:eq(' + i + ')').show();
              }

              itemsSentCount += pagination;
              
              if (itemsSentCount >= itemsSentMax) {
                  $('#showMore').hide();
              }
          };

            showNextItems();
          $('#showMore').on('click', function (e) {
              e.preventDefault();
              showNextItems();
          });
        </script>
@stop
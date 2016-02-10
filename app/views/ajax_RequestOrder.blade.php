
@foreach ($GiverInMeeting as $element)
    @if($element->status != 'completed') @if($element->status != 'accepted' )
        @if($element->status != 'archived' && $element->subject != '')
        <div class="col-sm-12 centralize clearfix introresult" > 
            <!-- single tab -->
              <div class="borderContainer mintopM0 col-xs-12">
                  <div class="col-xs-12 col-sm-5 pdding0 ">
                      <div class="noteBox tabtxt">
                          <div class="col-xs-5"> 
                              @if ($element['user_id_receiver']['piclink'] == "" || $element['user_id_receiver']['piclink'] == 'null')

                                <a href="profile/<?php echo strtolower($element['user_id_receiver']['fname'].'-'.$element['user_id_receiver']['lname']).'/'.$element['user_id_receiver']['id'] ;?>"><img alt="" src="/images/default.png" width="82" height="87"></a>
                            @else
                                <a href="profile/<?php echo strtolower($element['user_id_receiver']['fname'].'-'.$element['user_id_receiver']['lname']).'/'.$element['user_id_receiver']['id'] ;?>"> <img src="{{$element['user_id_receiver']['piclink']}}" width="82" height="87"></a>
                            @endif
                          </div>
                          <div class="col-xs-7">
                            <a href="profile/<?php echo strtolower($element['user_id_receiver']['fname'].'-'.$element['user_id_receiver']['lname']).'/'.$element['user_id_receiver']['id'] ;?>"><h4>
                             {{$element['user_id_receiver']['fname']." ".$element['user_id_receiver']['lname']}}
                            </h4></a>

                            <p title="{{KarmaHelper::stringCut($element['user_id_receiver']['headline'],80)}}">{{KarmaHelper::stringCut($element['user_id_receiver']['headline'],80)}}</p><p><span></span></p>
                          </div>
                          <div class="clr"></div>
                            <div class="borderPic">
                              <ul>
                                <li><a href="{{$element['user_id_receiver']['linkedinurl']}}"><img alt="" src="images/linkdin.png"></a></li>
                                @if (isset($element['user_id_receiver']['email']))
                                     <li>
                                    <a href="profile/<?php echo strtolower($element['user_id_receiver']['fname'].'-'.$element['user_id_receiver']['lname']).'/'.$element['user_id_receiver']['id'] ;?>"><img alt="" src="images/krmaicon.png"></a>
                                    <span>{{$element['user_id_receiver']['karmascore']}}</span>
                                  </li>
                                  @endif
                              </ul>
                            </div>
                      </div>
                  </div>
                  <div class="col-xs-12 col-sm-7 textR">
                    <h4>{{ date('F d,Y',strtotime($element->updated_at))}}</h4>

                    <p>{{$element['user_id_receiver']['location']}}</p>
                    <div class="clr"></div>
                    <div class="recive">
                     
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
                     <div class="recive">       
                        <a href="/meeting/<?php echo strtolower($element['user_id_receiver']['fname'].'-'.$element['user_id_receiver']['lname'].'-'.$element['user_id_giver']['fname'].'-'.$element['user_id_giver']['lname']);?>/{{$element['id']}}"><button type="button" class="btn btn-success smlGrnbtn">See Meeting Request Details</button></a>
                    </div>
                  </div>
                  <div class="clr"></div>
                  <div class="tabtxt">
                    <p class="np statusN">{{KarmaHelper::stringCut($element->subject,70)}}</p>
                    <p class="np">{{KarmaHelper::stringCut($element->notes,100)}}</p>
                  </div>
            </div>
         <!--  <div class="recive">       
             <a href="/meeting/{{$element['id']}}/{{$element['user_id_receiver']['fname'].'-'.$element['user_id_receiver']['lname'].'_'.$element['user_id_giver']['fname'].'-'.$element['user_id_giver']['lname']}}"><button type="button" class="btn btn-success smlGrnbtn">See Meeting Request Details</button></a>
          </div> -->
<!-- single tab/ -->
      </div>
           <?php $countRec++; ?>
        @endif  @endif @endif
    @endforeach
@if($countRec == '0')  
    <div class="centerText">
    <p>You haven't received any meeting request yet!</p>
    </div>
@endif
@if($countRec > MEETINGPERPAGE)
<div class="clr"></div>
<div class="loadMore" id ="showReceivedMore">
    <span>Load More</span>
</div>
@endif  
                      
<script type="text/javascript">
function showNextReceivedItems() {
      var pagination = <?php echo MEETINGPERPAGE; ?>;
      
      for (var i = itemsRecCount; i < (itemsRecCount + pagination); i++) {
          $('.meetingReceivedresult div.receivedresult:eq(' + i + ')').show();
      }

      itemsRecCount += pagination;
      
      if (itemsRecCount > itemsReceivedMax) {
          $('#showReceivedMore').hide();
      }
};
$('#showReceivedMore').on('click', function (e) {
  e.preventDefault();
  showNextReceivedItems();
});
</script>
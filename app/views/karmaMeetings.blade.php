@extends('common.master')
@section('content')
<?php 
$countMeet = 0;
foreach ($GiverInMeeting as $value_set){
 	if($value_set->status != 'completed')
 		$countMeet++;
}
$countRecord = 0;
foreach ($GiverInMeeting as $element){
	if($element->status != 'archived'){
	$countRecord++;
	}	
}
?>
	 {{ Form::hidden('pageIndex','karmameeting',array('class'=>'pageIndex')); }}
	<section class="mainWidth">
	    <div class="col-md-10 centralize profilepage pdding0 clearfix note meeting">
	      	@if($ReceiverInMeeting->isEmpty() &&  $countMeet == 0)
	      		 
		       <div class=" ">
					KarmaMeeting is a meeting of a Karma Giver and a Karma Receiver. Karma Giver is someone who is skilled and willing to help. Karma Receiver is someone who is seeking help around a specific topic. There is no financial motivation on either side. 
					</br>
					To see who all can you request KarmaMeeting from, click here <a href="http://www.karmacircles.com/groups/karmasphere/1">http://www.karmacircles.com/groups/karmasphere/1</a>
		        </div>
		       
	        @else

	            <div class="tabbed">    
	            <!-- Nav tabs -->
	                <ul class="nav nav-tabs" role="tablist">
	                  <li class="active"><a href="#home" role="tab"  data-toggle="tab" onclick="changeTab('requestSent')">Requests Sent</a></li>
	                  <li><a href="#profile" role="tab" data-toggle="tab" onclick="changeTab('requestReceived')">Requests Received</a></li>
	                  <li><a href="#messages" role="tab" data-toggle="tab" onclick="changeTab('archivedRequest')">Archived Requests</a></li> 
	                </ul>
					@if($countRecord > 0 )
					<div class="backlink clearfix subTopLink">
						<p class="pull-right tab3">
							<a id="Recent" onclick="changeorder('Recent')" >Recent</a>
							<a id="TopGivers" onclick="changeorder('TopGivers')" >Top Givers</a>
							{{-- <a id="KarmaIntro" onclick="changeorder('KarmaIntro')" >KarmaIntro</a> --}}
							<a id="All" onclick="changeorder('All')" >All</a>
						</p>  
					</div>
					@endif

	                <!-- Tab panes -->
	                <div class="tab-content">
	                    <!--1st-->
	                    <div class="tab-pane active meetingsentresult" id="home">                            
	                        <!--Received list-->
	                        <?php //echo '<pre>';print_r($ReceiverInMeeting);die;?>	                        
	                        @foreach ($ReceiverInMeeting as $key => $element)
	                        	<?php

	                        		$rcv_profileURL = '';
	                        		if (isset($element['user_id_giver']['email']))
	                        		$rcv_profileURL = "profile/".strtolower($element['user_id_giver']['fname'].'-'.$element['user_id_giver']['lname']).'/'.$element['user_id_giver']['id'];

	                        	?>
	                        	

									<div class="col-sm-12 centralize clearfix introresult" > 
			                            <!-- single tab -->
			                              <div class="borderContainer mintopM0 col-xs-12">
			                                  <div class="col-xs-12 col-sm-5 pdding0 ">
			                                      <div class="noteBox tabtxt">
			                                          <div class="col-xs-5">
			                                              @if ($element['user_id_giver']['piclink'] == "" || $element['user_id_giver']['piclink'] == 'null')
			                                              		@if (isset($element['user_id_giver']['email']))
								                             	<a href="{{$rcv_profileURL}}" ><img alt="" src="/images/default.png" width="82" height="87"></a>
								                             	@else
								                             		<img alt="" src="/images/default.png" width="82" height="87">
								                             	@endif
								                          @else
								                          	@if (isset($element['user_id_giver']['email']))
								                                 <a href="{{$rcv_profileURL}}" ><img src="{{$element['user_id_giver']['piclink']}}" width="82" height="87"></a>
								                            @else
								                            	<img src="{{$element['user_id_giver']['piclink']}}" width="82" height="87">
								                           	@endif

								                          @endif
			                                          </div>
			                                          <div class="col-xs-7">
			                                          	@if (isset($element['user_id_giver']['email']))
			                                            <a href="{{$rcv_profileURL}}" >
				                                            <h4>
				                                              {{$element['user_id_giver']['fname']." ".$element['user_id_giver']['lname']}}
				                                            </h4>
			                                        	</a>
			                                        	@else
			                                        		 <h4>
				                                              {{$element['user_id_giver']['fname']." ".$element['user_id_giver']['lname']}}
				                                            </h4>
				                                        @endif

			                                            <p title="{{KarmaHelper::stringCut($element['user_id_giver']['headline'],80)}}">{{KarmaHelper::stringCut($element['user_id_giver']['headline'],80)}}</p><p><span></span></p>
			                                          </div>
			                                          <div class="clr"></div>
			                                            <div class="borderPic">
			                                              <ul>
			                                                <li><a href="{{$element['user_id_giver']['linkedinurl']}}"><img alt="" src="images/linkdin.png"></a></li>
			                                                 @if (isset($element['user_id_giver']['email']))
						                                          	 <li>
						                                            <a href="profile/<?php echo strtolower($element['user_id_giver']['fname'].'-'.$element['user_id_giver']['lname']).'/'.$element['user_id_giver']['id'] ;?>"><img alt="" src="images/krmaicon.png"></a>
						                                           <a href="<?php echo URL::to('/');?>/FAQs/KarmaPoints/1"> <span>{{$element['user_id_giver']['karmascore']}}</span></a>
						                                          </li>
						                                          @endif 
			                                              </ul>
			                                            </div>
			                                      </div>
			                                  </div>
			                                  <div class="col-sm-7 col-xs-12 textR">
			                                    <h4>{{ date('F d,Y',strtotime($element->updated_at))}}</h4>

			                                    <p>{{$element['user_id_giver']['location']}}</p>
			                                    <div class="clr"></div>
			                                     <div class="recive">
			                                     <?php $getActionStateMessage=KarmaHelper::getMykarmaMessageForReceiverGiver($element->status,'Receiver');?>
		                                   			 @if ($element->status == 'pending' )
				                                		<button type="button" class="btn btn-success toggleBtn pending">{{$getActionStateMessage}}</button>
				                                	@elseif($element->status == 'archived')
				                                		 <button type="button" class="btn btn-success toggleBtn">{{$getActionStateMessage}}</button>
				                                	@elseif($element->status == 'confirmed')
				                                		<button type="button" class="btn btn-success toggleBtn accept">{{$getActionStateMessage}}</button>
				                                	@elseif($element->status == 'completed')
				                                		<button type="button" class="btn btn-success toggleBtn completed">{{$getActionStateMessage}}</button>
				                                	@elseif($element->status == 'over')
				                                		<button type="button" class="btn btn-success toggleBtn completed">{{$getActionStateMessage}}</button>
				                                	@elseif($element->status == 'scheduled')
				                                		<button type="button" class="btn btn-success toggleBtn completed">{{$getActionStateMessage}}</button>
				                                	@elseif($element->status == 'cancelled')
				                                		<button type="button" class="btn btn-success toggleBtn completed">{{$getActionStateMessage}}</button>
				                                	@elseif($element->status == 'responded')
				                                		<button type="button" class="btn btn-success toggleBtn completed">{{$getActionStateMessage}}</button>
				                                	@elseif($element->status == 'happened')
				                                		<button type="button" class="btn btn-success toggleBtn completed">{{$getActionStateMessage}}</button>
				                                	@endif		
			                                    </div> 
												<div class="recive">       
												<a href="/meeting/<?php echo strtolower($element['user_id_receiver']['fname'].'-'.$element['user_id_receiver']['lname'].'-'.$element['user_id_giver']['fname'].'-'.$element['user_id_giver']['lname']);?>/{{$element['id']}}"><button type="button" class="btn btn-success smlGrnbtn">See Meeting Request Details</button></a>
												</div> 

			                                  </div>
			                                  <div class="clr"></div>
			                                  

			                            </div>
			                          <!-- <div class="recive">       
			                      			<a href="/meeting/{{$element['id']}}/{{$element['user_id_receiver']['fname'].'-'.$element['user_id_receiver']['lname'].'_'.$element['user_id_giver']['fname'].'-'.$element['user_id_giver']['lname']}}"><button type="button" class="btn btn-success smlGrnbtn">See Meeting Request Details</button></a>
			                          </div> -->
			                <!-- single tab/ -->
			                      </div>


		                        	<?php $countSent++; ?>
		                        
	                        @endforeach	 
	                        @if($countSent == '0')
		                    	<div class="centerText">
		                    	<p>You haven't sent any meeting request yet!</p>
		                   		</div>
		                    @endif   
		                    @if($countSent > MEETINGPERPAGE)
                            <div class="clr"></div>
                            <div class="loadMore" id ="showMore">
                                <span>Load More</span>
                            </div>
                            @endif 
	                        <!--Received list-->
	                    </div>
	                    <!--1st-->
	                    <!--2nd-->
	                    <div class="tab-pane meetingReceivedresult" id="profile" >
	                        <!--sent list-->
	                        <?php //echo '<pre>';print_r($GiverInMeeting);?>		                       
		                        @foreach ($GiverInMeeting as $element)
		                        	@if($element->status != 'archived')
										
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
					                                            <a href="<?php echo URL::to('/');?>/FAQs/KarmaPoints/1"><span>{{$element['user_id_receiver']['karmascore']}}</span></a>
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
			                                     <?php $getActionStateMessage=KarmaHelper::getMykarmaMessageForReceiverGiver($element->status,'Giver');?>
		                                   			 @if ($element->status == 'pending' )
				                                		<button type="button" class="btn btn-success toggleBtn pending">{{$getActionStateMessage}}</button>
				                                	@elseif($element->status == 'archived')
				                                		 <button type="button" class="btn btn-success toggleBtn">{{$getActionStateMessage}}</button>
				                                	@elseif($element->status == 'confirmed')
				                                		<button type="button" class="btn btn-success toggleBtn accept">{{$getActionStateMessage}}</button>
				                                	@elseif($element->status == 'completed')
				                                		<button type="button" class="btn btn-success toggleBtn completed">{{$getActionStateMessage}}</button>
				                                	@elseif($element->status == 'over')
				                                		<button type="button" class="btn btn-success toggleBtn completed">{{$getActionStateMessage}}</button>
				                                	@elseif($element->status == 'scheduled')
				                                		<button type="button" class="btn btn-success toggleBtn completed">{{$getActionStateMessage}}</button>
				                                	@elseif($element->status == 'cancelled')
				                                		<button type="button" class="btn btn-success toggleBtn completed">{{$getActionStateMessage}}</button>
				                                	@elseif($element->status == 'responded')
				                                		<button type="button" class="btn btn-success toggleBtn completed">{{$getActionStateMessage}}</button>
				                                	@elseif($element->status == 'happened')
				                                		<button type="button" class="btn btn-success toggleBtn completed">{{$getActionStateMessage}}</button>
				                                	@endif		
			                                    </div> 
			                                     <div class="recive">       
			                      		 			<a href="/meeting/<?php echo strtolower($element['user_id_receiver']['fname'].'-'.$element['user_id_receiver']['lname'].'-'.$element['user_id_giver']['fname'].'-'.$element['user_id_giver']['lname']);?>/{{$element['id']}}"><button type="button" class="btn btn-success smlGrnbtn">See Meeting Request Details</button></a>
			                          			</div>
			                                  </div>
			                                  <div class="clr"></div>
			                                  
			                            </div>
			                         <!--  <div class="recive">       
			                      		 <a href="/meeting/{{$element['id']}}/{{$element['user_id_receiver']['fname'].'-'.$element['user_id_receiver']['lname'].'_'.$element['user_id_giver']['fname'].'-'.$element['user_id_giver']['lname']}}"><button type="button" class="btn btn-success smlGrnbtn">See Meeting Request Details</button></a>
			                          </div> -->
			                <!-- single tab/ -->
			                      </div>
		                        	   <?php $countRec++; ?>
		                        	@endif	
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
	                        <!--Sent list-->
	                    </div>
	                    <!--2nd-->
	                    
	                    <!--3rd-->
	                    <div class="tab-pane meetingArchiveresult" id="messages">
		                    <!--Archived list-->	                    
		                        @foreach ($GiverInMeeting as $element)
		                        	@if($element->status == 'archived')
										<div class="col-sm-12 centralize clearfix introresult" > 
			                            <!-- single tab -->
			                              <div class="borderContainer mintopM0 col-xs-12">
			                                  <div class="col-sm-5 col-xs-12 pdding0 ">
			                                      <div class="noteBox tabtxt">
			                                          <div class="col-xs-5">
			                                             @if ($element['user_id_receiver']['piclink'] == "" || $element['user_id_receiver']['piclink'] == 'null')
							                             	<a href="profile/<?php echo strtolower($element['user_id_receiver']['fname'].'-'.$element['user_id_receiver']['lname']).'/'.$element['user_id_receiver']['id'] ;?>"><img alt="" src="/images/default.png" width="82" height="87"></a>
							                            @else
							                                 <a href="profile/<?php echo strtolower($element['user_id_receiver']['fname'].'-'.$element['user_id_receiver']['lname']).'/'.$element['user_id_receiver']['id'] ;?>"><img src="{{$element['user_id_receiver']['piclink']}}" width="82" height="87"></a>
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
					                                            <a href="<?php echo URL::to('/');?>/FAQs/KarmaPoints/1"><span>{{$element['user_id_receiver']['karmascore']}}</span></a>
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
			                                     <?php $getActionStateMessage=KarmaHelper::getMykarmaMessageForReceiverGiver($element->status,'Receiver');?>
		                                   			 @if ($element->status == 'pending' )
				                                		<button type="button" class="btn btn-success toggleBtn pending">{{$getActionStateMessage}}</button>
				                                	@elseif($element->status == 'archived')
				                                		 <button type="button" class="btn btn-success toggleBtn">{{$getActionStateMessage}}</button>
				                                	@elseif($element->status == 'accepted')
				                                		<button type="button" class="btn btn-success toggleBtn accept">{{$getActionStateMessage}}</button>
				                                	@elseif($element->status == 'completed')
				                                		<button type="button" class="btn btn-success toggleBtn completed">{{$getActionStateMessage}}</button>
				                                	@endif		
			                                    </div>  
												<div class="recive">        
												 <a href="/meeting/<?php echo strtolower($element['user_id_receiver']['fname'].'-'.$element['user_id_receiver']['lname'].'-'.$element['user_id_giver']['fname'].'-'.$element['user_id_giver']['lname']);?>/{{$element['id']}}"><button type="button" class="btn btn-success smlGrnbtn">See Meeting Request Details</button></a>
												</div> 
			                                  </div>
			                                  <div class="clr"></div>
			                                  
			                            </div>
			                          
			                <!-- single tab/ -->
			                      </div>	
		                        	
		                        	 <?php $countArc++; ?>	
		                        	@endif	
		                        @endforeach	
		                    @if($countArc == '0')
								<div class="centerText">   
								<p>You haven't archived any meeting request yet!</p>
								</div>
		                    @endif
		                    @if($countArc > MEETINGPERPAGE)
                            <div class="clr"></div>
                            <div class="loadMore" id ="showArchiveMore">
                                <span>Load More</span>
                            </div>
                            @endif 
		                    <!--Sent list-->
		                    </div>
		                    <!--3rd-->
	                </div>
	            </div>
	            @endif
	        </div>
	    </div>    
	</section>
<script type="text/javascript">
$('.subTopLink').hide();
	var itemsSentCount = 0,
	itemsRecCount = 0,
	itemsArcCount = 0,
	itemsSentMax = $('.meetingsentresult div.sentresults').length;
	itemsReceivedMax = $('.meetingReceivedresult div.receivedresult').length;
	itemsArchiveMax = $('.meetingArchiveresult div.archiveresult').length;
	//alert(itemsReceivedMax);
	  $('.meetingsentresult div.sentresults').hide();
	  $('.meetingReceivedresult div.receivedresult').hide();
	  $('.meetingArchiveresult div.archiveresult').hide();

function showNextItems() {
      var pagination = <?php echo MEETINGPERPAGE; ?>;
      for (var i = itemsSentCount; i < (itemsSentCount + pagination); i++) {
          $('.meetingsentresult div.sentresults:eq(' + i + ')').show();
      }

      itemsSentCount += pagination;
      
      if (itemsSentCount > itemsSentMax) {
          $('#showMore').hide();
      }
};

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

function showNextArchiveItems() {
      var pagination = <?php echo MEETINGPERPAGE; ?>;
      
      for (var i = itemsArcCount; i < (itemsArcCount + pagination); i++) {
          $('.meetingArchiveresult div.archiveresult:eq(' + i + ')').show();
      }

      itemsArcCount += pagination;
      
      if (itemsArcCount > itemsArchiveMax) {
          $('#showArchiveMore').hide();
      }
}; 
 
showNextItems();
showNextReceivedItems();
showNextArchiveItems();

$('#showMore').on('click', function (e) {
  e.preventDefault();
  showNextItems();
});
$('#showReceivedMore').on('click', function (e) {
  e.preventDefault();
  showNextReceivedItems();
});
$('#showArchiveMore').on('click', function (e) {
	alert("aaya");
  e.preventDefault();
  showNextArchiveItems();
});
var url = window.location.href;
var chk = url.split("#"); 
if(chk[1] == 'profile'){

activaTab('profile');
$('.subTopLink').show();

}

function activaTab(tab){
	$('.nav-tabs a[href="#' + tab + '"]').tab('show');
};


function changeTab(action){
	if(action == 'requestReceived')
		$('.subTopLink').show();
	else
		$('.subTopLink').hide();
} 

function  changeorder (setting) { 
    $(".tab3").find("a").removeAttr("style");
    var currentTab =  'requestReceived';

    $.post('/request/getdataByorder',{ currentTab: currentTab,setting: setting}, function(data, textStatus, xhr) {
    if(currentTab == 'requestReceived'){
        $('.meetingReceivedresult').html(''); 
        $('.meetingReceivedresult').html(data);
    }
     $('#'+setting).css('background','#ededed');  
});

}
</script>

@stop
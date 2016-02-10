@extends('common.master')
@section('content')       
<section class="mainWidth">
        <div class="col-lg-10 col-md-10 col-sm-12 centralize pdding0">
           <!--  <div class="backlink pull-right clearfix">
                <a href="/dashboard">Back to Karma Circle</a>
            </div> -->
            <div class="col-md-11 col-sm-12 centralize">
                <div class="registrFrm sendnotes col-md-12">
                {{ Form::open(array('url' => 'submitIntroform' , 'method' => 'post','onsubmit'=>'return checkIntrovalidation()')) }}
                        <div class="col-md-9 col-sm-12 centralize infoFrm">                          
                            <div class="margin-bot20">
                                <div class="select col-xs-4 pdding0">
                                    <p>Karma Receiver</p>
                                </div>
                                <div class="select col-xs-8 pdding0">
                                    <div class="SearchboxIntro">
                                        <div id="ReceiverDisp" ></div>
                                            {{ Form::text('searchUser','',array('class'=>'form-control','id'=> 'searchReceiverKeyword', 'onkeyup'=>'searchKarmaReceiver()', 'autocomplete'=>'off')); }}

                                       <div id="searchresult" class="displayNone searchReceiverresult"></div>
                                    </div>
                                <div class="col-xs-12 pdding0" id= "receiverImage"></div>
                                </div>
                                
                            </div>


                              <div class="margin-bot20">
                                <div class="select col-xs-4 pdding0">
                                    <p>Karma Giver</p>
                                </div>
                                <div class="select col-xs-8 pdding0">
                                     <div class="SearchboxIntro">
                                        <div id="GiverDisp"></div>
                                            {{ Form::text('searchUser','',array('class'=>'form-control','id'=> 'searchGiverKeyword', 'onkeyup'=>'searchKarmaGiver()', 'autocomplete'=>'off')); }}
                                        <div id="searchresult" class="displayNone searchGiverresult"></div> 
                                        <div class="col-xs-12 pdding0" id= "giverImage" ></div>   
                                    </div>                        
                                </div>
                            </div>
                           
                    <div class="IntroContent" style="display:none;">
                            <div class="margin-bot20">
                                <div class="select col-xs-4 pdding0">
                                    <p>Details</p>
                                </div>
                                <div class="select col-xs-8 pdding0">
                                    {{ Form::textarea('note','',array('id'=>'note','class'=>'form-control','rows'=>'4')); }}
                                </div>
                            </div>

                            <div class="margin-bot20" style="display:none" id="giverMailbox">
                                <div class="select col-xs-4 pdding0">
                                    <p>Email (Optional)</p>
                                </div>
                                <div class="select col-xs-8 pdding0">
                                    <input type="email" class="form-control" name="giver_email" id="giver_email" placeholder='' >
                                </div>
                            </div>
                            <div class="margin-bot20" style="display:none" id="giverMailboxRequired">
                                <div class="select col-xs-4 pdding0">
                                    <p>Email</p>
                                </div>
                                <div class="select col-xs-8 pdding0">
                                    <input type="email" class="form-control" name="giver_email" id="giver_email" placeholder='' >
                                </div>
                            </div>

                             <div class="clr"></div>                    
                            <div class="sendnoteBox">
                                <ul class="iconList iconListNO col-md-11 centralize">
                                    <p id='returnpara'>In gratitude, I will do the following -</p>
                                    <li>{{Form::checkbox('receiverWR[]', "I'd pay it forward", true); }}I'll pay it forward.</li>
                                     <li>{{Form::checkbox('receiverWR[]', "I'd send you a Karma Note", true); }}I'll send you a <a href="/FAQs/KarmaNotes/1" target="_blank">KarmaNote</a>.</li>
                                    </li>
                                    
                                </ul>
                            </div>
                            
                    </div>
                    <div class="clr"></div>
                      <div align="center" class="minBtn">
                        <span class='error'></span>
                       </div>
                        <div class="clr"></div>
                    <div align="center" class="minBtn">
                       
                         <a href="{{URL::previous()}}"><button type="button" class="btn btn-warning">Cancel</button></a>
                        {{Form::submit('Request Meeting',array('class'=>'btn btn-success'));}}
                    </div>
                      {{ Form::close() }}
                   
                    </div>
                </div>
            </div>
        </div>  
        <div class="modal" style="display:none" id="LimitBox">
       <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button aria-label="Close" onclick="modelClose('LimitBox');" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Enter email address!</h4>
          </div>
          <div class="modal-body">   
            <p id="Limitboxmsg">Please specify the email address of to send this message.</p>
          </div>
          <div class="modal-footer">
           <button data-dismiss="modal" class="btn btn-default linkfullBTN gpBtn pull-right" type="button" onclick="modelClose('LimitBox');">Continue</button>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog --> 
    </div> 
</section>
    <!-- /Main colom -->
<script type="text/javascript">
    var xhr = null;
   function searchKarmaGiver(){
    //alert('asda');
        if( xhr != null ) {
                xhr.abort();
                xhr = null;
        }
        var keyword = $('#searchGiverKeyword').val();
        var optionVal = 'People';
        $('div.searchGiverresult').hide();
       // alert(keyword+'---'+optionVal);
        if((optionVal == 'People' || optionVal == 'Groups' || optionVal == 'Skills') && keyword != ''){
           var url='<?php echo URL::to('/');?>/ajaxsearchuserIntroGiver?searchUsers='+keyword+'&searchCat='+optionVal;
            //alert(url);
              xhr=   $.get(url,function(data) {
                if(data==""){
                    $('.searchGiverresult').html('');
                }
                else{
                    $('div.searchGiverresult').show();
                    $("div.searchGiverresult").html(data);
                }
            });    
        }
        else{
          return false;
        }
    }
      var xhrh = null;
   function searchKarmaReceiver(){
    //alert('asda');
        if( xhrh != null ) {
                xhrh.abort();
                xhrh = null;
        }
        var keyword = $('#searchReceiverKeyword').val();
        var optionVal = 'People';
        $('div.searchReceiverresult').hide();
       // alert(keyword+'---'+optionVal);
        if((optionVal == 'People' || optionVal == 'Groups' || optionVal == 'Skills') && keyword != ''){
           var url='<?php echo URL::to('/');?>/ajaxsearchuserIntroReceiver?searchUsers='+keyword+'&searchCat='+optionVal;
            //alert(url);
              xhrh=   $.get(url,function(data) {
                if(data==""){
                    $('.searchReceiverresult').html('');
                }
                else{
                    $('div.searchReceiverresult').show();
                    $("div.searchReceiverresult").html(data);
                }
            });    
        }
        else{
          return false;
        }
    }
    function validateSearch(){
          var keyword = $('#searchKeyword').val();
          if(keyword == ''){
            return false;
          }
    }
    function removeNameGiver() {
        $("#GiverDisp").html('');
        $('#Introsubject').val('');
        $('.IntroContent').css('display','none');
        $('#giverImage').html(" ");
            }
    function removeNameRec() {
         $("#ReceiverDisp").html('');
         $('#Introsubject').val('');
         $('.IntroContent').css('display','none');
         $('#receiverImage').html(" "); 

    }
    function  checkIntrovalidation() {

       var ReceiverDisp =  $('#ReceiverDisp').html();
       var GiverDisp= $('#GiverDisp').html();
        if(ReceiverDisp == "" && GiverDisp == ""){            
            $('.error').html('Please select karma Giver & karma Receiver');
            return false;
        }   
        else if(ReceiverDisp == ''){          
            $('.error').html('Please select karma Receiver');
            return false;
        }
        else if(GiverDisp == ''){            
            $('.error').html('Please select karma Giver');
            return false;
        }
        else{
           var giver_conn_id =   $('#giver_conn_id').val();
           var receiver_conn_id =   $('#receiver_conn_id').val();
            var detail =   $('#note').val();
           if(giver_conn_id == receiver_conn_id){
              $('.error').html("karma Giver & karma Receiver can't be same");
               return false;
           }
           
            if(detail == ''){
               $('.error').html("Please Input Intro details");
                return false;
           }         
             
        }

    }
    $(document).ready(function() {
		$("body").click(function(event ){
			var $target = $(event.target);
			if(!$target.parents().is("#searchresult") && !$target.is("#searchresult")){
				$("body").find(".searchGiverresult").hide();
				$("body").find(".searchReceiverresult").hide();
			}
		});
		
		// 24-12-2014 added to hide error messages on blurr 
		$('input').blur(function(){
			if($(this).val() != ''){
				$('.error').html(" ");  
			}
		});
    });
</script>
@stop
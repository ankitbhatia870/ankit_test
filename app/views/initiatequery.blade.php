@extends('common.master')
@section('content')
<section class="mainWidth">
        <div class="col-lg-10 col-md-10 col-sm-12 centralize pdding0">
           <!--  <div class="backlink clearfix subTopLink">
                
                <p class="pull-right">
                    <a href="">Back to Karma Circle</a>
                </p>
            </div> -->
              {{ Form::open(array('url' => 'submitQuery' , 'method' => 'post')) }}
            <div class="col-md-11 col-sm-12 centralize">
                <div class="registrFrm sendnotes col-md-12">
                        <div class="col-md-9 col-sm-12 centralize infoFrm">
                            <div class="margin-bot20 clearfix">
                                <div class="select col-xs-4 pdding0">
                                    <p>Subject</p>
                                </div>
                                <div class="select col-xs-8 pdding0">
                                    <input type="text" name="subject" maxlength="140" value="" placeholder="" class="form-control" required='required'>
                                    <input type="hidden" name="receiver_id" value="{{$CurrentUser->id;}}" placeholder="" class="form-control">
                                </div>
                            </div>

                            <div class="margin-bot20 clearfix" style="height:auto;">
                                <div class="select col-xs-4 pdding0">
                                    <p><p>Description</p></p>
                                </div>
                                <div class="select col-xs-8 pdding0">
                                     <textarea class="form-control" name="description" rows="4" required='required'></textarea>
                                </div>
                            </div>

                            <div class="margin-bot20 clearfix">
                                <div class="select col-xs-4 pdding0">
                                    <p>Skills(upto 3)</p>
                                </div>
                                <div class="select col-xs-8 pdding0 ">
                                   <div class="SearchboxIntro">
                                        <div id="SkillDisp" ></div>
                                            {{ Form::text('searchUser','',array('class'=>'form-control','id'=> 'searchskill', 'autocomplete'=>'off')); }}
                                       <div id="searchresult" class="displayNone searchReceiverresult"></div>
                                    </div>
                                </div>
                            </div>
                            <input name='privacysetting'  type="hidden" checked='checked' value='public'>

                            <!-- <div class="margin-bot20 clearfix">
                                <div class="select col-xs-4 pdding0">
                                    <p>Visibility</p>
                                </div>
                                <div class="select col-xs-8 pdding0  ">
                                   <label><input name='privacysetting' onclick="shareonLink('public')" type="radio" checked='checked' value='public'> Public</label>
                                    @if($inkcGroup == 1)
                                   <label><input name='privacysetting' type="radio" disabled="disabled" value='private'>Private (Can be viewed by group members only)</label>  
                                   @else
                                    <label><input name='privacysetting' onclick="shareonLink('private')" type="radio" <?php // if(empty($Usergroup)){ echo 'disabled';}?> value='private'>Private (Can be viewed by group members only)</label>  
                                   @endif      
                                </div>
                               {{Form::hidden('user_groups', $Usergroup)}}
                                <div class="clr"></div>
                            </div> -->
                             {{Form::hidden('user_groups', $Usergroup)}}
                            <div class="clr"></div>
                        </div> 
                         <div class="clr"></div>
                         <div align="center">
                              <input type="checkbox" checked="checked" name ='shareOnLinkedin' id="shareOnLinkedin" value="1"> <label>Share this query on Linkedin</label>
                          </div>
                         <!-- @if($inkcGroup == 1)
                          <div align="center">
                              <input type="checkbox" checked="checked" name ='shareOnLinkedin'  value="1" disabled="disabled"> <label>Share this query on Linkedin</label>
                          </div>
                          @else
                          <div align="center">
                              <input type="checkbox" checked="checked" name ='shareOnLinkedin' id="shareOnLinkedin" value="1"> <label>Share this query on Linkedin</label>
                          </div>
                          @endif-->
                       
                    </div>
                    <div class="clr"></div>
                    <div align="center" class="minBtn">
                         <a href="{{URL::previous()}}"><button type="button" class="btn btn-warning">Cancel</button></a>
                         {{Form::submit('Post',array('class'=>'btn btn-success'));}}
                         {{Form::close()}}
                    </div>
                </div>
            </div>
        </div>  
</section>
    <!-- /Main colom -->


    <script type="text/javascript">
   
   
    $('#searchskill').keydown(function(){
           clearTimeout(timer); 
           timer = setTimeout(callmeSearch, 200)  
    });
 
   var xhrh = null; 
   function callmeSearch(){ 
        
        if( xhrh != null ) {
                xhrh.abort();
                xhrh = null; 
        }
        var keyword = $('#searchskill').val();
        var optionVal = 'People';
        $('div.searchReceiverresult').hide();
       // alert(keyword+'---'+optionVal);
        if(keyword != ''){
           var url='<?php echo URL::to('/');?>/ajaxsearchskillsforquery?searchskill='+keyword;
            //alert(url);
              xhrh=   $.get(url,function(data) {
                if(data==""){
                    $('.searchReceiverresult').html('');
                }
                else{
                    $('div.searchReceiverresult').show();
                    $('div.searchReceiverresult').focus(); 
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
    function removeskill(id) {
         $(".skilldisp_"+id).remove();
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
           var subject =   $('#Introsubject').val();
           var detail =   $('#note').val();
           if(giver_conn_id == receiver_conn_id){
              $('.error').html("karma Giver & karma Receiver can't be same");
               return false;
           }
           if(subject == ''){
               $('.error').html("Please Input Intro subject");
                return false;
           }
            if(detail == ''){
               $('.error').html("Please Input Intro details");
                return false;
           }         
             
        }

    }
    function shareonLink (privacysetting) {
       if(privacysetting == 'public'){
        $('#shareOnLinkedin').attr('disabled', false);
       }
       else{
        $('#shareOnLinkedin').attr('disabled', true);
        $('#shareOnLinkedin').attr('checked', false);
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
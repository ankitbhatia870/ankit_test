@extends('admin.common.master')
@section('content')
<section class="mainWidth">
  {{ Form::open(array('url' => 'getreport' , 'method' => 'post')) }}
  <div class="table-responsive report_table ">
    <div class="table-row clearfix">
   <div class="col-sm-5">
  {{Form::label("Email", "Email")}}
  {{Form::input('email', '',"",array('id'=>'emailId', 'class'=>'adminsearchUI searchUser','autocomplete'=>'on'))}}
  </div> 
  <div class="col-sm-3" id='datetimepicker'>
    {{Form::label("Begin Date", "Begin Date")}}
  {{Form::input('searchUser', 'searchUser',"",array('class'=>'adminsearchUI searchUser','autocomplete'=>'off','id'=>'begin_date'))}}
    <span class="glyphicon-calendar glyphicon"></span>
  </div>
  <div class="col-sm-3" id='datetimepicker1'>
    {{Form::label("End Date ", "End Date")}}
  {{Form::input('searchUser', 'searchUser',"",array('class'=>'adminsearchUI searchUser','autocomplete'=>'off','id'=>'end_date'))}} 
    <span class="glyphicon-calendar glyphicon"></span>
  </div>
</div>
   <input type="button" value="Generate Report" class="btn btn-success" onclick="validateReport();" >
</div>
{{ Form::close() }}
</section>
  <div class="loderBox" style="margin-top:0px">
    <div id="loader"><img src="/images/loader.gif" /></div>
  </div> 

	<div id="reportdata"></div>
    <SCRIPT TYPE="text/javascript">
    $(function () {
        $('#loader').hide();
        $('#datetimepicker , #datetimepicker1').datetimepicker({
            pickTime: false
        });
    });

    function validateReport(){
      var end = $('#end_date').val();
      var begin = $('#begin_date').val();
      var email = $('#emailId').val();
    
      if(begin !="" && end == ""){
        alert("Please choose end date.");return false;
      }
      else if(begin == "" && end != ""){
        alert("Please choose begin date.");return false;
      }
      else
      {
        $.ajax({
            url: '<?php echo URL::to('/');?>/admin/getreport_data?email='+email+'&begin='+begin+'&end='+end,  
            type: "POST", 
            cache:false,
            async: true, 
            beforeSend: function(){
              $('#loader').show(); 
            },
            complete: function(){
              //clearconsole();
              $('#loader').hide();
            },
            success: function(data){
               
                $('#reportdata').html(data);
            }, 
            error: function(){
            }           
        });
        return false;
      }
      return false;
    }
    </SCRIPT>

@stop  

@extends('common.master')
@section('content')
    <section class="mainWidth greenBg ldprofile clearfix">
        <div class=" col-sm-10 centralize ">
            <div class="col-sm-3 col-xs-3">
              <div class="dpcontainer">
               
                @if ($CurrentUser->piclink == '')
                  <img alt="" src="/images/default.png">
                @else
                <img src="{{ $CurrentUser->piclink;}}" class="img-responsive"  alt = "{{$CurrentUser->fname;}}" title = "{{$CurrentUser->fname;}}">
                @endif 
                
               <!--  <img src="images/linkdin.png" class="linkdinIcon"> -->
              </div>  
            </div>
            <div class="col-sm-4  col-xs-5 profileDeatils">
                <h2>{{ $CurrentUser->fname.' '.$CurrentUser->lname ;}}</h2>
                <p>{{ $CurrentUser->headline;}}</p>
                <p>{{ $CurrentUser->location;}}</p>
            </div>
        </div>
    </section>
    <section class="mainWidth">
    {{ Form::open(array('url' => 'savegroupsetting' , 'method' => '  post', 'onsubmit'=>'return updategroup();')) }}
        <div class="col-sm-10 centralize clearfix">
            <h3>Update Group</h3>
            <div class="registrFrm col-md-12 ">               
                <div class="frmBox">                   
                    <div class="form-group clearfix">
                      {{ Form::label('', '',array('style'=>'display:block;'))}}
                        <div class="col-sm-12 centralize pull-left">
                          @if(!empty($groups))    
                         
                              <ul class="grouplist tag checkBoxtag" id = "userSkills">
                                  @foreach ($groups as $group)
                                      <li id = "skillList_{{$group->id}}" >
                                          <label >
                                              {{$group->name}}
                                              <input type="checkbox"   id="group_{{$group->id}}" value = "{{$group->id}}" name="Groups[]" >
                                          </label>    
                                      </li>
                                  @endforeach
                              </ul>
                              <span class='error'></span>
                          @endif                              
                        </div>
                   <!-- <div class="form-group clearfix meeting_setting">                     
                       <div class="select col-sm-10 pdding0"> 
                          @if ($CurrentUser->meeting_setting == 'accept from all')
                            {{Form::checkbox('meeting_setting', '1',"","" )}} 
                          @else
                             {{Form::checkbox('meeting_setting', '1',"checked","" )}} 
                          @endif               
                         
                          <span>Only members of selected groups can request meeting from me.</span>
                        </div> 
                    </div> -->
                    <div align="center">  
                        
                    </div>
                <div align="center">   
                      <a href="{{URL::previous()}}"> {{Form::button('Cancel',array('class'=>'btn btn-warning','onclick'=>'test()'));}}</a>
                      {{Form::submit('Submit',array('class'=>'btn btn-success'));}}
                </div>
                {{ Form::close() }}
            </div>
        </div>
      <div class="modal" style="display:none" id="UpdateGroup">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button aria-label="Close" onclick="modelClose('UpdateGroup');" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">Are you sure?</h4>
            </div>
            <input type="hidden" id="group-id" value="">
            <div class="modal-body group-body" >
              <p>Please join this group only if you belong to this group. If you don't belong to this group, your membership may be cancelled. Are you sure that you want to join?</p>
            </div>
            <div class="modal-footer">  
             
              <button data-dismiss="modal" class="btn btn-default linkfullBTN gpBtn pull-right" type="button" onclick="cancelcalled();">No</button> 
               <button data-dismiss="modal" class="btn btn-default linkfullBTN gpBtn pull-right" type="button" onclick="okcalled();">Yes</button>
            </div>
          </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog --> 
      </div>
    </section>
    <script type="text/javascript">
      <?php $js_groups = array();
          $js_groups = json_encode($Usersgroups); 
          echo "var javascript_Usergroups = ". $js_groups . ";\n";?>
     
      $(document).ready(function() {     
      $.each( javascript_Usergroups, function( key, value ) {
            var group_id = value.id;

            $('.error').html(""); 
           
            $('#group_'+group_id).attr('checked','true');
            $('#skillList_'+group_id).addClass('darkBg');
          });  

          $('input:checkbox').click(function(){
          var grpId = $(this).attr('id');
          var gId = grpId.split('group_');
          var gId = gId[1];
          $('.error').html("");
        
          var len = $("input:checkbox:checked").length;
         
          if(len==0)
             {
               /* alert('You can not pick more than 3 skills');
                $('#group_'+id).attr('checked',false);
                $('#skillList_'+listId).removeClass('darkBg');*/
                 $('.error').html("You are required to be a part of at least one group to be on KarmaCircles.");
             }
          else{
          $('.error').html("");
          if($(this).is(':checked'))
          {
           
           if(gId !=1)
            {
              $('.group-body').html('Please join this group only if you belong to this group. If you don'+"'"+'t belong to this group, your membership may be cancelled. Are you sure that you want to join?'); 
              $('#group-id').val(gId);
              openboxmodel('UpdateGroup',''); 
            }
            $('#skillList_'+gId).addClass('darkBg');
          }
          else
          {
          
            if(gId ==1)
            {
              $('#group-id').val(gId);
              $('.group-body').html('If you remove yourself from KarmaSphere group, it will significantly limit the number of people who you can request meetings from. Are you sure that you want to leave?');
              openboxmodel('UpdateGroup',''); 
            }
            $('#skillList_'+gId).removeClass('darkBg');
          }

          }
           
          });

      });     
     function updategroup()
     {
        $('.error').html("");
        var len = $("input:checkbox:checked").length;
        if(len==0){
         $('.error').html("You are required to be a part of at least one group to be on KarmaCircles.");
         return false;
        }
        else{
          $('.error').html("");
          return true;
        }
     }

     function okcalled(){
        modelClose('UpdateGroup');
        var gId = $('#group-id').val();
        if(gId !=1){
            $('#skillList_'+gId).addClass('darkBg');
            $('#group_'+gId).prop('checked', true);
        }
        else{
          $('#skillList_'+gId).removeClass('darkBg'); 
          $('#group_'+gId).prop('checked', false);
        }
     }
      function cancelcalled(){
        modelClose('UpdateGroup');
        var gId = $('#group-id').val();
        if(gId !=1){
            $('#skillList_'+gId).removeClass('darkBg'); 
            $('#group_'+gId).prop('checked', false);
        }
        else{
          $('#skillList_'+gId).addClass('darkBg'); 
          $('#group_'+gId).prop('checked', true);
        }
      }


    </script>

    <!-- /Main colom -->
@stop
    

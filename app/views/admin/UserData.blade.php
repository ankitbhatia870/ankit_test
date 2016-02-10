<div class="UserFancyTabDetail" >
	<form action="/admin/updateUser" method="post">
		<div class="form-group">
		 <label for="exampleInputEmail1">Karma Id:</label>
		 {{Form::text("id", $element->id,  array('class'=>'form-control','readonly'=>'readonly'))}}
		</div>
		<div class="form-group">
		 <label for="exampleInputEmail1">Name</label>
		 {{Form::text("name", $element->fname." ".$element->lname,  array('class'=>'form-control'))}}
		</div>
		<div class="form-group">
		 <label for="exampleInputEmail1">Linkedin Id:</label>
		  {{Form::text("linkedinid",$element->linkedinid,  array('class'=>'form-control','readonly'=>'readonly'))}}
		</div>
		<div class="form-group">
		 <label for="exampleInputEmail1">KarmaScore</label>
		   {{Form::text("karmascore",$element->karmascore,  array('class'=>'form-control','readonly'=>'readonly'))}}
		</div>
		<div class="form-group">
		 <label for="exampleInputEmail1">Total Connections</label>
		   {{Form::text("totalConnectionCount",$element->totalConnectionCount,  array('class'=>'form-control','readonly'=>'readonly'))}}
		</div>
		<div class="form-group">
		 <label for="exampleInputEmail1">Connections Received From Linkedin</label>
		   {{Form::text("connection_count",$connection_count,  array('class'=>'form-control','readonly'=>'readonly'))}}
		</div>
		<div class="form-group">
		 <label for="exampleInputEmail1">Profile Update Date:</label>
		{{Form::text("profileupdatedate", $element->profileupdatedate,  array('class'=>'form-control'))}}{{Form::hidden('id', $element->id)}}
		</div>
		<div class="form-group">
		 <label for="exampleInputEmail1">User Status:</label>
		{{Form::select('userstatus', array('pending'=>'Pending','TOS not accepted'=>'TOS not accepted','fetching connection'=>'Fetching Connection','ready for approval'=>'Ready For Approval','approved'=>'Approved','hidden'=>'Hidden'), $element->userstatus, array('class'=>'form-control'))}}
		</div>
		<div class="form-group">
		 <label for="exampleInputEmail1">User Role</label>
		{{Form::select('role', array('member'=>'Member','admin'=>'Admin'), $element->role, array('class'=>'form-control'))}}
		</div>
		
		<div class="form-group">
			<label for="exampleInputEmail1">User Groups</label>
	      	 <div class="col-sm-12 centralize pull-left">
	          @if(!empty($groups))    
	              <ul class="tag checkBoxtag" id = "userSkills">
	                  @foreach ($groups as $group)
	                      <li id = "skillList_{{$group->id}}" >
	                          <label>
	                              {{$group->name}}
	                              <input type="checkbox"   id="{{$group->id}}" value = "{{$group->id}}" name="Groups[]" >
	                          </label>    
	                      </li>
	                  @endforeach
	              </ul>
	              <span class='error'></span>
	          @endif                              
	        </div>
		</div>
		
		<div class="form-group">
			<span><a href="/admin/GetallConnections/{{$element->id}}" target="_blank">Get Raw Connection Data</a></span>
		</div>
		<div class="form-group">
		 {{Form::submit("Change", array('class'=>'btn btn-success','onclick'=>'return updateuser();'));}}
		 {{Form::button("Delete", array('class'=>'btn btn-success','onclick'=>'deleteUser('.$element->id.')'));}}
		</div>
	</form>
</div>


<script type="text/javascript">

<?php $js_groups = array();
          $js_groups = json_encode($Usersgroup); 
          echo "var javascript_Usergroups = ". $js_groups . ";\n";?>
     
      $(document).ready(function() {     
	      $.each( javascript_Usergroups, function( key, value ) {
	            var group_id = value.id;
	            $('#'+group_id).attr('checked','true');
	            $('#skillList_'+group_id).addClass('darkBg');
	          }); 

	      $('input:checkbox').click(function(){
            $('.error').html("");
            var len = $("input:checkbox:checked").length;
            
            if(len==0)
             $('.error').html("You are required to choose at least one group to be on KarmaCircles.");
            else
             $('.error').html("");
          });
      });     
  $(document).ready(function(){
        $('input[type="checkbox"]').click(function(){
            var id = $(this).attr('id');
            var listId = 'skillList_'+id;
            if($("#"+id).is(':checked')){
                $('#'+listId).addClass('darkBg');
            }else{
                $('#'+listId).removeClass('darkBg');
            }
        });
    })
	function deleteUser(id) { 

		
           if (confirm("Are you sure") == true) {
	       		$.post('/admin/deleteUser', {userId: id}, function(data, textStatus, xhr) {
	       			console.log(data);
	       			alert(data);
	       			//location.reload();
	       		});
		    } else {
		       return false;
		    }
       
	}

	function updateuser()
     { 
        $('.error').html("");
        var len = $("input:checkbox:checked").length;
        if(len==0){
         $('.error').html("You are required to choose at least one group to be on KarmaCircles.");
         return false;
        }
        else{ 
          $('.error').html("");
          return true;
        }
     }
	 
</script>

	
	
	
	

	



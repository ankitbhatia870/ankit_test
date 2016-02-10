<div class="UserFancyTabDetail" >
	<form action="/admin/editvanitydata" method="post"> 
		<div class="form-group">
		 <label for="exampleInputEmail1">Id</label>
		 {{Form::text("id",$vanityinfo->id,  array('class'=>'form-control','readonly'=>'readonly'))}}
		</div>
		<div class="form-group">
		 <label for="exampleInputEmail1">Name</label>
		 {{Form::text("vanityurl",$vanityinfo->vanityurl,  array('class'=>'form-control'))}}
		</div>
		<div class="form-group">
		 <label for="exampleInputEmail1">Redirect URL</label>
		 {{Form::textarea("redirecturl", $vanityinfo->redirecturl,array('class'=>'form-control','rows'=>'2','placeholder'=>'for user "/profile/fname-lname/{id}" and for group "/groups/name/{id}"'))}}
		  <label for="exampleInputEmail1">(Please replace space in name into "-" for group names and in lowercase. For example /groups/hatch-international/{id})</label>  
		</div>
		<div class="form-group">
		 {{Form::submit("Submit", array('class'=>'btn btn-success'));}}
		 {{Form::button("Delete", array('class'=>'btn btn-success','onclick'=>'deletevanityurl('.$vanityinfo->id.')'))}} 
		</div>
	</form>
</div>


<script type="text/javascript">
	function deletevanityurl(id) {
		 if (confirm("Are you sure") == true) {
	       		$.post('/admin/deletevanityurl', {vanityId: id}, function(data, textStatus, xhr) {
	       			alert(data);
	       			location.reload();
	       		});
		    } else {
		       return false;
		    }
	}
</script>
	
	
	

	



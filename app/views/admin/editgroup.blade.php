<div class="UserFancyTabDetail" >
	<form action="/admin/editgroupdata" method="post">
		<div class="form-group">
		 <label for="exampleInputEmail1">Group Id</label>
		 {{Form::text("id",$groupinfo->id,  array('class'=>'form-control','readonly'=>'readonly'))}}
		</div>
		<div class="form-group">
		 <label for="exampleInputEmail1">Group Name</label>
		 {{Form::text("name",$groupinfo->name,  array('class'=>'form-control'))}}
		</div>
		<div class="form-group">
		 <label for="exampleInputEmail1">Group Description</label>
		 {{Form::textarea("description", $groupinfo->description ,  array('class'=>'form-control'))}}
		</div>
		<div class="form-group">
		 <label for="exampleInputEmail1">Group URL</label>
		 {{Form::url("url",$groupinfo->url,  array('class'=>'form-control','placeholder'=>'http://example.com'))}}
		</div>
		<div class="form-group">
		 {{Form::submit("Submit", array('class'=>'btn btn-success'));}}
		 @if($groupinfo->id != 1) 
		 	{{Form::button("Delete", array('class'=>'btn btn-success','onclick'=>'deletegroup('.$groupinfo->id.')'));}}
		 @endif
		</div>
	</form>
</div>


<script type="text/javascript">
	function deletegroup(id) {
		 if (confirm("Are you sure") == true) {
				$.post('/admin/deletegroup', {groupId: id}, function(data, textStatus, xhr) {
	       			alert(data);
	       			location.reload();
	       		});
		    } else {
				return false;
		    }
	}
</script>
	
	
	

	



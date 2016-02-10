<div class="UserFancyTabDetail" >
	<?php //echo '<pre>';print_r($queryinfo);die;?>
	<form action="/admin/editquerydata" method="post">
		<div class="form-group">
		 <label for="exampleInputEmail1">Query Id</label>
		 {{Form::text("id",$queryinfo->id,  array('class'=>'form-control','readonly'=>'readonly'))}}
		</div>
		<div class="form-group">
		 <label for="exampleInputEmail1">Query Subject</label>
		 {{Form::text("subject",$queryinfo->subject,  array('readonly'=>'readonly','class'=>'form-control'))}}
		</div>
		<div class="form-group">
		 <label for="exampleInputEmail1">Query Description</label>
		 {{Form::textarea("description", $queryinfo->description ,  array('class'=>'form-control','maxlength'=>'150'))}}
		</div>
		
		<div class="form-group">
		 {{Form::submit("Submit", array('class'=>'btn btn-success'));}}
		 @if($queryinfo->id != 1) 
		 	{{Form::button("Delete", array('class'=>'btn btn-success','onclick'=>'deletequery('.$queryinfo->id.')'));}}
		 @endif
		</div>
	</form>
</div>


<script type="text/javascript">
	function deletequery(id) {
		 if (confirm("Are you sure") == true) {
				$.post('/admin/deletequery', {queryId: id}, function(data, textStatus, xhr) {
	       			alert(data);
	       			location.reload();
	       		});
		    } else {
				return false;
		    }
	}
</script>
	
	
	

	



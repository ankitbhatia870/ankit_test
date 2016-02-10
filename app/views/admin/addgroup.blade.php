<div class="UserFancyTabDetail" >
	<form action="/admin/addgroupdata" method="post">
		<div class="form-group">
		 <label for="exampleInputEmail1">Group Name</label>
		 {{Form::text("name","",  array('class'=>'form-control'))}}
		</div>
		<div class="form-group">
		 <label for="exampleInputEmail1">Group Description</label>
		 {{Form::textarea("description", "" ,  array('class'=>'form-control'))}}
		</div>
		<div class="form-group">
		 <label for="exampleInputEmail1">Group URL</label>
		 {{Form::url("url","",  array('class'=>'form-control','placeholder'=>'http://example.com'))}}
		</div>
		<div class="form-group"> 
		 {{Form::submit("Submit", array('class'=>'btn btn-success'));}}
		</div>
	</form>
</div>



	
	
	

	



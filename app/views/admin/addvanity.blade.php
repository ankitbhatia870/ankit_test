<div class="UserFancyTabDetail" >
	<form action="/admin/addvanitydata" method="post">
		<div class="form-group">
		 <label for="exampleInputEmail1"> Name</label>
		 {{Form::text("vanityurl","",  array('class'=>'form-control'))}}
		</div>
		<div class="form-group">
		 <label for="exampleInputEmail1">Redirect url()</label> 
		 {{Form::textarea("redirecturl", "" ,  array('placeholder'=>' for user "/profile/fname-lname/{id}" and for group "/groups/name/{id}"','class'=>'form-control','rows'=>'2'))}}
		  <label for="exampleInputEmail1">(Please replace space in group name into "-" for group names. For example /groups/hatch-international/{id})</label>  
		</div>
		<div class="form-group">
		 {{Form::submit("Submit", array('class'=>'btn btn-success'));}}
		</div>
	</form>
</div>



	
	
	

	



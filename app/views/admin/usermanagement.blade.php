@extends('admin.common.master')
@section('content')
<section class="mainWidth">
	<div class="table-responsive">
	{{Form::label("Search User ", "Search User by email")}}
	{{Form::input('searchUser', 'searchUser',"",array('class'=>'adminsearchUI searchUser','onkeyup'=>'SearchUser()','autocomplete'=>'off'))}}
	
	<div class="UserDetail" >
	    <table class="table">
	        <thead>
	            <tr>
	                <th>#</th>
	                <th>Email</th>
	                <th>Full Name</th>
	                <th>Linkedin Id</th>
	                <!-- <th>KScore</th> -->
	                <th>Profile Update Date</th>
	                <th>Status</th>
	                <th>Role</th>
	                <th>Linkedin Url</th>
	                <th>Edit</th>
	            </tr>
	        </thead>
	        <tbody id="userData">
	        	@foreach ($users as $key => $element)
	        		<tr>	        			 
		                <td>{{$key+1}}</td>
		                <td>{{$element->email}}</td>
		                <td>{{$element->fname." ".$element->lname}}</td>
		                <td>{{$element->linkedinid}}</td>
						<!-- <td>{{$element->karmascore}}</td> -->
						<td>{{$element->profileupdatedate}}</td>
						<td>{{$element->userstatus}}</td>
						<td>{{$element->role}}</td>
						
						<td align="center"> 
							<a href="{{$element->linkedinurl}}" target="_blank" ><img alt="" src="/images/linkdin.png" height="21" width="21"></a> 
						</td>
						<!-- <td>{{Form::submit("Change", array('class'=>'btn btn-success'))}}</td> -->
						<td><a class="edituserinfo fancybox.ajax" href="/admin/edituserinfo/<?php echo $element->id ?>" >Edit</a></td>
	            	</tr>
	        	@endforeach
	        </tbody>
	    </table>
    </div>
</div>

<script type="text/javascript">
	function SearchUser(){
		var searchName = $(".searchUser").val();
		$.post('/admin/adminSearchUserByEmail', {searchName:searchName}, function(data) {
			$('#userData').html(data);
		});
	} 
	$(".edituserinfo").fancybox({
		maxWidth  	: 600,
		maxHeight 	: 600,
		fitToView 	: false,
		autoSize  	: true,
		closeClick  : false,
		openEffect  : 'none',
		closeEffect : 'none',
		close  : [27]
	});
</script>
</section>
@stop
    

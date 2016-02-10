@extends('admin.common.master')
@section('content')
<section class="mainWidth">
	<div class="table-responsive">
	
	<a href="/admin/addGroup" class="addgroup fancybox.ajax clearfix"><button type="button" class="btn btn-success myButton">Add Group</button></a>
	<div class="UserDetail" >
	    <table class="table tablegroup">
	        <thead>
	            <tr>
	                <th>#</th>
	                <th>Name</th>
	                <th>Description</th>
	                <th>Members</th>
	                <th>Edit</th>
	            </tr>
	        </thead>
	        <tbody id="userGroup">
	        	@foreach ($groups as $key => $element)
	        		<tr>	        			 
		                <td>{{$key+1}}</td>
		                <td>{{$element->name}}</td>
		                <td>{{$element->description}}</td>
		                <td>{{$element->UserCount}}</td>
						<td><a class="editgroupinfo fancybox.ajax" href="/admin/editgroupinfo/<?php echo $element->id ?>" >Edit</a></td>
	            	</tr>
	        	@endforeach
	           
	            
	        </tbody>
	    </table>
    </div>
</div>

<script type="text/javascript">
	
	$(".addgroup").fancybox({
		maxWidth  	: 600,
		maxHeight 	: 600,
		fitToView 	: false,
		autoSize  	: true,
		closeClick  : false,
		openEffect  : 'none',
		closeEffect : 'none',
		close  : [27]
	});
	$(".editgroupinfo").fancybox({
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
    

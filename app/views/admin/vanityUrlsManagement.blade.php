@extends('admin.common.master')
@section('content')
<section class="mainWidth">
	<div class="table-responsive">
	
	<a href="/admin/addvanity" class="addvanity fancybox.ajax clearfix"><button type="button" class="btn btn-success myButton">Add Vanity Url</button></a>
	<div class="UserDetail" >
	    <table class="table">
	        <thead>
	            <tr>
	                <th>#</th>
	                <th>Name</th>
	                <th>Redirect URLs</th>
	                <th>Edit</th>
	            </tr>
	        </thead>
	        <tbody id="userGroup">
	        	@foreach ($vanityurls as $key => $element)
	        		<tr>	        			 
		                <td>{{$key+1}}</td>
		                <td>{{$element->vanityurl}}</td>
		                <td>{{$element->redirecturl}}</td>
						<td><a class="editvanityinfo fancybox.ajax" href="/admin/editvanityinfo/<?php echo $element->id ?>" >Edit</a></td>
	            	</tr>
	        	@endforeach
	        </tbody>
	    </table>
    </div>
</div>

<script type="text/javascript">
	
	$(".addvanity").fancybox({
		maxWidth  	: 600,
		maxHeight 	: 600,
		fitToView 	: false,
		autoSize  	: true,
		closeClick  : false,
		openEffect  : 'none',
		closeEffect : 'none',
		close  : [27]
	});
	$(".editvanityinfo").fancybox({
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
    

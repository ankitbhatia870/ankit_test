@extends('admin.common.master')
@section('content')
<section class="mainWidth">
	<div class="table-responsive">
	<?php //echo '<pre>';print_r($questions);die;?>
	
	<div class="UserDetail" >
	    <table class="table tablequery" style="text-align: none;">
	    	<thead>
	            <tr>
	                <th>#</th>
	                <th>Subject</th>
	                <th>Description</th>
	                <th>Query Status</th>
	                <th>Delete</th>
	            </tr>
	        </thead>
	        <tbody id="userGroup">
	        	@foreach ($questions as $key => $element)
	        		<tr>	        			 
		                <td>{{$key+1}}</td>
		                <td>{{$element->subject}}</td>
		                <td>{{$element->description}}</td>
		                <td>{{$element->queryStatus}}</td>
						<td><a class="editgroupinfo fancybox.ajax" href="/admin/editqueryinfo/<?php echo $element->id ?>" >Edit</a></td>
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
    

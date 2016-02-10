@extends('admin.common.master')
@section('content')
	<section class="mainWidth">
	<div class="table-responsive">	
	<div class="dashboardOption" >
	    <table class="table">
	        <thead>
	            <tr>
	                <th>#</th>
	                <th>Attribute Name</th>
	                <th>Attribute Value</th>
	                <th>Edit</th>
	                <th>Note</th>
	                	                
	            </tr>
	        </thead>
	        <tbody>	        	
	        		<tr> 
	        			{{ Form::open(array('url' => 'admin/updateRefreshtime' , 'method' => '  post')) }}
			                <td>1</td>
			                <td>{{Form::text("option_name",'Connection Refresh Time',  array('class'=>'form-control','readonly'=>'readonly'))}}</td>
			                @if (!empty($Admin_Refresh_option))
			                	<td>{{Form::text("option_value",$Admin_Refresh_option->option_value,  array('class'=>'form-control'))}}</td>
			                @else
			                	<td>{{Form::text("option_value",'360',  array('class'=>'form-control'))}}</td>
			                @endif							
							<td>{{Form::submit("Change", array('class'=>'btn btn-success'))}}</td>
							<td> Refresh Time is in hours. Minimum: 1 Hour</td>
						{{Form::close()}}
	            	</tr>
	            	<tr>
	        			{{ Form::open(array('url' => 'admin/updateRefreshtime' , 'method' => '  post')) }}
			                <td>2</td>
			                <td>{{Form::text("option_name",'KarmaNote Email Trigger Time',  array('class'=>'form-control','readonly'=>'readonly'))}}</td>
			                @if (!empty($Email_Trigger_Time_Karmanote))
			                	<td>{{Form::text("option_value",$Email_Trigger_Time_Karmanote->option_value,  array('class'=>'form-control'))}}</td>
			                @else
			                	<td>{{Form::text("option_value",'24',  array('class'=>'form-control'))}}</td>
			                @endif							
							<td>{{Form::submit("Change", array('class'=>'btn btn-success'))}}</td>
							<td>Time for Sending Email For Pending KarmaNote.Time is in hours. Minimum:1 Hour</td>
						{{Form::close()}}
	            	</tr>
	            	<tr>
	        			{{ Form::open(array('url' => 'admin/updateTestemail' , 'method' => '  post')) }}
			                <td>2</td>
			                <td>
			                	{{ Form::text('option_name','Test User Emails',  array('class'=>'form-control','readonly'=>'readonly')) }}
			                </td>
			                @if(isset($emailset))
			                 <td>
			                	{{ Form::textarea('email',$emailset,  array('class'=>'form-control')) }}
			                </td> 
			                @else
			                <td>
			                	{{ Form::textarea('email','Test User Emails',  array('class'=>'form-control')) }}
			                </td> 
			                @endif
			                					
							<td>{{Form::submit("Change", array('class'=>'btn btn-success'))}}</td>
							<td>Test email id's added comma seperated.</td>
						{{Form::close()}}
	            	</tr>
	            	<tr>
	        			{{ Form::open(array('url' => 'admin/updateWeeklySuggestion' , 'method' => '  post')) }}
			                <td>2</td>
			                <td>
			                	{{ Form::text('option_name','Weekly Suggestion',  array('class'=>'form-control','readonly'=>'readonly')) }}
			                </td>
			                @if(isset($weekly_suggestion))
			                 <td>
			                	{{ Form::text('option_value',$weekly_suggestion,  array('class'=>'form-control')) }}
			                </td> 
			                @else 
			                <td>
			                	{{ Form::text('option_value','KarmaNote',  array('class'=>'form-control')) }}
			                </td> 
			                @endif
			                					
							<td>{{Form::submit("Change", array('class'=>'btn btn-success'))}}</td>
							<td>Set weekly suggestion either KarmaMeeting or KarmaNote.</td>
						{{Form::close()}}
	            	</tr>
	            	<tr>
	        			{{ Form::open(array('url' => 'admin/update_DST' , 'method' => '  post')) }}
			                <td>2</td>
			                <td>
			                	{{ Form::text('option_name','Set DST value',  array('class'=>'form-control','readonly'=>'readonly')) }} 
			                </td>
			                @if(isset($dst_value))
			                 <td>
			                	{{ Form::text('option_value',$dst_value,  array('class'=>'form-control')) }}
			                </td> 
			                @else 
			                <td>
			                	{{ Form::text('option_value','0',  array('class'=>'form-control')) }}
			                </td> 
			                @endif
			                					
							<td>{{Form::submit("Change", array('class'=>'btn btn-success'))}}</td>
							<td>Set DST value for timezone either -1 or 0 .</td>
						{{Form::close()}}
	            	</tr>
					<tr>
	        			{{ Form::open(array('url' => 'admin/update_user_kscore' , 'method' => '  post')) }}
			                <td>2</td>
			                <td>
			                	{{ Form::text('option_name','Set Kscore Value',  array('class'=>'form-control','readonly'=>'readonly')) }} 
			                </td>
			                @if(isset($kscore))
			                 <td>
			                	{{ Form::text('option_value',$kscore,  array('class'=>'form-control')) }}
			                </td> 
			                @else 
			                <td>
			                	{{ Form::text('option_value','12',  array('class'=>'form-control')) }}
			                </td> 
			                @endif
			                					
							<td>{{Form::submit("Change", array('class'=>'btn btn-success'))}}</td> 
							<td>Set karma score value to get users only having kscore greater then this to show on home page suggestion box.</td>
						{{Form::close()}}
	            	</tr>
	        </tbody>
	    </table>
    </div>
</div>
</section>
@stop
    

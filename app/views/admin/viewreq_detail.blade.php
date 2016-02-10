<div class="UserFancyTabDetail" >
		<?php if($action =="request") {?>
		<div class="form-group">
		 <label for="exampleInputEmail1">subject</label>
		 {{Form::textarea("description", $karmaTrail['subject'] ,  array('class'=>'form-control','readonly'=>'readonly', 'rows'=>"2", 'cols'=>"50"))}}
		</div>
		<div class="form-group">
		 <label for="exampleInputEmail1">Details</label>
		 {{Form::textarea("description", $karmaTrail['notes'] ,  array('class'=>'form-control','readonly'=>'readonly', 'rows'=>"5", 'cols'=>"50"))}}
		</div>
		<?php } if($action =="accept"){?>

		<div class="form-group">
		 <label for="exampleInputEmail1">Reply</label>
		 {{Form::textarea("description", $karmaTrail['reply'] ,  array('class'=>'form-control','readonly'=>'readonly', 'rows'=>"5", 'cols'=>"50"))}}
		</div>
		<div class="form-group">
		 <label for="exampleInputEmail1">MeetingDuration</label>
		 {{Form::text("description", $karmaTrail['meetingduration'] ,  array('class'=>'form-control','readonly'=>'readonly'))}}
		</div>
		<div class="form-group">
		 <label for="exampleInputEmail1">MeetingType</label>
		 {{Form::text("description", $karmaTrail['meetingtype'] ,  array('class'=>'form-control','readonly'=>'readonly'))}}
		</div>
			<div class="form-group">
		 <label for="exampleInputEmail1">MeetingLocation</label>
		 {{Form::text("description", $karmaTrail['meetinglocation'] ,  array('class'=>'form-control','readonly'=>'readonly'))}}
		</div>
		<?php }?>
</div>


	
	
	

	



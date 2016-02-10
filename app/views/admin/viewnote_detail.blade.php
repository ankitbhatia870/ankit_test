
<div class="UserFancyTabDetail" >
		<div class="form-group">
		 <label for="exampleInputEmail1">Details</label>
		 {{Form::textarea("description", $karmaTrail[0]['karmaNotes'] ,  array('class'=>'form-control','readonly'=>'readonly', 'rows'=>"5", 'cols'=>"50"))}}
		</div>
		<div class="form-group">
		 <label for="exampleInputEmail1">Skills</label>
		 <?php 
		 	$skill= ''; 
		 	if(!empty($karmaTrail[0]['skills']))
		 	{	
				foreach ($karmaTrail[0]['skills'] as $element){
				$skill[] = $element->name;
				}
				$skill = implode($skill,', ');
		 	}
		 ?>
		 {{Form::textarea("name",$skill,  array('class'=>'form-control','readonly'=>'readonly', 'rows'=>"2", 'cols'=>"50"))}}
		</div>
		<div class="form-group">
		 <label for="exampleInputEmail1">Giver Status</label>
		 {{Form::text("name",$karmaTrail[0]['statusgiver'],  array('class'=>'form-control','readonly'=>'readonly'))}}
		</div>
		<div class="form-group">
		 <label for="exampleInputEmail1">Receiver Status</label>
		 {{Form::text("name",$karmaTrail[0]['statusreceiver'],  array('class'=>'form-control','readonly'=>'readonly'))}}
		</div>
</div>


	
	
	

	



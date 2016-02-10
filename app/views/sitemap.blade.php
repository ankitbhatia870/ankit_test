{{'<?xml version="1.0" encoding="UTF-8" ?>'}}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
         @foreach ($UserData as $value) 
			<?php $site_url=URL::to('/');
					$id=$value->id;
					$fname=$value->fname;
					$lname=$value->lname;
					$dynamic_name=$fname.'-'.$lname.'/'.$id;
					$public_profile_url=$site_url.'/profile/'.$dynamic_name;
			//echo $public_profile_url;
		
		?>
		<url>
                <loc>{{$public_profile_url}}</loc>
                
        </url>
        @endforeach
        <?php echo '----------------Group-----------------';?>
        
        @foreach ($GroupData as $value_group) 
		<?php	$site_url=URL::to('/');
			$id=$value_group->id;
			$group_name=strtolower(trim(str_replace(' ', '-', $value_group->name)));
			$group_dynamic_name=$group_name.'/'.$id;
			$public_group_url=$site_url.'/groups/'.$group_dynamic_name;
			//echo $public_group_url;
		
		?>
		<url>
                <loc>{{$public_group_url}}</loc>
        </url>
        @endforeach
        <?php echo '----------------Meeting-----------------';?>
        
        @foreach ($KarmaData as $value_karma)
        <?php	
			$site_url=URL::to('/');
		 	$id=$value_karma->req_id;
		 	$user_idreceiver=$value_karma->user_idreceiver;
		 	$user_idgiver=$value_karma->user_idgiver;
		 	if(!empty($user_idreceiver)){
		 		$KarmaName_receiver = DB::table('users')->where('id','=',$user_idreceiver)->select('fname','lname')->first();
		 	
		 	  $fname=$KarmaName_receiver->fname;
		 	  $lname=$KarmaName_receiver->lname;
		 	  $karmaNote_receiver_name=$fname.'-'.$lname;
		 	}
		 	if(!empty($user_idgiver)){
		 		$KarmaName_giver = DB::table('users')->where('id','=',$user_idgiver)->select('fname','lname')->first();
				$fname_giver=$KarmaName_giver->fname;
		 	 	$lname_giver=$KarmaName_giver->lname;
		 	  	$karmaNote_giver_name=$fname_giver.'-'.$lname_giver;// echo '<pre>';echo 
		 	  //echo '<pre>';print_r($karmaNote_giver_name);exit;
		 	  	if(!empty($user_idreceiver)){
		 	  		$dynamic_meeting=$karmaNote_receiver_name.'-'.$karmaNote_giver_name.'/'.$id;
		 	  	}
				$meeting_url=$site_url.'/meeting/'.$dynamic_meeting;
			
		?>
		<url>
             <loc>{{$meeting_url}}</loc>
        </url>
        <?php }?>
        @endforeach

</urlset>
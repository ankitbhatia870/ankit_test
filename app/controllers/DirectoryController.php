<?php

class DirectoryController extends \BaseController {

	
	/*
	*Directory for Skill
	*/
	public function searchbyskill($alpha){
		
		list($s,$alpha) = explode('-',$alpha);
		$azRange = array_merge(range('A', 'Z'), range('a', 'z'));
		$aplhachk = in_array($alpha,$azRange );
		if($aplhachk !=1 || $s != "skills" ){return Redirect::to('404');}
		$user_info = Auth::user();
		$skills = DB::table('tags')->where('tags.name','LIKE',$alpha.'%')->get();
		//echo"<pre>";print_r($skills);echo"</pre>"; die;
		if(isset($user_info)){
			$user_id = $user_info->id;	 
		}else{
			$user_id = 0;
		}
		foreach ($skills as $key => $value) {
			$searchquery = DB::table('users_tags')
			            ->leftjoin('users', 'users.id', '=', 'users_tags.user_id')
			            ->where('tag_id', '=', $value->id)
			            ->where('users.userstatus', '=', 'approved')
						->where('users.id', '!=', $user_id)			            
			            ->select('users_tags.id','users_tags.user_id','users_tags.tag_id')
			            ->get();
			            
			$skills[$key]->UserCount = count($searchquery);	 
		}  
		 
		/* $skills = array_values(array_sort($skills, function($value)
		{
		    return $value->name; 
		})); 
		//$skills = array_reverse($skills); 
		*/ 
		$seter = array();
		foreach($skills as $set){
			 $seter[] = (array) $set;
		} 
		$skills = KarmaHelper::skill_sort($seter,'name');     
		
		return View::make('footer.search_by_skill',array('pageTitle'=>'Skills & Expertise Directory | KarmaCircles','pageDescription'=>'Explore the vast database of professionals and experts using skills and fields of your interest, and get instant help.', 'alpha' => $alpha,'skills'=>$skills));
	}



}

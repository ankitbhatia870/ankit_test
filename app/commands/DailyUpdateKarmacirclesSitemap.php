<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class DailyUpdateKarmacirclesSitemap extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'karma:daily_update_karmacircles_sitemap';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Command for updating public url sitemap daily';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		
		$UserData = DB::table('users')->select('id','fname','lname')->get();
		$QueryData = DB::table('users')->select('id','fname','lname')->get();
		$GroupData = DB::table('groups')->select('id','name')->get();
		$QueryData = DB::table('questions')->get();
		$site_url=URL::to('/');
		$static_pages[]=$site_url.'/FAQs';
		$static_pages[]=$site_url.'/how-it-works';
		$static_pages[]=$site_url.'/about';
		$static_pages[]=$site_url.'/terms';
		$static_pages[]=$site_url.'/groupsAll';
		$letters = range('a', 'z');
		foreach ($letters as $value_letter) {
			$site_url=URL::to('/');
			$static_pages[]=$site_url.'/directory/skills-'.$value_letter;
		}
		foreach ($QueryData as $value_query) {
			$site_url=URL::to('/');
			$id=$value_query->id;
			$query_subject=$value_query->question_url;
			$dynamic_name=$query_subject.'/'.$id;
			$public_query_url[]=$site_url.'/question/'.$dynamic_name;
		}
		foreach ($UserData as $value_user) {
			$site_url=URL::to('/');
			$id=$value_user->id;
			$fname=$value_user->fname;
			$lname=$value_user->lname;
			$dynamic_name=$fname.'-'.$lname.'/'.$id;
			$public_profile_url[]=$site_url.'/profile/'.$dynamic_name;
		}
		foreach ($GroupData as $value_group) {
			$site_url=URL::to('/');
			$id=$value_group->id;
			$group_name=strtolower(trim(str_replace(' ', '-', $value_group->name)));
			$group_dynamic_name=$group_name.'/'.$id;
			$public_group_url[]=$site_url.'/groups/'.$group_dynamic_name;
		}
		
		$KarmaData = DB::table('karmanotes')->select('req_id','user_idreceiver','user_idgiver')->get();
		foreach ($KarmaData as $value_karma) {
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
				$meeting_url[]=$site_url.'/meeting/'.$dynamic_meeting;

			}
			
		}
		sort($static_pages);
		sort($meeting_url);
		sort($public_profile_url);
		sort($public_group_url);
		sort($public_query_url);
			$getSitemapUrl= array_merge($static_pages,$public_profile_url,$public_group_url,$meeting_url,$public_query_url);
			foreach ($getSitemapUrl as $key => $value) {
			 	$getSitemapUrlresult[]=htmlspecialchars("<sitemap><url>".$value."</url></sitemap>", ENT_QUOTES);
			}
			$getSitemapUrlresult=implode("\n", $getSitemapUrlresult);
			$getSitemapUrlresult=htmlspecialchars_decode('<sitemapset>'.$getSitemapUrlresult.'</sitemapset>');
			$file = public_path(). "/sitemap.xml";  // <- Replace with the path to your .xml file
     			$options = array('ftp' => array('overwrite' => true)); 
				$stream = stream_context_create($options);
				file_put_contents($file, $getSitemapUrlresult, 0, $stream);
				
	}
		
		
	

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			//array('example', InputArgument::REQUIRED, 'An example argument.'),
		);
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
			//array('example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
		);
	}

}

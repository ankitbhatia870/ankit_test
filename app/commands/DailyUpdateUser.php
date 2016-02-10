<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class DailyUpdateUser extends Command { 

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'karma:daily_update_user_profile'; 

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Command for updating user profile daily for those who havent loggedin in two months';

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
		Log::alert('Executed Cron Job to update user data daily.');
		$getusers = DB::table('users')
						->select(array('users.*'))
						->where('users.userstatus','=','approved')     
						->orderBy('created_at','DESC')   
						->get(); 
		if(!empty($getusers)){
			foreach ($getusers as $user_info) { 
				$diffDate = KarmaHelper::dateDiff(date("Y-m-d H:i:s"),$user_info->profileupdatedate);
				if($diffDate->days < 60){
					$user_data = User::find($user_info->id);
					$token = $user_data->token;
					//$result = json_decode(file_get_contents("https://api.linkedin.com/v1/people/~:(id,first-name,last-name,skills,headline,summary,industry,member-url-resources,picture-urls::(original),location,public-profile-url,email-address)?format=json&oauth2_access_token=$token"));	
					$curl_handle=curl_init();
					curl_setopt($curl_handle, CURLOPT_URL,'https://api.linkedin.com/v1/people/~:(id,first-name,last-name,skills,headline,summary,industry,member-url-resources,picture-urls::(original),location,public-profile-url,email-address)?format=json&oauth2_access_token=$token');
					curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
					curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
					curl_setopt($curl_handle, CURLOPT_USERAGENT, 'Your application name');
					$result = curl_exec($curl_handle);
					curl_close($curl_handle);
					$publicProfileUrl = "";

					if(isset($result->id))
					{ 
						$InsTag 	=    KarmaHelper::insertUsertag($user_data,$result); 
						// Close insertUserConnection due to linkedIn changes 
						//$InsConnection = KarmaHelper::insertUserConnection($user_data);
						$InsConnection = KarmaHelper::updateUserProfile($user_info->id,$result);
						//update user profile details
						if(!isset($result->publicProfileUrl) || ($result->publicProfileUrl== '')){
							$publicProfileUrl = $result->siteStandardProfileRequest['url'];  
						}
						else{  
						$publicProfileUrl = $result->publicProfileUrl; 
						}
						$imageurl = "";
						if(isset($result->pictureUrls->values[0])) 
						$imageurl = $result->pictureUrls->values[0];   

						$user = User::find($user_info->id);
						$user->email 				= $result->emailAddress;
						if(isset($imageurl)){
						$user->piclink 				= $imageurl;
						}	
						if(isset($result->summary))
						$user->summary		 		= $result->summary;
						if(!empty($result->location))
						$user->location 			= $result->location->name;
						if(isset($result->industry))
						$user->industry 			= $result->industry;
						if(isset($result->headline))
						$user->headline 			= $result->headline;
						if($publicProfileUrl!="") 
						$user->linkedinurl 			= $publicProfileUrl;
						$user->profileupdatedate 	= date('Y-m-d H:i:s'); 
						$user->save(); 
					}
					sleep('2');
				} 
			}
		}
	} 

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			array('example', InputArgument::OPTIONAL, 'An example argument.'),
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
			array('example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
		);
	}

}

<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class UpdateTestUserKscore extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'karmanote:update_test_user_kscore';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Cron to run weekly to update test users score weekly.';

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
		//get test users id		
		$getUser = KarmaHelper::getTestUsers();
		//print_r($getUser);die;

		if(!empty($getUser)){
			foreach ($getUser as $key => $value) {
				$helper = User::find($value);
				if(!empty($helper))
				{
					$helper->karmascore = 0;
					$helper->save();
				}
			}
		}

		Log::alert('Updated test user Karma score.');
	}

	

}

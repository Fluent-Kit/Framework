<?php

namespace FluentKit\Seeders;

use Seeder;
use Sentry;

class Seeder000001 extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{

		$this->createGroups();

	}

	private function createGroups(){

		/*
        try{
		    $group = Sentry::createGroup(array(
		        'name'        => 'SuperUser',
		        'permissions' => array(
		            'superuser' => 1,
		        ),
		    ));
		}catch (\Cartalyst\Sentry\Groups\NameRequiredException $e){
		    
		}catch (\Cartalyst\Sentry\Groups\GroupExistsException $e){
		    
		}

		try{
		    $group = Sentry::createGroup(array(
		        'name'        => 'Administrator',
		        'permissions' => array(
		            'admin' => 1,
		            'admin.login' => 1,

		            'user.create' => 1,
			        'user.delete' => 1,
			        'user.view'   => 1,
			        'user.update' => 1,
		        ),
		    ));
		}catch (\Cartalyst\Sentry\Groups\NameRequiredException $e){
		    
		}catch (\Cartalyst\Sentry\Groups\GroupExistsException $e){
		    
		}

		try{
		    $group = Sentry::createGroup(array(
		        'name'        => 'User',
		        'permissions' => array(
		        ),
		    ));
		}catch (\Cartalyst\Sentry\Groups\NameRequiredException $e){
		    
		}catch (\Cartalyst\Sentry\Groups\GroupExistsException $e){
		    
		}
        */

	}

}
<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

$app['router']->get('/', function() use ($app)
{

    $repo = new FluentKit\User\Repositories\UserRepository;
    //$user = $repo->firstOrCreate(array('email' => 'personal@leemason.co.uk', 'password' => 'testing123'));
    //$user->password_confirmation = 'testing123';
    //$user->save();
    //print_r($user->errors());
    
    return $app['view']->make('layouts/master');
    
    //Auth::login($user);
    
	//echo app_path();
	//return $app['view']->make('hello');

	//$coll = Plugin::all();
	/*
    $plugins = $coll->filter(function($plugin){
		return ($plugin->uid == 'clients') ? true : false;
	});
    */

	//print_r($plugins);

	//print_r(Plugin::get('clients1'));

	//print_r(Plugin::collection()->get('clients'));

    
	
	
	return $app['view']->make('hello');
});


$app['router']->get('/install', function() use ($app)
{
    
    
	$buffer = new \Symfony\Component\Console\Output\BufferedOutput;
	$buffer->writeln('Running base Migrations');
	\Artisan::call('migrate', array(), $buffer);
	$buffer->writeln('Running Plugin Migrations');
	\Artisan::call('migrate', array('--package' => 'fluentkit/plugin'), $buffer);
	$buffer->writeln('Seeding Database');
	\Artisan::call('db:seed', array(), $buffer);
    
    $data = array('installed' => true);
    $app['files']->put($app['path.storage'] . '/fluentkit', json_encode($data));
    
	return nl2br($buffer->fetch());
});
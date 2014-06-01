<?php
namespace FluentKit\Commands;

use PDO, PDOException;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ApplicationInstall extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'fluentkit:install';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Install the application';

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
        $this->info('Installing FluentKit...');
        
        $app = $this->getLaravel();
        $args = $this->argument();
        
        $app['events']->fire('installing', array($args));
        
        //test the db connection
        try{
            $dbh = new PDO('mysql:host='.$args['db-host'].';dbname='.$args['db-name'].'',$args['db-user'],$args['db-password']);
            $dbh = null;
        }catch(PDOException $ex){
            $this->error($ex->getMessage());
            return false;
        }
        
        $app['config']->set('app.url', $args['url']);
        $app['config']->set('app.key', $args['secret-key']);
        
        $app['config']->set('database.connections.mysql.host', $args['db-host']);
        $app['config']->set('database.connections.mysql.database', $args['db-name']);
        $app['config']->set('database.connections.mysql.username', $args['db-user']);
        $app['config']->set('database.connections.mysql.password', $args['db-password']);
        $app['config']->set('database.connections.mysql.prefix', $args['db-prefix']);
        
        $this->info('Running Migrations...');
        $this->call('migrate', array());
        
        $this->info('FluentKit/Plugin Migrations...');
        $this->call('migrate', array('--package' => 'fluentkit/plugin'));
        
        $this->info('Seeding Database...');
        $this->call('db:seed', array());
    
        $this->info('Writting Install File...');
        
        $data = $args;
        unset($data['command']);
        unset($data['admin-email']);
        unset($data['admin-password']);
        $data['installed'] = time();
        $app['files']->put($app['path.storage'] . '/fluentkit', json_encode($data));
        
        $this->info('Install Complete!');
        
        $app['events']->fire('installed', array($args));
        
    }

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
            array('db-host', InputArgument::REQUIRED, 'Database Host'),
            array('db-name', InputArgument::REQUIRED, 'Database Name'),
            array('db-user', InputArgument::REQUIRED, 'Database User'),
            array('db-password', InputArgument::REQUIRED, 'Database Password'),
            array('db-prefix', InputArgument::REQUIRED, 'Database Prefix'),
            array('url', InputArgument::REQUIRED, 'Application URL'),
            array('secret-key', InputArgument::REQUIRED, 'Secret Key'),
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
			
		);
	}

}

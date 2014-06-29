<?php
namespace FluentKit\Providers;

use Exception, PDO, PDOException;
use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;

class FluentKitServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;
    
    public function __construct($app){
        parent::__construct($app);
        
        $app = $this->app;
        
        $app['installed'] = false;
        
        //check install status
        try{
            $data = (array) json_decode($app['files']->get($app['path.storage'] . '/fluentkit'), true);
            //set runtime vars
            $app['config']->set('database.connections.mysql.host', $data['db-host']);
            $app['config']->set('database.connections.mysql.database', $data['db-name']);
            $app['config']->set('database.connections.mysql.username', $data['db-user']);
            $app['config']->set('database.connections.mysql.password', $data['db-password']);
            $app['config']->set('database.connections.mysql.prefix', $data['db-prefix']);
            
            $app['config']->set('app.url', $data['url']);
            $app['config']->set('app.key', $data['secret-key']);
            
            $app['config']->set('app.installed', $data['installed']);
            
            
            $app['installed'] = true;
        }catch (Exception $e){
            
            $app['config']->set('view.paths', array(app_path().'/views'));
            
            $this->app->before(function() use($app){
            
                //trigger redirect to install
                if(!$this->app['request']->is('install*')){
                    return $this->app['redirect']->to('/install');   
                }
                
                //install routes
                $app['router']->get('/install', function() use ($app)
                {
                    return $app['view']->make('install.index')->withTitle('FluentKit Install');
                });
                
                $app['router']->post('/install', array('before' => 'csrf', function() use ($app){
                    $data = array();
                    $v = $app['validator']->make($app['request']->all(), array(
                        'url' => 'required|url',
                        'dbhost' => 'required',
                        'dbname' => 'required',
                        'dbuser' => 'required',
                        'key' => 'required'
                    ));
                    
                    if($v->fails()){
                        $m = $v->messages();
                        $data['status'] = 'error';
                        $data['message'] = 'Whoops! Some of the data provided isnt suitable.';
                        $data['errors'] = array();
                        $data['errors']['url'] = $m->first('url');
                        $data['errors']['dbhost'] = $m->first('dbhost');
                        $data['errors']['dbname'] = $m->first('dbname');
                        $data['errors']['dbuser'] = $m->first('dbuser');
                        $data['errors']['key'] = $m->first('key');
                        return $app['response']->json($data);   
                    }
                    
                    //test the db connection
                    try{
                        $dbh = new PDO('mysql:host='.$app['request']->input('dbhost').';dbname='.$app['request']->input('dbname').'',$app['request']->input('dbuser'),$app['request']->input('dbpassword'));
                        $dbh = null;
                    }catch(PDOException $ex){
                        $data['status'] = 'error';
                        $data['message'] = 'Whoops! We could not connect to the Database with those details.';
                        $data['sql_error'] = $ex->getMessage();
                        return $app['response']->json($data);
                    }
                    
                    $buffer = new \Symfony\Component\Console\Output\BufferedOutput;
                    $app['artisan']->call('fluentkit:install', array('db-host' => $app['request']->input('dbhost'),'db-name' => $app['request']->input('dbname'), 'db-user' => $app['request']->input('dbuser'), 'db-password' => $app['request']->input('dbpassword'), 'db-prefix' => 'prefix_', 'url' => $app['request']->input('url'), 'secret-key' => $app['request']->input('key'), '--env' => 'local'), $buffer);
                    
                    $console = $buffer->fetch();
                    
                    if(str_contains($console, 'Install Complete!')){
                        $msgs = array_filter(explode("\n", $console));
                        $data['status'] = 'success';
                        $data['message'] = 'FluentKit Installed Successfully!';
                        $data['tasks'] = $msgs;
                        return $app['response']->json($data);
                    }
                    
                    return $app['response']->json($data);
                }));
            });
        }
    }

    public function register()
    {   
        $app = $this->app;

        $this->app->register('Barryvdh\Debugbar\ServiceProvider');
        

		//fluent providers
        if($this->app['installed'] === true){
            $this->app->register('FluentKit\User\UserServiceProvider');
            $this->app->register('FluentKit\Messages\MessagesServiceProvider');
            $this->app->register('FluentKit\Asset\AssetServiceProvider');
            $this->app->register('FluentKit\Theme\ThemeServiceProvider');
            $this->app->register('FluentKit\Plugin\PluginServiceProvider');
            
            //custom providers
            $this->app->register('Humweb\Filters\FiltersServiceProvider'); 
            $this->app->register('AdamWathan\BootForms\BootFormsServiceProvider');
        }
        
        
        
        
        //register facades
		//$this->registerFacade('Debugbar', 'Barryvdh\Debugbar\Facade');
		$this->registerFacade('Filters', 'Humweb\Filters\Facade');
        $this->registerFacade('BootForm', 'AdamWathan\BootForms\Facades\BootForm');
        
        require app_path() . '/src/FluentKit/pluggable.php';

    }

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
    }
    
    public function registerFacade($facade, $namespace)
	{
		$loader = AliasLoader::getInstance();
        $loader->alias($facade, $namespace);
	}

    public function provides(){
    	return array();
    }

}
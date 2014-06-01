<?php
namespace FluentKit\Providers;

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
            $app['config']->set('app.installed', $data['installed']);
            $app['installed'] = true;
        }catch (\Exception $e){
            
            $this->app->before(function() use($app){
            
                //trigger redirect to install
                if(!$this->app['request']->is('install*')){
                    return $this->app['redirect']->to('/install');   
                }
                
                //install routes
                $app['router']->get('/install', function() use ($app)
                {
                    $buffer = new \Symfony\Component\Console\Output\BufferedOutput;
                    $app['artisan']->call('fluentkit:install', array('db-host' => 'localhost','db-name' => 'fluentkit', 'db-user' => 'root', 'db-password' => '', 'db-prefix' => 'prefix_'), $buffer);
                    
                    return nl2br($buffer->fetch());
                });
            });
        }
    }

    public function register()
    {   
        $app = $this->app;
        

		//fluent providers
        if($this->app['installed'] === true){
            $this->app->register('FluentKit\User\UserServiceProvider');
            $this->app->register('FluentKit\Messages\MessagesServiceProvider');
            $this->app->register('FluentKit\Asset\AssetServiceProvider');
            $this->app->register('FluentKit\Theme\ThemeServiceProvider');
            $this->app->register('FluentKit\Plugin\PluginServiceProvider');
            
            //custom providers
            $this->app->register('Barryvdh\Debugbar\ServiceProvider');
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
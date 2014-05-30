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

    public function register()
    {   
        try{
            $data = (array) json_decode($this->app['files']->get($this->app['path.storage'] . '/fluentkit'), true);
            $this->app['installed'] = true;
        }catch (\Exception $e){  
            $this->app['installed'] = false;
        }
        

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
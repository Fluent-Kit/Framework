<?php
namespace FluentKit\Asset;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;

class AssetServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = true;

    public function register()
    {

		//register asset management
        $this->app->bindShared('fluentkit.asset', function(){

            $assetManager = new AssetManager;

            /////  PREDEFINED LIBRARIES  /////
            $assetManager->register('jquery', function($asset)
            {
                $asset->js('//ajax.googleapis.com/ajax/libs/jquery/{version}/jquery.min.js', array('version' => '1.11.0'));
            });
            $assetManager->register('jquery-ui', function($asset)
            {
                $asset->js('//ajax.googleapis.com/ajax/libs/jqueryui/{version}/jquery-ui.min.js', array('version' => '1.10.4'));
                $asset->css('//ajax.googleapis.com/ajax/libs/jqueryui/{version}/themes/smoothness/jquery-ui.css', array('version' => '1.10.4'));
                $asset->requires('jquery');
            });
            $assetManager->register('angular-js', function($asset)
            {
                $asset->js('//ajax.googleapis.com/ajax/libs/angularjs/{version}/angular.min.js', array('version' => '1.2.15'));
            });
            $assetManager->register('bootstrap', function($asset)
            {
                $asset->js('//netdna.bootstrapcdn.com/bootstrap/{version}/js/bootstrap.min.js', array('version' => '3.1.1'), array('defer' => 'defer'));
                $asset->requires('jquery', 'respond', 'html5shiv');
                $asset->css('//netdna.bootstrapcdn.com/bootstrap/{version}/css/bootstrap.min.css', array('version' => '3.1.1'));
            });
            
            $assetManager->register('respond', function($asset)
            {
                $asset->js('//oss.maxcdn.com/libs/respond.js/{version}/respond.min.js', array('version' => '1.4.2'));
            });
            
            $assetManager->register('html5shiv', function($asset)
            {
                $asset->js('//oss.maxcdn.com/libs/html5shiv/{version}/html5shiv.js', array('version' => '3.7.0'));
            });

            return $assetManager;
        });

    }

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {

    	//register facades
    	$loader = AliasLoader::getInstance();

		//fluent aliases
		$loader->alias('Asset', 'FluentKit\Asset\Facade');
        
        $app = $this->app;
        
        $this->app['events']->listen('head', function() use ($app){
            foreach( $app['fluentkit.asset']->getStyles() as $key => $asset ){
                echo $app['html']->element(
                    'link',
                    array_merge( array(
                        'href' => str_replace('{version}', array_get($asset->options, 'version'), $asset->url),
                        'rel' => 'stylesheet',
                        'type' => 'text/css'
                        ), $asset->attributes )
                ) . "\n";
            }
            foreach( $app['fluentkit.asset']->getScripts(null) as $asset ){
                echo $app['html']->element(
                    'script',
                    array_merge( array( 'src' => str_replace('{version}', array_get($asset->options, 'version'), $asset->url) ), $asset->attributes ),
                    array_get( $asset->options, 'content', '' )
                ) . "\n";
            }
        });
        
        $this->app['events']->listen('footer', function() use ($app){
            foreach( $app['fluentkit.asset']->getScripts('footer') as $asset ){
                echo $app['html']->element(
                    'script',
                    array_merge( array( 'src' => str_replace('{version}', array_get($asset->options, 'version'), $asset->url) ), $asset->attributes ),
                    array_get( $asset->options, 'content', '' )
                ) . "\n";
            }
        });

    }

    public function provides(){
    	return array('fluentkit.asset');
    }

}
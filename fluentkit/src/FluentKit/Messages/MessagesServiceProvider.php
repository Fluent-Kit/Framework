<?php
namespace FluentKit\Messages;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;

class MessagesServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = true;

    public function register()
    {

		//create our messages binding, simply a wrapper for a messagebag for use in templates
        $this->app->bindShared('fluentkit.messages', function ($app) {
            return with(new Messages)->setSessionStore($app['session.store']);
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
		$loader->alias('Messages', 'FluentKit\Messages\Facade');

        $app = $this->app;

        //save messages
        $app->after(function () use ($app) {
            $app['fluentkit.messages']->save();
        });

    }

    public function provides(){
    	return array('fluentkit.messages');
    }

}
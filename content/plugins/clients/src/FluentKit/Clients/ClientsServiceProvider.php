<?php
namespace FluentKit\Clients;

use FluentKit\Plugin\ServiceProvider;

class ClientsServiceProvider extends ServiceProvider{
    
    public $defer = true;
    
    public function register(){
        $this->registerPlugin('clients');
    }
    
    public function boot(){
        \Route::get('clients', function(){
            return \View::make('clients::foo');
        });
    }
    
}
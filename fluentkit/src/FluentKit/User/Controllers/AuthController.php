<?php
namespace FluentKit\User\Controllers;

use Illuminate\Foundation\Application;

use Controller;

use Illuminate\Auth\Reminders\PasswordBroker;

class AuthController extends Controller{
    
    public function __construct(Application $app){
        $this->app = $app;
        //parent::__construct();
    }
    
    public function logout(){
        $this->app['auth']->logout();
        $this->app['fluentkit.messages']->add('success', 'Logged Out');
        return $this->app['redirect']->to('login');
    }
    
    public function getIndex()
    {
        return $this->app['view']->make('login');
    }
    
    public function postIndex(){
        if (\Auth::attempt(array('email' => \Input::get('email'), 'password' => \Input::get('password'))))
        {
            return \Redirect::intended('/');
        }
        
        return \Redirect::to('/?errors=true');
    }
    
    public function getReset(){
        foreach($this->app['fluentkit.messages']->all() as $m){
            echo $m;   
        }
        echo $this->app['form']->open(array('url' => 'login/reset'));
            echo $this->app['form']->token();
            echo $this->app['form']->label('email', 'Email Address');
            echo $this->app['form']->email('email', $this->app['request']->get('email'));
            echo $this->app['form']->submit('Reset Password');
        echo $this->app['form']->close();
    }
    
    public function postReset(){
        
        $v = $this->app['validator']->make(
            $this->app['request']->only('email'), 
            array('email' => 'required|email|exists:users,email')
        );
        
        if($v->fails()){
            foreach($v->messages()->all() as $error){
                $this->app['fluentkit.messages']->add('error', $error);
            }
            return $this->app['redirect']->back();
        }
        
        switch ($response = $this->app['auth.reminder']->remind($this->app['request']->only('email')))
		{
			case PasswordBroker::INVALID_USER:
                $this->app['fluentkit.messages']->add('error', $this->app['translator']->get($response));
				return $this->app['redirect']->back();

			case PasswordBroker::REMINDER_SENT:
                $this->app['fluentkit.messages']->add('success', $this->app['translator']->get($response));
				return $this->app['redirect']->back();
		}
    }
    
    /**
	 * Display the password reminder view.
	 *
	 * @return Response
	 */
	public function getToken( $token = null )
	{
        if (is_null($token)) $this->app->abort(404);
        foreach($this->app['fluentkit.messages']->all() as $m){
            echo $m;   
        }
        echo $this->app['form']->open(array('url' => 'login/token'));
            echo $this->app['form']->hidden('token', $token);
            echo $this->app['form']->label('email', 'Email Address');
            echo $this->app['form']->email('email', $this->app['request']->get('email'));
            echo $this->app['form']->label('password', 'Password');
            echo $this->app['form']->password('password', $this->app['request']->get('password'));
            echo $this->app['form']->label('password_confirmation', 'Password Confirmation');
            echo $this->app['form']->password('password_confirmation', $this->app['request']->get('password_confirmation'));
            echo $this->app['form']->submit('Reset Password');
        echo $this->app['form']->close();
	}
    
    public function postToken(){
        
        $credentials = $this->app['request']->only(
			'email', 'password', 'password_confirmation', 'token'
		);

        $app = $this->app;
		$response = $this->app['auth.reminder']->reset($credentials, function($user, $password) use ($app)
		{
			$user->password = $password;
            $user->password_confirmation = $this->app['request']->get('password_confirmation');
			$user->save();
		});

		switch ($response)
		{
            case PasswordBroker::INVALID_TOKEN:
                $this->app['fluentkit.messages']->add('error', $this->app['translator']->get($response));
				return $this->app['redirect']->to('login/reset');
            
			case PasswordBroker::INVALID_PASSWORD:
			case PasswordBroker::INVALID_USER:
                $this->app['fluentkit.messages']->add('error', $this->app['translator']->get($response));
				return $this->app['redirect']->to('login/token/'.$this->app['request']->get('token'));

			case PasswordBroker::PASSWORD_RESET:
                $this->app['fluentkit.messages']->add('success', $this->app['translator']->get($response));
				return $this->app['redirect']->to('/login');
		}   
    }
}
<?php
namespace FluentKit\User\Models;

use FluentKit\SuperModel\SuperModel;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends SuperModel implements UserInterface, RemindableInterface{

    protected $table = 'users';
    
    protected $hidden = array('password');
    
    protected $fillable = array('email', 'password', 'first_name', 'last_name', 'status');
    
    protected $attributes_schema = array(
        'email' => 'string',
        'first_name' => 'string',
        'last_name' => 'string',
        'password' => 'hashed',
        'status' => 'int',
        'remember_token' => 'string',
        'activate_token' => 'string'
    );
    
    public $rules = array(
		'global' => array(
            'email' => 'unique|email|required',
            'password' => 'confirmed_if_dirty|between:10,100',
            'first_name' => 'alpha_spaces',
            'last_name' => 'alpha_spaces'
        ),
		'create' => array(
            'password' => 'required'
        ),
		'update' => array()
	);
    
    /**
	 * Get the unique identifier for the user.
	 *
	 * @return mixed
	 */
	public function getAuthIdentifier()
	{
		return $this->getKey();
	}

	/**
	 * Get the password for the user.
	 *
	 * @return string
	 */
	public function getAuthPassword()
	{
		return $this->password;
	}

	/**
	 * Get the token value for the "remember me" session.
	 *
	 * @return string
	 */
	public function getRememberToken()
	{
		return $this->remember_token;
	}

	/**
	 * Set the token value for the "remember me" session.
	 *
	 * @param  string  $value
	 * @return void
	 */
	public function setRememberToken($value)
	{
		$this->remember_token = $value;
	}

	/**
	 * Get the column name for the "remember me" token.
	 *
	 * @return string
	 */
	public function getRememberTokenName()
	{
		return 'remember_token';
	}

	/**
	 * Get the e-mail address where password reminders are sent.
	 *
	 * @return string
	 */
	public function getReminderEmail()
	{
		return $this->email;
	}
    
}
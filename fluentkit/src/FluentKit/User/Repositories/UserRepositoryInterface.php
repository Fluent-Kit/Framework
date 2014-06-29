<?php 
namespace FluentKit\User\Repositories;

use FluentKit\Repositories\RepositoryInterface;
 
/**
 * The UserRepositoryInterface contains ONLY method signatures for methods 
 * related to the User object.
 *
 * Note that we extend from RepositoryInterface, so any class that implements 
 * this interface must also provide all the standard eloquent methods (find, all, etc.)
 */
 
interface UserRepositoryInterface extends RepositoryInterface {
	
	public function findByUserName($username);
    
    public function findByEmail($email);
    
    public function active();
    
    public function inactive();
 
}
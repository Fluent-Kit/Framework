<?php 
namespace FluentKit\User\Repositories;
 
use FluentKit\Repositories\AbstractRepository;
 
class UserRepository extends AbstractRepository implements UserRepositoryInterface
{
	// This is where the "magic" comes from:
	protected $modelClassName = 'FluentKit\User\Models\User';
	
	// This class only implements methods specific to the UserRepository
	public function findByUserName($username)
	{
		$where = call_user_func_array("{$this->modelClassName}::where", array('email', '=', $username));
		return $where->get()->first();
	}
    
    public function findByEmail($email)
	{
		return $this->findByUserName($email);
	}
    
    public function active()
	{
		$where = call_user_func_array("{$this->modelClassName}::where", array('status', '=', 1));
		return $where->get();
	}
    
    public function inactive()
	{
		$where = call_user_func_array("{$this->modelClassName}::where", array('status', '=', 0));
		return $where->get();
	}
    
}
 
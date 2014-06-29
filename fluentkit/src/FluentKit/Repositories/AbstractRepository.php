<?php 
namespace FluentKit\Repositories;
 
/**
 * The Abstract Repository provides default implementations of the methods defined
 * in the base repository interface. These simply delegate static function calls 
 * to the right eloquent model based on the $modelClassName.
 */
 
abstract class AbstractRepository implements RepositoryInterface {
	
	protected $modelClassName;
 
	public function create(array $attributes)
	{
		return call_user_func_array("{$this->modelClassName}::create", array($attributes));
	}
    
    public function firstOrCreate(array $attributes)
	{
		return call_user_func_array("{$this->modelClassName}::firstOrCreate", array($attributes));
	}
    
    public function firstOrNew(array $attributes)
	{
		return call_user_func_array("{$this->modelClassName}::firstOrNew", array($attributes));
	}
 
	public function all($columns = array('*'))
	{
		return call_user_func_array("{$this->modelClassName}::all", array($columns));
	}
 
	public function find($id, $columns = array('*'))
	{
		return call_user_func_array("{$this->modelClassName}::find", array($id, $columns));
	}
    
    public function findOrFail($id, $columns = array('*'))
	{
		return call_user_func_array("{$this->modelClassName}::findOrFail", array($id, $columns));
	}
	
	public function destroy($ids)
	{
		return call_user_func_array("{$this->modelClassName}::destroy", array($ids));
	}
    
    public function paginate($items = 50){
        return call_user_func_array("{$this->modelClassName}::paginate", array($items));
    }

}
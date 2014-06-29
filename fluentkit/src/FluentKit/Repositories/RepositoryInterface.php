<?php 
namespace FluentKit\Repositories;
 
/**
 * RepositoryInterface provides the standard functions to be expected of ANY 
 * repository.
 */
interface RepositoryInterface {
	
	public function create(array $attributes);
    
     public function firstOrCreate(array $attributes);
    
    public function firstOrNew(array $attributes);
 
	public function all($columns = array('*'));
 
	public function find($id, $columns = array('*'));
    
    public function findOrFail($id, $columns = array('*'));
 
	public function destroy($ids);
    
    public function paginate($items = 50);
 
}
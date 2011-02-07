<?php	
/**
* 
*/
class ModelFilters
{
	private $valid_filters = array('beforeValidate', 'afterValidate', 
								   'beforeSave', 'afterSave', 
								   'afterCommit', 
								   'beforeCreate', 'afterCreate', 
								   'beforeUpdate', 'afterUpdate');
	private $model_class_name;
	static private $Hash;
	
	/**
	 * new model
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	static public function noo()
	{
		if(!(self::$Hash instanceof ModelFiltersHash)){
			self::$Hash = new ModelFiltersHash;
		}
		return new ModelFilters();
	}
	
	/**
	 * Set a filter
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function __call($filter, $callback)
	{
		if(!in_array($filter, $this->valid_filters))
			throw new Exception('unknown model filter: ' . $filter);
		self::$Hash->set($this->model_class_name, $filter, array_shift($callback));
	}
	
	/**
	 * Get the filters for the model and the type
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function get($filter)
	{
		if(!in_array($filter, $this->valid_filters))
			throw new Exception('unknown model filter: ' . $filter);
		$ModelFilters = self::$Hash->get($this->model_class_name);
		return ($ModelFilters instanceof HashArray) ? $ModelFilters->get($filter) : null;
	}
	
	/**
	 * set the model class name
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function setModelClassName($name)
	{
		$this->model_class_name = $name;
	}
	
	/**
	 * Export the Hash
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function export()
	{
		return self::$Hash->export();
	}
}

/**
* 
*/
class ModelFiltersHash extends Hash
{
	
	//run before-validate
	//run after-validate
	//run before-save
	//run after-save
	//run after-commit
	//run before-create
	//run after-create
	//run before-update
	//run after-update
	
	/**
	 * undocumented function
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function set($model, $filter, $callback)
	{
		$HashArray = $this->get($model);
		if(!($HashArray instanceof HashArray)){
			$HashArray = new HashArray;
		}
		$HashArray->set($filter, $callback);
		parent::set($model, $HashArray);
	}
	
	
}
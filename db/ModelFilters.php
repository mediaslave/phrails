<?php
/**
 * @license https://raw.github.com/mediaslave/phrails/master/LICENSE The MIT License
 * @package db
 */

/**
* @package db
* @author Justin Palmer
*/
class ModelFilters
{
	/**
	 * What is the difference between Validate and Judgement?
	 * Validate is run before save, but if you call validate directly the 'Validate' filters are bypassed.
	 * Judgement is on either side of running the actual rules and deciding if all the rules pass.
	 */
	private $valid_filters = array('beforeValidate', 'afterValidate',
								   'beforeJudgement', 'afterJudgement',
								   'beforeSave', 'afterSave',
								   'afterCommit',
								   'beforeCreate', 'afterCreate',
								   'beforeUpdate', 'afterUpdate',
								   'beforeDelete', 'afterDelete');
	private $model_class_name;
	//static private $Hash;
	private $Hash;
	function __construct() {
		$this->Hash = new ModelFiltersHash;
	}


  function destroy() {
    $this->Hash = new ModelFiltersHash;
    throw new Exception("You probably should not be doing this");
  }

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
		$this->Hash->set($this->model_class_name, $filter, array_shift($callback));
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
		$ModelFilters = $this->Hash->get($this->model_class_name);
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
		return $this->Hash->export();
	}

	/**
	 * Merge the passed in ModelFilters with the current one.
	 * 
	 * @param ModelFilters $filters
	 * @return void
	 */
	public function merge(ModelFilters $filters){
		foreach ($filters->export() as $CallbackHash) {
			foreach ($CallbackHash->export() as $filter => $callbacks) {
				foreach ($callbacks as $key => $callback) {
					//new \Dbug($filter, '', false, __FILE__, __LINE__);
					//new \Dbug($model_name, '', false, __FILE__, __LINE__);
					$this->Hash->set($this->model_class_name, $filter, $callback);
				}
			}
		}
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

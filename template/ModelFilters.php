<?php	
/**
* 
*/
class ModelFilters extends Filters
{
	/**
	 * The constants for this class
	 */
	
	const save = 'save';
	
	function __construct($object)
	{
		if(!$object instanceof Model)
			throw new Exception("'ModelFilters' expects a 'Model' as it's first parameter");
		parent::__construct($object);
	}
	
	/**
	 * return the actions, because it is not used when dealing with Models.
	 *
	 * @return array
	 * @author Justin Palmer
	 **/
	protected function addToMethods($actions)
	{
		return $actions;
	}
	/**
	 * Run the type of filter
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function run($type)	
	{
		$run = true;
		$for = $this->filters->get($type);
		foreach($for->array as $filters => $filter){
			$object = $this->object;
			if(is_array($filter) && sizeof($filter) > 0){
				$object = array_shift($filter);
				$filters = array_shift($filter);
				if(!is_object($this->object->$object))
					throw new Exception("There is no object in model property: '$object'.");
				$object = $this->object->$object;
			}
			//if the method does not exist we need to tell them to create it.
			if(!method_exists($object, $filters))
				throw new Exception("The filter: '$filter()' does not exist please create it in your controller.");
			//Can we run the filter? Then run it.
			if($run)
				$object->$filters();
		}
	}
	
	/**
	 * Set a before filter for.
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function beforeSave($filter)
	{
		$this->add($this->getName(self::before, self::save), $filter);
	}
	/**
	 * Set an after filter for.
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function afterSave($filter)
	{	
		$this->add($this->getName(self::after, self::save), $filter);	
	}
	
	/**
	 * Reset the filters for the controller
	 * 
	 * @return void
	 */
	protected function reset(){
		
		$filters = new Hash;
		
		$filters->set($this->getName(self::before, self::save), new Hash);

		$filters->set($this->getName(self::after, self::save), new Hash);
		
		$this->filters = $filters;
	}
	
}

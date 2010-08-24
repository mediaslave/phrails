<?php	
/**
* 
*/
class ControllerFilters extends Filters
{
	/**
	 * The constants for this class
	 */
	
	const around = 'around';
	
	const except = 'except';
	
	function __construct($object)
	{
		if(!$object instanceof Controller)
			throw new Exception("'ControllerFilters' expects a 'Controller' as it's first parameter");
		parent::__construct($object);
	}
	
	/**
	 * Add to public child methods. If no actions were specified we will add 
	 * all of the actions of this controller to the actions array.
	 *
	 * @return array
	 * @author Justin Palmer
	 **/
	protected function addToMethods($actions)
	{
		//If no actions were specified we will add all of the actions of this
		//controller to the actions array.
		if(empty($actions)){
			$r = new Reflections($this->object);
			$actions = $r->getPublicChildMethods();
		}
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
		$action = $this->object->pr_action;
		$for = $this->filters->get($type);
		$except = $this->filters->get($this->exceptName($type));
		foreach($for->array as $filter => $actions){
			//Make sure the except does not hold this action.
			if($except->isKey($filter)){
				if(in_array($action, $except->get($filter), true))
					$run = false;
			}
			//If it is not an empty array and it is not in the action list don't run.
			if(!empty($actions) && !in_array($action, $actions))
				$run = false;
			//if the method does not exist we need to tell them to create it.
			if(!method_exists($this->object, $filter))
				throw new Exception("The filter: '$filter()' does not exist please create it in your controller.");
			//Can we run the filter? Then run it.
			if($run)
				$this->object->$filter();
		}
	}
	
	/**
	 * Set a before filter for.
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function before($filter, $actions=null)
	{
		$args = func_get_args();
		$filter = array_shift($args);
		$this->add(self::before, $filter, $args);
	}
	
	/**
	 * Set a before filter except.
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function beforeExcept($filter, $actions)
	{			
		$args = func_get_args();
		$filter = array_shift($args);
		$this->add($this->exceptName(self::before), $filter, $args);
	}
	
	/**
	 * Set an around filter for.
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function around($filter, $actions=null)
	{
		$args = func_get_args();
		$filter = array_shift($args);
		$this->add(self::around, $filter, $args);
	}
	
	/**
	 * Set an around filter except.
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function aroundExcept($filter, $actions)
	{

			$args = func_get_args();
			$filter = array_shift($args);
			$this->add($this->exceptName(self::around), $filter, $args);
	}
	
	/**
	 * Set an after filter for.
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function after($filter, $actions=null)
	{
		
			$args = func_get_args();
			$filter = array_shift($args);
			$this->add(self::after, $filter, $args);	
	}
	/**
	 * Set an after filter except.
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function afterExcept($filter, $actions)
	{
		$args = func_get_args();
		$filter = array_shift($args);
		$this->add($this->exceptName(self::after), $filter, $args);	
	}
	
	/**
	 * Get the except name
	 *
	 * @return string
	 * @author Justin Palmer
	 **/
	private function exceptName($type)
	{
		return $this->getName($type, self::except);
	}
	
	/**
	 * Reset the filters for the controller
	 * 
	 * @return void
	 */
	protected function reset(){
		$filters = new Hash;
		$filters->set(self::before, new Hash);
		$filters->set($this->exceptName(self::before), new Hash);
		
		$filters->set(self::around	, new Hash);
		$filters->set($this->exceptName(self::around), new Hash);
		
		$filters->set(self::after	, new Hash);
		$filters->set($this->exceptName(self::after) , new Hash);
		$this->filters = $filters;
		//print 'reset controllerfilters';
	}
	
}
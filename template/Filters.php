<?php	
/**
* 
*/
class Filters
{
	/**
	 * The constants for this class
	 */
	const before = 'before';
	
	const around = 'around';
	
	const after = 'after';
	
	const except = 'except';
	
	protected $filters;
	protected $controller;
	
	function __construct(Controller $controller)
	{
		$this->controller = $controller;
		$filters = new Hash;
		$filters->set(self::before	, new Hash);
		$filters->set($this->exceptName(self::before), new Hash);
		
		$filters->set(self::around	, new Hash);
		$filters->set($this->exceptName(self::around), new Hash);
		
		$filters->set(self::after	, new Hash);
		$filters->set($this->exceptName(self::after) , new Hash);
		$this->filters = $filters;
	}
	
	/**
	 * Add the filter to the correct hash.
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	private function add($type, $filter, array $actions)
	{
		//If no actions were specified we will add all of the actions of this
		//controller to the actions array.
		if(empty($actions)){
			$r = new Reflections($this->controller);
			$actions = $r->getPublicChildMethods();
		}
		//Get the hash that has the correct type in it.
		$Set = $this->filters->get($type);
		//Is the filter passed in already in the hash?
		if($Set->isKey($filter)){
			$array = $Set->get($filter);
			$array = array_merge($array, $actions);
			$Set->set($filter, $array);
		}else{
			$Set->set($filter, $actions);
		}
		$this->filters->set($type, $Set);
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
		$action = $this->controller->pr_action;
		$for = $this->filters->get($type);
		$except = $this->filters->get($this->exceptName($type));
		//new Dbug($for, '', false, __FILE__, __LINE__);
		//new Dbug($except, '', false, __FILE__, __LINE__);
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
			if(!method_exists($this->controller, $filter))
				throw new Exception("The filter: '$filter()' does not exist please create it in your controller.");
			//Can we run the filter? Then run it.
			if($run)
				$this->controller->$filter();
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
		return $type . '-' . self::except;
	}
	
	/**
	 * undocumented function
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function filters()
	{
		return $this->filters;
	}
	
}

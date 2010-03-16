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
	const before_except = 'before-except';
	const before_all = 'before-all';
	
	const around = 'around';
	const around_except = 'around-except';
	const around_all = around-all;
	
	const after = 'after';
	const after_except = 'after-except';
	const after_all = 'after-all';
	
	protected $filters;
	
	function __construct()
	{
		$filters = new Hash;
		$filters->set(self::before			, new Hash);
		$filters->set(self::before_except	, new Hash);
		$filters->set(self::before_all		, new Hash);
		$filters->set(self::around			, new Hash);
		$filters->set(self::around_except	, new Hash);
		$filters->set(self::around_all		, new Hash);
		$filters->set(self::after			, new Hash);
		$filters->set(self::after_except	, new Hash);
		$filters->set(self::after_all		, new Hash);
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
		$filter = array_sift($args);
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
		$filter = array_sift($args);
		$this->add(self::before_except, $filter, $args);
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
		$filter = array_sift($args);
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
			$filter = array_sift($args);
			$this->add(self::around_except, $filter, $args);
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
			$filter = array_sift($args);
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
		$filter = array_sift($args);
		$this->add(self::after_except, $filter, $args);	
	}
	
	
}

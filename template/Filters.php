<?php
/**
 * * @license https://raw.github.com/mediaslave/phrails/master/LICENSE The MIT License
 * @package template
 */
/**
 * class description
 *
 * @package template
 * @author Justin Palmer
 */
abstract class Filters
{
	/**
	 * The constants for this class
	 */
	const before = 'before';

	const after = 'after';
	/**
	 * Filters hash that holds all of the filters and different states.
	 *
	 * @var Hash
	 */
	protected $filters;
	/**
	 * Object that the filters will be run with.
	 *
	 * @var Controller|Model
	 */
	protected $object;
	/**
	 * Constructor.
	 */
	function __construct($object)
	{
		$this->object = $object;
		$this->reset();
	}

	/**
	 * Add the filter to the correct hash.
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	protected function add($type, $filter, array $actions=array())
	{
		$actions = $this->addToMethods($actions);
		//Get the hash that has the correct type in it.
		$Set = $this->filters->get($type);
		//Is the filter passed in already in the hash?
		if(!is_array($filter) && $Set->isKey($filter)){
			$array = $Set->get($filter);
			$array = array_merge($array, $actions);
			$Set->set($filter, $array);
		}else{
			if(is_array($filter)){
				$actions = $filter;
				$filter = $filter[0];
			}
			$Set->set($filter, $actions);
		}
		$this->filters->set($type, $Set);
	}

	/**
	 * remove a filter
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function remove($type, $filter)
	{
		//Get the hash that has the correct type in it.
		$Set = $this->filters->get($type);
		$Set->remove($filter);
	}

	/**
	 * Get the except name
	 *
	 * @return string
	 * @author Justin Palmer
	 **/
	public function getName($pre, $post)
	{
		return $pre . '-' . $post;
	}

	/**
	 * Add to all methods if you are a controller
	 *
	 * @return mixed
	 * @author Justin Palmer
	 **/
	abstract protected function addToMethods($actions);

	/**
	 * Run the type of filter
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	abstract public function run($type);

	/**
	 * Clear all filters.
	 *
	 * @return void
	 */
	abstract protected function reset();

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

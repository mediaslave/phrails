<?php

/**
* 
*/
abstract class JsonView extends View
{
	public $can_have_layout=false;
	public $should_fallback_to_html = false;
	public $extension = 'json';
	
	/**
	 * process the view
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function process();
	
	/**
	 * get the class name of the view
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function class_name();
}

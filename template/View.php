<?php

/**
* 
*/
abstract class View implements ViewInterface
{
	public $can_have_layout=true;
	
	public $extension = 'html';
	
	public $should_fallback_to_html = true;
	

	
	/**
	 * get the class name of the view
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function class_name(){
		return get_class($this);
	}
}

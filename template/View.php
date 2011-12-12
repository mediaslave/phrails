<?php
/**
 * @license https://raw.github.com/mediaslave/phrails/master/LICENSE The MIT License
 * @package template
 */
/**
 * class description
 *
 * @package template
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

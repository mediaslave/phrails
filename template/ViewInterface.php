<?php

/**
* 
*/
interface ViewInterface
{
	/**
	 * process the view
	 *
	 * @param string $countent
	 * @return void
	 * @author Justin Palmer
	 **/
	public function process($content);
	
	/**
	 * get the class name of the view
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function class_name();
}

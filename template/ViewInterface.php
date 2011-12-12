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

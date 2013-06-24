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
class RenderToStringController extends Controller
{
	/**
	 * __call
	 * 
	 * @return boolean
	 */
	public function __call($name, array $arguments){}

	/**
	 * description...
	 * 
	 * @return boolean
	 */
	public function import($vars){
		foreach($vars as $key => $value){
			$this->$key = $value;
		}
	}
}

<?php
/**
 * @license https://raw.github.com/mediaslave/phrails/master/LICENSE The MIT License
 * @package tests
 */
/**
 * Description
 *
 * @package tests
 * @author Justin Palmer
 */
/**
*
*/
class FormElementTest extends PHPUnit_Framework_TestCase
{

	/**
	 * @test
	 **/
	public function options()
	{
		$stub = $this->getMockForAbstractClass('FormElement', array('first_name', 'Justin', 'class:bar'));

		$this->assertEquals(' name="first_name" id="first_name_id" class="bar"', $stub->options());
	}
}

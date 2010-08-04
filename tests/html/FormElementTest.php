<?php
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
		
		$this->assertEquals(' name="first_name" value="Justin" id="first_name_id" class="bar"', $stub->options());
		
	}
}

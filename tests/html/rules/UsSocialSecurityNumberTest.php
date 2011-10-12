<?php
/**
* 
*/
class UsSocialSecurityNumberTest extends PHPUnit_Framework_TestCase
{
	/**
	 * undocumented function
	 *
	 * @return void
	 * @author Shaun Gilliland
	 **/
	public function setUp()
	{
		$this->o = new UsSocialSecurityNumberRule();
	}
	/**
	 * @test
	 **/
	public function valid()
	{
		$nums = array(	'123-45-6789',
				'987-54-4321',
				'555-55-5555'
			      );
		foreach($nums as $num){
			$this->o->value = $num;
			$this->assertTrue($this->o->run());
		}
	}
	
	/**
	 * @test
	 **/
	public function invalid()
	{
		$nums = array(	'12345-6789',
				'555 55 5555',
				'555555555'
			      );
		foreach($nums as $num){
			$this->o->value = $num;
			$this->assertFalse($this->o->run());
		}	
	}
}

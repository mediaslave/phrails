<?php
/**
* 
*/
class UsTaxIdTest extends PHPUnit_Framework_TestCase
{
	/**
	 * undocumented function
	 *
	 * @return void
	 * @author Shaun Gilliland
	 **/
	public function setUp()
	{
		$this->o = new UsTaxIdRule();
	}
	/**
	 * @test
	 **/
	public function valid()
	{
		$nums = array(	'123456789',
				'98-7544321'
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
				'555-555555',
				'555-555555',
				'5555-55555',
				'55555-5555',
				'555555-555',
				'5555555-55',
				'55555555-5'
			      );
		foreach($nums as $num){
			$this->o->value = $num;
			$this->assertFalse($this->o->run());
		}	
	}
}

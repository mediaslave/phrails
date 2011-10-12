<?php
/**
* 
*/
class UsMoneyTest extends PHPUnit_Framework_TestCase
{
	/**
	 * undocumented function
	 *
	 * @return void
	 * @author Shaun Gilliland
	 **/
	public function setUp()
	{
		$this->o = new UsMoneyRule();
	}
	/**
	 * @test
	 **/
	public function valid()
	{
		$nums = array(	'123.04',
				'9.87',
				'555.65',
				'0.65'
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
		$nums = array(	'123.456',
				'555.00007',
				'.65',
				'5.079',
				'0.098'
			      );
		foreach($nums as $num){
			$this->o->value = $num;
			$this->assertFalse($this->o->run());
		}	
	}
}

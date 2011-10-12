<?php
/**
* 
*/
class UsPhoneTest extends PHPUnit_Framework_TestCase
{
	/**
	 * undocumented function
	 *
	 * @return void
	 * @author Shaun Gilliland
	 **/
	public function setUp()
	{
		$this->o = new UsPhoneRule();
	}
	/**
	 * @test
	 **/
	public function valid()
	{
		$nums = array(	'123 456 7890',
				'987-544-3210',
				'361 727 1256',
				'936-829 2137',
				'903-549-3213',
				'903 597 6200',
				'817-596-8973'
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
				'55555555-5',
				'5555555555'
			      );
		foreach($nums as $num){
			$this->o->value = $num;
			$this->assertFalse($this->o->run());
		}	
	}
}

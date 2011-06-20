<?php
/**
* 
*/
class InternationalPostalCodeTest extends PHPUnit_Framework_TestCase
{
	/**
	 * undocumented function
	 *
	 * @return void
	 * @author Shaun Gilliland
	 **/
	public function setUp()
	{
		$this->o = new InternationalPostalCodeRule();
	}
	/**
	 * @test
	 **/
	public function valid()
	{
		$nums = array(	'26-600',
				'20-806',
				'11-500',
				'98531',
				'47-206',
				'97200',
				'26400',
				'04-035',
				'34422-5534'
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
		$nums = array(  '2342,232',
				'1234_987',
				'&w3h3h3',
				'987 9-3*',
				'12847389-a^'
			      );
		foreach($nums as $num){			
			$this->o->value = $num;
			$this->assertFalse($this->o->run());
		}	
	}
}

<?php
/**
* 
*/
class InternationalPhoneTest extends PHPUnit_Framework_TestCase
{
	/**
	 * undocumented function
	 *
	 * @return void
	 * @author Shaun Gilliland
	 **/
	public function setUp()
	{
		$this->o = new InternationalPhoneRule();
	}
	/**
	 * @test
	 **/
	public function valid()
	{
		$nums = array(	'1 360 736 5555',
				'3607365554',
				'1-360-736-5556',
				'360-736-5557',
				'+42 360 736 5558',
				'+42-360-736-5559',
				'+423607365560',
				'+42-1-360-736-5561',
				'+42 1 360 736 5562'
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
		$nums = array(	'1+360+736+5555',
				'++3607365554',
				'1+360+736+5556',
				'360+736+5557',
				'+42+360 736 5558',
				'+42+360+736-5559',
				'+42+360-736+5560',
				'+42-1-360+736-5561',
				'+42 1 3A0 73F 55E2'
			      );
		foreach($nums as $num){
			$this->o->value = $num;
			$this->assertFalse($this->o->run());
		}	
	}
}

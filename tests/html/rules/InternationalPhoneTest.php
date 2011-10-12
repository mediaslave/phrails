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
		$nums = array(	'+0048 (56) 6866183',
				'+48 (94) 3432847',
				'+48 (94) 3406252',
				'+48 (94) 3162213',
				'+42 360 736 5558',
				'+42-360-736-5559',
				'+423607365560',
				'+42-1-360-736-5561',
				'+42 1 360 736 5562',
				'+ 42 12345 12345-123456789',
				'+048 (48) 3442255'
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
				'+42 1 3A0 73F 55E2',
				' 42 1 360-555-1212',
				' +3605551212',
				'+ 42 12345 12345-123456789101112'
			      );
		foreach($nums as $num){
			$this->o->value = $num;
			$this->assertFalse($this->o->run());
		}	
	}
}

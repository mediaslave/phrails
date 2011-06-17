<?php
/**
* 
*/
class AlphaExtraTest extends PHPUnit_Framework_TestCase
{
	/**
	 * undocumented function
	 *
	 * @return void
	 * @author Shaun Gilliland
	 **/
	
	/**
	 * @test
	 **/
	public function valid_alexs()
	{
		$extra = '0-4';
		$this->o = new AlphaExtraRule($extra);
		$alexs = array('ThisIsValid',
				'TestingIsFun',
				  'FreDFlintstone',
				     'This234',
					'that23',
					   'dis4'
			     );
		foreach($alexs as $alex){
			$this->o->value = $alex;
			$this->assertTrue($this->o->run());
		}
	}
	
	/**
	 * @test
	 **/
	public function invalid_alexs()
	{
		$extra = '0-4';
		$this->o = new AlphaExtraRule($extra);
		$alexs = array('ThisIsNotValid234@',
				'@thisAndThat',
				    'This Is Not Valid',
				       '4234@#$@',
					  'this s',
					     'thisandthat567'
			      );
		foreach($alexs as $alex){
			$this->o->value = $alex;
			$this->assertFalse($this->o->run());
		}	
	}
}

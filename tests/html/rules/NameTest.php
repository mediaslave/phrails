<?php
/**
* 
*/
class NameTest extends PHPUnit_Framework_TestCase
{
	/**
	 * undocumented function
	 *
	 * @return void
	 * @author Shaun Gilliland
	 **/
	public function setUp()
	{
		$this->o = new NameRule();
	}
	/**
	 * @test
	 **/
	public function valid_names()
	{
		$names = array('Fred Flintstone',
				'Fred Flint-stone',
				  'Fred.Flintstone',
				    "Fred Flint'Stone"
			      );
		foreach($names as $name){
			$this->o->value = $name;
			$this->assertTrue($this->o->run());
		}
	}
	
	/**
	 * @test
	 **/
	public function invalid_names()
	{
		$names = array('Fred Fl1ntstone',
				'freddy23',
				  'Fred+Flintstone',
				    "Fred Flint'5tone"
			      );
		foreach($names as $name){
			$this->o->value = $name;
			$this->assertFalse($this->o->run());
		}	
	}
}

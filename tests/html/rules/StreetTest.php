<?php
/**
* 
*/
class StreetTest extends PHPUnit_Framework_TestCase
{
	/**
	 * undocumented function
	 *
	 * @return void
	 * @author Shaun Gilliland
	 **/
	public function setUp()
	{
		$this->o = new StreetRule();
	}
	/**
	 * @test
	 **/

	public function valid()
	{
		$nums = array(	"Siegfriedstraße",
				"ª",
				"Þ",
				"ƒ",
				"ªßµÐ",
				".-,",
				"#100-200 Nelson St.",
				"thisaddyß-thatstreet",
				"Carrera 29  45  94, Of 708 Bucaramanga",
				"Av. Prof. Othon Gama D'Eça, 900  Loja 13  Térreo  Centro Executivo Casa do Barão"
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
		$nums = array(	'%$#',
				'This%2@',
				')(*&',
				'*&^%',
				'!@$'
			      );
		foreach($nums as $num){
			$this->o->value = $num;
			$this->assertFalse($this->o->run());
		}	
	}
}

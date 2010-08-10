<?php
/**
 * 
 */
class TagTest extends PHPUnit_Framework_TestCase
{
	
	/**
	 * @test
	 **/
	public function options()
	{
		$stub = $this->getMockForAbstractClass('Tag', array('class:foo'));
		
		$this->assertEquals(' class="foo"', $stub->options());
		
		$stub = $this->getMockForAbstractClass('Tag', array('class:bar,alt:quo'));
		
		$this->assertEquals(' class="bar" alt="quo"', $stub->options());
		
	}
	/**
	 * @test
	 **/
	public function to_string()
	{
		$stub = $this->getMockForAbstractClass('Tag');
		$stub->expects($this->any())
			 ->method('start')
			 ->will($this->returnValue('<a>'));

		$stub->expects($this->any())
			 ->method('end')
			 ->will($this->returnValue('</a>'));

		$this->assertEquals('<a>', $stub->start());
		$this->assertEquals('</a>', $stub->end());
		
		$stub->display('A Link!');
		$this->assertEquals('<a>A Link!</a>', $stub->__toString());
	}
}

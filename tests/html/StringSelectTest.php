<?php
/**
 * @license https://raw.github.com/mediaslave/phrails/master/LICENSE The MIT License
 * @package tests
 */
/**
 * Description
 *
 * @package tests
 */
/**
*
*/
class StringSelectTest extends PHPUnit_Framework_TestCase
{

	/**
	 * @test
	 **/
	public function construct_with_options()
	{
		$o = new StringSelect('state', null, 'Washington:WA;California:CA', 'prompt: - Select One - , class:foo');

		$expected = '<select name="state" id="state_id" class="foo">
						<option value="">- Select One -</option>
						<option value="WA">Washington</option>
						<option value="CA">California</option>
					</select>';

		$this->assertXmlStringEqualsXmlString($expected, $o->__toString());
	}
	/**
	 * @test
	 **/
	public function construct_with_array_options()
	{
		$o = new StringSelect('state', null, 'Washington:WA;California:CA', array('prompt' => ' - Select One - ', 'class' => 'foo'));

		$expected = '<select name="state" id="state_id" class="foo">
						<option value=""> - Select One - </option>
						<option value="WA">Washington</option>
						<option value="CA">California</option>
					</select>';

		$this->assertXmlStringEqualsXmlString($expected, $o->__toString());
	}

}

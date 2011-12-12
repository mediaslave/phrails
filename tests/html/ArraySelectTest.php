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
class ArraySelectTest extends PHPUnit_Framework_TestCase
{

	/**
	 * @test
	 **/
	public function construct()
	{
		$with = new ArraySelect('state', array(new Option('Washington', '0'), new Option('California', '1')), 0, 'class:foo');
		$with_out = new ArraySelect('state', array(new Option('Washington', '0'), new Option('California', '1')), 0);
		$with_only_prompt = new ArraySelect('state', array(new Option('Washington', '0'), new Option('California', '1')), 0, 'prompt: - Select One - ');

		$expected = '<select name="state" id="state_id" class="foo">
						<option value="0" selected="selected">Washington</option>
						<option value="1">California</option>
					</select>';

		$expected_without = '<select name="state" id="state_id">
						<option value="0" selected="selected">Washington</option>
						<option value="1">California</option>
					</select>';

		$expected_with_prompt = '<select name="state" id="state_id">
								 	<option value="">- Select One -</option>
								 	<option value="0" selected="selected">Washington</option>
								 	<option value="1">California</option>
								 </select>';

		$this->assertXmlStringEqualsXmlString($expected, $with->__toString());
		$this->assertXmlStringEqualsXmlString($expected_without, $with_out->__toString());
		$this->assertXmlStringEqualsXmlString($expected_with_prompt, $with_only_prompt->__toString());
	}
}

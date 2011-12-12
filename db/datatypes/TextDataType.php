<?php
/**
 * @license https://raw.github.com/mediaslave/phrails/master/LICENSE The MIT License
 * @package db
 * @subpackage datatypes
 */

/**
* @package db
* @subpackage datatypes
* @author Justin Palmer
*/
class TextDataType extends DataType{


	/**
	 * Run an nl2br on the value
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function toBr()
	{
		return nl2br($this->value());
	}

	/**
	 *
	 * Apply markdown to the text
	 *
	 * @return ret
	 * @author Justin Palmer
	 **/
	public function markdown()
	{
		static $parser;
		if (!isset($parser)) {
			$parser = new Markdown;
		}

		# Transform text using parser.
		return $parser->transform($this->value());
	}
}

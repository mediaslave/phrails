<?php
/**
 * @license https://raw.github.com/mediaslave/phrails/master/LICENSE
 */
/**
 * class description
 *
 * @package html
 * @author Justin Palmer
 */
class InputFile extends Input
{
	protected $options = 'type:file';
	/**
	 * Constructor
	 *
	 * @param string $display
	 * @param string or array $options
	 * @author Justin Palmer
	 */
	function __construct($name, $value, $options=null)
	{
		parent::__construct($name, $value, $options);
	}
}

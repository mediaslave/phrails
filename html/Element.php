<?php
/**
 * Creates a 'label'.
 *
 * @author Justin Palmer
 * @package html
 */
abstract class Element extends Tag
{
	/**
	 * Constructor
	 *
	 * @param string $display
	 * @param string or array $options
	 * @author Justin Palmer
	 */
	function __construct($options=null, array $optionExceptions=array())
	{
		parent::__construct($options, $optionExceptions);
	}
}

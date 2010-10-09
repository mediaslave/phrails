<?php
/**
 * Creates a 'label'.
 * 
 * @author Justin Palmer
 * @package html
 */
abstract class FormElement extends Element
{
	public $value = '';
	/**
	 * Constructor
	 *
	 * @param string $display 
	 * @param string or array $options 
	 * @author Justin Palmer
	 */
	function __construct($name, $value, $options=null, array $optionExceptions=array())
	{
		if($name != '')
			$this->options .= ",name:$name";
		if($value !== null)
			$this->value = $value;
		$id = $name . '_id';
		$matches = array();
		if(preg_match("/^(?P<table>[a-z_]*)\[(?P<id>[a-z_]*)\]/i", $id, $matches))
			$id = $matches['table'] . '_' . $matches['id'] . '_id';
		$this->options .= ",id:$id";
		parent::__construct($options, $optionExceptions);
	}
}
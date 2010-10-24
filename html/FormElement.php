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
	protected $is_hidden = false;
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
		if(!$this->is_hidden){
			$id = self::getId($name);
			$this->options .= ",id:$id";
		}
		parent::__construct($options, $optionExceptions);
	}
	
	/**
	 * Generate the id statically
	 *
	 * @return string
	 * @author Justin Palmer
	 **/
	public static function getId($name)
	{
		$id = $name . '_id';
		$matches = array();
		if(preg_match("/^(?P<table>[a-z_]*)\[(?P<id>[a-zA-Z_]*)\](\[(?P<array>[a-z0-9A-Z_\-\.]*)\])*/i", $id, $matches))
			$id = $matches['table'] . '_' . $matches['id'] . '_';
		if(isset($matches['array']))
			$id .= $matches['array'] . '_';
		$id .= 'id';
		return $id;
	}
}
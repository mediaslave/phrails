<?php
/**
 * Creates a 'label'.
 * 
 * @author Justin Palmer
 * @package html
 */
class Input extends Element
{
	protected $hasEndTag = false;
	/**
	 * Constructor
	 *
	 * @param string $display 
	 * @param string or array $options 
	 * @author Justin Palmer
	 */
	function __construct($name, $value, $options=null)
	{
		if($name != '')
			$this->options .= ",name:$name";
		if($value != '')
			$this->options .= ",value:$value";
		$id = $name . '_id';
		$matches = array();
		if(preg_match("/^(?P<table>[a-z_]*)\[(?P<id>[a-z_]*)\]/i", $id, $matches))
			$id = $matches['table'] . '_' . $matches['id'] . '_id';
		$this->options .= ",id:$id";
		parent::__construct($options);
	}
	/**
	 * @see Tag::start()
	 */
	public function start()
	{
		return '<input' . $this->options . ' />';
	}
	/**
	 * @see Tag::end();
	 **/
	public function end(){}
}
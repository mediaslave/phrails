<?php
/**
 * @license https://raw.github.com/mediaslave/phrails/master/LICENSE The MIT License
 * @package html
 */
/**
 * class description
 *
 * @package html
 */
abstract class FormElement extends Element
{
	public $value = '';
	protected $is_hidden = false;
	private $optionExceptions = array('tab'=>'tabindex');
	/**
	 * Constructor
	 *
	 * @param string $display
	 * @param string or array $options
	 * @author Justin Palmer
	 */
	function __construct($name, $value, $options=null, array $optionExceptions=array())
	{
		if($name != ''){
			if($this->options !== ''){
				$this->options .= ',';
			}
			$this->options .= "name:$name";
		}
		if($value !== null){
			$this->value = $value;
		}else{
			$this->value = OptionsParser::findAndDestroy('value', $options);
		}
		if(!$this->is_hidden){
			$id = self::getId($name);
			$this->options .= ",id:$id";
		}
		parent::__construct($options, array_merge($this->optionExceptions, $optionExceptions));
	}

	/**
	 * Generate the id statically
	 *
	 * @return string
	 * @author Justin Palmer
	 **/
	public static function getId($name)
	{
		$id = '_id';
		if(count(explode(']', $name)) > 1)
			$id = 'id';
		return str_replace(array('[', ']'), '_', $name) . $id;
		$matches = array();
		if(preg_match("/^(?P<table>[a-z_]*)\[(?P<id>[a-zA-Z_]*)\](\[(?P<array>[a-z0-9A-Z_\-\.]*)\])*/i", $id, $matches)
							&& sizeof($matches) > 0){
			$id = $matches['table'] . '_' . $matches['id'] . '_';
			if(isset($matches['array']) && $matches['array'] != '')
				$id .= $matches['array'] . '_';
			$id .= 'id';
		}
		return $id;
	}
}

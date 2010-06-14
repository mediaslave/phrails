<?php
/**
 * Is the current model property an integer.
 *
 * @package html
 * @subpackage rules
 * @author Justin Palmer
 **/
class ContentTypeRule extends PregRule
{

	private $type;
	
	/**
	 * Constructor
	 *
	 * @params $name Name of the $_FILES array to look into.
	 * @params $array of content types.
	 * @params $message that you would like displayed if it fails.
	 * @return ContentTypeRule
	 * @author Justin Palmer
	 **/
	public function __construct($type, $array)
	{
		$this->type = $type;
		$types = str_replace('/', '\/', implode('|', $array));
		if(sizeof($array) > 1)
			$types = "($types)";
		$preg = "/^" . $types . "$/";
		$message = '%s should be one of the following file types: ' . implode(', ', $array) . ', type given: ' . $type;
		parent::__construct($preg, $message);
	}
	
	/**
	 * @see Rule::run()
	 **/
	 public function run(){
		return Rule::run(!preg_match($this->preg, $this->type));
	 }
} // END class Rule
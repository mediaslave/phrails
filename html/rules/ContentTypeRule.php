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

	private $name;
	
	/**
	 * Constructor
	 *
	 * @params $name Name of the $_FILES array to look into.
	 * @params $array of content types.
	 * @params $message that you would like displayed if it fails.
	 * @return ContentTypeRule
	 * @author Justin Palmer
	 **/
	public function __construct($name, $array, $message=null)
	{
		$this->name = $name;
		$types = str_replace('/', '\/', implode('|', $array));
		$this->preg = "/^(" . $types . ")$/";
		$this->message = $message;
		parent::__construct();
	}
	
	/**
	 * @see Rule::run()
	 **/
	 public function run(){
		return parent::run(!preg_match($this->preg, $_FILES[$this->name]['type']));
	 }
} // END class Rule
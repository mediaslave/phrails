<?php
/**
 * base rule
 *
 * @package html
 * @subpackage rules
 * @author Justin Palmer
 **/
class Rule
{
	public $value;
	public $message;
	public $custom_message='';
	/**
	 * Constructor
	 *
	 * @param string $customMessage	
	 * @return Rule
	 * @author Justin Palmer
	 **/
	public function __construct($customMessage='')
	{
		$this->custom_message = $customMessage;
		if($this->message === null)
			throw new MessageForRuleException(get_class($this));
	}
	
	/**
	 * Run the rule
	 *
	 * @return boolean
	 * @author Justin Palmer
	 **/
	 public function run($comparison){
		$boolean = true;
		if($comparison)
		{
			if($this->custom_message != '')
				$this->message = $this->custom_message;
			//$this->message = trim(sprintf($this->message, '<span class="label">' . $this->label . '</label>'));
			$boolean = false;
		}
		return $boolean;
	}
} // END class Rule
<?php
/**
 * @license https://raw.github.com/mediaslave/phrails/master/LICENSE The MIT License
 * @package html
 * @subpackage rules
 */
/**
 * class description
 *
 * @package html
 * @subpackage rules
 * @author Justin Palmer
 */
class Rule
{
	/**
	 * The value that the rule should be run against.
	 *
	 * @author Justin Palmer
	 * @var string
	 */
	public $value;

	/**
	 * The name of the field that is being validated
	 *
	 * @author Dave Kerschner
	 * @var string
	 */
	public $property;

	/**
	 * The model that the rule is being applied to
	 *
	 * @author Dave Kerschner
	 * @var string
	 */
	public $model;

	/**
	 * The default message that should be returned.
	 *
	 * @author Justin Palmer
	 * @var string
	 */
	public $message;
	/**
	 * A custom error message that will override the default $message
	 *
	 * @author Justin Palmer
	 * @var string
	 */
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
	 * @param boolean $comparison
	 * @return boolean
	 * @author Justin Palmer
	 **/
	 public function run($comparison){
		$boolean = true;
		if($comparison)
		{
			if($this->custom_message != '')
				$this->message = $this->custom_message;
			$boolean = false;
		}
		return $boolean;
	}
} // END class Rule

<?php
/**
 * 
 *
 * @package html
 * @subpackage rules
 * @author Dave Kerschner
 **/
class AgeRangeRule extends DatabaseRule
{

	private $lower_bound;
	private $upper_bound;
	/**
	 * @see Rule::message
	 */

	public $message;

	public function __construct($lower_bound, $upper_bound=null, $customMessage='') {
		$this->message = '%s must be at least ' . $lower_bound . ' years old.';

		parent::__construct($customMessage);
		
		if($upper_bound !== null) {
			$this->message = '%s must be less than ' . $upper_bound . ' years old at least ' . $lower_bound . ' years old.';
			if ($lower_bound > $upper_bound) {
				throw new Exception("Lower bound $lower_bound can not be greater than upper bound $upper_bound");
			}
		}
		
		$this->lower_bound = $lower_bound;
		$this->upper_bound = $upper_bound;
	}

	/**
	 * @see Rule::run()
	 **/
	 public function run(){
		 $pass = true;
     
     $dr = new DateRule();
     $dr->value = $this->value;
     
     if (!$dr->run()) {
       return false;
     }


		 $age = $this->model->objectify($this->property)->age('%y');

		 if ($age <= $this->lower_bound) {
			 $pass = false;
		 }

		 if($this->upper_bound !== null 
				&& $age >= $this->upper_bound) {
			 $pass = false;
		 }
		 
		 return parent::run(!$pass);
	 }
} // END class Rule
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
 */
class UniqueDatabaseRule extends DatabaseRule
{
	/**
	 * @see Rule::message
	 */
	public $message = '%s is already used. Please enter another one.';

	/**
	 * @see Rule::run()
	 **/
	 public function run(){
	 	try {
	 		$dynamicFinder = 'findFirstBy' . Inflections::classify($this->property);
	 		$record = $this->model->$dynamicFinder($this->value);
			return parent::run($record->id != $this->model->id);
	 	} catch (RecordNotFoundException $e) {
	 		return true;
	 	}
	 }
} // END class Rule

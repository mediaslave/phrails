<?php
/**
 * @license https://raw.github.com/mediaslave/phrails/master/LICENSE
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

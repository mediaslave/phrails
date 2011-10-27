<?php
/**
 * Is the value for a given column unique in the db
 *
 * @package html
 * @subpackage rules
 * @author Dave Kerschner
 **/
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

<?php
/**
 * Base class for database rules
 *
 * @package html
 * @subpackage rules
 * @author Dave Kerschner
 **/
class DatabaseRule extends Rule
{

	protected $model;
	
	public function __construct(Model $model, $customMessage='') {
		$this->model = $model;
		parent::__construct($customMessage);
	}
	
} // END class Rule
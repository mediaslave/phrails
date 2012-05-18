<?php
/**
 * @license https://raw.github.com/mediaslave/phrails/master/LICENSE The MIT License
 * @package template
 */
/**
 * class description
 *
 * @package template
 */

class RoutesModelImporter extends RoutesImporter{
	private $columns_for_import = array('name', 'path', 'controller', 'action', 'namespace');
	private $Model;

	function __construct(Routes $Routes, Model $Model) {
		$this->Model = $Model;
		parent::__construct($Routes);
		$this->validate();
	}

	/**
	 * Validates that the importer can impor the routes.
	 * 
	 * @return boolean
	 * @throws RoutesImporterValidationException
	 */
	public function validate(){
		foreach ($this->columns_for_import as $column) {
			if(!$this->Model->columns()->isKey($column)){
				throw new RoutesImporterValidationException('The Model "' . get_class($object) . '" does not have the column: "' . $column . '" and therefore does not conform.');
			}
		}
	}

	/**
	 * Import the object into the Routes
	 * 
	 * @return Routes
	 */
	public function import(){
		//Find all of the routes that need added.
		$records = $this->Model->findAll();
		foreach ($records as $route) {
			$this->Routes->add($route->name, $route->path, $route->controller, $route->action, $route->namespace);
		}
		return parent::import();
	}
}
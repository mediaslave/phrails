<?php
/**
 * @license https://raw.github.com/mediaslave/phrails/master/LICENSE The MIT License
 * @package template
 */
/**
 * class description
 *
 * @todo refactor
 * @package template
 */
class RenderToStringControllerTemplate extends ControllerTemplate
{
	protected $layouts_path = 'layouts';

	 function __construct($controller, $view_type) {
	 	parent::__construct($controller);
	 	$this->view_type = $view_type;
	 }

	/**
	 * Sets the file path and route array.
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	protected function prepare()
	{
		$this->route->view_type = $this->view_type;
		parent::prepare();
	}

}

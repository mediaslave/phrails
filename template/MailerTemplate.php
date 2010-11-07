<?php
/**
 * Template class handles the hand off from the controller to the view.
 *
 * @package template
 * @author Justin Palmer
 */
class MailerTemplate extends TemplateCache
{
	private $method=null;
	
	protected $layouts_path = 'mailers/layouts';
	
	function __construct($controller, $method) {
		parent::__construct($controller);
		$this->method = $method;
	}
	/**
	 * Sets the file path and route array.
	 *
	 * @refactor
	 * @return void
	 * @author Justin Palmer
	 **/
	protected function prepare()
	{
		$explode = explode('\Mailers\\', get_class($this->Controller));
		
		$explode = explode('\\', array_pop($explode));
		
		$path = 'mailers/' . strtolower(rtrim(implode('/', $explode), 'Mailer'));
		
		$this->view_path = $path . '/' . $this->method . '.html.php';
	}
}
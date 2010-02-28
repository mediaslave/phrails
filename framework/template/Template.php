<?php
/**
 * Template class handles the hand off from the controller to the view.
 *
 * @package template
 * @author Justin Palmer
 */
class Template
{
	/**
	 * The current controller.
	 * @var Controller
	 */
	private $Controller;
	public static $ContentFor;
	public static $current_view_path;
	/**
	 * Create a new Template
	 *
	 * @param Controller $controller 
	 * @return Template
	 * @author Justin Palmer
	 */
	public function __construct($controller)
	{
		$this->Controller = $controller;
		self::$ContentFor = new stdClass;
	}
	/**
	 * Return the template in a string.
	 *	
	 * @return string
	 * @author Justin Palmer
	 */
	public function display()
	{
		$Route = Registry::get('pr-route');
		self::$current_view_path = preg_replace('/([^\s])([A-Z])/', '\1-\2', $Route['controller']);
		$file = preg_replace('/([^\s])([A-Z])/', '\1-\2', $Route['action']);
		$path = strtolower($file) . $this->Controller->pr_view_type . '.php';
		if(self::getCurrentViewPath() !== '')
			$path = self::getCurrentViewPath() . '/' . $path;
		return ($this->Controller->pr_layout === null) ? $this->displayNoLayout($path) 
													   : $this->displayWithLayout($path);
	}
	/**
	 * Get the declared vars that are available to the template.
	 * 
	 * @return array
	 */
	public function vars()
	{
		return array_reverse($this->objectVars($this->Controller));
	}
	/**
	 * Grab the object vars from the object passed in.
	 *
	 * @return array
	 * @author Justin Palmer
	 **/
	public function objectVars($object)
	{
		return get_object_vars($object);
	}
	/**
	 * Get the current view path
	 *
	 * @return string
	 * @author Justin Palmer
	 **/
	public static function getCurrentViewPath()
	{
		return strtolower(self::$current_view_path);
	}	
	/**
	 * @nodoc
	 */
	private function displayWithLayout($path)
	{
		extract($this->objectVars($this->Controller), EXTR_REFS);
		ob_start();
		include $path;
		$pr_view = ob_get_contents();
		ob_clean();
		extract($this->objectVars(self::$ContentFor), EXTR_REFS);
		include 'layouts/' . $this->Controller->pr_layout . '.html.php';
		return ob_get_clean();
	}
	/**
	 * @nodoc
	 */
	private function displayNoLayout($path)
	{
		extract($this->vars(), EXTR_REFS);
		ob_start();
		include $path;
		return ob_get_clean();
	}
}
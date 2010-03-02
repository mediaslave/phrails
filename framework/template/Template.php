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
		//var_dump($Route);
		try{
			$this->checkViewType($Route);
		}catch(NoViewTypeException $e){
			$Route['controller'] = '';
			$Route['action'] = 'prNoViewType';
			$Route['requested'] = $e->getMessage();
			$Route['view-type'] = 'html';
			Registry::set('pr-route', $Route);
			$this->Controller->pr_layout = null;
		}
		self::$current_view_path = preg_replace('/([^\s])([A-Z])/', '\1-\2', $Route['controller']);
		$file = preg_replace('/([^\s])([A-Z])/', '\1-\2', $Route['action']);
		$path = strtolower($file) . '.' . $Route['view-type'] . '.php';
		if(self::getCurrentViewPath() !== '')
			$path = self::getCurrentViewPath() . '/' . $path;
		if($Route['view-type'] != 'html')
			$this->Controller->pr_layout = null;
		return ($this->Controller->pr_layout === null) ? $this->displayNoLayout($path, $Route['view-type']) 
													   : $this->displayWithLayout($path);
	}
	/**
	 * Make sure there is a view of the right type.
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	private function checkViewType($route)
	{
		if(!$this->Controller->pr_view_types->isKey($route['view-type']))
			throw new NoViewTypeException($this->Controller->pr_view_types, $route['view-type']);
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
		ob_start();
			extract($this->objectVars($this->Controller), EXTR_REFS);
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
	private function displayNoLayout($path, $type)
	{
		if($type == 'json')
			return json_encode($this->Controller->pr_view_types->get('json'));
		extract($this->vars(), EXTR_REFS);
		ob_start();
		include $path;
		return ob_get_clean();
	}
}
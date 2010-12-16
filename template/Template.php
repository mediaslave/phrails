<?php
/**
 * Template class handles the hand off from the controller to the view.
 *
 * @package template
 * @author Justin Palmer
 */
abstract class Template
{
	/**
	 * The current controller.
	 * @var Controller
	 */
	protected $Controller;
	/**
	 * ContentFor is a way to pass data from the view to the layout.
	 *
	 * @var stdClass
	 * @author Justin Palmer
	 */
	public static $ContentFor;
	/**
	 * The current view that is going to be rendered.
	 *
	 * @var string
	 * @author Justin Palmer
	 */
	public static $current_view_path;
	/**
	 * Store the SHA of the current view path;
	 *
	 * @var string
	 */
	protected $view_path=null;
	/**
	 * Array of the current route.
	 *
	 * @var array
	 */
	protected $route;
	
	protected $layouts_path = 'layouts/';
	
	private $request;
	
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
		$this->request = new Request;
		$this->init();
	}
	/**
	 * Sets the file path and route array.
	 *
	 * @refactor
	 * @return void
	 * @author Justin Palmer
	 **/
	abstract protected function prepare();
	/**
	 * Return the template in a string.
	 *	
	 * @return string
	 * @author Justin Palmer
	 */
	public function display()
	{
		$this->prepare();
		//Return the appropriate layout and view or view.
		return ($this->Controller->pr_layout === null) ? $this->displayNoLayout($this->view_path, $this->route->view_type) 
													   : $this->displayWithLayout($this->view_path);
	}
	/**
	 * Make sure there is a view of the right type.
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	protected function checkViewType($route)
	{
		if(!$this->Controller->pr_view_types->isKey($route->view_type))
			throw new NoViewTypeException($this->Controller->pr_view_types, $route->view_type);
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
			$t = include $path;
			$pr_view = ob_get_contents();
		ob_clean();
			extract($this->objectVars(self::$ContentFor), EXTR_REFS);
			include $this->layouts_path . '/' . $this->Controller->pr_layout . '.html.php';
			$content = ob_get_clean();
		$this->init();
		return $content;
	}
	/**
	 * @nodoc
	 */
	private function displayNoLayout($path, $type)
	{
		if($type == 'json'){
			$callback = null;
			$jsonify = $this->Controller->pr_view_types->get('json');
			if(isset($jsonify->callback))
				$callback = $jsonify->callback;
			return Json::encode($jsonify->json, $callback);
		}
		extract($this->vars(), EXTR_REFS);
		ob_start();
		include $path;
		$content = ob_get_clean();
		$this->init();
		return $content;
	}
	
	/**
	 * init
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	private function init()
	{
		self::$ContentFor = new stdClass;
	}
	
	/**
	 * The params that the server knows about
	 *
	 * @return mixed
	 * @author Justin Palmer
	 **/
	public function params($key=null)
	{
		return ($key === null) ? $this->request->export() : $this->request->$key;
	}
}
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
	 * Store the sha of the current view path;
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
		//$this->prepare();
	}
	/**
	 * Sets the file path and route array.
	 *
	 * @refactor
	 * @return void
	 * @author Justin Palmer
	 **/
	private function prepare()
	{
		//Get the current route.
		$Route = $this->route = Registry::get('pr-route');
		//Check to make sure that the view type (html/json) is a registered view
		//through $controller->respond_to
		try{
			$this->checkViewType($Route);
		}catch(NoViewTypeException $e){
			//If it is not a view type then we will change the route to
			//change the view to the prNoViewType
			$Route->controller = '';
			$Route->action = 'prNoViewType';
			$Route->requested = $e->getMessage();
			$Route->view_type = 'html';
			$this->route = $Route;
			//Set the route
			Registry::set('pr-route', $Route);
			//Set the layout to null
			$this->Controller->pr_layout = null;
		}
		//Get the current view path based off of the controller
		self::$current_view_path = preg_replace('/([^\s])([A-Z])/', '\1-\2', $Route->controller);
		//Get the file to render from the action of the route.
		$file = preg_replace('/([^\s])([A-Z])/', '\1-\2', $Route->action);
		//Concat the necassary items to complete the path.
		$path = strtolower($file) . '.' . $Route->view_type . '.php';
		//Make sure the path is set
		if(self::getCurrentViewPath() !== '')
			$path = self::getCurrentViewPath() . '/' . $path;
		//If the view is not html then we will set the layout to null
		//json will not use a layout.
		if($Route->view_type != 'html')
			$this->Controller->pr_layout = null;
		//Users can specify a direct view path.
		if($this->Controller->pr_view_path !== null)
			$path = rtrim($this->Controller->pr_view_path, '/') . '/' . $path;
		//Save the sha of the file path.
		$this->view_path = $path;
	}
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
	private function checkViewType($route)
	{
		//var_dump($this->Controller->pr_view_types);
		//var_dump($route->view_type);
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
		//var_dump($path);
		ob_start();
			extract($this->objectVars($this->Controller), EXTR_REFS);
			include $path;
			$pr_view = ob_get_contents();
		ob_clean();
			extract($this->objectVars(self::$ContentFor), EXTR_REFS);
			include 'layouts/' . $this->Controller->pr_layout . '.html.php';
			$content = ob_get_clean();
		return $content;
	}
	/**
	 * @nodoc
	 */
	private function displayNoLayout($path, $type)
	{
		if($type == 'json')
			return Json::encode($this->Controller->pr_view_types->get('json'));
		extract($this->vars(), EXTR_REFS);
		ob_start();
		include $path;
		return ob_get_clean();
	}
}
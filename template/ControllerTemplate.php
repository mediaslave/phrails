<?php
/**
 * Template class handles the hand off from the controller to the view.
 *
 * @package template
 * @author Justin Palmer
 */
class ControllerTemplate extends TemplateCache
{
	protected $layouts_path = 'layouts';
	
	/**
	 * Sets the file path and route array.
	 *
	 * @refactor
	 * @return void
	 * @author Justin Palmer
	 **/
	protected function prepare()
	{
		//Get the current route.
		$Route = $this->route = Registry::get('pr-route');
		//Check to make sure that the view type (html/json) is a registered view
		//through $controller->respond_to
		try{
			$this->checkViewType($Route);
			$this->getView($Route->view_type);
			//Get the current view path based off of the controller
			self::$current_view_path = preg_replace('%\\\\-%', '/', preg_replace('/([^\s])([A-Z])/', '\1-\2', $Route->controller));
			//print self::$current_view_path . '<br/>';
			//exit();
			
			//Get the file to render from the action of the route.
			$file = preg_replace('/([^\s])([A-Z])/', '\1-\2', $Route->action);
			$this->setViewPath($file, $Route->view_type);
			//Let's make sure that the view path exists
			if(!is_file($this->view_path) && is_object($this->View) &&
					$this->View->should_fallback_to_html &&
					!($this->View instanceof HtmlView)){
				$this->View->extension = 'html';
				$this->setViewPath($file, $Route->view_type);
			}
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
			$this->view_path = 'pr-no-view-type.html.php';
			$this->View = new HtmlView();
		}catch(NoViewException $e){
			//If it is not a view type then we will change the route to
			//change the view to the prNoView
			$Route->controller = '';
			$Route->action = 'prNoView';
			$Route->requested = $this->view_path;
			$Route->view_type = 'html';
			$this->route = $Route;
			//Set the route
			Registry::set('pr-route', $Route);
			//Set the layout to null
			$this->Controller->pr_layout = null;
			$this->view_path = 'pr-no-view.html.php';
		}
		
	}
	
	/**
	 * Set the view path
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function setViewPath($file, $view_type)
	{
		//Concat the necassary items to complete the path.
		$path = $file . '.' . $this->View->extension . '.php';
		//Make sure the path is set
		if(self::getCurrentViewPath() !== '')
			$path = self::getCurrentViewPath() . '/' . $path;
			
		$path = strtolower($path);
		//If the view is not html then we will set the layout to null
		//json will not use a layout.
		if($this->View->can_have_layout == false){
			$this->Controller->pr_layout = null;
		}
		//Users can specify a direct view path.
		if($this->Controller->pr_view_path !== null)
			$path = rtrim($this->Controller->pr_view_path, '\\') . '/' . $path;
		//Save the sha of the file path.
		$this->view_path = $path;
		if(!is_file(Registry::get('pr-real-install-path') . '/app/views/' . $path) && !($this->Controller->pr_view_types->get($view_type) instanceof stdClass)){
			throw new NoViewException();
		}
	}
}
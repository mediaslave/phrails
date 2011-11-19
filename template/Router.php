<?php
/**
 * @license https://raw.github.com/mediaslave/phrails/master/LICENSE
 * @package template
 */
/**
 * class description
 *
 * @package template
 * @author Justin Palmer
 */
class Router
{
	/**
	 * The extension of the request
	 *
	 * @var string
	 */
	private $extension = "html";

	static private $tag_expression = "/{([a-zA-Z\_\-]*?)}/i";

	/**
	 * Route the request
	 *
	 * @todo this method can be clean up considerably.
	 *
	 * @return Controller
	 * @author Justin Palmer
	 **/
	public function route()
	{
		//print '<pre>';
		//Process the request.
		try{
			//Figure out what page the user is trying to access.
			$route = $this->findByPath();
			//Set the current routes information in the registry.
			Registry::set('pr-route', $route);
			//Default namespace is the application namespace
			$namespace = PR_APPLICATION_NAMESPACE;
			//Does the route specify it's own namespace.
			if($route->namespace !== null){
				$namespace = $route->namespace;
			}
			//Create the controller vars for instantiation and calling.
			$controller = $namespace . '\app\controllers\\' . str_replace('/', '\\', $route->controller) . 'Controller';
			$action = $route->action;
			//Instantiate the correct controller and call the action.
			$Controller = new $controller();
			//This is a hack.  There is no way to get the method called from a class.
			$Controller->pr_action = $action;
			//Make sure the user has implemented the action
			if(!method_exists($Controller, $action))
				throw new NoActionException();

		}catch(NoRouteException $e){
			Registry::set('pr-route', array('controller' => '',
											'action' => 'prNoRoute',
											'requested' => $_SERVER['REQUEST_URI'],
											'view_type' => 'html',
											'message' => $e->getMessage()));
			$Controller = new Controller();
			$Controller->pr_layout = null;
			$Controller->pr_action = 'prNoRoute';
		}catch(NoControllerException $e){
			Registry::set('pr-route', array('controller' => '',
											'action' => 'prNoController',
											'requested' => $controller,
											'view_type' => 'html'));
			$Controller = new Controller();
			$Controller->pr_layout = null;
			$Controller->pr_action = 'prNoController';
		}catch(NoActionException $e){
			Registry::set('pr-route', array('controller' => '',
											'action' => 'prNoAction',
											'no_action' => $action,
											'no_controller'=> $controller,
											'view_type' => 'html'));
			$Controller = new Controller();
			$Controller->pr_layout = null;
			$Controller->pr_action = 'prNoAction';
		}
		return $Controller;
	}


	public function isRequestUriCurrentRoute($uri) {
		return $this->isActualPath(\Registry::get('pr-route'), $uri);
	}

	/**
	 * Find the route that is a match from the path in the REQUEST_URI
	 *
	 * @return array
	 * @author Justin Palmer
	 */
	private function findByPath()
	{
		//Get the correct request_uri
		$request_uri = $this->requestUri();
		//print $request_uri . '<br/>';
		//Find the closest route
		$close_route = $this->findRoute($request_uri);
		$ret = $close_route['ret'];
		$test = (array)$ret;
		if(empty($test))
			throw new NoRouteException();
		//the view_type extension
		$ret->view_type = $this->extension;
		//Return the route array
		return $ret;
	}
	/**
	 * Find the route that matches the closest.
	 */
	private function findRoute($request_uri)
	{
		$request_uri = ($request_uri == '/') ? '/' : rtrim($request_uri, '/');
		$Routes = Routes::routes();
		$ret = new stdClass;
		$controller_action = null;
		foreach($Routes->export() as $route){
			$route = (object) $route;
			if($request_uri == $route->path){
				$ret = $route;
				break;
			}
			if($this->isActualPath($route, $request_uri)){
				$ret = $route;
				break;
			}
			$controller = preg_replace('/([^\s])([A-Z])/', '\1-\2', $route->controller);

			$first = $second = '/' . strtolower($controller) . '/' . $route->action;
			$second = $second . '/';
			if($first == $request_uri || $second == $request_uri)
				$controller_action = $route;
		}
		//print $controller_action . '<br/>';
		return array('ret' => $ret, 'controller-action' => $controller_action);
	}
	/**
	 * Is it the actual path compared to the request uri?
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	private function isActualPath($route, $request_uri)
	{
		$current = explode('/', $route->path);
		$request = explode('/', $request_uri);
		$diff = array_diff($current, $request);
    $local = new Hash();

		foreach($diff as $key => $val){
			if(isset($request[$key]) && preg_match($this->getTagExpression(), $val)){
				$current[$key] = $request[$key];
				$get = ltrim(rtrim($val, '}'), '{');
				$local->set($get, $request[$key]);
			}
		}
		$actual_path = implode('/', $current);
    $bool = ($request_uri == $actual_path);
    if ($bool) {
      foreach($local->export() as $k => $v) {
        $r = new Request();
				$r->get($k, $v);
      }
    }
		return $bool;
	}
	/**
	 * Get the request uri for comparison.
	 *
	 * @todo str_replace in both cases below should be replaced with preg_replace.
	 */
	private function requestUri()
	{
		$request_uri = explode('?', $_SERVER['REQUEST_URI']);
		//$request_uri[0] = rtrim($request_uri[0], '/');
		//var_dump($request_uri);
		/**
		 * Strip of the install path and add a / all routes begin with /.
		 */
		if(Registry::get('pr-install-path') != '/')
			$request_uri[0] = '/' . str_replace(Registry::get('pr-install-path'), '', $request_uri[0]);
		//print $request_uri[0] . '<br/>';
		//var_dump(Registry::get('pr-install-path'));
		if($request_uri[0] == '')
			$request_uri[0] = '/';
		$extension = explode('.', $request_uri[0]);
		//print_r($extension);
		//var_dump($extension);
		$size = sizeof($extension);
		//print sizeof($extension) . '<br/>';
		//print $request_uri[0] . '<br/>';
		//print $extension[0] . '<br/>';
		if($size > 1 && trim($request_uri[0]) != trim($extension[0])){
			$this->extension = $extension[$size - 1];
		}
		//print $this->extension . '<br/>';
		//print $extension[0] . '<br/>';
		return str_replace('.' . $this->extension, '', $extension[0]);
	}

	/**
	 * Get the tag expression
	 *
	 * @return string
	 * @author Justin Palmer
	 **/
	static public function getTagExpression()
	{
		return self::$tag_expression;
	}
}

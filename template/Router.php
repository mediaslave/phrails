<?php
/**
 * Route the request to the correct controller and action
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
		//Process the request.
		try{
			//Figure out what page the user is trying to access.
			$route = $this->findByPath();
			//Set the current routes information in the registry.
			Registry::set('pr-route', $route);
			
			//Create the controller vars for instantiation and calling.
			$controller = $route->controller . 'Controller';
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
											'view_type' => 'html'));
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
		//Find the closest route
		$close_route = $this->findClosestRoute($request_uri);
		$ret = $close_route['ret'];
		//Create two arrays one for the route and one for the request_uri
		$uri   = explode('/', $request_uri);
		$route = explode('/', $ret->path);
		//verify that the route exists. This method will throw an exception if there is a problem.
		$verified = $this->verifyRoute($uri, $route, $close_route['controller-action']);
		if($verified !== null)
			$ret = $verified;
		//the view_type extension
		$ret->view_type = $this->extension;
		//Return the route array
		return $ret;
	}
	/**
	 * Find the route that matches the closest.
	 */
	private function findClosestRoute($request_uri)
	{
		$Routes = Routes::routes();
		$closeness = 0;
		$ret = new stdClass;
		$controller_action = null;
		foreach($Routes->export() as $key => $value){
			$value = (object) $value;
			if($request_uri == $value->path){
				$ret = $value;
				break;
			}
			$current = similar_text($request_uri, $value->path);
			if($current > $closeness){
				$closeness = $current;
				$ret = $value;
			}
			$controller = preg_replace('/([^\s])([A-Z])/', '\1-\2', $value->controller);
			$first = $second = '/' . strtolower($controller) . '/' . $value->action;
			$second = $second . '/';
			if($first == $request_uri || $second == $request_uri)
				$controller_action = $value;
		}
		return array('ret' => $ret, 'controller-action' => $controller_action);
	}
	/**
	 * Verify that the route that is the closest, is actually a real route.
	 */
	private function verifyRoute($uri, $route, $controller_action)
	{
		$ret = null;
		$rsize = sizeof($route);
		$size = sizeof($uri);
		$count = 0;
		for($i = 0; $i < $size; $i++){
			$vroute = null;
			$vuri = null;
			if(isset($route[$i]))
				$vroute = $route[$i];
			if(isset($uri[$i]))
				$vuri = $uri[$i];
			$tag = (preg_match('/{([a-zA-Z])*}/i', $vroute));
			if($tag == 1){
				$count++;
				$key = rtrim(ltrim($vroute, '{'), '}');
				$_GET[$key] = $vuri;
			}else{
				if($vuri == $vroute){
					$count++;
				}
			}
		}
		//If they don't match let's see if they are requesting by controller/action
		//otherwise throw a no route exception.
		if($size !== $count){
			$ret = $controller_action;
			if($controller_action === null)
				throw new NoRouteException();
		}
		return $ret;
	}
	/**
	 * Get the request uri for comparison.
	 */
	private function requestUri()
	{
		$request_uri = explode('?', $_SERVER['REQUEST_URI']);
		$extension = explode('.', $request_uri[0]);
		//print_r($extension);
		$size = sizeof($extension);
		if($size > 0 && $request_uri[0] != $extension[0])
			$this->extension = $extension[$size - 1];
		//print $this->extension;
		return rtrim($extension[0], $this->extension);
	}
}

<?php
/**
* 
*/
class Router
{	
	/**
	 * Find the route that is a match from the path in the REQUEST_URI
	 */
	public function findByPath()
	{
		//Get the correct request_uri
		$request_uri = $this->requestUri();
		//Find the closest route
		$close_route = $this->findClosestRoute($request_uri);
		$ret = $close_route['ret'];
		//Create two arrays one for the route and one for the request_uri
		$uri   = explode('/', $request_uri);
		$route = explode('/', $ret['path']);
		//verify that the route exists. This method will throw an exception if there is a problem.
		$verified = $this->verifyRoute($uri, $route, $close_route['controller-action']);
		if($verified !== null)
			$ret = $verified;
		//We only handle .html files for now.  We will except other types later.
		$ret['view-type'] = '.html';
		return $ret;
	}
	/**
	 * Find the route that matches the closest.
	 * @return array
	 */
	private function findClosestRoute($request_uri)
	{
		$Routes = Routes::routes();
		$closeness = 0;
		$ret = array();
		$controller_action = null;
		foreach($Routes->export() as $key => $value){
			//$_SERVER['REQUEST_URI]
			if($request_uri == $value['path']){
				$ret = $value;
				break;
			}
			$current = similar_text($request_uri, $value['path']);
			if($current > $closeness){
				$closeness = $current;
				$ret = $value;
			}
			$controller = preg_replace('/([^\s])([A-Z])/', '\1-\2', $value['controller']);
			$first = $second = '/' . strtolower($controller) . '/' . $value['action'];
			$second = $second . '/';
			if($first == $request_uri || $second == $request_uri)
				$controller_action = $value;
		}
		return array('ret' => $ret, 'controller-action' => $controller_action);
	}
	/**
	 * Verify that the route that is the closests, is actually a real route.
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
				Registry::set($key, $vuri);
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
		return $request_uri[0];
	}
}

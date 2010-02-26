<?php
/**
* 
*/
class Router
{
	/**
	 * similar_text will need to be changed to take in the current path.
	 * @todo replace '/profile/12345/edit' with $_SERVER['REQUEST_URI']
	 * @todo refactor!!!!!!
	 */
	public function findByPath()
	{
		$Routes = Routes::routes();
		$closeness = 0;
		$ret = array();
		foreach($Routes->export() as $key => $value){
			//$_SERVER['REQUEST_URI]
			if($_SERVER['REQUEST_URI'] == $value['path']){
				$ret = $value;
				break;
			}
			$current = similar_text($_SERVER['REQUEST_URI'], $value['path']);
			if($current > $closeness){
				$closeness = $current;
				$ret = $value;
			}
		}
		$uri   = explode('/', $_SERVER['REQUEST_URI']);
		$route = explode('/', $ret['path']);
		if(is_array($route) && is_array($uri)){
			$rsize = sizeof($route);
			$size = sizeof($uri);
			if($rsize != $size)
				throw new NoRouteException();
			$count = 0;
			for($i = 0; $i < sizeof($uri); $i++){
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
			if($size !== $count)
				throw new NoRouteException();
		}
		return $ret;
	}
}

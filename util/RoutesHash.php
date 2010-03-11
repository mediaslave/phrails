<?php
/**
 * A Hash to deal with routes.
 *
 * @package util
 * @author Justin Palmer
 */
class RoutesHash extends Hash{
	/**
	 * The current name of the route being set.
	 *
	 * @author Justin Palmer
	 * @var string
	 */
	public $name = null;
	/**
	 * Set the current route.
	 *
	 * @param string $key 
	 * @param mixed $value 
	 * @return void
	 * @author Justin Palmer
	 */
	public function set($key, $value){
		if($key == 'name')
			$this->name = $value;
		if($this->name === null)
			throw new Exception('You must specify a name before adding to the Routes Hash.');
		$this->array[$this->name][$key] = $value;
	}
	/**
	 * Get the current route.
	 *
	 * @param string $name 
	 * @param string $key 
	 * @return mixed
	 * @author Justin Palmer
	 */
	public function get($name, $key){
		if(!$this->isKey($name))
			throw new Exception('Route does not exist: ' . $name);
		return $this->array[$name][$key];
	}
	/**
	 * Set a route.
	 *
	 * @param string $name 
	 * @param string $path 
	 * @param string $controller 
	 * @param string $action 
	 * @return void
	 * @author Justin Palmer
	 */
	public function route($name, $path, $controller, $action){
		//$install_path = Registry::get('pr-install-path');
		//$path = rtrim($install_path, '/') . $path;
		//print $path . '<br/>';

		$this->set('name', $name);
		$this->set('path', $path);
		$this->set('controller', $controller);
		$this->set('action', $action);
	}

}
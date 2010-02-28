<?php

class RoutesHash extends Hash{

	public $name = null;

	public function set($key, $value){
		if($key == 'name')
			$this->name = $value;
		if($this->name === null)
			throw new Exception('You must specify a name before adding to the Routes Hash.');
		$this->array[$this->name][$key] = $value;
	}

	public function get($name, $key){
		if(!$this->isKey($name))
			throw new Exception('Route does not exist: ' . $name);
		return $this->array[$name][$key];
	}
	
	public function route($name, $path, $controller, $action){
		$this->set('name', $name);
		$this->set('path', $path);
		$this->set('controller', $controller);
		$this->set('action', $action);
	}

}
<?php

class RoutesHash extends Hash{

	public $name = null;

	function set($key, $value){
		if($key == 'name'){
			$this->name = $value;
		}
		if($this->name === null){
			throw new Exception('You must specify a name before adding to the Routes Hash.');
		}
		$this->array[$this->name][$key] = $value;
		return true;
	}

	function get($name, $key){
		if(!$this->isKey($name))
			throw new Exception('Route does not exist: ' . $name);
		return $this->array[$name][$key];
	}

	function route($name, $path, $controller, $action){
		$this->set('name', $name);
		$this->set('path', $path);
		$this->set('controller', $controller);
		$this->set('action', $action);
	}

	function __toString(){
		var_dump($this->array);
		return '';
	}
}
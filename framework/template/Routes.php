<?php

class Routes{

	static $Hash;

	function __construct(){
		self::$Hash = new RoutesHash();
	}

	function add($name, $path, $controller, $action){
		self::$Hash->route($name, $path, $controller, $action);
	}
	
	function resources($name, $controller){
		$path = '/' . $name;
		$this->add($name, $path, $controller, 'index');
		$this->add('edit-' . $name, $path . '/{id}/edit', $controller, 'edit');
		$this->add('new-' . $name, $path . '/new', $controller, 'create');
		$this->add('delete-' . $name, $path . '/{id}/delete', $controller, 'delete');
	}
	
	public static function path($name, $options=null){
		$args = OptionsParser::toArray($options);
		$path = preg_replace('/{([a-zA-Z])*}/i', '%s', self::$Hash->get($name,'path'));
		$match = preg_match('/(\%s)/', $path);
		if($match && empty($args))
			throw new Exception("The path '$name' should be passed some arguments.");
		return vsprintf($path, $args);
	}
	
	public static function routes()
	{
		return self::$Hash;
	}

	public function root($path, $controller, $action)
	{
		if(self::$Hash->isKey('root'))
			throw new Exception('You may only define one root route.');
		self::$Hash->route('root', $path, $controller, $action);
	}
}
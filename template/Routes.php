<?php
/**
 * Routes helps the user build out routes so that the app
 * knows what to do with certain page requests.
 *
 * @package template
 * @author Justin Palmer
 */
class Routes{
	/**
	 * Hash that holds all of the routes
	 *
	 * @var $RoutesHash
	 * @author Justin Palmer
	 */
	static $Hash;
	/**
	 * Create a Routes object
	 *
	 * @return Routes
	 * @author Justin Palmer
	 */
	public function __construct(){
		self::$Hash = new RoutesHash();
	}
	
	/**
	 * Declare the root route
	 *
	 * @param string $path 
	 * @param string $controller 
	 * @param string $action 
	 * @return void
	 * @author Justin Palmer
	 */
	public function root($path, $controller, $action)
	{
		self::$Hash->route('root', $path, $controller, $action);
	}
	/**
	 * Add a route to the hash.
	 *
	 * @param string $name 
	 * @param string $path 
	 * @param string $controller 
	 * @param string $action 
	 * @return void
	 * @author Justin Palmer
	 */			
	function add($name, $path, $controller, $action, $namespace=null){
		self::$Hash->route($name, $path, $controller, $action, $namespace);
	}
	/**
	 * Create four routes for the given controller.
	 *
	 * index, edit, create, delete
	 *
	 * @param string $name 
	 * @param string $controller 
	 * @return void
	 * @author Justin Palmer
	 */
	function resources($name, $controller=null, $namespace=null){
		$pieces = explode('\\', $name);
		if($controller === null)
			$controller = $name;
		$name_for_path = '';
		$name_for_path_end = array_pop($pieces);
		if(sizeof($pieces) > 0){
			foreach($pieces as $piece){
				$id = '{' . str_replace('-', '_', Inflections::singularize(Inflections::tableize($piece))) . '_id}';
				$name_for_path .= $piece . '/' . $id . '/';
			}
		}
		$path = preg_replace('/([a-z])([A-Z])/', '$1-$2', $name_for_path . $name_for_path_end);
		$path = strtolower('/' . $path);
		
		//If the singular and the plural are the same add -index to the index route.
		$index = $name = strtolower(preg_replace('/([a-z])([A-Z])/', '$1-$2', str_replace('\\', '-', $name)));
		$singular = Inflections::singularize($name);
		if($index == $singular)
			$index .= '-index';
		$this->add($index, $path, $controller, 'index', $namespace);
		$this->add('new-' . Inflections::singularize($name), $path . '/new', $controller, 'init', $namespace);
		$this->add('create-' . Inflections::singularize($name), $path . '/create', $controller, 'create', $namespace);
		$this->add($singular, $path . '/{id}', $controller, 'view', $namespace);
		$this->add('edit-' . Inflections::singularize($name), $path . '/{id}/edit', $controller, 'edit', $namespace);
		$this->add('update-' . Inflections::singularize($name), $path . '/{id}/update', $controller, 'update', $namespace);
		$this->add('delete-' . Inflections::singularize($name), $path . '/{id}/delete', $controller, 'delete', $namespace);
	}
	/**
	 * Return the path for the given named route
	 *
	 * @param string $name 
	 * @param string $options 
	 * @return string
	 * @author Justin Palmer
	 */
	public static function path($name, $options=null){
		$app_path = Registry::get('pr-install-path');
		$args = OptionsParser::toArray($options);
		$path = preg_replace('/{([a-zA-Z\_\-])*}/i', '%s', self::$Hash->get($name,'path'));
		$match = preg_match('/(\%s)/', $path);
		if($match && empty($args))
			throw new Exception("The path '$name' should be passed some arguments.");
		return vsprintf(rtrim($app_path, '/') . $path, $args);
	}
	/**
	 * Get the current routes that are declared
	 *
	 * @return RoutesHash
	 * @author Justin Palmer
	 */
	public static function routes()
	{
		return self::$Hash;
	}
}
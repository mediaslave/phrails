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
	
	public static function path($name){
		$args = func_get_args();
		$args = self::prepareArgs($args);
		$path = preg_replace('/{([a-zA-Z])*}/i', '%s', self::$Hash->get($name,'path'));
		$args = (is_array($args[0])) ? $args[0] : array();
		$match = preg_match('/(\%s)/', $path);
		if($match && empty($args))
			throw new Exception("The path '$name' should be passed some arguments.");
		return vsprintf($path, $args);
	}
	
	public static function routes()
	{
		return self::$Hash;
	}
	
	public static function prepareArgs($args)
	{
		//remove the first element of the array.
		array_shift($args);
		return $args;
	}
	public function root($path, $controller, $action)
	{
		if(self::$Hash->isKey('root'))
			throw new Exception('You may only define one root route.');
		self::$Hash->route('root', $path, $controller, $action);
	}
	/**
	 * similar_text will need to be changed to take in the current path.
	 * @todo replace '/profile/12345/edit' with $_SERVER['REQUEST_URI']
	 * @todo refactor!!!!!!
	 */
	public function findByPath()
	{
		$closeness = 0;
		$ret = array();
		foreach(self::$Hash->export() as $key => $value){
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

/**
* 
*/
class Hash
{
	public $array;
	
	public function __construct(array $array=array())
	{
		$this->array = $array;								
	}
	
	public function set($key, $value)
	{
		$this->array[$key] = $value;
	}
	
	public function get($key)
	{
		if(!$this->isKey($key))
			throw new Exception('The key specified does not exist.');
		return $this->array[$key];
	}
	
	public function isKey($key)
	{
		return (isset($this->array[$key]));
	}
	
	public function export()
	{
		return $this->array;
	}
}

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

/**
* 
*/
class Template
{
	public $data;
	
	private $layout;
	
	public static $current_view_path;
	
	public function __construct($layout)
	{
		$this->data = new Hash();
		$this->layout = $layout;
	}

	public function display()
	{
		$Route = Registry::get('pr-route');
		self::$current_view_path = preg_replace('/([^\s])([A-Z])/', '\1-\2', $Route['controller']);
		$file = preg_replace('/([^\s])([A-Z])/', '\1-\2', $Route['action']);
		$path = strtolower($file) . '.php';
		if($viewPath !== '')
			$path = self::getCurrentViewPath() . '/' . $path;
		extract($this->data->export());
		ob_start();
		include $path;
		$pr_template = ob_get_contents();
		ob_clean();
		include 'layouts/' . $this->layout . '.php';
		return ob_get_clean();
	}
	
	public static function getCurrentViewPath()
	{
		return strtolower(self::$current_view_path);
	}
}
/**
* 
*/
class TemplatePartial
{
	public static function render($args)
	{
		$file = self::path(array_shift($args));
		$args = (!empty($args) && is_array($args[0])) ?  $args[0] : array();
		return self::get($file, $args);
	}
	private function get($file, array $array = array())
	{
		if(!empty($array))
			extract($array);
		ob_start();
		include $file;
		return ob_get_clean();
	}
	private function path($file)
	{
		$match = preg_match('/(\/)/', $file);
		if(!$match)
			$file = Template::getCurrentViewPath() . '/_' . $file . '.php';
		if(!is_file($file))
			throw new Exception("The template at the path '$file' does not exist.");
		return $file;
	}
}

/**
* 
*/
class Controller
{
	protected $layout = 'application';
	
	public function __construct()
	{
		$this->Template = new Template($this->layout);
	}
	/**
	 * No controller action.
	 */
	public function prNoController(){}
	
	public function set($key, $value)
	{
		$this->Template->data->set($key, $value);
	}
}

class Profile
{
	public function edit()
	{
		print 'we are editing the profile';
	}
}

/**
* 
*/
abstract class Tag
{
	protected $start;
	protected $end;
	protected $hasEnd = true;
	protected $display = '';
	abstract function start();
	abstract function end();
	public function display()
	{
		return $this->display;
	}
	public function __toString()
	{
		return $this->start() . $this->display() . $this->end();
	}
}

/**
* 
*/
class A extends Tag
{
	
	function __construct($display, $href="", $class='')
	{
		$this->display = $display;
		$this->href = $href;
		$this->class = $class;
	}
	public function start()
	{
		return '<a href="' . $this->href . '">';
	}
	public function end()
	{
		return '</a>';
	}
}









function include_all_once ($pattern) {
    foreach (glob($pattern) as $file) { // remember the { and } are necessary!
        include $file;
    }
}




/**
* 
*/
class Registry	
{
	static $Hash;
	
	public function __construct()
	{
		self::$Hash = new Hash();
	}
	
	public static function set($key, $value)
	{
		self::init();
		self::$Hash->set($key, $value);
	}
	
	public static function get($key)
	{
		self::init();
		return self::$Hash->get($key);
	}
	
	public static function export()
	{
		self::init();
		return self::$Hash->array;
	}
	
	private function init()
	{
		if(self::$Hash === null){
			self::$Hash = new Hash();
		}
	}
}
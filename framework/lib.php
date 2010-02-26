<?php
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
	private $controller;
	
	public static $current_view_path;
	
	public function __construct($controller)
	{
		$this->controller = $controller;
	}

	public function display()
	{
		$Route = Registry::get('pr-route');
		self::$current_view_path = preg_replace('/([^\s])([A-Z])/', '\1-\2', $Route['controller']);
		$file = preg_replace('/([^\s])([A-Z])/', '\1-\2', $Route['action']);
		$path = strtolower($file) . '.php';
		if(self::getCurrentViewPath() !== '')
			$path = self::getCurrentViewPath() . '/' . $path;
		extract($this->vars(), EXTR_REFS);
		ob_start();
		include $path;
		$pr_template = ob_get_contents();
		ob_clean();
		include 'layouts/' . $this->controller->pr_layout . '.php';
		return ob_get_clean();
	}
	/**
	 * Get the declared vars that are available to the template.
	 * @return array
	 */
	public function vars()
	{
		return get_object_vars($this->controller);
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
		$included = @include $file;
		if($included === false)
			throw new Exception("The template at the path '$file' does not exist.");
		return ob_get_clean();
	}
	
	private function path($file)
	{
		$match = preg_match('/(\/)/', $file);
		if(!$match)
			$file = Template::getCurrentViewPath() . '/_' . $file . '.php';
		return $file;
	}
}

/**
* 
*/
class Controller
{
	public $pr_layout = 'application';
	public $pr_controller;
	public function __construct()
	{
		$this->pr_controller = get_class($this);
	}
	/**
	 * No controller action.
	 */
	public function prNoController(){}
	
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
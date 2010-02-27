<?php
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
		$path = strtolower($file) . $this->controller->pr_view_type . '.php';
		if(self::getCurrentViewPath() !== '')
			$path = self::getCurrentViewPath() . '/' . $path;
		return ($this->controller->pr_layout === null) ? $this->displayNoLayout($path) 
													   : $this->displayWithLayout($path);
	}
	private function displayWithLayout($path)
	{
		extract($this->vars(), EXTR_REFS);
		ob_start();
		include $path;
		$pr_template = ob_get_contents();
		ob_clean();
		include 'layouts/' . $this->controller->pr_layout . '.html.php';
		return ob_get_clean();
	}
	private function displayNoLayout($path)
	{
		extract($this->vars(), EXTR_REFS);
		ob_start();
		include $path;
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
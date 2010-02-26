<?php
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
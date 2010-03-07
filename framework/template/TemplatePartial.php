<?php
/**
 * A class to handle all of the partial methods of the Template.
 * 
 * @author Justin Palmer
 * @package template
 */
class TemplatePartial
{
	/**
	 * Render a partial for the template.
	 *
	 * @param mixed $args 
	 * @return string
	 * @author Justin Palmer
	 */
	public static function render($args)
	{
		$file = self::path(array_shift($args));
		$args = (!empty($args) && is_array($args[0])) ?  $args[0] : array();
		return self::get($file, $args);
	}
	/**
	 * Get the file specified by render()
	 *
	 * @see render
	 * @param string $file 
	 * @param array $array 
	 * @return string
	 * @author Justin Palmer
	 */
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
	/**
	 * Figure out if the path should include the current views path.
	 * Or, if it should just take the path as it is.
	 *
	 * @param string $file 
	 * @return string
	 * @author Justin Palmer
	 */
	private function path($file)
	{
		$match = preg_match('/(\/)/', $file);
		if(!$match)
			$file = Template::getCurrentViewPath() . '/_' . $file . '.php';
		return $file;
	}
}
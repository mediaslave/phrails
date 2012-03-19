<?php
/**
 * @license https://raw.github.com/mediaslave/phrails/master/LICENSE The MIT License
 * @package template
 */
/**
 * class description
 *
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
	private function get($_file, array $array = array())
	{
		self::checkExtractVars($array);
	    if(!empty($array))
	      extract($array);
	    ob_start();
	    $_included = include $_file;
	    if($_included === false)
	      throw new Exception("The template at the path '$_file' does not exist.");
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
			$file = Template::getCurrentViewPath() . '/_' . $file . '.html.php';
		return $file;
	}

	/**
	 * Check to make sure that the vars for $_included and $_file are not used,
	 * these are special values
	 * 
	 * @throws Exception
	 * @return void
	 */
	public function checkExtractVars(array $array){
		if(in_array('_included', array_keys($array)) || 
			in_array('_file', array_keys($array))){
			throw new Exception('TemplatePartial::render can not be passed vars that have a key of "_included" or "_file"');
		}
	}
}

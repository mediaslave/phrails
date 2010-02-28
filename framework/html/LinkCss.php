<?php
/**
* 
*/
class LinkCss extends Link
{
	protected $hasEndTag = false;
	protected $options = 'type:text/css';
	
	function __construct($path, $options='')
	{
		$path = $path . '.css';
		$app_path = Registry::get('pr-install-path');
		if($app_path != null)
			$path = $app_path . 'public/stylesheets/' . $path;
		parent::__construct($path, $options);
	}
}
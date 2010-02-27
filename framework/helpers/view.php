<?php
/**
 * @package helpers 
 */

/**
 * Render a partial or file to the current view.
 *
 * If the file to render is in the current views directory then you can pass
 * in just the name of the file minus the '_' and the '.php'
 *
 * pr_render('count', array('count'=>array('1','2','3')))
 * pr_render('login');
 *
 * If the file to render is <b>outside</b> the  current views directory you must
 * pass in the full path.
 *
 * pr_render('home/_count.php', array('count'=>array('1','2','3')))
 * pr_render('login/_login.php');
 *
 * @return string
 */
function pr_render(){
	$args = func_get_args();
	return TemplatePartial::render($args);
}

/**
 * Return the path of a route with arguments passed if given.
 *
 * @return string
 */
function pr_path($name)
{
	$args = func_get_args();
	//Get rid of the $name var from the $args array
	array_shift($args);
	return Routes::path($name, $args);
}

function pr_link($display, $path)
{
	return new A($display, $path);
}
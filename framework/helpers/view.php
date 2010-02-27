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
 * render('count', array('count'=>array('1','2','3')))
 * render('login');
 *
 * If the file to render is <b>outside</b> the  current views directory you must
 * pass in the full path.
 *
 * render('home/_count.php', array('count'=>array('1','2','3')))
 * render('login/_login.php')
 *
 * @return string
 */
function render(){
	$args = func_get_args();
	return TemplatePartial::render($args);
}

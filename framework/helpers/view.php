<?php
/**
 * @package helpers 
 */

/**
 * Render a partial or file to the current view.
 *
 * @example examples/render.php
 * @param string
 * @param array
 * @return string
 * @author Justin Palmer
 */
function render(){
	$args = func_get_args();
	return TemplatePartial::render($args);
}

/**
 * Create a link tag for the stylesheet
 *
 * @example examples/stylesheet-link-tag.php
 * @param string $file
 * @param string $options=''
 * @return string
 * @author Justin Palmer
 **/
function stylesheet_link_tag($file, $options='')
{
	return new LinkCss($file, $options);
}
/**
 * Set the content for a certain var from the view.
 * 
 * Use of this method will over right any vars set in the controller 
 * that are used in the layout. 
 *
 * @param string $key 
 * @param string $value 
 * @return void
 * @author Justin Palmer
 */
function content_for($key, $value){
	(isset(Template::$ContentFor->$key)) 
					? Template::$ContentFor->$key = $value . Template::$ContentFor->$key
					: Template::$ContentFor->$key = $value;
}
/**
 * Get the value of an existing content_for key.
 *
 * @return string $key
 * @author Justin Palmer
 **/
function get_content_for($key){
	return (isset(Template::$ContentFor->$key)) ? Template::$ContentFor->$key : null ;
}

/**
 * Flash the message on the screen.
 * 
 * You can create your own Flash by extending the <code>Flash</code> class 
 * and implementing the <code>display()</code> method.
 *
 * @return string
 * @author Justin Palmer
 **/
function flash_it($flash)
{
	if($flash instanceof Flash)
		return $flash->display();
	return $flash;
}
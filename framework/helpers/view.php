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
 * stylesheet_link_tag('screen', 'media:all') will generate
 * <link href="/public/stylesheets/screen.css" media="all" type="text/css" />
 *
 * Link to a css file in a sub folder
 * stylesheet_link_tag('sub/folder/screen');
 * <link href="/public/stylesheets/sub/folder/screen.css" type="text/css" />
 *
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
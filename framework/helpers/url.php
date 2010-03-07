<?php
/**
 * The helpers to helper to deal with url's, paths.
 *
 * @author Justin Palmer
 * @package helpers
 */
/**
 * Return the path of a route with arguments passed if given.
 *
 * Options are parsed and replace {value} from route.
 *
 * path('edit-profile', 'id:12345')
 *
 * @param string $name
 * @param string $options
 * @return string
 */
function path($name, $options=null)
{
	return Routes::path($name, $options);
}
/**
 * This will create a link.
 *
 * Options are parsed into attributes in format attribute-name:value
 * 
 * link_to('home', path('home'), 'class:menu,id:home-menu')
 * 
 * @param string $display
 * @param string $path
 * @param string $options
 * @return string
 */
function link_to($display, $path, $options=null)
{
	return new A($display, $path, $options);
}

/**
 * Create a link_to if a condition is true
 *
 * @param boolean $condition
 * @param string $display
 * @param string $path
 * @param string $options
 * @return string
 * @author Justin Palmer
 **/
function link_to_if($condition, $display, $path, $options=null)
{
	//var_dump($condition);
	$ret = '';
	if($condition){
		$ret = link_to($display, $path, $options);
	}
	return $ret;
}

/**
 * Create a link_to unless a condition is true
 *
 * @param boolean $condition
 * @param string $display
 * @param string $path
 * @param string $options
 * @return string
 * @author Justin Palmer
 **/
function link_to_unless($condition, $display, $path, $options=null)
{
	$condition = ($condition) ? false : true;
	return link_to_if($condition, $display, $path, $options);
}
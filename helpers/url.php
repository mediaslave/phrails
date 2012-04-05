<?php
/**
 * The helpers to helper to deal with url's, paths.
 * @license https://raw.github.com/mediaslave/phrails/master/LICENSE The MIT License
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
 * Get the full url with pr-domain-uri
 *
 * @return void
 **/
function url($name, $options=null)
{
	return Registry::get('pr-domain-uri') . path($name, $options);
}

/**
 * Asset url
 *
 * @return void
 **/
function aurl($name, $options=null)
{
	static $acount;
	if($acount === null) $acount = 0;
	$assets = (array) Registry::get('pr-asset-uris');
	$assets = array_values($assets);
	if($assets === null){
		return url($name, $options);
	}
	$url = $assets[$acount] . path($name, $options);
	($acount == (count($assets) - 1)) ? $acount = 0 : $acount++;
	return $url;
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
 **/
function link_to_if($condition, $display, $path, $options=null)
{
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
 **/
function link_to_unless($condition, $display, $path, $options=null)
{
	$condition = ($condition) ? false : true;
	return link_to_if($condition, $display, $path, $options);
}

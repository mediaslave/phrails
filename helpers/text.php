<?php
/**
 * Text helpers
 * * @license https://raw.github.com/mediaslave/phrails/master/LICENSE The MIT License
 * @package helpers
 * @author Justin Palmer
 */
/**
 * @see String::$escape()
 **/
function h($string)
{
	return String::escape($string);
}

/**
 * @see String::$decode()
 **/
function decode($string)
{
	return String::decode($string);
}

/**
 * @see Inflections::$pluralize()
 **/
function pluralize($string)
{
	return Inflections::pluralize($string);
}

/**
 * @see Inflections::$pluralizeIf()
 **/
function pluralizeIf($integer, $string)
{
	return Inflections::pluralizeIf($integer, $string);
}

/**
 * @see Inflections::$singularize()
 **/
function singularize($string)
{
	return Inflections::singularize($string);
}
/**
 * @see String::$truncate()
 **/
function truncate($string, $limit, $pad='...', $break='.')
{
	return String::truncate($string, $limit, $pad, $break);
}

/**
 * @see Inflections::$titleize()
 **/
function titleize($string)
{
	return Inflections::titleize($string);
}

function markdown($string) {
#
# Initialize the parser and return the result of its transform method.
#
	# Setup static parser variable.
	static $parser;
	if (!isset($parser)) {
		$parser = new Markdown;
	}

	# Transform text using parser.
	return $parser->transform($string);
}

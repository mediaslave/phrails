<?php
/**
 * Text helpers
 *
 * @package helpers
 * @author Justin Palmer
 */
/**
 * @see String::escape();
 **/
function h($string)
{
	return String::escape($string);
}

/**
 * @see String::decode();
 **/
function decode()
{
	return String::decode($string);
}

/**
 * @see Inflections::pluralize
 **/
function pluralize($string)
{
	return Inflections::pluralize($string);
}

/**
 * @see Inflections::singularize
 **/
function singularize($string)
{
	return Inflections::singularize($string);
}
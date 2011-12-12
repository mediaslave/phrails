<?php
/**
 * Text helpers
 * @license https://raw.github.com/mediaslave/phrails/master/LICENSE The MIT License
 * @package helpers
 */
/**
 * Image submit button
 *
 * @return string
 **/
function image_submit_tag($name, $src, $alt, $options='')
{
	return new InputImage($name, $src, $alt, $options);
}
/**
 * Submit button
 *
 * @return string
 **/
function submit_tag($name, $value, $options='')
{
	return new InputSubmit($name, $value, $options);
}
/**
 * Form tag
 *
 * @return string
 **/
function form_tag($action, $options='')
{
	return new Form($action, $options) .
		   new InputHidden(FormBuilder::authenticity_token_key, FormBuilder::authenticityToken());
}
/**
 * Return the end form tag
 *
 * @return string
 **/
function end_form_tag()
{
	$form = new Form('');
	return $form->end();
}

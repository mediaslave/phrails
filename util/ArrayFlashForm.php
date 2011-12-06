<?php
/**
 * @license https://raw.github.com/mediaslave/phrails/master/LICENSE
 * @package util
 */
/**
 * class description
 *
 * @todo Create a way to set the FlashForm::class through the 'form.php' initializer.  FlashForm::setClass('form-errors); (Static $class)
 * @package template
 * @author Justin Palmer
 */
class ArrayFlashForm extends FlashForm
{
	/**
	 * Constructor
	 *
	 * @return Flash
	 * @author Justin Palmer
	 **/
	public function __construct($args, $title="Form Errors")
	{
		$args = func_get_args();
		//Get the errors
		$this->array = array_shift($args);
		//Get the class and the title
		$this->title = array_shift($args);
	}
} // END class String

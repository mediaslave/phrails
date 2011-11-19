<?php
/**
 * @license https://raw.github.com/mediaslave/phrails/master/LICENSE
 */
/**
 * class description
 *
 * @todo Create a way to set the FlashForm::class through the 'form.php' initializer.  FlashForm::setClass('form-errors); (Static $class)
 * @package template
 * @author Justin Palmer
 */
class FlashForm extends Flash
{
	protected $array = array();
	protected $class='flash-form-errors';
	protected $title='Form Errors';
	/**
	 * Label values that have been registered with the current view.
	 *
	 * @author Justin Palmer
	 * @var Hash
	 */
	public static $Labels;
	/**
	 * Constructor
	 *
	 * @return Flash
	 * @author Justin Palmer
	 **/
	public function __construct($args, $title="Form Errors")
	{
		$args = func_get_args();
		//Get the class and the title
		$this->title = array_pop($args);
		//instantiate an array for the errors.
		$array = array();

		foreach($args as $model){
			$array = array_merge($array, $model->errors()->export());
		}
		$this->array = $array;
	}
	/**
	 * Add a label
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public static function setLabel($key, $value)
	{
		if(!self::$Labels instanceof Hash)
			self::$Labels = new Hash();
		self::$Labels->set($key, $value);
	}
	/**
	 * @see Flash::display()
	 **/
	public function display()
	{
		$ret = '';
		$lis = '';
		$labels = self::$Labels;
		if(!empty($this->array)){
			$ret = '<div class="' . $this->class . '">
						<div>' . $this->title . '</div>
						<ul>';
			foreach($this->array as $key => $value){
				if(!is_array($value))
					$value = array($value);
				foreach($value as $message){
					$label = '';
					if($labels !== null && $labels->isKey($key)){
						$label = '<span class="form-element-description">' . $labels->get($key) . '</span>';
					}
					$ret .= '<li>' . sprintf($message, $label) . '</li>';
				}
			}
			$ret .= '
						</ul>
					</div>';
		}
		return $ret;
	}
} // END class String

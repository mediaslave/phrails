<?php
/**
 * The flash for the view.
 *
 * @package util
 * @author Justin Palmer
 **/
class FlashForm extends Flash
{
	private $array = array();
	private $class='flash-form-errors';
	private $title='Form Errors';
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
	public function __construct($args, $title="Form Errors", $class='flash-form-errors')
	{
		$args = func_get_args();
		
		$class = $args[sizeof($args) - 1];
		$this->class = $class;
		unset($args[sizeof($args) - 1]);
		
		$this->title = $args[sizeof($args) - 1];
		unset($args[sizeof($args) - 1]);
		$array = array();
		
		foreach($args as $model){
			//var_dump($model);
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
			//var_dump($this->array);
			$ret = '';
			$lis = '';
			$labels = self::$Labels;
			if(!empty($this->array)){
				$ret = '<div class="' . $this->class . '">
							<div>' . $this->title . '</div>
							<ul>';
				foreach($this->array as $key => $value){
					foreach($value as $message){
						$ret .= '<li>' . sprintf($message, '<span class="form-element-description">' . $labels->get($key) . '</span>') . '</li>';
					}
				}
				$ret .= '
							</ul>
						</div>';
			}
			return $ret;
	}
} // END class String
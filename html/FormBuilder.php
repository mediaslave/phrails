<?php
/**
 * Form builder holds all of the methods to build forms.
 * 
 * @author Justin Palmer
 * @package html
 */
class FormBuilder
{
	protected $model;
	static protected $class = 'form-error';
	static protected $required_hint = '( Required ) ';
	static protected $Registered;
	/**
	 * Constructor
	 *
	 * @param Model $model 
	 * @author Justin Palmer
	 * @return FormBuilder
	 */
	function __construct($model=null)
	{
		if(!($model instanceof Model) && $model !== null)
			throw new Exception("Parameter one in 'FormBuilder' should be a 'Model' Object or null.");
		$this->model = $model;
		//self::$class = $on_error_class;
		//self::$Registered = new Hash();
	}
	/**
	 * return a Label for a model property
	 *
	 * @see Label
	 * @return Label
	 * @author Justin Palmer
	 **/
	public function label($property, $text, $options='')
	{
		$name = $this->getElementName($property);
		$options = $this->checkForErrors($property, $options);
		FlashForm::setLabel($name, $text);
		$hint = '';
		//var_dump($this->model->schema()->required);
		if(in_array($property, $this->model->schema()->required))
			$hint = self::$required_hint;
		return new Span($hint, "class:" . self::$class) . new Label($text, $this->model->alias() . "_$property" . "_id", $options);
	}
	/**
	 * return a InputText for a model property
	 *
	 * @see InputText
	 * @return InputText
	 * @author Justin Palmer
	 **/
	public function text_field($property, $options='')
	{	
		$options = $this->checkForErrors($property, $options);
		return new InputText($this->getElementName($property), $this->model->$property, $options);
	}	
	/**
	 * return a InputHidden for a model property
	 *
	 * @see InputHidden
	 * @return InputHidden
	 * @author Justin Palmer
	 **/
	public function hidden_field($property, $options='')
	{
			$options = $this->checkForErrors($property, $options);
		return new InputHidden($this->getElementName($property), $this->model->$property, $options);
	}	
	/**
	 * return a InputFile for a model property
	 *
	 * @see InputFile
	 * @return InputFile
	 * @author Justin Palmer
	 **/
	public function file_field($property, $options='')
	{
			$options = $this->checkForErrors($property, $options);
		return new InputFile($this->getElementName($property), $this->model->$property, $options);
	}
	/**
	 * return a InputCheckbox for a model property
	 *
	 * @see InputCheckbox
	 * @return InputCheckbox
	 * @author Justin Palmer
	 **/
	public function check_box($property, $checked=false, $options='')
	{
			$options = $this->checkForErrors($property, $options);
		return new InputCheckbox($this->getElementName($property), $this->model->$property, $checked, $options);
	}
	/**
	 * return a InputRadio for a model property
	 *
	 * @see InputRadio
	 * @return InputRadio
	 * @author Justin Palmer
	 **/
	public function radio_button($property, $value, $checked=false, $options='')
	{
			$options = $this->checkForErrors($property, $options);
		$name = $this->getElementName($property);
		if($checked == false && $this->model->$property == $value)
			$checked = true;
		return new InputRadio($name, $value, $checked, $options);
	}
	/**
	 * return a Textarea for a model property
	 *
	 * @see Textarea
	 * @return Textarea
	 * @author Justin Palmer
	 **/
	public function text_area($property, $options='')
	{
			$options = $this->checkForErrors($property, $options);
		return new Textarea($this->getElementName($property), $this->model->$property, $options);
	}
	/**
	 * Register a new object with FormBuilder
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	static public function register($form_element)
	{
		if(self::$Registered === null)
			self::$Registered = new Hash;
		self::$Registered->set(Inflections::underscore($form_element), $form_element);
	}
	/**
	 * Generate a field
	 *
	 * @return string
	 * @author Justin Palmer
	 **/
	public function field($name, $property, $options='')
	{
		$options = $this->checkForErrors($property, $options);
		if(!self::$Registered->isKey($name))
			throw new Exception("The 'FormElement': $name is not a register field type. Register them in config/initializers/form.php");
		$object = self::$Registered->get($name);
		return new $object($this->getElementName($property), $this->model->$property, $options);
	}
	/**
	 * Check to see if the model has errors registered for this element.
	 *
	 * @return string
	 * @author Justin Palmer
	 **/
	private function checkForErrors($property, $options)
	{
		if($this->model->errors()->isKey($this->getElementName($property))){
			if($options != '')
				$options .= ',';
			$options .= 'class:' . self::$class;
		}
		return $options;
	}
	/**
	 * Get the elements name
	 *
	 * @return string
	 * @author Justin Palmer
	 **/
	private function getElementName($property)
	{
		$this->model->hasProperty($property);
		return $this->model->alias() . "[$property]";
	}
	
	/**
	 * Set the class of when an error happens.  This will be the css class for the form element.
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function setClass($value)
	{
		self::$class = $value;
	}
	/**
	 * Set the required hint.  This will show up before the label.
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function setRequiredHint($value)
	{
		self::$required_hint = $value;
	}
}
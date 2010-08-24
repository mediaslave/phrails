<?php
/**
 * Form builder holds all of the methods to build forms.
 * 
 * @todo should not have to register the elements.  Use __call.
 * 
 * @author Justin Palmer
 * @package html
 */
class FormBuilder
{
	/**
	 * Constant for the csrf key
	 * 
	 * @var constant
	 */
	const authenticity_token_key = 'phrails-form-authenticity-token';
	/**
	 * Authenticity error message;
	 * 
	 * @var string
	 */
	static private $authenticity_token_error_message = 'Phrails has detected a Cross Site Request Forgery, check it out wikipedia.  The form can not be submitted under these conditions.';
	/**
	 * The model that is currently being worked on.
	 * 
	 * @var Model
	 */
	protected $model;
	/**
	 * This can be set in the form initializer
	 * 
	 * @var string
	 */
	static protected $class = 'form-error';
	/**
	 * This can be set in the form initializer
	 * 
	 * @var string
	 */
	static protected $required_hint = '( Required ) ';
	/**
	 * These are special form builder objects that are loaded in the form initializer
	 * 
	 * @var Hash
	 */
	static protected $Registered;
	/**
	 * The Request object.
	 * 
	 * @var Request
	 */
	protected $request;
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
		$this->request = Registry::get('pr-request');
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
		if($this->model !== null && in_array($property, $this->model->schema()->required))
			$hint = self::$required_hint;
		$id = ($this->model !== null) ? $this->model->alias() . "_$property" : $property;
			
		return new Span($hint, "class:" . self::$class) . new Label($text, $id . "_id", $options);
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
		return new InputText($this->getElementName($property), $this->getValue($property), $options);
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
		return new InputHidden($this->getElementName($property), $this->getValue($property), $options);
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
		return new InputFile($this->getElementName($property), $this->getValue($property), $options);
	}
	/**
	 * return a InputCheckbox for a model property
	 *
	 * @see InputCheckbox
	 * @return InputCheckbox
	 * @author Justin Palmer
	 **/
	public function check_box($property, $options='', $checked_value='1', $unchecked_value='0')
	{
		$options = $this->checkForErrors($property, $options);
		$check = new InputCheckbox($this->getElementName($property), $options, $checked_value, $unchecked_value);
		$check->setChecked($this->getValue($property));
		return $check;
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
		if($checked == false && $this->getValue($property) == $value)
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
		return new Textarea($this->getElementName($property), $this->getValue($property), $options);
	}
	/**
	 * return a record set for a model property
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function result_set_select($property, ResultSet $set, $options='', $optionDisplay='name', $id='id')
	{
		$options = $this->checkForErrors($property, $options);
		return new ResultSetSelect($this->getElementName($property), $set, $this->getValue($property), $options, $optionDisplay, $id);
	}
	/**
	 * Boolean select with yes and no as the options
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function yes_no_select($property, $options='', $yes=1, $no=0)
	{
		$options = $this->checkForErrors($property, $options);
		return new Select($this->getElementName($property), $this->getValue($property), new Option('Yes', $yes), new Option('No', $no), $options);
	}
	/**
	 * Range select that will have numbers in the range that you provide
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function range_select($property, $start, $end, $options='')
	{
		$options = $this->checkForErrors($property, $options);
		$array = array();
		for($i=$start; $i <= $end; $i++)
			$array[] = new Option($i);
		return new ArraySelect($this->getElementName($property), $array, $this->getValue($property), $options);
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
		return new $object($this->getElementName($property),  $this->getValue($property), $options);
	}
	/**
	 * Check to see if the model has errors registered for this element.
	 *
	 * @return string
	 * @author Justin Palmer
	 **/
	private function checkForErrors($property, $options)
	{
		//if we are working with out a model then just return the options.
		if($this->model === null)
			return $options;
		//if we have a model then let's see if there are errors and if so set
		//the css class to the correct thing.
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
		//If there is no model then we will just pass it back how it came in.
		if($this->model === null)
			return $property;
		//Create the element name based off of the model.
		$this->model->hasProperty($property);
		return $this->model->alias() . "[$property]";
	}
	
	/**
	 * Get the value depending if the model is null or not
	 *
	 * @return string
	 * @author Justin Palmer
	 **/
	public function getValue($property)
	{
		//Get the value from the request object if we do not have a model
		//or get it from the model property if we have a model.
		return ($this->model === null) ? $this->request->params($property) 
		 							   : $this->model->$property;
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
	
	/**
	 * Set/Get the form authenticity token this prevents cross site request forgery.
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public static function authenticityToken()
	{
		if(isset($_SESSION[self::authenticity_token_key]))
			return $_SESSION[self::authenticity_token_key];
		$value = md5(mt_rand());
		$_SESSION[self::authenticity_token_key] = $value;
		return $value;
	}
	/**
	 * Is the authenticity token valid?
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public static function isValidAuthenticityToken()
	{
		$request = Registry::get('pr-request');
		return ($request->session(self::authenticity_token_key) == $request->post(self::authenticity_token_key));
	}
	/**
	 * Get the authenticity error message.
	 *
	 * @return string
	 * @author Justin Palmer
	 **/
	public static function getAuthenticityErrorMessage()
	{
		return self::$authenticity_token_error_message;
	}
	/**
	 * Set the authenticity error message
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public static function setAuthenticityErrorMessage($value)
	{
		self::$authenticity_token_error_message = $value;
	}
}
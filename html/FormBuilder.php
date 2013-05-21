<?php
/**
 * @license https://raw.github.com/mediaslave/phrails/master/LICENSE The MIT License
 * @package html
 */
/**
 * class description
 *
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
	static private $authenticity_token_error_message = 'Phrails has detected a Cross Site Request Forgery, check it out on wikipedia.  The form can not be submitted under these conditions.';
	/**
	 * The model that is currently being worked on.
	 *
	 * @var Model
	 */
	protected $model;
	protected $alt_element_name;
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
	 * Are elements required?  Usually for search, etc.. many of the elements are not required.
	 */
	protected $disable_required_hint = false;
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
	 * Does this element need to be array.
	 *
	 * @var boolean
	 */
	private $array_it = false;
	/**
	 * Does this array_it have a value?
	 *
	 * @var string
	 */
	protected $array_it_value = '';
	protected $array_it_second_value = null;
	protected $array_it_actual_value = false;
	const ARRAY_IT_BOOL_VALUE = false;
	const ARRAY_IT_ACTUAL_VALUE = true;

	protected $models;
	protected $modelsForeignKey;
	protected $modelsSecondForeignKey;
	/**
	 * Constructor
	 *
	 * @param Model $model
	 * @author Justin Palmer
	 * @return FormBuilder
	 */
	function __construct($model=null)
	{
		if ($model === null) {
			$model = new Hash();
		}

		if(!($model instanceof Model) && !($model instanceof Hash))
			throw new Exception("Parameter one in 'FormBuilder' should be a 'Model' Object or null.");
		$this->model = $model;
		$this->request = Registry::get('pr-request');
	}
	/**
	 * Do we show the required hint or not?
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function disableRequiredHints($bool=true)
	{
		$this->disable_required_hint = $bool;
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
		if((!($this->model instanceof Hash) && in_array($property, $this->model->schema()->required)) ||
			 OptionsParser::findAndDestroy('required', $options) == true)
			if(!$this->disable_required_hint) $hint = self::$required_hint;
		$id = FormElement::getId($this->getElementName($property));

		return new Span($hint, "class:" . self::$class) . new Label($text, $id, $options);
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
	 * return a InputText for a model property
	 *
	 * @see InputText
	 * @return InputText
	 * @author Justin Palmer
	 **/
	public function password_field($property, $options='')
	{
		$options = $this->checkForErrors($property, $options);
		return new InputPassword($this->getElementName($property), $this->getValue($property), $options);
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
	public function result_set_select($property, array $set, $options='', $optionDisplay='name', $id='id', $optgroup='optgroup')
	{
		$options = $this->checkForErrors($property, $options);
		return new ResultSetSelect($this->getElementName($property), $set, $this->getValue($property), $options, $optionDisplay, $id, $optgroup);
	}
	/**
	 * radio group
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
//	public function array_radio_group($property, array $set, $options='', $optionDisplay='name', $id='id')
	public function array_radio_group($property, array $set, $options='')
	{
		$options = $this->checkForErrors($property, $options);
		return new InputRadioGroup($this->getElementName($property), $set, $this->getValue($property), $options);
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
	 * Create a select
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function select($property, $optionTags, $options='')
	{
		$optionTags = func_get_args();
		$property = array_shift($optionTags);
		$options = array_pop($optionTags);
		$options = $this->checkForErrors($property, $options);
		return new Select($this->getElementName($property), $this->getValue($property), $optionTags, $options);
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
	 * @deprecated
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
	 * @deprecated
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
	 * Load dynamic form fields
	 *
	 * @return string
	 * @author Justin Palmer
	 **/
	public function __call($name, $args)
	{
		$klass = Inflections::classify($name);
		$property = array_shift($args);
		$select = array_pop(explode('_', $name));
		if($select == 'select'){
			$array = array_shift($args);
			if(is_array($array)){
				//this is a type of array select
				return new $klass($this->getElementName($property), 
							  $array, 
							  $this->getValue($property), 
							  $this->checkForErrors($property,array_shift($args)));
			}else{
				//this is a type of array select
				return new $klass($this->getElementName($property), 
							  $this->getValue($property), $array);
			}
			
		}
		$options = array_shift($args);
		$options = $this->checkForErrors($property, $options);
		return new $klass($this->getElementName($property), $this->getValue($property), $options);
	}
	/**
	 * Check to see if the model has errors registered for this element.
	 *
	 * @return string
	 * @author Justin Palmer
	 **/
	protected function checkForErrors($property, $options)
	{
		//if we are working with out a model then just return the options.
		if($this->model instanceof Hash)
			return $options;
		$array_it = $this->array_it;
		//if we have a model then let's see if there are errors and if so set
		//the css class to the correct thing.
		if($this->model->errors()->isKey($this->getElementName($property))){
			if($options != '' && !is_array($options)){
				$options = OptionsParser::toArray($options);
			}
			$options['class'] = self::$class;
		}
		$this->array_it = $array_it;
		return $options;
	}
	/**
	 * Get the elements name
	 *
	 * @return string
	 * @author Justin Palmer
	 **/
	protected function getElementName($property)
	{
		//If there is no model then we will just pass it back how it came in.
		if($this->model instanceof Hash)
			return $property;
		//Create the element name based off of the model.
		$this->model->hasProperty($property);
		$modelName = $this->model->alias();
		if($this->alternateElementName() !== null)
			$modelName = $this->alternateElementName();
		$name = $modelName . "[$property]";

		if($this->array_it){
			if($this->array_it_value != ''){
				$name .= "[$this->array_it_value]";
				if($this->array_it_second_value !== null){
					$name .= "[$this->array_it_second_value]";
				}
			}else{
				$name .= '[]';
			}
		}
		return $name;
	}

	/**
	 * Should this element be an array of elements
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function arrayIt($value = null, $getActualValue=self::ARRAY_IT_BOOL_VALUE, $secondValue=null)
	{
		$this->array_it = true;
		$this->array_it_actual_value = $getActualValue;
		if($value !== null)
			$this->array_it_value = $value;
		if($secondValue !== null)
			$this->array_it_second_value = $secondValue;
		return $this;
	}

	public function modelsForCheckbox(array $models, $foreignKey=null, $secondForeignKey=null) {
		$this->models = $models;
		$this->modelsForeignKey = $foreignKey;
		$this->modelsSecondForeignKey = $secondForeignKey;
		if($foreignKey === null){
				$this->modelsForeignKey = Inflections::foreignKey($this->model->table_name());
		}
		return $this;
	}

	/**
	 * Get the value depending if the model is null or not
	 *
	 * @return string
	 * @author Justin Palmer
	 **/
	public function getValue($property)
	{
		//Checkboxes are there models to set the value.
		if ($this->models !== null) {
			$foreignKey = $this->modelsForeignKey;
			$secondForeignKey = $this->modelsSecondForeignKey;
			//new \Dbug($foreignKey, '', false, __FILE__, __LINE__);
			//new \Dbug($secondForeignKey, '', false, __FILE__, __LINE__);
			foreach ($this->models as $m) {
				//new \Dbug($m->$foreignKey, '', false, __FILE__, __LINE__);	
				//new \Dbug($this->array_it_value, '', false, __FILE__, __LINE__);
				//new \Dbug($this->modelsSecondForeignKey, '', false, __FILE__, __LINE__);
				//new \Dbug(($m->$foreignKey == $this->array_it_value), '', false, __FILE__, __LINE__);
				if ($m->$foreignKey == $this->array_it_value && $secondForeignKey === null) {
					//die('what?');
					return ($this->array_it_actual_value) 
									 ? $m->$property
									 : true;
				}elseif($m->$foreignKey == $this->array_it_value && $m->$secondForeignKey == $this->array_it_second_value){
					return ($this->array_it_actual_value) 
									 ? $m->$property
									 : true;
				}
			}
		}
		//Get the value from the request object if we do not have a model
		//or get it from the model property if we have a model.
		$value = $this->request->params($property);
		if($value !== ''){
			return $value;
		}

		if ($this->model instanceof Hash) {
			return $this->model->get($property);
		}

		if ($this->model instanceof Model) {
			return $this->model->$property;
		}
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
		$value = md5(time());
		$_SESSION[self::authenticity_token_key] = $value;
		return $value;
	}
	/**
	 * Is the authenticity token valid?
	 *
	 * @todo do we need to check authenticity token to GET, PUT AND DELETE?
	 * @return void
	 * @author Justin Palmer
	 **/
	public static function isValidAuthenticityToken()
	{
		$request = new Request();
		if(!$request->has('post') && !$request->has('get') && !$request->has('delete'))
			return true;
		return ($request->session(self::authenticity_token_key) ==
				$request->post(self::authenticity_token_key) ||
				$request->session(self::authenticity_token_key) ==
				$request->get(self::authenticity_token_key));
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
	/**
	 * Get the model that is in the FormBuilder
	 *
	 * @return Model
	 * @author Justin Palmer
	 **/
	public function model()
	{
		return $this->model;
	}
	/**
	 *
	 * Set an alternate element name;
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function alternateElementName($value=null)
	{
		if($value !== null)
			$this->alt_element_name = $value;
		return $this->alt_element_name;
	}

	/**
	 *
	 * Get the request object to set form element value
	 *
	 * @return Request
	 * @author Justin Palmer
	 **/
	public function request()
	{
		return $this->request;
	}
}

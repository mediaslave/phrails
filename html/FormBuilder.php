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
	protected $class = 'form-error';
	/**
	 * Constructor
	 *
	 * @param Model $model 
	 * @author Justin Palmer
	 * @return FormBuilder
	 */
	function __construct(Model $model, $on_error_class='form-error')
	{
		$this->model = $model;
		$this->class = $on_error_class;
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
		return new Label($text, $this->model->alias() . "_$property" . "_id", $options);
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
			$options .= 'class:' . $this->class;
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
}
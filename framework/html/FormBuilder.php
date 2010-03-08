<?php
/**
 * Form builder holds all of the methods to build forms.
 * 
 * @author Justin Palmer
 * @package html
 */
class FormBuilder
{
	private $model;
	/**
	 * Constructor
	 *
	 * @param Model $model 
	 * @author Justin Palmer
	 * @return FormBuilder
	 */
	function __construct(Model $model)
	{
		$this->model = $model;
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
		return new Textarea($this->getElementName($property), $this->model->$property, $options);
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
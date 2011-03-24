<?php
/**
 * Creates a 'checkbox'.
 * 
 * @author Justin Palmer
 * @package html
 */
class InputRadio extends InputChecked
{	
	protected $options = 'type:radio';
	public $id;
	protected $label = '';
	/**
	 * Constructor
	 *
	 * @param string $display 
	 * @param string or array $options 
	 * @author Justin Palmer
	 */
	function __construct($name, $value, $checked=false, $label='', $options=null)
	{
		$this->label = $label;
		$this->setChecked($checked);
		$this->id = $this->getId(strtolower(str_replace(' ', '', $value )) . '_' . $name);
		$this->options .= ',id:' . $this->id;
		parent::__construct($name, $value, $options);
	}
	/**
	 * Should we check it?
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function setChecked($value)
	{
		if($value){
			$this->checked = ' checked';
		}
	}
	/**
	 * To string
	 *
	 * @return string
	 * @author Justin Palmer
	 **/
	public function __toString()
	{
		$s = parent::__toString();
		if($this->label !== ''){
			$s .= '&nbsp;' . new Label($this->label, $this->id) . '&nbsp;';
		}
		return $s;
	}
}
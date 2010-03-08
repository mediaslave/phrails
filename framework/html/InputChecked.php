<?php
/**
 * Creates a 'checkbox'.
 * 
 * @author Justin Palmer
 * @package html
 */
class InputChecked extends Input
{		
	protected $checked = '';
	
	/**
	 * We have the InputHidden print before the checkbox so that if it is not checked
	 * we will still have a post value
	 *
	 * @var string
	 */
	private $Hidden;
	/**
	 * Constructor
	 *
	 * @param string $display 
	 * @param string or array $options 
	 * @author Justin Palmer
	 */
	function __construct($name, $value, $checked=false, $options=null)
	{
		if($checked)
			$this->checked = 'checked';
		parent::__construct($name, $value, $options);
	}
	/**
	 * undocumented function
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function start()
	{
		return '<input' . $this->options . ' ' . $this->checked . '/>';
	}
}
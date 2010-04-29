<?php
/**
 * Creates a 'label'.
 * 
 * @author Justin Palmer
 * @package html
 */
class ResultSetSelect extends Select
{
	protected $optionDisplay = 'name';
	protected $id = 'id';
	/**
	 * Constructor
	 *
	 * @param string $name
	 * @param string $selectedValue 
	 * @param Option $optionsTags 
	 * @param string or array $options 
	 * @author Justin Palmer
	 */
	function __construct($name, ResultSet $resultSet, $selectedValue=null, $options=null, $optionDisplay='name', $id='id')
	{
		$this->optionDisplay = $optionDisplay;
		$this->id = $id;
		foreach($resultSet as $record){
			//var_dump($record);
			$optionDisplay = $this->optionDisplay;
			$id = $this->id;
			$select = false;
			if($record->$id == $selectedValue)
				$select = true;
			$this->display .= new Option($record->$optionDisplay, $record->$id, true) . "\n";
		}
		parent::__construct($name, $selectedValue, null, $options);
	}
}
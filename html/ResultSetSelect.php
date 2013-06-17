<?php
/**
 * @license https://raw.github.com/mediaslave/phrails/master/LICENSE The MIT License
 * @package html
 */
/**
 * class description
 *
 * @package html
 * @deprecated
 */
class ResultSetSelect extends Select
{
	protected $optionDisplay = 'name';
	protected $id = 'id';
	protected $optgroup = 'optgroup';
	protected $Group = null;
	/**
	 * Constructor
	 *
	 * @param string $name
	 * @param string $selectedValue
	 * @param Option $optionsTags
	 * @param string or array $options
	 * @author Justin Palmer
	 */
	function __construct($name, array $resultSet, $selectedValue=null, $options=null, $optionDisplay='name', $id='id', $optgroup='optgroup')
	{
		$this->Group = new HashArray();
		$this->optionDisplay = $optionDisplay;
		$this->id = $id;
		$this->optgroup = $optgroup;
		$options = $this->preparePrompt($options);
		foreach($resultSet as $record){
			if(is_array($record)){
				$record = (object)$record;
			}
			$id = $this->id;
			$select = false;
			if((string)$record->$id === (string)$selectedValue)
				$select = true;
			$this->fillHashOrDisplay($record, $select);
		}
		$this->createOptGroup();
		//new \Dbug($options, '', false, __FILE__, __LINE__);
		parent::__construct($name, $selectedValue, null, $options);
	}

	/**
	 * Fill the Group HashArray or the display depending on if the
	 * result set has an optgroup property
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	private function fillHashOrDisplay($record, $select)
	{
		$optionDisplay = $this->optionDisplay;
		$id = $this->id;
		$optgroup = $this->optgroup;
		$option = new Option($record->$optionDisplay, $record->$id, $select);
		if(!isset($record->$optgroup) || $record->$optgroup === null){
			$this->display .= $option;
		}else{
			$this->Group->set($record->$optgroup, $option);
		}
	}

	/**
	 * Create the optgroup if we need to
	 *
	 * @return false or void
	 * @author Justin Palmer
	 **/
	private function createOptGroup()
	{
		if($this->Group->isEmpty())
			return;
		foreach($this->Group->export() as $key => $value){
			$this->display .= new OptGroup($key, $value);
		}
	}
}

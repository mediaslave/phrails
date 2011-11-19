<?php
/**
 * @license https://raw.github.com/mediaslave/phrails/master/LICENSE
 * @package html
 * @subpackage rules
 */
/**
 * class description
 *
 * @package html
 * @subpackage rules
 * @author Justin Palmer
 */
class RuleJudge
{
	private $Props;
	private $Schema;
	private $errors = array();

	function __construct(Hash $Props, Schema $Schema)
	{
		$this->Props = $Props;
		$this->Schema = $Schema;
	}

	/**
	 * undocumented function
	 *
	 * @param Model $Model - some rules need this reference for callbacks
	 * @return Hash
	 * @author Justin Palmer
	 **/
	public function judge(Model $Model)
	{
		$last_prop_name = '';
		$Hash = new Hash();

		foreach($this->Props->export() as $property => $value){
			if(empty($this->errors))
				$last_prop_name = $property;
			if(!empty($this->errors) && $last_prop_name != $property){
				$Hash->set($Model->alias() . '[' . $last_prop_name . ']', $this->errors);
				$last_prop_name = $property;
				$this->errors = array();
			}

      if($Model->validateNulls == false && $value === null) {
        continue;
      }

			//if the value is null and it is not a required property then lets just
			//continue onto the next property, nothing to do here.
			if((($value instanceof Expression) ||
          $value === null ||
          $value == '') &&
				!in_array($property, $this->Schema->required)){
				continue;
			}

			//Run the rules for the property if there are any.
			$this->runRulesForProperty($property, $value, $Model);

		}
		if(!empty($this->errors)){
			$Hash->set($Model->alias() . '[' . $property . ']', $this->errors);
		}
		return $Hash;
	}

	/**
	 * Run the rule for a property
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function runRulesForProperty($property, $value, Model $Model)
	{
		$rules = $this->Schema->rules();
		//If there are rules for the property let's go through them.
		if($rules->isKey($property)){
			//print $property . ':' . $value . '<br/><br/>';
			//Get the rules.
			$prop_rules = $rules->get($property);
			//var_dump($prop_rules);
			foreach($prop_rules as $rule){
				$rule->value = $value;
				$rule->property = $property;
				$rule->model = $Model;
				if(!$rule->run()){
					//Add the error message to some sort of array. So that we can add it to a flash.
					$this->errors[] = $rule->message;
				}
			}
		}
	}
}

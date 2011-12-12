<?php
/**
 * * @license https://raw.github.com/mediaslave/phrails/master/LICENSE The MIT License
 * @package template
 */
/**
 * class description
 *
 * @package template
 * @author Justin Palmer
 */
class Request extends Hash
{
	private $do_unset = false;

	private $types = array('post'		=>'_POST',
				   		   'env'		=>'_ENV',
						   'server'		=>'_SERVER',
						   'request'	=>'_REQUEST',
						   'cookie'		=>'_COOKIE',
						   'session'	=>'_SESSION',
						   'files'		=>'_FILES',
						   'get'		=>'_GET',
						   'put'		=>'_PUT',
						   'delete'		=>'_DELETE'
						  );
	/**
	 * Constructor
	 *
	 * @param array $array
	 * @return Hash
	 * @author Justin Palmer
	 */
	public function __construct()
	{
		parent::__construct(array_merge($_ENV, $_SERVER, $_REQUEST, $_COOKIE, $_SESSION, $_FILES, $_GET, $_POST));
	}
	/**
	 * Get a value for the given key
	 *
	 * @param string $key
	 * @return mixed
	 * @author Justin Palmer
	 */
	public function params($key, $value=null)
	{
		if($value !== null){
			$this->set($key, $value);
		}else{
			return $this->stripSlashes(parent::get($key));
		}
	}

	/**
	 * Get or set a GET ITEM.
	 *
	 * We can not use __call here because we are overloading the parent::get
	 *
	 * @return mixed
	 * @author Justin Palmer
	 **/
	public function get($key=null, $value=null)
	{
		return $this->perform('_GET', $key, $value);
	}

	/**
	 * Get/set a var of one of the server globals
	 *
	 * @return mixed
	 * @author Justin Palmer
	 **/
	public function __call($method, $args)
	{
		if(!array_key_exists($method, $this->types))
			throw new Exception('Invalid request type: Request::' . $method . '()');
		$key = array_shift($args);
		$value = array_shift($args);
		return $this->perform($this->types[$method], $key, $value);
	}

	/**
	 * Prepare for an unset.
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function del()
	{
		$this->do_unset = true;
		return $this;
	}
	/**
	 * Does the app have a GLOBALS specified?
	 *
	 * @return boolean
	 * @author Justin Palmer
	 **/
	public function has($type)
	{
		if(array_key_exists($type, $this->types))
			$type = $this->types[$type];
		return (isset($GLOBALS[$type]) && !empty($GLOBALS[$type]));
	}

	private function perform($type, $key, $value)
	{
		if($this->do_unset){
			$this->do_unset = false;
			if(isset($GLOBALS[$type][$key])){
				unset($GLOBALS[$type][$key]);
			}
		}
		if($key === null && $value === null)
			return $GLOBALS[$type];
		//If we are setting a global then let's set it and return
		if($value !== null){
			$GLOBALS[$type][$key] = $value;
			return;
		}
		//get the var specified, if it is not set then return null
		$var = (isset($GLOBALS[$type][$key])) ? $GLOBALS[$type][$key] : null;
		return $this->stripSlashes($var);
	}

	private function stripSlashes($var){
		if(is_array($var)){
			$ret = array();
			foreach($var as $key => $value){
				if(is_array($value)){
					$ret[$key] = $this->stripSlashes($value);
					continue;
				}
				$ret[$key] = (is_object($value)) ? $value : trim(stripslashes($value));
			}
		}else{
			$ret = (is_object($var)) ? $var : trim(stripslashes($var));
		}
		return $ret;
	}
}

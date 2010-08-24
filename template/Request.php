<?php
/**
 * A Hash that holds all of the global vars, POST, GET, FILES, SESSION, COOKIE, REQUEST, SERVER, ENV
 * (in the above order when using get())
 * 
 * @package util
 */
class Request extends Hash
{
	/**
	 * Constructor
	 *
	 * @param array $array 
	 * @return Hash
	 * @author Justin Palmer
	 */
	public function __construct()
	{	
		parent::__construct($_ENV += $_SERVER += $_REQUEST += $_COOKIE += $_SESSION += $_FILES += $_GET += $_POST);
		//parent::__construct(array_merge($_ENV, $_SERVER, $_REQUEST, $_COOKIE, $_SESSION, $_FILES, $_GET, $_POST));							
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
	 * Get or set a POST ITEM
	 *
	 * @return mixed
	 * @author Justin Palmer
	 **/
	public function post($key, $value=null)
	{
		return $this->perform('_POST', $key, $value);
	}
	
	/**
	 * Get or set a GET ITEM
	 *
	 * @return mixed
	 * @author Justin Palmer
	 **/
	public function get($key, $value=null)
	{
		return $this->perform('_GET', $key, $value);
	}
	
	/**
	 * Get or set a FILES ITEM
	 *
	 * @return mixed
	 * @author Justin Palmer
	 **/
	public function files($key, $value=null)
	{
		return $this->perform('_FILES', $key, $value);
	}
	
	/**
	 * Get or set a SESSION ITEM
	 *
	 * @return mixed
	 * @author Justin Palmer
	 **/
	public function session($key, $value=null)
	{
		return $this->perform('_SESSION', $key, $value);
	}
	
	/**
	 * Get or set a COOKIE ITEM
	 *
	 * @return mixed
	 * @author Justin Palmer
	 **/
	public function cookie($key, $value=null)
	{
		return $this->perform('_COOKIE', $key, $value);
	}
	
	/**
	 * Get or set a REQUEST ITEM
	 *
	 * @return mixed
	 * @author Justin Palmer
	 **/
	public function request($key, $value=null)
	{
		return $this->perform('_REQUEST', $key, $value);
	}
	
	/**
	 * Get or set a SERVER ITEM
	 *
	 * @return mixed
	 * @author Justin Palmer
	 **/
	public function server($key, $value=null)
	{
		return $this->perform('_SERVER', $key, $value);
	}
	
	/**
	 * Get or set a ENV ITEM
	 *
	 * @return mixed
	 * @author Justin Palmer
	 **/
	public function env($key, $value=null)
	{
		return $this->perform('_ENV', $key, $value);
	}
	
	private function perform($type, $key, $value)
	{
		//If we are setting a global then let's set it and return
		if($value !== null){
			$GLOBALS[$type][$key] = $value;
			return;
		}
		//If there is no type then we will get or set from the combined hash.
		//Otherwise get it and return it.
		$var = (isset($GLOBALS[$type][$key])) ? $GLOBALS[$type][$key] : null;
		return $this->stripSlashes($var);
	}
	
	private function stripSlashes($var){
		if(is_array($var)){
			$ret = array();
			foreach($var as $key => $value){
				$ret[$key] = trim(stripslashes($value));
			}
		}else{
			$ret = trim(stripslashes($var));
		}
		return $ret;
	}
}
<?php
/**
 * @license https://raw.github.com/mediaslave/phrails/master/LICENSE
 */
/**
 * class description
 *
 * @package template
 * @author Justin Palmer
 */
class Session{

	//In seconds
	public static $timeout = '1800';

	//The key
	public static $key = 'phrails_session_key';

	/**
	 * has the session expired
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public static function isValid()
	{
		if(self::$timeout === false)
			return true;
		$boolean = false;
		$Request = new Request();
		if($Request->session(self::$key)){
			$life = time() - $Request->session(self::$key);
			if($life <= self::$timeout){
				$boolean = true;
				Session::start();
			}
		}
		return $boolean;
	}

	/**
	 * Start the session
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public static function start()
	{
		$Request = new Request();
		$Request->session(self::$key, time());
	}

}

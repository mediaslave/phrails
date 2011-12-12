<?php
/**
 * @license https://raw.github.com/mediaslave/phrails/master/LICENSE The MIT License
 * @package mail
 */
/**
 * @see lib/class.phpmailer.php
 */
require_once('lib/class.phpmailer.php');
/**
 * class description
 *
 * @package mail
 * @author Justin Palmer
 */
class Mailer extends PHPMailer
{
	private static $settings = array();

	private static $dev_email = null;

	public $pr_layout = 'application';

	private $valid_settings = array('deliveryMethod', 'host', 'port', 'username', 'password', 'secure', 'from', 'debug');

	function __construct(array $vars=array(), $exceptions=false)
	{
		foreach($vars as $key => $value){
			$this->$key = $value;
		}
		parent::__construct($exceptions);
		$this->settings();
	}

	/**
	 * Send an email
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function send($method)
	{
		if(!in_array($method, get_class_methods($this)))
			throw new Exception($method . ' is not a valid method in ' . get_class($this));
		$this->$method();
		$t = new MailerTemplate($this, $method);
		$html = $t->display();

		$this->html($html);
		return parent::Send();
	}

	/**
	 * Set the subject
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function subject($subject)
	{
		$this->Subject = $subject;
	}

	/**
	 * Set the text only body
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function text($text)
	{
		$this->AltBody = $text;
	}

	/**
	 * Set the html body
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function html($html)
	{
		return parent::MsgHTML($html);
	}

	/**
	 * Add an attachment
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function attach($path)
	{
		return parent::AddAttachment($path);
	}

	/**
	 * Set the to address(es)
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function to($address, $name=null)
	{
		return $this->setRecipients('AddAddress', $address, $name);
	}

	/**
	 *
	 * Set the reply to email address.
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function replyTo($address, $name='', $clearReplyTo=false)
	{
		if($clearReplyTo)
			$this->ClearReplyTos();
		return $this->AddReplyTo( $address, $name);
	}

	/**
	 * cc an address
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function cc($address, $name='')
	{
		return $this->setRecipients('AddCC', $address, $name);
	}

	/**
	 * bcc an address
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function bcc($address, $name='')
	{
		return $this->setRecipients('AddBCC', $address, $name);
	}

	/**
	 * set the debug flag
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function debug($boolean)
	{
		$this->SMTPDebug = $boolean;
	}

	/**
	 * Set up the server settings
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public static function serverSettings(array $array)
	{
		self::$settings = $array;
	}

	/**
	 * Set the email if we want all emails sent out to only go
	 * to a specific email address and override what the app tells
	 * it to go to.  This is good for development environments.
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public static function developmentEmail($email)
	{
		self::$dev_email = $email;
	}

	/**
	 * Set the cc, bcc and to
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	private function setRecipients($method, $address, $name='')
	{
		if(is_array($address)){
			foreach(array_values($address) as $to){
				if(!is_array($to))
					throw new Exception('Mailer::to, Mailer::cc, Mailer::bcc expects that $address is an array of arrays. Example: array(array("email_address", "name")), name is optional.');
				$email = (self::$dev_email !== null) ? self::$dev_email : $to[0];
				$name = '';
				if(sizeof($to) == 2)
					$name = $to[1];
				parent::$method($email, $name);
			}
		}else{
			$email = (self::$dev_email !== null) ? self::$dev_email : $address;
			parent::$method($email, $name);
		}
	}
	/**
	 * Prepare the initial settings.
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	private function settings()
	{
		foreach(self::$settings as $key => $val){
			$this->$key($val);
		}
	}

	/**
	 * Set the host
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	private function host($host)
	{
		$this->Host = $host;
	}

	/**
	 * Set the port
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	private function port($port)
	{
		$this->Port = $port;
	}

	/**
	 * Set the username
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	private function username($username)
	{
		$this->SMTPAuth = true;
		$this->Username = $username;
	}

	/**
	 * Set the password
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	private function password($password)
	{
		$this->Password = $password;
	}

	/**
	 * Authentication tls or ssl
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	private function secure($val)
	{
		$verify = array('ssl', 'tls');
		if(!in_array($val, $verify))
			throw new Exception('Mailer::serverSettings() secure can be "ssl" or "tls"');
		$this->SMTPSecure = $val;
	}

	/**
	 * Sets the AddReplyTo and SetFrom
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	private function from($val)
	{
		$address = $val;
		$name = '';
		if(is_array($val)){
			if(sizeof($val) != 2){
				throw new Exception('Mailer::serverSettings() from expects an array size of two: array($address, $name)');
			}
			$address = $val[0];
			$name = $val[1];
		}
		$this->AddReplyTo($address, $name);
		$this->SetFrom($address, $name);
	}

	/**
	 * Delivery method
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	private function deliveryMethod($method)
	{
		$valid = array('smtp', 'mail', 'sendmail');
		if(!in_array($method, $valid))
			throw new Exception('Mailer::serverSettings() deliveryMethod can be only be "smtp" or "mail".');
		$this->Mailer = $method;
		if($method == 'sendmail')
			parent::IsSendmail();

	}
}

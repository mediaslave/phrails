<?
/**
 * @license https://raw.github.com/mediaslave/phrails/master/LICENSE The MIT License
 * @package util
 */
/**
 * class description
 *
 * @package util
 * @author Justin Palmer
 */
class Encryption
{
	private $resource;
	private $iv;
	private $key=null;

	function __construct($algorithm=MCRYPT_RIJNDAEL_256, $algorithm_directory='', $mode='ofb', $mode_directory='')
	{
		$this->resource = mcrypt_module_open($algorithm, $algorithm_directory, $mode, $mode_directory);
		$this->iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($this->resource), MCRYPT_DEV_RANDOM);
	}

	/**
	 * Intialize the mcrypt generic
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function init($key=null)
	{
		if($key === null && $this->key === null)
			throw new Exception('You have to specify a key at least once.');
		if($key !== null)
			$this->key = substr(md5($key), 0, mcrypt_enc_get_key_size($this->resource));
		mcrypt_generic_init($this->resource, $this->key, $this->iv);
	}

	/**
	 * encrypt the data
	 *
	 * @return string
	 * @author Justin Palmer
	 **/
	public function encrypt($data, $close=false)
	{
		$data = mcrypt_generic($this->resource, $data);
		mcrypt_generic_deinit($this->resource);
		if($close)
			$this->close();
		return $data;
	}

	/**
	 * decrypt the data
	 *
	 * @return string
	 * @author Justin Palmer
	 **/
	public function decrypt($data, $close=false)
	{
		$data = mdecrypt_generic($this->resource, $data);
		mcrypt_generic_deinit($this->resource);
		if($close)
			$this->close();
		return trim($data);
	}

	/**
	 * close the encryption resource
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function close()
	{
		mcrypt_module_close($this->resource);
	}
}

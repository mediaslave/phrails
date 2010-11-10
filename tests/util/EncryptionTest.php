<?php
/**
* 
*/
class EncryptionTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @test
	 **/
	public function Encrypt()
	{
		$key = 'lsdkjflksdjfdsklfj';
		$string = 'Encryption is cool!';
		$e = new Encryption();
		$e->init($key);
		$encrypted = $e->encrypt($string);
		$e->init();
		$decrypt = $e->decrypt($encrypted, true);
		$this->assertEquals($string, $decrypt);
	}
}

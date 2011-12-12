<?php
/**
 * @license https://raw.github.com/mediaslave/phrails/master/LICENSE The MIT License
 * @package template
 */
/**
 * class description
 *
 * @todo refactor
 * @package template
 */
class MailerTemplate extends TemplateCache
{
	private $method=null;

	protected $layouts_path = 'mailers/layouts';

	function __construct($controller, $method) {
		parent::__construct($controller);
		$this->method = $method;
	}
	/**
	 * Sets the file path and route array.
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	protected function prepare()
	{
		$this->View = new HtmlView;
		$explode = explode('\Mailers\\', get_class($this->Controller));
		$mailer = preg_replace('%Mailer$%', '', array_pop($explode));

		$path = 'mailers/' . strtolower(preg_replace('%\\\\-%', '/', preg_replace('/([^\s])([A-Z])/', '\1-\2', $mailer)));

		$this->view_path = $path . '/' . $this->method . '.html.php';
	}
}

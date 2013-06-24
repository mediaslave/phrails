<?php
/**
 * @license https://raw.github.com/mediaslave/phrails/master/LICENSE The MIT License
 * @package template
 */
/**
 * class description
 *
 * @package template
 */
class XmlView extends View
{
	public $should_fallback_to_html = false;
	public $extension = 'xml';
	protected $mime_type = 'text/xml';
	/**
	 * process the view
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function process($content){
		return $content;
	}
}
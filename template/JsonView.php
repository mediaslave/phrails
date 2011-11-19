<?php
/**
 * @license https://raw.github.com/mediaslave/phrails/master/LICENSE
 * @package template
 */
/**
 * class description
 *
 * @package template
 * @author Justin Palmer
 */
class JsonView extends View
{
	public $can_have_layout=false;
	public $should_fallback_to_html = false;
	public $extension = 'json';

	/**
	 * process the view
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function process($content){
		return Json::encode($content);
	}
}

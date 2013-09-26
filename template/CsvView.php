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
class CsvView extends View
{
	public $can_have_layout=false;
	public $should_fallback_to_html = false;
	public $extension = 'csv';

	/**
	 * process the view
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function process($content){
		$csv = new Csv;
		header('content-type: application/octet-stream');
		header('content-disposition: attachment; filename=download.csv');
		return $csv->encode($content);
	}
}

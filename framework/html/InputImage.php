<?php
/**
 * Creates a 'label'.
 * 
 * @author Justin Palmer
 * @package html
 */
class InputImage extends Input
{
	protected $options = 'type:image';
	/**
	 * Constructor
	 *
	 * @param string $display 
	 * @param string or array $options 
	 * @author Justin Palmer
	 */
	function __construct($src, $alt, $options=null)
	{
		$this->options .= "src:$src,alt:$alt";
		parent::__construct('', '', $options);
	}
}
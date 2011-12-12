<?php
/**
 * * @license https://raw.github.com/mediaslave/phrails/master/LICENSE The MIT License
 * @package html
 */
/**
 * class description
 *
 * @package html
 * @author Justin Palmer
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
	function __construct($name, $src, $alt, $options=null)
	{
		$this->options .= ",src:$src,alt:$alt";
		parent::__construct($name, '', $options);
	}
}

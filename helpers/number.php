<?php
/**
 * Text helpers
 * @license https://raw.github.com/mediaslave/phrails/master/LICENSE The MIT License
 * @package helpers
 * @author Justin Palmer
 */
/**
 * Formats a number grouped by the thousands.
 *
 * @return void
 * @author Justin Palmer
 **/
function number_with_delimiter($num, $delimiter=',', $seperator='.')
{
	$num = explode('.', $num);
	$ret = array_shift($num);
	$ret = preg_replace('/(\d)(?=(\d\d\d)+(?!\d))/', "\\1$delimiter", $ret);
	return (!empty($num)) ? $ret = $ret . $seperator . implode($seperator, $num)
					        : $ret = $ret;
}

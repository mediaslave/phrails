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
class Csv
{

	const HAS_HEADER_ROW_TRUE = true;
	const HAS_HEADER_ROW_FALSE = false;
	const IS_FILE_PATH_TRUE = true;
	const IS_FILE_PATH_FALSE = false;

	private $encoded_string = '';
	/**
	 *
	 * Decode a csv array
	 *
	 * Will return an array with Hash's in it if the string has a header row.
	 * if not, then just an array of array's
	 *
	 * @todo implement
	 * @return array
	 * @author Justin Palmer
	 **/
	public function decode($string, $has_header_row = true, $is_file_path = false){
		return ($is_file_path) ? $this->decodeFromFile($string, $has_header_row)
							   : $this->decodeFromString($string, $has_header_row);
	}

	/**
	 * Decode from a string
	 * 
	 * @return array
	 */
	private function decodeFromString($string, $has_header_row){ return array(); }

	/**
	 * Decode from a file
	 * 
	 * @return array
	 */
	private function decodeFromFile($string, $has_header_row){ return array(); }

	/**
	 * Convert the headers into keys and return
	 * 
	 * @return array
	 */
	public function convertHeaders(array $header){	}

	/**
	 * Encode the data
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function encode(array $array, $has_header_row = true){
		return ($has_header_row) ? $this->encodeWithHeaderRow($array)
								 : $this->encodeWithoutHeaderRow($array);
	}

	/**
	 * Encode with a header row
	 * 
	 * @return string
	 */
	private function encodeWithHeaderRow(array $array){
		$models = array
	}

	/**
	 * Encode without a header row
	 * 
	 * @return string
	 */
	private function encodeWithoutHeaderRow(array $array){
		
	}
}

$array = Csv::decode('/path/to/file', Csv::HAS_HEADER_ROW_TRUE);

$to_csv_with_header = array(
	array(
			array('street'=>'US Street',
				  'city'=>'US City',
				  'state'=>'US State'),
			$uslivingaddressmodel
		),
	array(
		array('first'=> 'First Name',
			'last'=> 'Last Name'),
			$participantprofilemodel
		)

	);

$csv_string = Csv::encode($to_csv_with_header);


$to_csv_with_out_header = array(
				$uslivingaddressmodel,
				$participantprofilemodel
			);

$csv_string = Csv::encode($to_csv_with_out_header, Csv::HAS_HEADER_ROW_FALSE);



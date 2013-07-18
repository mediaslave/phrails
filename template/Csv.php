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

	private $has_header_row = false;
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
	public function encode(array $array, $has_header_row = null){
		if(is_null($has_header_row)){
			$has_header_row = $this->probeForHeaderRow($array);
		}
		return ($has_header_row) ? $this->encodeWithHeaderRow($array)
								 : $this->encodeWithoutHeaderRow($array);
	}

	/**
	 * Encode with a header row
	 * 
	 * @return string
	 */
	private function encodeWithHeaderRow(array $array){
		$this->has_header_row = true;
		foreach ($array['header'] as $key => $value) {
			$this->encoded_string .= '"' . $this->escapeSpecialChars($value) . '",';
		}
		$this->encoded_string = rtrim($this->encoded_string, ',') . "\n";
		return $this->encodeWithoutHeaderRow($array);
	}

	/**
	 * Encode without a header row
	 * 
	 * @return string
	 */
	private function encodeWithoutHeaderRow(array $array){
		$records = ($this->has_header_row) ? $array['content'] : $array;
		$propHeadersAdded = false;
		foreach ($records as $model) {
			if ($this->has_header_row) {
				foreach ($array['header'] as $var => $value) {
					$column = explode('.', $var);
					if(count($column) == 2){
						$relationship = $column[0];
						$var = $column[1];
						$this->encoded_string .= '"' . $this->escapeSpecialChars($model->$relationship->$var) . '",';
					}else{
						$this->encoded_string .= '"' . $this->escapeSpecialChars($model->$var) . '",';
					}
				}
				$this->encoded_string = rtrim($this->encoded_string, ',') . "\n";
				continue;
			}
			$props = ($model instanceof Model) ? $model->props()->export()
												: $model;
			if (!$propHeadersAdded) {
				$propHeadersAdded = true;
				foreach ($props as $key => $value) {
					$this->encoded_string .= '"' . $this->escapeSpecialChars($key) . '",';
				}
				$this->encoded_string = rtrim($this->encoded_string, ',') . "\n";
			}
			//If we don't have a header row let's loop thru the props and get each one.
			foreach($props as $key => $value){
				$this->encoded_string .= '"' . $this->escapeSpecialChars($value) . '",';
			}
			$this->encoded_string = rtrim($this->encoded_string, ',') . "\n";
		}

		return $this->encoded_string;
	}

	/**
	 * Escape " with ""
	 * 
	 * @return string
	 */
	private function escapeSpecialChars($val){
		return str_replace('"', '""', $val);
	}

	/**
	 * private function to look for a header row array.
	 * 
	 * @return boolean
	 */
	private function probeForHeaderRow(array $array){
		return (array_key_exists('header', $array));
	}
}
/*
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
*/
<?php
/**
 * * @license https://raw.github.com/mediaslave/phrails/master/LICENSE The MIT License
 * @package util
 */
/**
 * class description
 *
 * @package util
 * @author Justin Palmer
 */
class SortableDirectoryIterator implements IteratorAggregate {


	private $array = array();
	private $method = 'getFileName';
	private $doHidden = false;

	/**
	 *
	 */
    public function __construct($path) {

			if (!is_array($path)) {
				$path = func_get_args();
			}


			$this->sort($path);
    }

		public function setMethod($method = 'getFileName') {
			$this->method = $method;
		}

		public function doHidden($doHidden = false) {
			$this->doHidden = false;
		}

    public function getIterator() {
		return new ArrayIterator($this->array);
    }

	/**
	 * Sort the items by the correct method
	 *
	 * @DonaldKnuth
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	private function sort($args) {
		$method = $this->method;
		foreach($args as $path){
			if (!(is_dir($path))) {
					continue;
			}
			$i = new DirectoryIterator($path);

			foreach($i as $File){
				if($this->doHidden == false && $File->isDot() || $File->isDir()){
					continue;
				}
				$key = $File->$method();
				$this->array[$key] = new SplFileInfo($File->getPathName());
			}
		}
		ksort($this->array);
		$this->array = array_values($this->array);
	}
}

<?
/**
* Allows you to pass in a method to sort by.  This can be any method that is is available
* from the DirectoryIterator item method.
*/
class SortableDirectoryIterator implements IteratorAggregate
{
	private $array = array();
	private $method;
	private $doHidden;
	
	/**
	 * 
	 */
    public function __construct($path, $method='getFileName', $doHidden=false)
    {
		$this->method = $method;
		$this->doHidden = $doHidden;
		$this->sort($path);
    }

    public function getIterator()
    {
		return new ArrayIterator($this->array);
    }

	/**
	 * Sort the items by the correct method
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	private function sort($path)
	{	
		$method = $this->method;
		$i = new DirectoryIterator($path);
		foreach($i as $File){
			if($this->doHidden == false && $File->isDot() || $File->isDir()){
				continue;
			}
			$key = $File->$method();
			$this->array[$key] = new SplFileInfo($File->getPathName());
		}
		ksort($this->array);
		$this->array = array_values($this->array);
	}
}
<?php
/**
 * @license https://raw.github.com/mediaslave/phrails/master/LICENSE The MIT License
 * @package html
 */
/**
 * class description
 *
 * @package html
 * @author Justin Palmer
 */
abstract class AssetTag extends Tag
{

	/**
	 * The source of the url.
	 *
	 * @var string
	 */
	protected $source;
	private static $asset_num = 0;
	protected $path = 'public/images/';

	function __construct($source, $options, $from_base=true) {
		$this->setSource($source, $from_base);
		parent::__construct($options);
	}

	/**
	 * Set the source.
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	private function setSource($source, $from_base)
	{
		$app_path = Registry::get('pr-install-path');
		if($from_base){
			$rule = new UriRule();
			$rule->value = $source;
			if($app_path != null && !$rule->run()){
				$path = $app_path . $this->path . $source;
				$source = $this->addAssetUri($path);
			}else{
				$source = $this->getAssetUri($source);
			}
		}
		$this->source = $source;
	}

	/**
	 * Check if we have asset uri and return a source if we do.
	 *
	 * @return string
	 * @author Justin Palmer
	 **/
	private function getAssetUri($source)
	{
		$url = (object) parse_url($source);
		$uri = $url->scheme . '://' . $url->host;
		//If the uri is the 'pr-domain-uri' and we have asset uris
		if ($uri == Registry::get('pr-domain-uri') && is_object(Registry::get('pr-asset-uris'))) {
			//Stip out the 'pr-domain-uri' out of the source an input an asset uri.
			$path = array_pop(explode($uri, $source));
			$source = $this->addAssetUri($path);
		}
		return $source;
	}

	/**
	 * Add the asset host to the path
	 *
	 * @return string
	 * @author Justin Palmer
	 **/
	private function addAssetUri($path)
	{
		//if we do not have an asset uris then just return the path back.
		if(!is_object(Registry::get('pr-asset-uris')))
			return $path;
		//Get the array of 'pr-asset-uris'
		$assets = array_values((array) Registry::get('pr-asset-uris'));
		//Count how many there are for the mod operation
		$mod = count($assets);
		//Calculate with mod wich element of the assets array we should use.
		$key = self::$asset_num % $mod;
		//Create the source
		$source = $assets[$key] . $path;
		//Increase the asset counter
		self::$asset_num++;
		//Return the source.
		return $source;
	}
}

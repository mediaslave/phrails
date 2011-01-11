<?php
/**
* Base class for all tags.
* @author Justin Palmer
* @package html
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
			$source = Registry::get('pr-domain-uri') . $app_path . $this->path . $source . '?' . time();
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
		$get = '';
		$assets = array_values((array) Registry::get('pr-asset-uris'));
		$source = $assets[self::$asset_num] . $path;
		//Increase the asset_num or set it to zero.
		(self::$asset_num == (count($assets) - 1)) ? self::$asset_num = 0 : self::$asset_num++;
		//Return the source.
		return $source;
	}
}
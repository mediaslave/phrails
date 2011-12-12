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
class Reflections extends ReflectionClass
{

	/**
	 * Get an array of child methods that are public.
	 *
	 * @return array
	 * @author Justin Palmer
	 **/
	public function getPublicChildMethods()
	{
		$ret = array();
		$declaringMethods = $this->getMethods(ReflectionMethod::IS_PUBLIC);

		foreach($declaringMethods as $method) {
		    $parentClass = $this->getMethod($method->getName())
		                            ->getDeclaringClass()
		                            ->getName();

		    if($parentClass === $this->getName() && $method->getName() !== '__construct') {
		        $ret[] = $method->getName();
		    }
		}
		return $ret;
	}
} // END class String

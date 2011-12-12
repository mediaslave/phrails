<?php
/**
 * @license https://raw.github.com/mediaslave/phrails/master/LICENSE The MIT License
 * @package util
 */
/**
 * Thanks to http://www.eval.ca/articles/php-pluralize (MIT license)
 *           http://dev.rubyonrails.org/browser/trunk/activesupport/lib/active_support/inflections.rb (MIT license)
 *           http://www.fortunecity.com/bally/durrus/153/gramch13.html
 *           http://www2.gsu.edu/~wwwesl/egw/crump.htm
 *
 * @package util
 */
class Inflections
{
    static $plural = array(
        '/(quiz)$/i'               => "$1zes",
        '/^(ox)$/i'                => "$1en",
        '/([m|l])ouse$/i'          => "$1ice",
        '/(matr|vert|ind)ix|ex$/i' => "$1ices",
        '/(x|ch|ss|sh)$/i'         => "$1es",
        '/([^aeiouy]|qu)y$/i'      => "$1ies",
        '/(hive)$/i'               => "$1s",
        '/(?:([^f])fe|([lr])f)$/i' => "$1$2ves",
        '/(shea|lea|loa|thie)f$/i' => "$1ves",
        '/sis$/i'                  => "ses",
        '/([ti])um$/i'             => "$1a",
        '/(tomat|potat|ech|her|vet)o$/i'=> "$1oes",
        '/(bu)s$/i'                => "$1ses",
        '/(alias)$/i'              => "$1es",
        '/(octop)us$/i'            => "$1i",
        '/(ax|test)is$/i'          => "$1es",
        '/(us)$/i'                 => "$1es",
        '/s$/i'                    => "s",
        '/$/'                      => "s"
    );

    static $singular = array(
        '/(quiz)zes$/i'             => "$1",
        '/(matr)ices$/i'            => "$1ix",
        '/(vert|ind)ices$/i'        => "$1ex",
        '/^(ox)en$/i'               => "$1",
        '/(alias)es$/i'             => "$1",
        '/(octop|vir)i$/i'          => "$1us",
        '/(cris|ax|test)es$/i'      => "$1is",
        '/(shoe)s$/i'               => "$1",
        '/(o)es$/i'                 => "$1",
        '/(bus)es$/i'               => "$1",
        '/([m|l])ice$/i'            => "$1ouse",
        '/(x|ch|ss|sh)es$/i'        => "$1",
        '/(m)ovies$/i'              => "$1ovie",
        '/(s)eries$/i'              => "$1eries",
        '/([^aeiouy]|qu)ies$/i'     => "$1y",
        '/([lr])ves$/i'             => "$1f",
        '/(tive)s$/i'               => "$1",
        '/(hive)s$/i'               => "$1",
        '/(li|wi|kni)ves$/i'        => "$1fe",
        '/(shea|loa|lea|thie)ves$/i'=> "$1f",
        '/(^analy)ses$/i'           => "$1sis",
        '/((a)naly|(b)a|(d)iagno|(p)arenthe|(p)rogno|(s)ynop|(t)he)ses$/i'  => "$1$2sis",
        '/([ti])a$/i'               => "$1um",
        '/(n)ews$/i'                => "$1ews",
        '/(h|bl)ouses$/i'           => "$1ouse",
        '/(corpse)s$/i'             => "$1",
        '/(us)es$/i'                => "$1",
        '/s$/i'                     => ""
    );

    static $irregular = array(
        'move'   => 'moves',
        'foot'   => 'feet',
        'goose'  => 'geese',
        'sex'    => 'sexes',
        'child'  => 'children',
        'man'    => 'men',
        'tooth'  => 'teeth',
        'person' => 'people'
    );

    static $uncountable = array(
        'sheep',
        'fish',
        'deer',
        'series',
        'species',
        'money',
        'rice',
        'information',
        'equipment'
    );

	/**
	 * Add irregulars
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	static public function addIrregular(array $array)
	{
		foreach($array as $key => $value){
			self::$irregular[$key] = $value;
		}
	}
	/**
	 * addUncountable
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	static public function addUncountable($args)
	{
		$args = func_get_args();
		self::$uncountable = array_merge($args, self::$uncountable);
	}
	/**
	 * Change a table name into a class name
	 *
	 * @return string
	 * @author Justin Palmer
	 **/
	public static function classify($string)
	{
		$string = Inflections::singularize($string);
		//if it is underscored class.
		$string = str_replace('_', ' ', $string);
		//if it it is a lower case namespaced class.
		$string = str_replace('\\', ' ', $string);
		//if it is hyphen class
		$string = str_replace('-', ' ', $string);
		return str_replace(' ', '', ucwords($string));
	}
	/**
	 * Underscore a camel case word
	 *
	 * @return string
	 * @author Justin Palmer
	 **/
	public static function underscore($string)
	{
		return strtolower(preg_replace('/([^\s])([A-Z\-])/', '\1_\2', $string));
	}
	/**
	 * Make a table name out of string
	 *
	 * @return string
	 * @author Justin Palmer
	 **/
	public static function tableize($string)
	{
		$ex = explode('\\', $string);
		$string = array_pop($ex);
		return self::pluralize(self::underscore($string));
	}
	/**
	 * Change a string to plural
	 *
	 * @param string $string
	 * @return string
	 * @author Justin Palmer
	 */
    public static function pluralize( $string )
    {
        // save some time in the case that singular and plural are the same
        if ( in_array( strtolower( $string ), self::$uncountable ) )
            return $string;

        // check for irregular singular forms
        foreach ( self::$irregular as $pattern => $result )
        {
            $pattern = '/' . $pattern . '$/i';

            if ( preg_match( $pattern, $string ) )
                return preg_replace( $pattern, $result, $string);
        }

        // check for matches using regular expressions
        foreach ( self::$plural as $pattern => $result )
        {
            if ( preg_match( $pattern, $string ) )
                return preg_replace( $pattern, $result, $string );
        }

        return $string;
    }
	/**
	 * change a string to a foreign key
	 *
	 * @return string
	 * @author Justin Palmer
	 **/
	public static function foreignKey($string)
	{
		return self::underscore(self::singularize($string)) . '_id';
	}
	/**
	 * Change a string to singular
	 *
	 * @param string $string
	 * @return string
	 * @author Justin Palmer
	 */
    public static function singularize( $string )
    {
        // save some time in the case that singular and plural are the same
        if ( in_array( strtolower( $string ), self::$uncountable ) )
            return $string;

        // check for irregular plural forms
        foreach ( self::$irregular as $result => $pattern )
        {
            $pattern = '/' . $pattern . '$/i';

            if ( preg_match( $pattern, $string ) )
                return preg_replace( $pattern, $result, $string);
        }

        // check for matches using regular expressions
        foreach ( self::$singular as $pattern => $result )
        {
            if ( preg_match( $pattern, $string ) )
                return preg_replace( $pattern, $result, $string );
        }

        return $string;
    }
	/**
	 * Change a string to plural conditionally.
	 *
	 * @param string $count
	 * @param string $string
	 * @return string
	 * @author Justin Palmer
	 */
    public static function pluralizeIf($count, $string)
    {
        if ($count == 1)
            return "1 $string";
        else
            return $count . " " . self::pluralize($string);
    }

	/**
	 * Create a title out of string
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public static function titleize($string)
	{
		$restricted = array('and', 'but', 'or', 'yet', 'for', 'nor', 'so', 'as',
							'if', 'once', 'than', 'that', 'till', 'when',
							'at', 'by', 'down', 'from', 'in', 'into', 'like', 'near', 'of',
							'off', 'on', 'onto', 'over', 'past', 'to', 'upon', 'with', 'a', 'an', 'the');
		$array = explode(' ', implode(' ', explode('_', $string)));
		$string = '';
		for($i = 0; $i < sizeof($array); $i++){
			if($i == 0 || !in_array($array[$i], $restricted) || $i == sizeof($array) - 1){
				$string .= ucfirst(strtolower($array[$i])) . ' ';
			}else{
				$string .= strtolower($array[$i]) . ' ';
			}
		}
		return trim($string);
	}
}

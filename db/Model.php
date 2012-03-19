<?php
/**
 * @license https://raw.github.com/mediaslave/phrails/master/LICENSE The MIT License
 * @package db
 */
/**
 * The base model.
 *
 *
 * @package db
 **/
abstract class Model extends ActiveRecord
{
	/**
	 * The primary key.
	 *
	 * @author Justin Palmer
	 * @var string
	 */
	protected $primary_key = 'id';
	/**
	 * The models table name.
	 *
	 * @author Justin Palmer
	 * @var string
	 */
	protected $table_name;

	/**
	 * The database name for this project
	 */
	protected $database_name;
	/**
	 * The alias for the model.
	 *
	 * @author Justin Palmer
	 * @var string
	 */
	protected $alias;
	/**
	 * The Schema for the model.
	 *
	 * @author Justin Palmer
	 * @var Schema
	 */
	protected $schema;
	/**
	 * The properties for the model set by the user.
	 *
	 * @author Justin Palmer
	 * @var Hash
	 */
	protected $props;
	/**
	 * An array of properties that have changed.
	 *
	 * @author Justin Palmer
	 * @var array
	 */
	protected $props_changed;
	/**
	 * The valid columns for the model from the db.
	 *
	 * @author Justin Palmer
	 * @var Hash
	 */
	protected $columns;
	/**
	 * The current errors, if any during the validation process.
	 *
	 * @author Justin Palmer
	 * @var Hash
	 */
	protected $errors;

  public $validateNulls = false;
	/**
	 * The filters for the model
	 *
	 * @author Justin Palmer
	 * @var ModelFilters
	 */
	private $filters;
	/**
	 * Constructor
	 *
	 * @param array $array
	 * @return Model
	 * @author Justin Palmer
	 **/
	public function __construct($array=array())
	{
		$this->table_name(true);
		if($this->alias === null){
			$this->alias(Inflections::singularize($this->table_name));
		}
		$this->database_name(DatabaseConfiguration::get('database'));
		parent::__construct();

		$this->columns = $this->adapter()->cacheColumns(get_class($this), '`' . $this->database_name . '`.`' . $this->table_name . '`');

		//new Dbug($this->columns, '', false, __FILE__, __LINE__);

		$this->props = new Hash();
		$this->errors = new Hash();
		if(is_array($array)){
			$this->setProperties($array);
		}

		$this->filters = new ModelFilters();
		$this->schema = new Schema($this);

		$this->init();
	}

	/**
	 * Create a new model object to perform queries on.
	 *
	 * This is because PHP will not allow method declarations with the keyword `new`.
	 * We are using the phonetic spelling.
	 *
	 * @param array $props
	 * @return Model
	 * @author Justin Palmer
	 **/
	final static public function noo(array $props = array())
	{
		$model = get_called_class();
		return new $model($props);
	}

	/**
	 * Save the model
	 *
	 * @todo there has to be a better way to do this.  We should not return false,
	 * but we also do not want every save call wrapped in a try/catch block.
	 *
	 * @return boolean
	 * @author Justin Palmer
	 **/
	public function save($runCSRF = true)
	{
		try{
			$this->filter('beforeValidate');
			if(!$this->validate($runCSRF)) return false;
			$this->filter('afterValidate');
			return parent::save();
		}catch(FailedModelFilterException $e){
			return false;
		}catch(FailedActiveRecordCreateUpdateException $e){
			return false;
		}
	}

	/**
	 * Validate the model props against the schema
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	final public function validate($runCSRF = false)
	{
		//Make sure we are not a csrf haxor.
		if($runCSRF && !FormBuilder::isValidAuthenticityToken()){
			$this->errors->set('authenticity-token', FormBuilder::getAuthenticityErrorMessage());
			return false;
		}

		$Judge = new RuleJudge($this->props, $this->schema);

		$this->errors = $Judge->judge($this);
		if(!$this->errors->isEmpty()){
			return false;
		}
		return true;
	}

  /**
   * reload the relationship, destroying the current results
   * @param string $key relationship to reload
   */
  final public function live($key) {
    return $this->loadRelationship($key);
  }

  private function loadRelationship($key) {
    if($this->schema->relationships->isKey($key)) {
      return $this->lazy($this, array($key=>$this->schema->relationships->get($key)), true);
    }
  }

	/**
	 * __get model properties
	 *
	 * @return string
	 * @author Justin Palmer
	 **/
	final public function __get($key)
	{
		//if there is a property with this key in the model return the value.
		if($this->props->isKey($key)){
			return $this->props->get($key);
		}
		if(!is_object($this->schema) && !is_object($this->schema->relationships))
			return;
		//If it is a relationship that is not set then run the query and return the key.
		if (!isset($this->$key)){
			return $this->loadRelationship($key);
		}
	}
	/**
	 * __set model properties
	 *
	 * @todo Second throw statement should throw NoModelRelationshipException($key, $this->table_name());
	 * @return void
	 * @author Justin Palmer
	 **/
	final public function __set($key, $value)
	{
		if(!$this->columns->isKey($key) && !($this->schema->relationships instanceof Hash))
			throw new NoColumnInTableException($key, $this->table_name());
		if($this->columns->isKey($key)){
			if($value !== null && $value !== $this->props()->get($key)){
				$this->props_changed[] = $key;
			}
			return $this->props->set($key, $value);
		}
		if (!$this->schema->relationships->isKey($key)) {
			throw new NoColumnInTableException($key, $this->table_name());
		}

		$this->$key = $value;
	}
	/**
	 * Does the model actually have the property specified.
	 *
	 * @return boolean
	 * @author Justin Palmer
	 **/
	final public function hasProperty($column)
	{
		if(!$this->columns->isKey($column))
			throw new NoColumnInTableException($column, $this->table_name());
		return true;
	}
	/**
	 * Does this model have validation errors
	 *
	 * @return boolean
	 * @author Justin Palmer
	 **/
	final public function hasErrors()
	{
		return (empty($this->errors)) ? false : true;
	}
	/**
	 * Get the error array
	 *
	 * @return array
	 * @author Justin Palmer
	 **/
	final public function errors($errors = null)
	{
		if($errors instanceof Hash){
			$this->errors = $errors;
		}
		return $this->errors;
	}
	/**
	 * Get the valid columns
	 *
	 * @return Hash
	 * @author Justin Palmer
	 **/
	final public function columns()
	{
		return $this->columns;
	}
	/**
	 * Get the current properties set
	 *
	 * @param array $props - should be a key value paired array to set the props of the model.
	 * @return Hash
	 * @author Justin Palmer
	 **/
	final public function props($props=array())
	{
		if(empty($props)){
			return $this->props;
		}
		//if $props (passed in a array)is not empty then we will set the properties of the model
		$this->setProperties($props);
	}
	/**
	 * Get the schema object.
	 *
	 * @return Schema
	 * @author Justin Palmer
	 **/
	final public function schema()
	{
		return $this->schema;
	}
	/**
	 * Get the alias
	 *
	 * @return string
	 * @author Justin Palmer
	 **/
	final public function alias($alias = null)
	{
		if($alias !== null){
			$this->alias = $alias;
		}
		return $this->alias;
	}
	/**
	 * Get the table name.
	 *
	 * @return string
	 * @author Justin Palmer
	 **/
	final public function table_name($set=false)
	{
		//Generate the table name if it is not set.
		if($this->table_name === null && $set = true){
			$klass = explode('\\', get_class($this));
			$this->table_name = Inflections::tableize(array_pop($klass));
		}
		return $this->table_name;
	}
	/**
	 * Get the table name.
	 *
	 * @return string
	 * @author Justin Palmer
	 **/
	final public function database_name($name=null)
	{
		if($name !== null && $this->database_name === null){
			$this->database_name = $name;
		}
		return $this->database_name;
	}
	/**
	 * Get the primary_key value
	 *
	 * @return string
	 * @author Justin Palmer
	 **/
	final public function primary_key()
	{
		return $this->primary_key;
	}
	/**
	 * Get the filters object to manipulate filters
	 *
	 * @return ModelFilters
	 * @author Justin Palmer
	 **/
	final public function filters()
	{
		$this->filters->setModelClassName(get_class($this));
		return $this->filters;
	}

	/**
	 * run a filter(s)
	 *
	 * $this->filter('beforeSave')
	 *
	 * @return boolean if any filter returns false or throws an exception return false.
	 * @author Justin Palmer
	 **/
	final protected function filter($filter)
	{
		$filterType = $filter;
		$filters = $this->filters()->get($filter);
		if($filters !== null){
			foreach(array_values($filters) as $filter){
				try{
					if($filter instanceof Closure){
						if($filter() === false){
							throw new FailedModelFilterException(get_class($this), $filterType, $filter);
						}
					}elseif(is_array($filter)){
						$property = array_shift($filter);
						$method = array_shift($filter);
						if($this->$property->$method() === false){
							throw new FailedModelFilterException(get_class($this), $filterType, $filter);
						}
					}elseif($this->$filter() === false){
						throw new FailedModelFilterException(get_class($this), $filterType, $filter);
					}
				}catch(Exception $e){
					throw $e;
				}
			}
		}
		return true;
	}

	/**
	 * Try to return a datatype object for the specified column
	 *
	 * @return DataType
	 * @author Justin Palmer
	 **/
	final public function objectify($key)
	{
		//if there is a property with this key in the model return the value.
		if($this->columns->isKey($key)){
			$column = $this->columns->get($key);
			return DataTypeFactory::process($column->Type, $this->props->get($key));
		}
		return $this->$key;
	}

	/**
	 * To string returns the props
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function __toString()
	{
		return $this->props()->export();
	}
	/**
	 * Set the properties
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	private function setProperties(array $array)
	{
		//Make sure all of the columns get a value.
		$keys = $this->columns->keys();
		foreach(array_values($keys) as $key){
			$array[$key] = (isset($array[$key])) ? $array[$key] : null;
		}
		//Set all of the props.
		foreach($array as $key => $value){
			$this->$key = $value;
		}
	}
	/**
	 * init
	 *
	 * @return mixed
	 * @author Justin Palmer
	 **/
	abstract public function init();
}

<?php
/**
 * @license https://raw.github.com/mediaslave/phrails/master/LICENSE The MIT License
 * @package db
 */
/**
 * Build sql statements
 *
 * @todo named_scope
 *
 * @todo This really sucks.  SqlBuilder really should not be building this join.  The adapter should be.
 * Do to time constraints this is going to have to wait. self::join should be able to take multiple
 * relationships and pass the "config" off to the adapter for build.
 *
 * @package db
 */
class SqlBuilder
{
	static private $adapter = null;

	private $Hash;

	private $raw=false;
	/**
	 * Constructor
	 *
	 * @return SqlBuilder
	 * @author Justin Palmer
	 **/
	public function __construct()
	{
		self::$adapter = DatabaseAdapter::get();
		$this->reset();
	}

	/**
	 * select text snippet
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	final public function select($select, $replace = true)
	{
		$this->Hash->select($this->Hash->select() . ", $select");
		if($replace)
			$this->Hash->select($select);
		return $this;
	}

	/**
	 * count
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function join($join)
	{
		$this->Hash->join($join);
		return $this;
	}

	/**
	 * build a join from a schema relationship
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	final public function addJoinFromRelationship($relationship, $join_type="INNER", $additional_on='')
	{
		$join = '';
		if($relationship->type == 'has-one' || $relationship->type == 'belongs-to'){
			$this->raw();
		}
		$join = $this->Hash->join();
		$klass = $relationship->klass;
		$obj = new $klass;
		$on = str_replace('?', '`' . $this->alias() . "`.`" . $relationship->prop . '`', $relationship->on);
		if($relationship->thru != ''){
			$join .= $relationship->join . ' ' . $on;
			$join .= " $join_type JOIN `" . $obj->database_name() . "`.`" . $relationship->table . "`
					 AS `" . $relationship->alias . "`
					  ON " . $relationship->join_on . " $additional_on ";
		}else{
			$join .= " $join_type JOIN `" . $obj->database_name() . "`.`" . $relationship->table . "`
					 AS `" . $relationship->alias . "`
					  ON " . $on . " $additional_on";
		}
		
		self::join($join);
	}

	/**
	 * add raw join into the sql
	 * 
	 * @return this
	 */
	final public function joinRaw($sql, $args=array()){
		$join = $this->Hash->join();
		$this->Hash->whereArgs($args);
		$join .= ' ' . $sql . ' ';
		$this->Hash->join($join);
		return $this;
	}

	/**
	 * count
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function count($column=null, $as=null, $distinct='')
	{
		$this->Hash->count($column, $as, $distinct);
		return $this;
	}

	/**
	 * table
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	final public function from($db, $table, $as=null)
	{
		$from = $this->adapter()->tick($db) . '.' . $this->adapter()->tick($table);
		if($as !== null)
			$from .= ' AS ' . $this->adapter()->tick($as);
		$this->Hash->from($from);
		return $this;
	}

	/**
	 * where
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	final public function where(/*$conditions, $args*/)
	{
		$args = func_get_args();
		$where = array_shift($args);
		$newArgs = array();
		($this->Hash->where() != '') ? $this->Hash->where($this->Hash->where() . ' AND ' . $where)
									 : $this->Hash->where($where);
		$this->Hash->whereArgs($args);
		return $this;
	}

	/**
	 * Where in
	 * 
	 * @return SqlBuilder
	 */
	public function whereIn($property, array $array){
		return $this->whereInAndNotIn($property, $array);
	}

	/**
	 * Where not in
	 * 
	 * @return SqlBuilder
	 */
	public function whereNotIn($property, array $array){
		return $this->whereInAndNotIn($property, $array, 'NOT IN');
	}

	/**
	 * Where in and not in
	 * 
	 * @return SqlBuilder
	 */
	private function whereInAndNotIn($property, array $array, $type='IN'){
		$question_marks = '';
		foreach($array as $value){
			$question_marks .= '?,';
		}
		$question_marks = rtrim($question_marks, ',');
		$this->where("$property $type ($question_marks)", $array);
		return $this;
	}

	/**
	 * order
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	final public function order(/* $order, $order, ... */)
	{
		$this->Hash->order(func_get_args());
		return $this;
	}

	/**
	 * If limit is not provided the function assumes you want the offset to be zero (0) and the limit to
	 * be the first parameter passed in.
	 *
	 * @param integer $offset
	 * @param integer $limit
	 * @return void
	 * @author Justin Palmer
	 **/
	final public function limit($offset, $limit=null)
	{
		$this->Hash->offset($offset);
		$this->Hash->limit($limit);
		if($limit === null){
			$this->Hash->offset(0);
			$this->Hash->limit($offset);
		}
		return $this;
	}

	/**
	 * group
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	final public function group($group)
	{
		$this->Hash->group($group);
		return $this;
	}

	/**
	 * having
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	final public function having($having)
	{
		$this->Hash->having($having);
		return $this;
	}

	/**
	 * Build the query.  Pass the build onto Adapter.
	 *
	 * @return Hash
	 * @author Justin Palmer
	 **/
	final protected function build($method)
	{
		$this->Hash->props($this->props());
		$method = 'build' . ucfirst($method);
		return $this->adapter()->$method($this->Hash);
	}

	/**
	 * return the adapter for use.
	 *
	 * @return DatabaseAdapter
	 * @author Justin Palmer
	 **/
	final public function adapter()
	{
		return self::$adapter;
	}
	/**
	 * get the connection from the adapter
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	final public function conn()
	{
		return $this->adapter()->conn();
	}
	/**
	 * Set the fetchmode to take into account the class or not
	 *
	 * If not, it will return the raw results from the pdo
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	final public function raw($value=true)
	{
		$this->raw = $value;
		return $this;
	}

	/**
	 * Is the mode raw
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	final public function isRaw()
	{
		return $this->raw;
	}

	/**
	 * Set the return class for the query
	 * 
	 * @return this
	 */
	final public function setReturnClass($name){
		$this->return_class($name);
		return $this;
	}

	/**
	 * export the hash
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function export()
	{
		return $this->Hash->export();
	}

	/**
	 * Reset to factory default
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	final protected function reset()
	{
		$this->Hash = new SqlBuilderHash();
		$this->from($this->database_name(), $this->table_name(), $this->alias());
	}
}

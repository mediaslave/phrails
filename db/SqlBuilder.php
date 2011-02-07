<?php
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
 * @author Justin Palmer
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
	final public function addJoinFromRelationship($relationship)
	{		
		$join = '';		
		if($relationship->type == 'has-one' || $relationship->type == 'belongs-to'){
			$this->raw();
			$klass = $relationship->klass;
			$obj = new $klass;
			$on = str_replace('?', $this->alias() . "." . $relationship->prop, $relationship->on);
			$join .= " INNER JOIN `" . $obj->database_name() . "`.`" . $relationship->table . "` 
						 AS " . $relationship->alias . " 
						  ON " . $on . " ";
		}
		self::join($join);
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
		($this->Hash->where() != '') ? $this->Hash->where($this->Hash->where() . ' AND ' . $where)
								: $this->Hash->where($where);
		$this->Hash->whereArgs(array_merge($this->Hash->whereArgs(), $args));
		return $this;		
	}
	
	/**
	 * order
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	final public function order($order)
	{
		$this->Hash->order($order);
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
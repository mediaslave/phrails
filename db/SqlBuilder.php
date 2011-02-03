<?php
/**
 * Build sql statements
 *
 * @todo named_scope
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
	public function select($select, $replace = true)
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
	public function from($db, $table, $as=null)
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
	public function where(/*$conditions, $args*/)
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
	public function order($order)
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
	public function limit($offset, $limit=null)
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
	public function group($group)
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
	public function having($having)
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
	public function build($method)
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
	public function adapter()
	{
		return self::$adapter;
	}
	/**
	 * get the connection from the adapter
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function conn()
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
	public function raw($value=true)
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
	public function isRaw()
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
	protected function reset()
	{
		$this->Hash = new SqlBuilderHash();
		$this->from($this->database_name(), $this->table_name(), $this->alias());
	}
}
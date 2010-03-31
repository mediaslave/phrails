<?php

/**
* @todo break this apart to handle multiple types of databases.  Now it is set to handle mysql.
*/
abstract class Migration extends Model
{
	private $table;
	private $stack;
	private $alter_stack = array();
	private $valid_options = array('limit'=>'', 'null'=>false, 'primary'=>false, 'auto'=>false, 'index'=>false, 'unique'=>false, 'default'=>'', 'after'=>false);
	private $statement;
	private $is_create = true;
	private $config;
	
	/**
	 * Migration class.
	 *
	 * 
	 * @return Migration
	 * @author Justin Palmer
	 */
	function __construct()
	{
		$this->config = $this->db()->getConfig();
		$this->stack = array();
	}
	
	
	/**
	 * Create table
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function createTable($name, $primary='id', $engine='INNODB', $charset='utf8', $collation='utf8_general_ci')
	{
		$this->migrate();
		$this->stack = array();
		$this->table = $name;
		$this->statement = "CREATE TABLE `" . $this->config->database . "`.`" . $name . "`(%s)ENGINE=$engine CHARACTER SET $charset COLLATE $collation";
		$this->integer('id', 'auto:true');
	}
	
	/**
	 * Alter table
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function alterTable($name, $engine=null, $charset=null, $collation=null)
	{
		$this->migrate();
		$this->stack = array();
		$operation = '';
		if($engine !== null)
			$operation .= "ENGINE=$engine";
		if($charset !== null)
			$operation .= " CHARACTER SET $charset";
		if($collation !== null)
			$operation .= " COLLATE $collation";
		$this->statement = "ALTER TABLE `" . $this->config->database . "`.`" . $name . "` %s $operation";
	}
	
	/**
	 * migrate the system
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function migrate()
	{
		if(!empty($this->stack)){
			$columns = '';
			foreach($this->stack as $value){
				$columns .= $value . ',';
			}
			$columns = rtrim($columns, ',');
			print sprintf($this->statement, $columns);
			print '<br/>' . '<br/>';
		}
		foreach($this->alter_stack as $query){
			print $query;
			print '<br/><br/>';
		}
	}
	
	/**
	 * int
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function integer($name, $options='')
	{
		$this->add('INT', $name, $options);
	}
	
	/**
	 * float
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function float($name, $options='')
	{
		$invalid = array('auto');
		$this->add('FLOAT', $name, $options, $invalid);
	}
	
	
	
	/**
	 * Boolean is actually a int(1)
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function boolean($name, $options='')
	{
		$invalid = array('auto');
		$this->add('BOOL', $name, $options, $invalid);
	}
	
	/**
	 * enum
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function enum($name, Array $key_value, $options=''){}
	
	/**
	 * varchar
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function string($name, $options='')
	{
		$invalid = array('auto');
		$this->add("VARCHAR", $name, $options, $invalid);
	}
	
	/**
	 * text
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function text($name, $options='')
	{
		$invalid = array('limit', 'primary', 'index', 'unique', 'auto');
		$this->add("TEXT", $name, $options, $invalid);
	}
	
	/**
	 * date
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function date($name, $options='')
	{
		$invalid = array('null', 'index', 'unique', 'default', 'auto');
		$this->add('DATE', $name, $options, $invalid);
	}
	/**
	 * time
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function time($name, $options='')
	{
		$invalid = array('null', 'index', 'unique', 'default', 'auto');
		$this->add('TIME', $name, $options, $invalid);
	}
	/**
	 * datetime
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function datetime($name, $options='')
	{
		$invalid = array('null', 'index', 'unique', 'default', 'auto');
		$this->add('DATETIME', $name, $options, $invalid);
	}
	
	/**
	 * timestamp
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function timestamp($name, $options='')
	{
		$invalid = array('null', 'index', 'unique', 'default', 'auto');
		$this->add('TIMESTAMP', $name, $options, $invalid);
	}
	
	/**
	 * timestamps
	 * 
	 * This will create a datetime column for created_at and updated_at.
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function timestamps()
	{	
		$this->datetime('updated_at');
		$this->datetime('created_at');	
	}
	
	/**
	 * Add reference to another table
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function references($table, $options='')
	{
		$column = Inflections::tableize($table) . '_id';
		$this->integer($column, $options);
		$this->index($this->table, $column);
	}
	
	/**
	 * Add the type to the stack for us to build the query
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	private function add($datatype, $name, $options, $invalid=array())
	{
		//Get the options ready
		if(!empty($invalid))
			$invalid = array_combine(array_values($invalid), array_values($invalid));
		$options = OptionsParser::toArray($options);
		$options = array_intersect_key($options, $this->valid_options);
		$options = array_diff_key($options, $invalid);
		//array('limit'=>'', 'null'=>false, , 'default'=>'', 'after'=>false);
		//'primary'=>false, 'index'=>false, 'unique'=>false
		//Add to the string the bits needed.
		$bit = "`$name` $datatype";
		if(isset($options['limit']))
			$bit .= "(" . $options['limit'] . ")";
		if(isset($options['null'])){
			($options['null']) ? $bit .= ' NULL' : $bit .= ' NOT NULL';
		}
		if(isset($options['default']))
			$bit .= " DEFAULT '" . $options['default'] . "'";
			
		if(isset($options['auto']))
			$bit .= " AUTO_INCREMENT PRIMARY KEY";
			
		if(isset($options['after']))
			$bit .= " AFTER `" . $options['after'] . "`";
		
		if(isset($options['primary']) && $options['primary'] == 'true' && !isset($options['auto']))
			$this->primary($this->table, $name);
			
		$this->stack[] = $bit;
	}
	
	/**
	 * Make a column unique
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function unique($table, $column)
	{
		$args = func_get_args();
		array_shift($args);
		$unique = '';
		foreach($args as $value)
			$unique .= "`$value`, ";
		$unique = rtrim($value, ','); 
		$this->alter($table, "ADD UNIQUE(" . $unique . ")");
	}
	
	/**
	 * Make a column unique
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function index($table, $column)
	{
		$args = func_get_args();
		array_shift($args);
		$unique = '';
		foreach($args as $value)
			$unique .= "`$value`, ";
		$unique = rtrim($value, ',');
		$this->alter($table, "ADD INDEX(" . $unique . ")");
	}
	
	/**
	 * Make a primary key
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function primary($table, $column)
	{
		$this->alter($table, "ADD PRIMARY KEY(`$column`)");
	}
	
	/**
	 * Alter table
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function alter($table, $alter)
	{
		$this->alter_stack[] = "ALTER TABLE `" . $this->config->database . "`.`" . $table . "` " . $alter;
	}
	
	/**
	 * up
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	abstract public function up();
	
	/**
	 * down
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	abstract public function down();
	
	public function init(){}
	
}


class sresu extends Migration{
	
	/**
	 * undocumented function
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function up()
	{
		$this->createTable('blah2');
			$this->string('var_char', 'limit:255');
			$this->references('user');	
			$this->text('text_of_some_sort');
			$this->unique('blah2', 'var_char');
		$this->migrate();
		
		$this->index('blah2', 'users_id');
	}
	
	/**
	 * 
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function down()
	{
		
	}
}
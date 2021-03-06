<?php
/**
 * @license https://raw.github.com/mediaslave/phrails/master/LICENSE The MIT License
 * @package db
 */

/**
* @todo refactor
* @todo break this apart to handle multiple types of databases.  Now it is set to handle mysql.
* @todo this really is just hacked out, I really need to refactor this.  It fits my needs for now.
* The stack should turn into a hash of stacks, the key would be the timestamp with milliseconds every time
* createTable or updateTable is called.  That way messages can then be queue.
* @package db
* @author Justin Palmer
*/
abstract class Migration extends Model
{
	private $table;
	private $stack;
	private $alter_stack = array();
	private $valid_options = array('limit'=>'', 'null'=>false, 'primary'=>false, 'auto'=>false, 'index'=>false, 'unique'=>false, 'default'=>'', 'after'=>false);
	private $statement;
	private $config;
	public $operations;
	/**
	 * The type relects if we are doing alter or create.
	 */
	private $type;

	/**
	 * Migration class.
	 *
	 *
	 * @return Migration
	 * @author Justin Palmer
	 */
	function __construct()
	{
		parent::__construct();
		$this->config = DatabaseConfiguration::getConfig();
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

		$this->type = 'create';
		$name = Inflections::tableize($name);

		$this->migrateIfNeeded();

		$this->operations .= "\033[0;36;1m$name\033[0m | \033[0;35;1m$engine\033[0m | \033[0;36;1m$charset\033[0m | \033[0;35;1m$collation\033[0m\n";
		$this->stack = array();
		$this->alter_stack = array();
		$this->table = $name;
		$this->statement = "CREATE TABLE `" . $this->config->database . "`.`" . $name . "`(\n\t%s\n)ENGINE=$engine CHARACTER SET $charset COLLATE $collation";
		$this->integer($primary, 'auto:true');
	}

	/**
	 * Alter table
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function alterTable($name, $engine=null, $charset=null, $collation=null, $raw=null)
	{
		$this->type = 'alter';

		$this->migrateIfNeeded();

		$this->stack = array();
		$this->alter_stack = array();
		$operation = '';
		if($engine !== null)
			$operation .= "ENGINE=$engine";
		if($charset !== null)
			$operation .= " CHARACTER SET $charset";
		if($collation !== null)
			$operation .= " COLLATE $collation";
		if($raw != true)
			$name = Inflections::tableize($name);

		$this->table = $name;
		$this->statement = "ALTER TABLE `" . $this->config->database . "`.`" . $name . "` \n\t%s\n $operation";
	}


	/**
	 * Executes migrations if there are any to run
	 *
	 * @author Dave Kerschner
	 * @access private
	 */
	private function migrateIfNeeded() {
		if($this->statement != "") {
			$this->migrateTable();
		}
		if (!empty($this->alter_stack)) {
			$this->migrateIndex();
		}
	}

	/**
	 * Drop table
	 *
	 * @author Dave Kerschner
	 * @access public
	 */
  public function dropTable($name) {
		$this->statement = "DROP TABLE `" . $this->config->database . "`.`" . Inflections::tableize($name) . "`";
  }

	/**
	 * Drop a column
	 *
	 * @author Dave Kerschner
	 * @access public
	 */
	public function drop($column) {
		$this->stack[] = "DROP `" . $column . "`";
	}


	/**
	 * Execute Table migrations
	 *
	 * @author Dave Kerschner
	 * @access private
	 */
	private function migrateTable() {
		if(!empty($this->stack)){
			$columns = '';
			foreach($this->stack as $value){
				if($this->type == 'alter')
					$value = ' ' . $value;
				$columns .= $value . ',';
			}
			$columns = rtrim($columns, ',');
			$query = sprintf($this->statement, $columns);

			$stmt = $this->conn()->prepare($query);

			$this->log($query);
		} else {
			$query = $this->statement;

      $stmt = $this->conn()->prepare($query);

      $this->log($query);
    }

		if($query != "" && !$stmt->execute() ) {
			print("ERROR: " . get_class($this) . "\n");
			var_dump($stmt->errorInfo());
			var_dump($query);
			print("\n");
			die();
		}
	}

	/**
	 * Execute index migrations
	 *
	 * @author Dave Kerschner
	 * @access private
	 */
	private function migrateIndex() {
		foreach($this->alter_stack as $query){
			$stmt = $this->conn()->prepare($query);

			if($query != "" && !$stmt->execute()) {
				print("ERROR: " . get_class($this) . "\n");
				var_dump($stmt->errorInfo());
				var_dump($query);
				print("\n");
				die();
			}
			$this->log($query);
		}
		print $this->operations;
		$this->operations = '';
	}

	/**
	 * migrate the system
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function migrate()
	{
		$this->migrateTable();
		$this->migrateIndex();
		$this->statement = "";
		$this->stack = array();
		$this->alter_stack = array();
	}
	/**
	 * log of queries ran for the current migration
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function log($query)
	{
		if ($query == '') {
			$query = "No query was specified for this action. This may mean that the migration is using pdo directly.";
		}
		$o = "\n" . get_class($this) . "\n";
		$o .= '=============================================================' . "\n";
		$o .= $query . "\n";
		$o .= '=============================================================' . "\n";
		$this->operations .= $o;
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
	 * float
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function decimal($name, $options='')
	{
		$invalid = array('auto');
		$this->add('DECIMAL', $name, $options, $invalid);
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
	 * @todo implement
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
	 * year
	 *
	 * @return void
	 * @author Dave Kerschner
	 **/
	public function year($name, $options='')
	{
		$invalid = array('null', 'index', 'unique', 'default', 'auto');
		$this->add('YEAR', $name, $options, $invalid);
	}


	/**
	 * blob
	 *
	 * @return void
	 * @author Dave Kerschner
	 **/
	public function blob($name, $options='')
	{
		$invalid = array('index', 'unique', 'default', 'auto');
		$this->add('BLOB', $name, $options, $invalid);
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
	public function references($table, $options='', $type='INDEX')
	{
		$column = Inflections::underscore($table) . '_id';
		$this->integer($column, $options);
		$this->doIndex(array($this->table, $column), $type);
	}

	public function referencesUnique($table, $options='') {
		$this->references($table, $options, 'UNIQUE');
	}

	/**
	 * Drop table reference
	 *
	 * @author Dave Kerschner
	 * @access public
	 */
	public function dropReferences($table)
	{
		$column = Inflections::underscore($table) . '_id';
		$this->drop($column);
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
		$bit = "";

		if($this->type == 'alter') {
			$bit = "ADD ";
		}

		$bit .= " `$name` $datatype";
		if(isset($options['limit'])){
			$bit .= "(" . str_replace('.', ',', $options['limit']) . ")";
		}elseif($datatype == 'VARCHAR'){
			$bit .= "(255)";
		}

		if(isset($options['null']) && $options['null']) ? $bit .= ' NULL' 
																										: $bit .= ' NOT NULL';

		if(isset($options['default']))
			$bit .= " DEFAULT '" . $options['default'] . "'";

		if(isset($options['auto']))
			$bit .= " AUTO_INCREMENT PRIMARY KEY";

		if(isset($options['after']))
			$bit .= " AFTER `" . $options['after'] . "`";

		if(isset($options['primary']) && $options['primary'] == 'true' && !isset($options['auto']))
			$this->primary($this->table, $name);

		$this->stack[] = $bit . "\n\t";
	}

	/**
	 * Make a column unique
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function unique($table, $column)
	{
		$this->doIndex(func_get_args(), 'UNIQUE');
	}

	/**
	 * Drop unique index
	 *
	 * @author Dave Kerschner
	 * @access public
	 */
	public function dropUnique($table, $column)
	{
		$this->doIndex(func_get_args(), 'UNIQUE', 'DROP');
	}

	/**
	 * Make a column unique
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function index($table, $column)
	{
		$this->doIndex(func_get_args(), 'INDEX');
	}

	/**
	 * Drop index
	 *
	 * @author Dave Kerschner
	 * @access public
	 */
	public function dropIndex($table, $column)
	{
		$this->doIndex(func_get_args(), 'INDEX', 'DROP');
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
		$this->alter_stack[] = "ALTER TABLE `" . $this->config->database . "`.`" . Inflections::tableize($table) . "` " . $alter;
	}

	/**
	 * Get the current table
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function table()
	{
		return $this->table;
	}

	/**
	 *
	 * This method is for unique() and index() to prevent duplicate code.
	 *
	 * @author Justin Palmer <justin@mediaslave.net>
	 * @param array $args
	 * @param string $type
	 * @return void
	 */
	private function doIndex($args, $type, $add_drop = "ADD"){
		$table = array_shift($args);
		$name = implode('_', $args);
		$unique = '';
		foreach($args as $value){
			$unique .= "`$value`, ";
		}
		$unique = rtrim($unique, ', ');
		$items = "  (" . $unique . ")";
		if($add_drop == 'DROP'){
			$items = '';
		}
		$this->alter($table, "$add_drop $type `$name` $items");
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

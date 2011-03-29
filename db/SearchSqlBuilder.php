<?
/**
* @todo take in multiple models.  Only handles one model at the moment.
*/
class SearchSqlBuilder
{
	
	private $models; 
	private $exclude=array();
	private $only=array();
	private $where = '';
	private $where_params = array();
	
	function __construct(/* \Model $Model, ... */) {
		$this->models = func_get_args();
	}
	
	/**
	 * Reset all of the vars
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function reset()
	{
		$this->exclude = array();
		$this->only = array();
		$this->where = '';
		$this->where_params = array();
	}
	
	
	/**
	 * undocumented function
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function where()
	{
		return $this->where;
	}
	
	/**
	 * Exclude from the automated system.
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function exclude(/* column_name, column_name, ...*/)
	{
		$this->exclude = func_get_args();
	}
	
	/**
	 * Only these columns
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function only(/* column_name, column_name, ....*/)
	{
		$this->only = func_get_args();
	}
	
	/**
	 * undocumented function
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function whereParams()
	{
		return $this->where_params;
	}
	
	/**
	 * Prepare the where and the where params for use in a sql build where call.
	 *
	 * @return void
	 * @author Justin Palmer
	 **/
	public function prepare($operand='AND')
	{
		$args = func_get_args();
		$operand = array_shift($args);
		foreach($this->models as $model){
			foreach($model->props()->export() as $key => $value){
				if($value === null || $value == '' || 
					in_array($key, $this->exclude) || 
					(count($this->only) > 0 && !in_array($key, $this->only)) ||
					in_array($value, $args)){
					continue;
				}
				$column_type = $model->columns()->get($key)->Type;
				$column_type = array_shift(explode('(', $column_type));
				switch($column_type){
					case 'varchar':
						$this->where .= '`' . $model->alias() . '`.' . $key . ' LIKE ? ' . $operand . ' ';
						$this->where_params[] = '%' . $value . '%';
						break;
					default:
						$this->where .= $key . ' = ? ' . $operand . ' ';
						$this->where_params[] = $value;
				}
			}
		}
		$this->where = rtrim($this->where, ' ' . $operand . ' ');
	}
	
}
<?
/**
* @todo take in multiple models.  Only handles one model at the moment.
*/
class SearchSqlBuilder
{
	
	private $models; 
	private $where = '';
	private $where_params = array();
	
	function __construct(\Model $Model) {
		$args = func_get_args();
		$this->models = $args;
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
	public function prepare()
	{
		foreach($this->models as $model){
			foreach($model->props()->export() as $key => $value){
				if($value === null || $value == ''){
					continue;
				}
				$column_type = $model->columns()->get($key)->Type;
				$column_type = array_shift(explode('(', $column_type));
				switch($column_type){
					case 'varchar':
						$this->where .= '`' . $model->alias() . '`.' . $key . ' LIKE ? AND ';
						$this->where_params[] = '%' . $value . '%';
						break;
					default:
						$this->where .= $key . ' = ? AND ';
						$this->where_params[] = $value;
				}
			}
		}
		$this->where = rtrim($this->where, ' AND ');
	}
	
}
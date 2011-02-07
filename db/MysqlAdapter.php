<?php
/**
 * Mysql adapter
 *
 * @package db
 * @author Justin Palmer
 */				
class MysqlAdapter extends AnsiAdapter
{
	
	/**
	 * Store the column structure in a hash.
	 * 
	 * @todo move to base DatabaseAdapter and take a third parameter for the field to make into the Hash key.
	 *
	 * @return array
	 * @author Justin Palmer
	 **/
	public function cacheColumns($class_name, $table_name)
	{
		//Hold the columns from the db to make sure properties, rules and relationships set actually exist.
		if(self::$ColumnsCache->isKey($class_name)){
			return self::$ColumnsCache->get($class_name);
		}else{
			$cols = $this->showColumns($table_name);
			$cache = new Hash();
			foreach($cols as $column){
				$cache->set($column->Field, $column);
			}
			self::$ColumnsCache->set($class_name, $cache);
			return $cache;	
		}
	}
}
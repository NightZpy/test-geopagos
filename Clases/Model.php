<?php
require_once ('ValidationNotNullException.php');
require_once ('Connection.php');
require_once ('helpers.php');
/**
* 
*/
class Model
{	
	const INSERT_QUERY = 'INSERT INTO $table_name$ ( $fields$ ) VALUES ( $values$)';
	const UPDATE_QUERY = 'UPDATE $table_name$ SET $values$';
	const DELETE_QUERY = 'DELETE FROM $table_name$';
	const WHERE_QUERY = 'WHERE $filter$';
	const FILTER_WHERE_QUERY = 'SELECT * FROM $table_name$ WHERE $filter$';
	const COUNT_WHERE_QUERY = 'SELECT COUNT(*) as c FROM $table_name$ WHERE $filter$';
	private $db = null;
	private $rules = null;

	function __construct($attributes = null)
	{
		if ($attributes)
			self::create($attributes);
		else
			$this->mapToValidAttributes();
		$this->db = new Connection;
	}

	private function run($query)
	{		
		$attributes = $this->runQuery($query);		
		return $attributes;
	}

	public static function create($attributes = [])
	{
		//throw new MiExcepción('1 no es un parámetro válido', 5);
		if ( count($attributes) ) {
			$this->mapToValidAttributes($attributes);	
			$this->run(self::makeQuery('insert', $attributes));
		}
	}	

	public function save()
	{
		$attributes = $this->attributesToArray();
		$this->validate();
		$this->run(self::makeQuery('insert', $attributes));
	}

	public function update()
	{
		$attributes = $this->attributesToArray();
		$filter = [$this->primaryKey => $attributes[$this->primaryKey]];
		unset($attributes[$this->primaryKey]);
		$this->validate();		
		$this->run(self::makeQuery('update', $attributes, $filter));
	}

	public function delete($field = null)
	{
		$attributes = $this->attributesToArray();
		if(!$field && isset($this->primaryKey)) {
			$filter = [$this->primaryKey => $attributes[$this->primaryKey]];
			unset($attributes[$this->primaryKey]);
		} else {
			$filter = [$field => $attributes[$field]];					
		} 

		$this->run(self::makeQuery('delete', null, $filter));
	}		

	public function select($filters = [])
	{
		return $this->run(self::makeQuery('select', $filters));
	}

	public function count($filters = [])
	{
		$count = $this->run(self::makeQuery('count', $filters));
		print_r($count);
		return (boolean)$count[0]['c'];
	}

	public function findByPk($value)
	{
		$query = 'SELECT * FROM ' . $this->tableName . ' WHERE ' . $this->primaryKey . '=' . $value . ' LIMIT 1';
		$attributes = $this->run($query);
		$this->makeAttributes($attributes[0]);
	}

	public function findBy($field, $value)
	{
		$query = 'SELECT * FROM ' . $this->tableName . ' WHERE ' . $field . '=' . $value;
		$rows = $this->run($query);
		if (!count($rows)) 
			return false;
		return $this->makeObjectsFromRows($rows);
	}	

	public function all()
	{
		$query = 'SELECT * FROM ' . $this->tableName;
		$rows = $this->run($query);
		if (!count($rows)) 
			return false;
		return $this->makeObjectsFromRows($rows);
	}

	private function makeFilter($filters, $comparator = '=', $separator = 'AND')
	{
		$filter = '';
		$i = 0;
		foreach ($filters as $key => $value) {
			if ($i == count($filters) - 1) {
				$filter .= $key . $comparator . "'" . $value . "'";
			} else {
				$filter .= $key . $comparator . "'" . $value . "' " . $separator . ' ';
			}		
			$i++;	
		}
		return $filter;
	}

	public function makeQuery($action, $attributes = null, $filters = null)
	{
		$query = null;			
		$filter = null;		
		if ($filters) {
			$filter = $this->makeFilter($filters);
		}	

		switch ($action) {
			case 'insert':								
				if (isset($this->primaryKey) && (!isset($attributes[$this->primaryKey]) || empty($attributes[$this->primaryKey])))
					unset($attributes[$this->primaryKey]);

				foreach ($attributes as $key => $value) 
						$attributes[$key] = "'" . $value . "'";	
				$keys 	= array_keys($attributes);

				$values = implode(', ', array_values($attributes));
				$query 	= str_replace('$table_name$', $this->tableName, self::INSERT_QUERY);
				$query 	= str_replace('$fields$', implode(', ', $keys), $query);
				$query 	= str_replace('$values$', $values, $query);				
			break;

			case 'update':
				$values = $this->makeFilter($attributes, '=', ', ');
				$query 	= str_replace('$table_name$', $this->tableName, self::UPDATE_QUERY);
				$query 	= str_replace('$values$', $values, $query);
				if ($filter)
					$query .= ' ' . str_replace('$filter$', $filter, self::WHERE_QUERY);
			break;	

			case 'select':
				$query 	= str_replace('$table_name$', $this->tableName, self::FILTER_WHERE_QUERY);
				$filter = $this->makeFilter($attributes);
				$query = str_replace('$filter$', $filter, $query);
			break;

			case 'count':
				$query 	= str_replace('$table_name$', $this->tableName, self::COUNT_WHERE_QUERY);
				$filter = $this->makeFilter($attributes);
				$query = str_replace('$filter$', $filter, $query);
			break;
			
			case 'delete':
				$query 	= str_replace('$table_name$', $this->tableName, self::DELETE_QUERY);
				if ($filters)
					$query .= ' ' . str_replace('$filter$', $filter, self::WHERE_QUERY);
			break;	

			default:
				
			break;
		}
		print("\nQuery: $query\n");
		return $query;
	}

	public function runQuery($query)
	{
		$results = $this->db->fetch($query);
		$array = [];
		while ($result = $results->fetchArray(SQLITE3_ASSOC)) {
			$array[] = $result;
		}
		return $array;
	}

	private function makeObjectsFromRows($rows)
	{
		$objets = [];				
		foreach ($rows as $row) 
			$objets[] = $this->makeObjectFromRow($row);
		return $objets;
	}

	private function makeObjectFromRow($row)
	{
		$class = get_class($this);
		$object = new $class;
		foreach ($row as $attribute => $value) {
			$attribute = underscoreToCamelCase($attribute);
			$object->$attribute = $value;
		}
		return $object;
	}

	public function mapToValidAttributes($attributes = null)
	{
		if($attributes)
			$tmpArray = array_combine($this->attributes, $attributes);
		else 
			$tmpArray = array_fill_keys($this->attributes, null);

		if ($attributes) 
			$validAttributes = array_intersect_key($attributes, $tmpArray);
		else 
			$validAttributes = $tmpArray;

		$this->makeAttributes($validAttributes);
		return $validAttributes;		
	}

	public function makeAttributes($attributes)
	{
		foreach ($attributes as $key => $value) {
			$attribute =  underscoreToCamelCase($key);
			$this->$attribute = $value;
		}
	}

	public function attributesToArray()
	{
		$attributes = [];	
		foreach ($this->attributes as $attribute => $value) {
			$attributeVar = underscoreToCamelCase($value);
			$attributes[$value] = $this->$attributeVar;
		}
		return $attributes;
	}

	private function parseValidationRules()
	{
		$this->rules = [];
		foreach ($this->validationRules as $field => $rules) {
			$rules = explode('|', $rules);
			if (count($rules)) {
				$subRules = [];
				foreach ($rules as $rule) {
					$subRule = explode(':', $rule);
					if (count($subRule) > 1)						
						if (count($subRule) == 3)							
							$subRules[$subRule[0]] = [$subRule[1] => $subRule[2]];					
						else
							$subRules[$subRule[0]] = $subRule[1];					
					else
						$subRules[] = $subRule[0];					
				}
				$this->rules[$field] = $subRules;
			}
		}
	}

	public function validate()
	{
		$this->parseValidationRules();
		if (!count($this->rules))
			return true;
		print("\nRules: ");
		//print_r($this->rules);

		foreach ($this->rules as $field => $rules) {
			$attributeVar = underscoreToCamelCase($field);
			$value = $this->$attributeVar;
			foreach ($rules as $key => $limit) {
				print("\nkey=$key");
				if (is_numeric($key)) {
					switch ($limit) {
						case 'required':
							if (empty($value))
								throw new Exception("$attributeVar El valor no puede estar vacío!", 1);
						break;						
						default:
							# code...
						break;
					}
				} else {
					switch ($key) {
						case 'min':
							print("\nValue=$value");
							if ($value < $limit)
								throw new Exception("$attributeVar: El valor no puede ser inferior a $limit!", 1);
						break;

						case 'date':	
							print("\nLimite....");
							foreach ($limit as $rule => $condition) {
								switch ($rule) {
									case 'min':
										if ($condition == 'today') {
											if((time()-(60*60*24)) > strtotime($value))
												throw new Exception("$attributeVar: La fecha no debe haber pasado!", 1);
										}	
									break;
									
									default:
										# code...
										break;
								}
							}
						break;
						
						default:
							# code...
							break;
					}
				}
			}
		}
	}
}
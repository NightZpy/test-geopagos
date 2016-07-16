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
		$this->validate();		
		return $this->runQuery($query);		
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
		$this->run(self::makeQuery('insert', $attributes));
	}

	public function update()
	{
		$attributes = $this->attributesToArray();
		$filter = [$this->primaryKey => $attributes[$this->primaryKey]];
		unset($attributes[$this->primaryKey]);
		$this->run(self::makeQuery('update', $attributes, $filter));
	}

	public function delete()
	{
		$attributes = $this->attributesToArray();
		$filter = [$this->primaryKey => $attributes[$this->primaryKey]];
		unset($attributes[$this->primaryKey]);
		$this->run(self::makeQuery('delete', null, $filter));
	}		

	public function select($fields = [], $filters = [], $order = [])
	{
		# code...
	}

	public function findByPk($value)
	{
		$query = 'SELECT * FROM ' . $this->tableName . ' WHERE ' . $this->primaryKey . '=' . $value . ' LIMIT 1';
		$this->attributes = $this->run($query);
		$this->makeAttributes($this->attributes);
	}

	public function findBy($field, $value)
	{
		$query = 'SELECT * FROM ' . $this->tableName . ' WHERE ' . $field . '=' . $value;
		$this->attributes = $this->run($query);
		if (!count($this->attributes)) 
			return false;
		$objets = [];
		foreach ($this->attributes as $attribute) {			
			$this->makeAttributes($this->attributes);
			$objets[] = $this;
		}
		return $objets;
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
				$keys 	= array_keys($attributes);
				if (!isset($attributes[$this->primaryKey]) || empty($attributes[$this->primaryKey]))
					$attributes[$this->primaryKey] = 'DEFAULT';

				foreach ($attributes as $key => $value) 
					if (!$value == 'DEFAULT')
						$attributes[$key] = "'" . $value . "'";	

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
		return $this->db->fetch($query)->fetchArray(SQLITE3_ASSOC);
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

	private function makeAttributes($attributes)
	{
		foreach ($attributes as $key => $value) {
			$attribute =  underscoreToCamelCase($key);
			$this->$attribute = $value;
		}
	}

	private function attributesToArray()
	{
		$attributes = [];	
		print("\nAttributes....................");
		print_r($this->attributes);
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
		print_r($this->rules);

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
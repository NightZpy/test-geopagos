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

	public static function delete($attributes = [], $filter = [])
	{
		if ( count($attributes) ) {
			$this->mapToValidAttributes($attributes);
			$this->run(self::makeQuery('delete', $attributes, $filter));
		}
		return $this;
	}		

	public function select($fields = [], $filters = [], $order = [])
	{
		# code...
	}

	public function findBy($field, $value)
	{
		$query = 'SELECT * FROM ' . $this->tableName . ' WHERE ' . $field . '=' . $value . ' LIMIT 1';
		$this->attributes = $this->run($query);
		$this->makeAttributes($this->attributes);
	}

	private function makeFilter($filters, $comparator = '=', $logic = 'AND')
	{
		$filter = '';
		$i = 0;
		foreach ($filters as $key => $value) {
			if ($i == count($filters)) {
				
			} else {

			}			
		}


			$filters[$key] = "'" . $value . "'";
		$filter = http_build_query ($filters, '', ' AND ');
		print("\nFiltesrs: $filter");
	}

	public function makeQuery($action, $attributes, $filters = null)
	{
		$query = null;
		$keys 	= array_keys($attributes);
		$filter = null;		
		if ($filters) {
			$filter = $this->makeFilter($filters);
		}	


		foreach ($attributes as $key => $value) 
			$attributes[$key] = "'" . $value . "'";

		switch ($action) {
			case 'insert':				
				$values = implode(', ', array_values($attributes));
				$query 	= str_replace('$table_name$', $this->tableName, self::INSERT_QUERY);
				$query 	= str_replace('$fields$', implode(', ', $keys), $query);
				$query 	= str_replace('$values$', $values, $query);				
			break;

			case 'update':
				$values = http_build_query	($attributes, '', ', ');
				$filters =
				$query 	= str_replace('$table_name$', $this->tableName, self::UPDATE_QUERY);
				$query 	= str_replace('$values$', $values, $query);
				if ($filters)
					$query .= ' ' . str_replace('$filter$', $filters, self::WHERE_QUERY);
			break;	

			case 'select':

			break;
			
			case 'delete':
				$query 	= str_replace('$table_name$', $this->tableName, self::DELETE_QUERY);
				if ($filters)
					$query .= ' ' . str_replace('$filter$', $filters, self::WHERE_QUERY);
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
			$attributeVar = underscoreToCamelCase($attribute);
			$attributes[$attribute] = $this->$attributeVar;
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

		foreach ($this->rules as $field => $rules) {
			
		}
	}
}
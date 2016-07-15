<?php
/**
* 
*/
class Connection
{
	private $db = null;

	function __construct($path = 'data/data.db')
	{
		$this->db = new SQLite3($path);	
	}

	public function fetch($query)
	{
		return $this->db->query($query);
	}
}
<?php

class Database
{
	public $Connection;

	function connect($hostname, $username, $password, $database)
	{
		$this->Connection = mysqli_connect($hostname, $username, $password, $database);
		if (!$this->Connection)
	  	{
	  		die('Could not connect: ' . mysql_error());
	  	}
		mysqli_select_db($this->Connection, $database);		
	}

	function ExecuteQuery($query)
	{
		$result = mysqli_query($this->Connection, $query);
		if (mysqli_num_rows($result) > 0) 
		{
			return mysqli_fetch_array($result);
		}
		else 
		{
			return NULL;
		}
	}

	function ExecuteListQuery($query)
	{
		$result = mysqli_query($this->Connection, $query);
		if (mysqli_num_rows($result) > 0) 
		{
			while($row = mysqli_fetch_array($result))
			{
    			$ListArray[] = $row;
			}
			return $ListArray;
		}
		else 
		{
			return NULL;
		}
	}

	function ExecuteInsert($insertSql, $responseObj)
	{
		if (mysqli_query($this->Connection, $insertSql) === TRUE) 
		{
			$responseObj->ObjectInsertID = mysqli_insert_id($this->Connection);
    		return TRUE;
		} 
		else 
		{
			$responseObj->ResponseMessage = mysqli_error($this->Connection);
    		return FALSE;
		}		
	}
}

?>
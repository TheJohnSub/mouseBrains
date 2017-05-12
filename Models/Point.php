<?php

class Point
{
	public $PointID = 0;
	public $MapID = 0;

	public $Name = '';
	public $Notes = '';

	public $XCoordinate = 0;
	public $YCoordinate = 0;

	public $DateCreated;

	public function CreateFromQuery($DBRow)
	{
		$this->PointID = $DBRow[0];
		$this->MapID = $DBRow[1];

		$this->Name = $DBRow[2];
		$this->Notes = $DBRow[3];

		$this->XCoordinate = $DBRow[4];
		$this->YCoordinate = $DBRow[5];
		
		$this->DateCreated = $DBRow[6];
	}

	public function CreateFromJson($jsonPoint)
	{
		$jsonPointObj = json_decode($jsonPoint);
		$this->MapID = $jsonPointObj->MapID;

		$this->Name = $jsonPointObj->Name;
		$this->Notes = str_replace(array("\r\n", "\r", "\n"), "<br />", $jsonPointObj->Notes); 

		$this->XCoordinate = $jsonPointObj->XCoordinate;
		$this->YCoordinate = $jsonPointObj->YCoordinate;
		
		$this->DateCreated = date('Y-m-d H:i:s');
	}

	public function toJSON()
	{
	    return json_encode($this);
	}

	function ValListStr($db)
	{
		$str = "'" . mysqli_real_escape_string($db->Connection, $this->MapID) . "', '" . mysqli_real_escape_string($db->Connection, $this->Name) . "', '" . mysqli_real_escape_string($db->Connection, $this->Notes) . "', '" . mysqli_real_escape_string($db->Connection, $this->XCoordinate) . "', '" . mysqli_real_escape_string($db->Connection, $this->YCoordinate) . "', '" . mysqli_real_escape_string($db->Connection, $this->DateCreated) . "'";
		return $str;
	}
}

?>
<?php

class Map
{
	public $MapID = 0;
	public $UserID = 0;

	public $Name = '';
	public $Description = '';

	public $MouseName = '';
	public $MouseStrain = '';
	public $BodyWeight = 0;
	public $DateOfBirth;

	public $BregmaX = 0;
	public $BregmaY = 0;
	public $BregmaZ = 0;

	public $LambdaX = 0;
	public $LambdaY = 0;
	public $LambdaZ = 0;

	public $MidlineX = 0;
	public $MidlineY = 0;
	public $MidlineZ = 0;

	public $Notes = '';
	public $DateCreated;

	public $Points;
	public $ChangeLog = '';


	function CreateFromQuery($DBRow)
	{
		$this->MapID = $DBRow[0];
		$this->UserID = $DBRow[1];

		$this->Name = $DBRow[2];
		$this->Description = $DBRow[3];
		$this->MouseName = $DBRow[4];
		$this->MouseStrain = $DBRow[5];
		$this->BodyWeight = $DBRow[6];
		$this->DateOfBirth = $DBRow[7];

		$this->BregmaX = $DBRow[8];
		$this->BregmaY = $DBRow[9];
		$this->BregmaZ = $DBRow[10];

		$this->LambdaX = $DBRow[11];
		$this->LambdaY = $DBRow[12];
		$this->LambdaZ = $DBRow[13];

		$this->MidlineX = $DBRow[14];
		$this->MidlineY = $DBRow[15];
		$this->MidlineZ = $DBRow[16];

		$this->Notes = $DBRow[17];

		$this->DateCreated = $DBRow[18];
	}

	function CreateFromJson($jsonMap)
	{
		$jsonMap = json_decode($jsonMap);

		$this->Name = $jsonMap->Name;
		$this->Description = str_replace(array("\r\n", "\r", "\n"), "<br />", $jsonMap->Description); 
		$this->MouseName = $jsonMap->MouseName;
		$this->MouseStrain = $jsonMap->MouseStrain;
		$this->BodyWeight = $jsonMap->BodyWeight;
		$this->DateOfBirth = $jsonMap->DateOfBirth;

		$this->BregmaX = $jsonMap->BregmaX;
		$this->BregmaY = $jsonMap->BregmaY;
		$this->BregmaZ = $jsonMap->BregmaZ;

		$this->LambdaX = $jsonMap->LambdaX;
		$this->LambdaY = $jsonMap->LambdaY;
		$this->LambdaZ = $jsonMap->LambdaZ;

		$this->MidlineX = $jsonMap->MidlineX;
		$this->MidlineY = $jsonMap->MidlineY;
		$this->MidlineZ = $jsonMap->MidlineZ;

		$this->DateCreated = date('Y-m-d H:i:s');
	}

	function ToLinkTableRow()
	{
		$LinkHTML = '<tr><td><a href="/mouseBrains/map/view/' . $this->MapID . '"">' . $this->MapID . '</a></td><td><a href="/mouseBrains/map/view/' . $this->MapID . '"">' . $this->Name . '</a></td><td>' . $this->MouseName . '</td><td>' . $this->DateCreated . '</td></tr>';
		return $LinkHTML;
	}

	function JSONSanitize()
	{
		$tmpValue = '';
       foreach ($this as $key => $value) {
       		if(!(is_array($this->$key)) && (isset($this->$key))) {
       			$tmpValue = str_replace('"', "&quot;", $value);
       			$tmpValue = str_replace("'", "&#39;", $tmpValue);
       			$this->$key = $tmpValue;
       		}
       }
	}

	function toJSON()
	{
	    return htmlspecialchars(json_encode($this));
	}

	function ValListStr($db)
	{
		$str = "'" . mysqli_real_escape_string($db->Connection, $this->UserID) . "', '" . mysqli_real_escape_string($db->Connection, $this->Name) . "', '" . mysqli_real_escape_string($db->Connection, $this->Description) . "', '" . mysqli_real_escape_string($db->Connection, $this->MouseName) . "', '" . mysqli_real_escape_string($db->Connection, $this->MouseStrain) . "', '" . mysqli_real_escape_string($db->Connection, $this->BodyWeight) . "', '" . mysqli_real_escape_string($db->Connection, $this->DateOfBirth) . "', '" . mysqli_real_escape_string($db->Connection, $this->BregmaX)  . "', '" . mysqli_real_escape_string($db->Connection, $this->BregmaY) . "', '" . mysqli_real_escape_string($db->Connection, $this->BregmaZ) . "', '" . mysqli_real_escape_string($db->Connection, $this->LambdaX) . "', '" . mysqli_real_escape_string($db->Connection, $this->LambdaY) . "', '" . mysqli_real_escape_string($db->Connection, $this->LambdaZ) . "', '" . mysqli_real_escape_string($db->Connection, $this->MidlineX) . "', '" . mysqli_real_escape_string($db->Connection, $this->MidlineY) . "', '" . mysqli_real_escape_string($db->Connection, $this->MidlineZ) . "', '" . mysqli_real_escape_string($db->Connection, $this->Notes) . "', '" . mysqli_real_escape_string($db->Connection, $this->DateCreated) . "'";
		return $str;
	}
}

?>
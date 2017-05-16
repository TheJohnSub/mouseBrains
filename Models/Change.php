<?php

class Change
{
	public $ChangeID = 0;
	public $MapID = 0;
	public $UserID = 0;
	public $ChangeDate;
	public $ChangeType = 0;
	public $OriginalValue = '';
	public $NewValue = '';
	public $IsPublicChange = 'TRUE';
	public $ChangeLog = '';


	function CreateFromQuery($DBRow)
	{
		$this->ChangeID = $DBRow[0];
		$this->MapID = $DBRow[1];
		$this->UserID = $DBRow[2];

		$this->ChangeDate = $DBRow[3];
		$this->ChangeType = $DBRow[4];
		$this->OriginalValue = $DBRow[5];
		$this->NewValue = $DBRow[6];
		$this->IsPublicChange = $DBRow[7];
		$this->ChangeLog = $DBRow[8];
	}

	function CreateCoordinateUpdateChange($oldMapObj, $newMapObj, $CoorType)
	{
		$newXYZ = '';
		$oldXYZ = '';

		if ($CoorType == 'Bregma') {
			$oldXYZ = '(' . $oldMapObj->BregmaX . ', ' . $oldMapObj->BregmaY . ', ' . $oldMapObj->BregmaZ . ')';
			$newXYZ = '(' . $newMapObj->BregmaX . ', ' . $newMapObj->BregmaY . ', ' . $newMapObj->BregmaZ . ')';
		} 
		else if ($CoorType == 'Lambda') {
			$oldXYZ = '(' . $oldMapObj->LambdaX . ', ' . $oldMapObj->LambdaY . ', ' . $oldMapObj->LambdaZ . ')';
			$newXYZ = '(' . $newMapObj->LambdaX . ', ' . $newMapObj->LambdaY . ', ' . $newMapObj->LambdaZ . ')';
		} 
		else if ($CoorType == 'Midline') {
			$oldXYZ = '(' . $oldMapObj->MidlineX . ', ' . $oldMapObj->MidlineY . ', ' . $oldMapObj->MidlineZ . ')';
			$newXYZ = '(' . $newMapObj->MidlineX . ', ' . $newMapObj->MidlineY . ', ' . $newMapObj->MidlineZ . ')';
		} 

		$this->MapID = $newMapObj->MapID;
		$this->ChangeType = 1;
		$this->OriginalValue = $CoorType . ': ' . $oldXYZ;
		$this->NewValue = $CoorType . ': ' . $newXYZ;
		$this->ChangeDate = date('Y-m-d H:i:s');
		$this->ChangeLog = '<b>' . $this->ChangeDate . '</b> ' . $CoorType . ' values were updated. <br/> Old values: ' . $oldXYZ . '. New values: ' . $newXYZ . '. <br/><br/>';
	}

	function ValListStr($db)
	{
		$str = "'" . mysqli_real_escape_string($db->Connection, $this->MapID) . "', '" . mysqli_real_escape_string($db->Connection, $this->UserID) . "', '" . mysqli_real_escape_string($db->Connection, $this->ChangeDate) . "', '" . mysqli_real_escape_string($db->Connection, $this->ChangeType) . "', '" . mysqli_real_escape_string($db->Connection, $this->OriginalValue) . "', '" . mysqli_real_escape_string($db->Connection, $this->NewValue) . "', '" . mysqli_real_escape_string($db->Connection, $this->IsPublicChange) . "', '" . mysqli_real_escape_string($db->Connection, $this->ChangeLog) . "'";
		return $str;
	}
}

?>
<?php

class PointRoute
{
	function GenerateResponse($db, $pathArr, $postData)
	{
		if ($pathArr[ARRAY_START+2] == "view")
		{
			$point = $this->GetPointById($pathArr[ARRAY_START+3], $db);
			if (is_null($point))
			{
				$response = new Response(404, "Point not found.", NULL);
			}
			else 
			{
				$point = JSONSanitize($point);
				$response = new Response(200, "Found point.", $point);
			}
			return $response->toJSON();
		}

		if ($pathArr[ARRAY_START+2] == "add")
		{
			header('Content-type: application/json');
			$response = new Response(0, "", NULL);
			$pointJson = $postData;
			$point = new Point();
			$point->CreateFromJson($pointJson);
			if ($this->InsertPoint($db, $point, $response) == TRUE)
			{
				$response->ResponseObject = $point;
				$response->ResponseCode = 200;
				$response->ResponseMessage = "A point was successfully inserted.";
				$response->ResponseObject->PointID = $response->ObjectInsertID;		
			}
			else 
			{
				$response->ResponceCode = 500;
			}
			return $response->toJSON();
		}
	}

	function GetPoints($db)
	{
		$sql = 'SELECT * FROM points';
		$point = new Point($db->ExecuteQuery($sql));
		return $point;
	}

	function GetPointById($id, $db)
	{
		$sql = 'SELECT * FROM points WHERE PointID = ' . $id;
		$queryResult = $db->ExecuteQuery($sql);
		if (is_null($queryResult))
		{
			return NULL;
		}		
		else
		{
			$pointObj = new Point();
			$pointObj->CreateFromQuery($queryResult);
			return $pointObj;
		}
	}

	function InsertPoint($db, $pointObj, $responseObj)
	{
		$objAsStr = $pointObj->ValListStr($db);
		$sql = 'INSERT INTO points (MapID, Name, Notes, XCoordinate, YCoordinate, DateCreated) VALUES (' . $objAsStr . ')';	

		$result = '';
		
		if ($db->ExecuteInsert($sql, $responseObj) === TRUE) {
			$pointObj->PointID = $responseObj->ObjectInsertID;
    		$result = TRUE;
		} else {
    		$result =  FALSE;
		}
		return $result;
	}
}

?>
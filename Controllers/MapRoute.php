<?php

class MapRoute
{
	function GenerateResponse($db, $pathArr, $postData)
	{
		if ($pathArr[ARRAY_START+1] == "view")
		{
			$map = $this->GetMapById($pathArr[ARRAY_START+2], $db);
			$HTMLContent = "";
			if (is_null($map))
			{
				$response = new Response(404, "Map not found.", NULL);
			}
			else 
			{
				$map->JSONSanitize();
				$response = new Response(200, "Found map.", $map);
			}
			$HTMLContent = file_get_contents('./Views/view.html');	
			$HTMLContent = str_replace("<!--MAP_JSON_DATA-->", $response->toJSON(), $HTMLContent);
			return $HTMLContent;
		}

		else if ($pathArr[ARRAY_START+1] == "edit")
		{
			$HTMLContent = "";
			$map = $this->GetMapById($pathArr[ARRAY_START+2], $db);
			if (is_null($map))
			{
				$response = new Response(404, "Map not found.", NULL);
			}
			else 
			{
				$map->JSONSanitize();
				$response = new Response(200, "Found map.", $map);
			}
			$HTMLContent = file_get_contents('./Views/newmap.html');	
			$HTMLContent = str_replace("<!--MAP_JSON_DATA-->", $response->toJSON(), $HTMLContent);
			return $HTMLContent;
		}


		else if ($pathArr[ARRAY_START+1] == "create")
		{
			$HTMLContent = file_get_contents('./Views/createmap.html');	
			return $HTMLContent;
		}

		else if ($pathArr[ARRAY_START+1] == "save")
		{
			$response = new Response(0, "", NULL);
			$postData = json_decode($postData);
			if ($postData->Notes == "" || $this->SaveMap($db, $postData->MapID, $postData->Notes, $response) == TRUE)
			{		
				$this->redirect('/mouseBrains/map/view/' . $postData->MapID);			
			}
			else 
			{
				$response->ResponceCode = 500;
			}
			return $response->toJSON();
		}

		else if ($pathArr[ARRAY_START+1] == "new")
		{
			$response = new Response(0, "", NULL);
			$HTMLContent = '';
			$map = new Map();
			$map->CreateFromJson($postData);
			if ($this->InsertMap($db, $map, $response) == TRUE)
			{
				$map->JSONSanitize();
				$response->ResponseObject = $map;
				$response->ResponseCode = 200;
				$response->ResponseMessage = "A map was successfully inserted.";
			}
			else 
			{
				$response->ResponceCode = 500;
			}
			$HTMLContent = file_get_contents('./Views/newmap.html');	
			$HTMLContent = str_replace("<!--MAP_JSON_DATA-->", $response->toJSON(), $HTMLContent);
			return $HTMLContent;
		}

		else if ($pathArr[ARRAY_START+1] == "coorup")
		{
			header('Content-type: application/json');
			$response = new Response(0, "", NULL);
			
			$requestObj = json_decode($postData);
			$oldMapObj = $this->GetMapById($requestObj->MapID, $db);
			$CoorType = $requestObj->CoorType;
			if ($this->UpdateMapCoordinate($db, $requestObj, $response) == TRUE)
			{
				$newMapObj = $this->GetMapById($requestObj->MapID, $db);
				$changeObj = new Change();
				$changeObj->CreateCoordinateUpdateChange($oldMapObj, $newMapObj, $requestObj->CoorType);

				$this->InsertChange($db, $changeObj, $response);
				$xyzResp = new XYZResp($newMapObj->{$CoorType . 'X'}, $newMapObj->{$CoorType . 'Y'}, $newMapObj->{$CoorType . 'Z'});

				$response->ResponseObject = $xyzResp;
				$response->ResponseCode = 200;
				$response->ResponseMessage = "Coordinates saved.";
			}
			else 
			{
				$response->ResponceCode = 500;
			}
			return $response->toJSON();
		}

		else if ($pathArr[ARRAY_START+1] == "point")
		{
			$pointRoute = new PointRoute();
			return $pointRoute->GenerateResponse($db, $pathArr, $postData);
		}
	}

	function GenerateIndex($db)
	{
		$linkTableHTML = $this->GetMaps($db);
		$HTMLContent = file_get_contents('./Views/index.html');	
		$HTMLContent = str_replace("<!--MAP_LINK_TABLE-->", $linkTableHTML, $HTMLContent);
		
		return $HTMLContent;	
	}

	function GetMaps($db)
	{
		$sql = 'SELECT * FROM maps';
		$queryResult = $db->ExecuteListQuery($sql);
		$linkTableHTML = '';
		foreach($queryResult as $map) {
			$newMap = new Map();
			$newMap->CreateFromQuery($map);
			$newMap->JSONSanitize();
			$linkTableHTML = $linkTableHTML . $newMap->ToLinkTableRow();
		}		
		return $linkTableHTML;
	}

	function GetMapById($id, $db)
	{
		$sql = 'SELECT * FROM maps WHERE MapID = ' . $id;
		$queryResult = $db->ExecuteQuery($sql);
		$mapObj = new Map();
		if (is_null($queryResult))
		{
			return NULL;
		}
		else
		$mapObj->CreateFromQuery($queryResult);
		$sql = 'SELECT * FROM points WHERE MapID = ' . $id;
		$queryResult = $db->ExecuteListQuery($sql);
		if (is_null($queryResult))
		{
			return $mapObj;
		}
		$mapObj->Points = array();
		foreach($queryResult as $point) {

			$newPoint = new Point();
			$newPoint->CreateFromQuery($point);
			JSONSanitize($newPoint);
			$mapObj->Points[] = $newPoint;
		}
		return $mapObj;
	}

	function InsertMap($db, $mapObj, $responseObj)
	{
		$objAsStr = $mapObj->ValListStr($db);
		$sql = 'INSERT INTO maps (UserID, Name, Description, MouseName, MouseStrain, BodyWeight, DateOfBirth, BregmaX, BregmaY, BregmaZ, LambdaX, LambdaY, LambdaZ, MidlineX, MidlineY, MidlineZ, Notes, DateCreated) VALUES (' . $objAsStr . ')';
		$result = '';
		if ($db->ExecuteInsert($sql, $responseObj) === TRUE) {
			$mapObj->MapID = $responseObj->ObjectInsertID;
    		$result = "New record created successfully.";
		} else {
    		$result =  "Error inserting record.";
		}
		return $result;
	}

	function SaveMap($db, $mapId, $mapNotes, $responseObj)
	{
		$mapNotes = str_replace(array("\r\n", "\r", "\n"), "<br />", $mapNotes); 
		$newNotesData =  "<b>" . date('Y-m-d H:i:s') . "</b><br/>" . mysqli_real_escape_string($db->Connection, $mapNotes) . "<br/>" . "<br/>";
		$sql = "UPDATE maps SET Notes = Concat(Notes, '" . $newNotesData . "')  WHERE MapID = " . mysqli_real_escape_string($db->Connection, $mapId); 		
		//$sql = "UPDATE maps SET Notes = '" . mysqli_real_escape_string($db->Connection, $mapNotes) . "'  WHERE MapID = " . mysqli_real_escape_string($db->Connection, $mapId); 	
		$result = '';
		if ($db->ExecuteInsert($sql, $responseObj) === TRUE) {
    		$result = "New record created successfully.";
		} else {
    		$result =  "Error inserting record.";
		}
		return $result;		
	}

	function UpdateMapCoordinate($db, $requestObj, $responseObj)
	{
		$sql = 		   "UPDATE maps ";
		$sql =  $sql . "SET " . $requestObj->CoorType . "X = '" . $requestObj->X . "', ";
		$sql =  $sql . $requestObj->CoorType . "Y = '" . $requestObj->Y . "', ";
		$sql =  $sql . $requestObj->CoorType . "Z = '" . $requestObj->Z . "'";
		$sql =  $sql . "WHERE MapID = " . mysqli_real_escape_string($db->Connection, $requestObj->MapID);

		if ($db->ExecuteInsert($sql, $responseObj) === TRUE) {
    		$result = TRUE;
		} else {
    		$result =  FALSE;
		}
		return $result;			
	}

	function redirect($url, $statusCode = 303)
	{
   		header('Location: ' . $url, true, $statusCode);
   		die();
	}

	function InsertChange($db, $changeObj, $responseObj)
	{
		$objAsStr = $changeObj->ValListStr($db);
		$sql = 'INSERT INTO changes(MapID, UserID, ChangeDate, ChangeType, OriginalValue, NewValue, IsPublicChange, ChangeLog) VALUES (' . $objAsStr . ')';
		$result = '';
		if ($db->ExecuteInsert($sql, $responseObj) === TRUE) {
    		$result = "New record created successfully.";
		} else {
    		$result =  "Error inserting record.";
		}
		return $result;	
	}
}

?>
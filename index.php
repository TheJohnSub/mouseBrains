<?php

include_once('DB.php');
include_once('Response.php');
include_once('app-config.php');
include_once('Models/Map.php');
include_once('Models/Point.php');
include_once('Controllers/MapRoute.php');
include_once('Controllers/PointRoute.php');

//comment

$pathDetail = strtok($_SERVER["REQUEST_URI"],'?');
$pathArr = explode('/',$pathDetail);
$pathArr[ARRAY_START] = strtolower($pathArr[ARRAY_START]);

$db = new Database();
$db->Connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$HTMLContent = '';
$postData = '';

if($_SERVER['REQUEST_METHOD'] == 'POST')
{
	if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') 
	{
    	$postData = file_get_contents('php://input');
	}
	else 
	{
		$postData = json_encode($_POST);
	}
}

if ($pathArr[ARRAY_START] == "map")
{
	$mapRoute = new MapRoute();
	$HTMLContent = $mapRoute->GenerateResponse($db, $pathArr, $postData);
}

else if ($pathArr[ARRAY_START] == "")
{
	$mapRoute = new MapRoute();
	$HTMLContent = $mapRoute->GenerateIndex($db);
}

print $HTMLContent;

function JSONSanitize($object)
{
	$tmpValue = '';
   foreach ($object as $key => $value) {
		if(!(is_array($object->$key)) && (isset($object->$key))) {
			$tmpValue = str_replace('"', "&quot;", $value);
       		$tmpValue = str_replace("'", "&#39;", $tmpValue);
           	$object->$key = $tmpValue;
		}
	}	

   return $object;
}

?>
<?php

include_once('../DB.php');
include_once('../app-config.php');
include_once('../Models/Map.php');
include_once('../Controllers/MapRoute.php');

$db = new Database();
$db->Connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$mapRoute = new MapRoute();
$map = $mapRoute->GetMaps($db);
print($map->toJSON());

?>
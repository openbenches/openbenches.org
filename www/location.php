<?php
require_once ("config.php");
require_once ("functions.php");

$location = $params[2];
$latlng = get_location_from_name($location);

header("Location: /#{$latlng}/13",TRUE,307);

die();

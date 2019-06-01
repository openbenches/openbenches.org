<?php
//	Remove old routing
$query = str_replace("q=data.json/", "", $_SERVER['QUERY_STRING']);
header("Location: /api/v1.0/data.json/?".$query);
die();

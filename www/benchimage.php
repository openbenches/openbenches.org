<?php
require_once ('config.php');
require_once ('mysql.php');

$benchID = $params[2];
echo get_image($benchID);

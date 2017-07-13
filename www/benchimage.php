<?php
require_once ('config.php');
require_once ('mysql.php');

$benchID = $_GET["benchID"];
echo get_image($benchID);

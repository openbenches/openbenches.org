<?php
session_start();
require_once ("config.php");
require_once ("mysql.php");
require_once ("functions.php");
include("header.php");

//	Is an admin using this?
[$user_provider, $user_providerID, $user_name] = get_user_details(true);

//	Hardcoded for @edent
if ("twitter" == $user_provider && 14054507 == $user_providerID)
{
	$originalID  = $_GET["originalID"];
	$duplicateID = $_GET["duplicateID"];
	merge_benches($originalID, $duplicateID);
	
	echo "Redirected <a href='https://openbenches.org/bench/{$duplicateID}'>{$duplicateID}</a>";

} else {
	die();
} 

include("footer.php");
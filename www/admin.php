<?php
require_once ("config.php");
require_once ("mysql.php");
require_once ("functions.php");

require_once ('codebird.php');
$twitter = twitter_login();

//	Get the user's ID & name
$id_str =      $twitter["id_str"];
$screen_name = $twitter["screen_name"];

//	Is the user an approved admin? Hardcoded for now!
if ("edent" == $screen_name or "summerbeth" == $screen_name) {
	//	Start the admin page
	include("header.php");
	echo get_admin_list();
	include("footer.php");
} else {
	die();
}

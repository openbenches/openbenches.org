<?php
if(!isset($_SESSION)) { session_start(); }
require_once ("config.php");
require_once ("mysql.php");
require_once ("functions.php");

//	Is an admin using this?
if (!is_admin_user()) {
    http_response_code(403);
    die();
}

$page_title = "- Merge";
include("header.php");

//	Has anything been POSTed?
$originalID  = $_POST["originalID"];
$duplicateID = $_POST["duplicateID"];
if ($originalID != null && $duplicateID != null)
{
	merge_benches($originalID, $duplicateID);
	echo "Redirected <a href='https://{$_SERVER['HTTP_HOST']}/bench/{$duplicateID}'>{$duplicateID}</a><br>";
}
?>
<form action="merge.php" method="post" autocomplete="off">
	Original:  <input type="text" name="originalID" autofocus><br>
	Duplicate: <input type="text" name="duplicateID"><br>
	<input type="submit" value="Merge Benches">
</form>

<?php
include("footer.php");
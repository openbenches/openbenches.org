<?php
if(!isset($_SESSION)) { session_start(); }
require_once ("config.php");
require_once ("mysql.php");
require_once ("functions.php");

//	Start the normal page
include("header.php");

$error_message = "";
$resultsHTML = "";

$results = get_duplicates_results();
$resultsHTML .= "<h2>Possible Duplicates</h2>";
$resultsHTML .= "<p>List of benches with identical Soundex</p>";
$resultsHTML .= "<div id=\"search-results\">
<ol>";
foreach ($results as $key => $value) {
	$resultsHTML .= "<li><a href='/search/?soundex={$key}'>{$value}</a><hr></li>";
}
$resultsHTML .="</ol>";

$resultsHTML .="</div>";

?>
	</hgroup>
	<?php
		echo $resultsHTML;
		include("searchform.php");
	?>
<?php
	include("footer.php");

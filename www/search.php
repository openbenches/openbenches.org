<?php
session_start();
require_once ("config.php");
require_once ("mysql.php");
require_once ("functions.php");

//	Start the normal page
include("header.php");

//	Get the search query, or recover in case of error
$query = $_GET['search'];
$error_message = "";
$resultsHTML = "";

//	Has a photo been posted?
if (null != $query)
{
	$results = get_search_results($query);

	$resultsHTML = "<ul>";
	foreach ($results as $key => $value) {
		$resultsHTML .= "<li><a href='/bench/{$key}'>{$value}</a></li>";
	}
	$resultsHTML .="</ul>";

}

if ("<ul></ul>" == $resultsHTML) {
	$error_message = "No results found";
}

?>
	<br>
	<form action="/search/" enctype="multipart/form-data" method="get">
		<?php
			echo $error_message;
		?>
		<h2>Search for an inscription</h2>
		<div>
			<input type="search" id="inscription" name="search" value="<?php echo htmlspecialchars($query); ?>">
			<input type="submit" value="Search inscriptions" />
		</div>
	</form>
	<div>
		<?php echo $resultsHTML; ?>
	</div>
<?php
	include("footer.php");

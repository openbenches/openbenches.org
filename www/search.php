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
		$thumb = get_image_thumb($key);
		$thumb_width = IMAGE_THUMB_SIZE;
		$thumb_html = "<img src=\"{$thumb}\" class=\"search-thumb\" width=\"{$thumb_width}\">";
		$resultsHTML .= "<li><a href='/bench/{$key}'>{$thumb_html}{$value}</a></li>";
	}
	$resultsHTML .="</ul>";

}

if ("<ul></ul>" == $resultsHTML) {
	$error_message = "No results found";
}

?>
	<br>
	<div id="search-results">
		<?php echo $resultsHTML; ?>
	</div>
	<form action="/search/" enctype="multipart/form-data" method="get">
		<?php
			echo $error_message;
		?>
		<h2>Search for an inscription</h2>
		<div>
			<input type="search" id="inscription" name="search" class="search" value="<?php echo htmlspecialchars($query); ?>">
			<input type="submit" class="hand-drawn" value="Search inscriptions" />
		</div>
	</form>
<?php
	include("footer.php");

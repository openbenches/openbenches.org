<?php
if(!isset($_SESSION)) { session_start(); }
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

	if (0 == count($results)){
		$resultsHTML = "";
		$error_message = "No results found";
	}
	else {
		$resultsHTML = "<div id=\"search-results\"><ul>";
		foreach ($results as $key => $value) {
			$thumb = get_image_thumb($key);
			$thumb_width = IMAGE_THUMB_SIZE;
			$thumb_html = "<img src=\"{$thumb}\" class=\"search-thumb\" width=\"{$thumb_width}\" alt=\"\">";
			$resultsHTML .= "<li><a href='/bench/{$key}'>{$thumb_html}{$value}</a></li>";
		}
		$resultsHTML .="</ul></div>";
	}
}

?>
	</hgroup>
	<?php echo $resultsHTML; ?>
	<form action="/search/" enctype="multipart/form-data" method="get">
		<?php
			echo $error_message;
		?>
		<h2>Search for an inscription</h2>
		<div>
			<input type="search" id="inscription" name="search" class="search" value="<?php echo htmlspecialchars($query); ?>">
			<input type="submit" class="button buttonColour" value="Search inscriptions" />
		</div>
	</form>
<?php
	include("footer.php");

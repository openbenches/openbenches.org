<?php
if(!isset($_SESSION)) { session_start(); }
require_once ("config.php");
require_once ("mysql.php");
require_once ("functions.php");

//	Start the normal page
include("header.php");

//	Get the search query, or recover in case of error
if(isset($_GET['search'])){
	$query = $_GET['search'];
} else {
	$query = "";
}
$query_encoded = urlencode($query);

if(isset($_GET['page'])){
	$page = (int)$_GET['page'];
} else {
	$page = 0;
}

if(isset($_GET['count'])){
	$count = (int)$_GET['count'];
} else {
	$count = 20;
}

$error_message = "";
$resultsHTML = "";

//	Has a search been requested?
if (null != $query)
{
	$results = get_search_results($query, $page, $count);
	$total_results = get_search_count($query);

	if (0 == count($results)){
		$resultsHTML = "";
		$error_message = "No results found";
	}
	else {
		$first = ($count * $page)+1;
		$last  = ($count * ($page+1));

		$resultsHTML = "<div id=\"search-results\">
			<h2>Total benches found: {$total_results}.</h2>
		<ol start='{$first}'>";
		foreach ($results as $key => $value) {
			$thumb = get_image_thumb($key);
			$thumb_width = IMAGE_THUMB_SIZE;
			$thumb_html = "<img src=\"{$thumb}\" class=\"search-thumb\" width=\"{$thumb_width}\" alt=\"\">";
			$resultsHTML .= "<li><a href='/bench/{$key}'>{$thumb_html}{$value}</a><hr></li>";
		}
		$resultsHTML .="</ol></div></div>";
	}

	$resultsHTML .= "<div id=\"pagination\">";

	if ($page > 0) {
		$previous = $page - 1;
		$resultsHTML .= "<a href='/search/?search={$query_encoded}&page={$previous}' class='button buttonColour'><strong>⬅️</strong> Previous Results</a>&emsp;&emsp;";
	}
	if ( ($count * ($page+1)) < $total_results) {
		$next = $page + 1;
		$resultsHTML .= "<a href='/search/?search={$query_encoded}&page={$next}'     class='button buttonColour'>More Results <strong>➡️</strong></a>";
	}
	$resultsHTML .="</div>";
}

?>
	</hgroup>
	<?php
		echo $resultsHTML;
		include("searchform.php");
	?>
<?php
	include("footer.php");

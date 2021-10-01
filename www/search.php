<?php
if(!isset($_SESSION)) { session_start(); }
require_once ("config.php");
require_once ("mysql.php");
require_once ("functions.php");

//	Start the normal page
include("header.php");

$soundex = false;

//	Get the search query, or recover in case of error
if(isset($_GET['search'])){
	$query = $_GET['search'];
} else if(isset($_GET['soundex'])){
	$query = $_GET['soundex'];
	$soundex = true;
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
	$results = get_search_results($query, $page, $count, $soundex);
	$total_results = get_search_count($query);

	if (0 == count($results)){
		$resultsHTML = "";
		$error_message = "No results found";
	}
	else {
		$first = ($count * $page)+1;
		$last  = ($count * ($page+1));

		include("searchform.php");

		$resultsHTML = "<div id=\"search-results\">";
		if ($soundex == false){
			$resultsHTML .= "<h2>Total benches found: {$total_results}.</h2>";
		}
		$resultsHTML .= "<ul class=\"searchResults\" start='{$first}'>";
		$currentNumber = $first;
		foreach ($results as $key => $value) {
			$thumb = get_image_thumb($key);
			$thumb_width = IMAGE_THUMB_SIZE;
			$thumb_html = "<img src=\"{$thumb}\" class=\"search-thumb\" width=\"{$thumb_width}\" alt=\"\">";
			if (strlen($value)>300){
				$value = substr($value, 0, 300)."...";
			}
			$resultsHTML .= "<li><div class=\"number\">".$currentNumber.".</div><a href='/bench/{$key}'>{$thumb_html}<div class=\"text\">{$value}</div></a></li>";
			$currentNumber++;
		}
		$resultsHTML .="</ul></div></div>";
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
	<?php
		echo $resultsHTML;
	?>
<?php
	include("footer.php");

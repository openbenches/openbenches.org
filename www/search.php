<?php
if(!isset($_SESSION)) { session_start(); }
require_once ("config.php");
require_once ("mysql.php");
require_once ("functions.php");

//	Start the normal page
$page_title = "- Search";
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
		$error_message = "<h2>No results found</h2>";
	}
	else {
		$first = ($count * $page)+1;
		$last  = ($count * ($page+1));

		$resultsHTML = "<div id=\"search-results\">";
		if ($soundex == false){
			$resultsHTML .= "<h2>Total benches found: {$total_results}.</h2>";
		}
		$resultsHTML .= "<ol class=\"searchResults\" start='{$first}'>";
		$currentNumber = $first;
		foreach ($results as $key => $value) {
			$thumb = get_image_thumb($key);
			$thumb_width = IMAGE_THUMB_SIZE;
			$thumb_html = "<img src=\"{$thumb}\" class=\"search-thumb\" width=\"{$thumb_width}\" alt=\"\" loading=\"lazy\" />";
			if (mb_strlen($value)>300){
				$value = mb_substr($value, 0, 300)."...";
			}
			$resultsHTML .= "<li><div class=\"number\">".$currentNumber.".</div><a href='/bench/{$key}'>{$thumb_html}<div class=\"text\">{$value}</div></a></li>";
			$currentNumber++;
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
		$resultsHTML .= "<a href='/search/?search={$query_encoded}&page={$next}'     class='button buttonColour'>More Results <strong>➡️</strong></a><br>";
	}
	$resultsHTML .= "<a href='/api/v1.0/data.json/?truncated=false&format=raw&media=true&search={$query_encoded}'     class='button buttonColour'><strong>💾</strong> Download GeoJSON</a>";
	$resultsHTML .="</div>";
}

//	Show merge button if only two soundexes
//	Is an admin using this?
[$user_provider, $user_providerID, $user_name] = get_user_details(true);

//	Hardcoded for @edent
if ("twitter" == $user_provider && 14054507 == $user_providerID)
{
	if ( $soundex && ( 2 == count($results)) ) {
		$originalID  = array_keys($results)[0];
		$duplicateID = array_keys($results)[1];
		
		$resultsHTML .= '<form action="/merge.php" method="post" autocomplete="off">
		Original:  <input type="text" name="originalID"  value="' . $originalID  . '"><br>
		Duplicate: <input type="text" name="duplicateID" value="' . $duplicateID . '"><br>
		<input type="submit" value="Merge Two Benches">
		</form>';
	}	
}

echo $resultsHTML;

include("searchform.php");

include("footer.php");

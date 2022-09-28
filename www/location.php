<?php
require_once ("config.php");
require_once ("functions.php");

//	Comes in as /location/uk/england/derby
//	Drop the 1st parameter and reverse the list
$locations = array_reverse( array_slice( $params, 2, null, true) );

$location_string = "";
foreach ($locations as $location) {
	$location_string .= $location . ", ";
}
$location_string  = rtrim($location_string , ", ");

$locations = array_slice( $params, 2, null, true);
$location_html = "🗺 ➡️ ";
$location_link = "/location/";
foreach ($locations as $location) {
	if (null != $location) {
		$location_link .= "/" . urldecode($location);
		$location_html .= "<a href=\"$location_link\">" . htmlspecialchars( urldecode($location) ). "</a> ➡️ ";
	}
}
$location_html = rtrim($location_html, " ➡️ ");

list($lat_ne, $lng_ne, $lat_sw, $lng_sw, $lat, $lng) = get_bounding_box_from_name($location_string);

$page_title = " - " . htmlspecialchars( urldecode($location_string) );

include("header.php");

//	/user/1234?page=2&count=5
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

$page_html = "Benches in $location_html";

$results = get_bounding_box_benches_list($lat_ne, $lng_ne, $lat_sw, $lng_sw, $page, $count);

$total_results = get_bounding_box_benches_count($lat_ne, $lng_ne, $lat_sw, $lng_sw);

if (0 == count($results)){
	$resultsHTML = "<h2>No results found</h2>";
	// $resultsHTML .= "$lat_ne, $lng_ne, $lat_sw, $lng_sw";
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
		$thumb_html = "<img src=\"{$thumb}\" class=\"search-thumb\" width=\"{$thumb_width}\">";
		$resultsHTML .= "<li><a href='/bench/{$key}'>{$thumb_html}{$value}</a><hr></li>";
	}
	$resultsHTML .="</ol></div>";
}

$resultsHTML .= "</div><div id=\"pagination\">";
if ($page > 0) {
	$previous = $page - 1;
	$resultsHTML .= "<a href='{$thisURL}?page={$previous}' class='button buttonColour'><strong>⬅️</strong> Previous Results</a>&emsp;&emsp;";
}
if ( ($count * ($page+1)) < $total_results) {
	$next = $page + 1;
	$resultsHTML .= "<a href='{$thisURL}?page={$next}'     class='button buttonColour'>More Results <strong>➡️</strong></a>";
}
?>

<?php
echo "<h3>{$page_html}</h3>";
?>

<div>
	<div id='map'></div>
	<div id='benchImage' ></div>
</div>
<br>

<div id="search-results">

<?php
	echo $resultsHTML;
?>
</div>

<script src="/api/v1.0/data.json/?truncated=true"></script>

<?php echo get_map_javascript($lat, $lng, "10"); ?>

<script>
// map.on("moveend", function () {
// 	var stateObj = { x: "y" };
// 	history.pushState(stateObj, "Open Benches", "/#" +
// 		map.getCenter().lat.toPrecision(7) + "/" +
// 		map.getCenter().lng.toPrecision(7) + "/" +
// 		map.getZoom().toString()
// 	);
// });

markers.on('click', function (bench) {
	var xhr = typeof XMLHttpRequest != 'undefined' ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
	xhr.open('get', '/benchimage/'+bench.layer["options"]["benchID"], true);
	xhr.onreadystatechange = function() {
		if (xhr.readyState == 4 && xhr.status == 200) {
			document.getElementById("benchImage").innerHTML = xhr.responseText;
		}
	}
	xhr.send();
});

for (var i = 0; i < benches.features.length; i++) {
	var bench = benches.features[i];
	var lat = bench.geometry.coordinates[1];
	var longt = bench.geometry.coordinates[0];
	var benchID = bench.id;
	var title = bench.properties.popupContent + "<br><a href='/bench/"+bench.id+"/'>View details</a>";

	// console.log('bench ' + benchID);
	var marker = L.marker(new L.LatLng(lat, longt), {  benchID: benchID });

	marker.bindPopup(title);
	markers.addLayer(marker);
}

map.addLayer(markers);
</script>

<?php
include("footer.php");
<?php
	include("header.php");

	//	/tag/cat

	$tag = $params[2];
	$thisURL = "/tag/" . urlencode($tag);

	//	/tag/cat?page=2&count=5
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

	$userHTML = "Benches tagged with \"" . htmlspecialchars(urldecode($tag)) . "\"";

	$results = get_benches_from_tag_text($tag, $page, $count);
	$total_results = get_bench_tag_count($tag);


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
			$thumb_html = "<img src=\"{$thumb}\" class=\"search-thumb\" width=\"{$thumb_width}\" loading=\"lazy\" />";
			$resultsHTML .= "<li><a href='/bench/{$key}'>{$thumb_html}{$value}</a><hr></li>";
		}
		$resultsHTML .="</ol></div></div>";
	}

	$resultsHTML .= "<div id=\"pagination\">";
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
	echo "<h3>{$userHTML}</h3>";
?>
<div id="map"></div>
<div id="benchImage"></div>

<div id="search-results">

	<?php
		echo $resultsHTML;
	?>
</div>

<script src="/api/v1.0/data.json/?tagText=<?php echo urlencode($tag); ?>" type="text/javascript"></script>

<?php
	//	Map shows most of the world
	echo get_map_javascript(16.3, 0, "2");
?>
<script>
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

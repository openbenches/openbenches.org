<?php
	require_once ('config.php');
	require_once ('mysql.php');
	require_once ('functions.php');

	//	Either
	//	/user/twitter/edent (shows external username)
	//	or
	//	/user/1234 (shows internal userid)

	$option = $params[2];

	if (is_numeric($option)){
		$userID   = $option;
		$thisURL  = "/user/{$userID}/";
	} else {
		$username = $params[3];
		$provider = $option;
		$thisURL  = "/user/{$provider}/{$username}/";
	}

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

	$userHTML = "Benches added or edited by ";

	//	/user/1234
	if (is_numeric($option)) {
		$user = get_user($userID);
		$username   = $user["name"];
		$provider   = $user["provider"];
		$providerID = $user["providerID"];

		if("twitter" == $provider && is_numeric($providerID)) {
			$userURL = "https://twitter.com/intent/user?user_id=" . $providerID;
			$userHTML .= "Twitter user <a href=\"{$userURL}\">{$username}</a>";
		} else if("twitter" == $provider && !is_numeric($providerID)) {
			$userURL = "https://twitter.com/" . $providerID;
			$userHTML .= "Twitter user <a href=\"{$userURL}\">{$username}</a>";
		} else if("github" == $provider) {
			$userURL = "https://edent.github.io/github_id/#" . $providerID;
			$userHTML .= "GitHub user <a href=\"{$userURL}\">{$username}</a>";
		} else if("facebook" == $provider) {
			$userURL = "https://facebook.com/" . $providerID;
			$userHTML .= "Facebook user <a href=\"{$userURL}\">{$username}</a>";
		} else if("flickr" == $provider) {
			$userHTML .= "the <a href=\"https://www.flickr.com/\">Flickr importer</a>";
		} else if("wikipedia" == $provider) {
			$userHTML .= "the <a href=\"https://www.wikipedia.org/\">Wikipedia importer</a>";
		} else if("readtheplaque" == $provider) {
			$userHTML .= "the <a href=\"https://www.readtheplaque.com/\">ReadThePlaque importer</a>";
		} else if("geograph" == $provider) {
			$userHTML .= "the <a href=\"https://www.geograph.org.uk/\">Geograph importer</a>";
		} else if("linkedin" == $provider) {
			$userHTML .= "LinkedIn user {$username}";
		} else {
			$userHTML .= "an anonymous user";
		}
	} else {
		//	Old style /user/twitter/edent
		if("twitter" == $provider) {
			$userID = get_user_id($provider, $username);
			$userURL = "https://twitter.com/" . $username;
			$userHTML .= "Twitter user <a href=\"{$userURL}\">{$username}</a>";
		} else if("github" == $provider) {
			$userID = get_user_id($provider, $username);
			$userURL = "https://github.com/" . $username;
			$userHTML .= "GitHub user <a href=\"{$userURL}\">{$username}</a>";
		} else if("facebook" == $provider) {
			$userID = get_user_id($provider, $username);
			$userURL = "https://facebook.com/" . $username;
			$userHTML .= "GitHub user <a href=\"{$userURL}\">{$userID}</a>";
		} else {
			$userHTML = "Invalid User";
		}
	}

	$page_title = " - " . strip_tags($userHTML);
	include("header.php");


	$results = get_user_bench_list($userID, $page, $count);
	$total_results = get_user_bench_count($userID);


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
			$thumb_html = "<img src=\"{$thumb}\" class=\"search-thumb\" width=\"{$thumb_width}\">";
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
<div class="button-bar">
	<a href="/api/v1.0/data.json/?truncated=false&format=raw&media=true&userID=<?php echo $userID;?>" class="button buttonColour"><strong>💾</strong> Download GeoJSON</a>
</div>
<div id="search-results">

	<?php
		echo $resultsHTML;
	?>
</div>

<script src="/api/v1.0/data.json/?userID=<?php echo $userID; ?>" type="text/javascript"></script>

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

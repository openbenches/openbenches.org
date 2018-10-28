<?php
	include("header.php");

	$provider = $params[2];
	$username = $params[3];
	$userHTML = "Benches added or edited by ";
	if (is_numeric($provider)) {
		$user = get_user($provider);
		$userID     = $provider;
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
			$userHTML .= "Facebook user {$username}";
		} else if("flickr" == $provider) {
			$userHTML .= "the <a href=\"https://www.flickr.com/\">Flickr importer</a>";
		} else if("wikipedia" == $provider) {
			$userHTML .= "the <a href=\"https://www.wikipedia.org/\">Wikipedia importer</a>";
		} else {
			$userHTML .= "an anonymous user";
		}
	} else {
		//	Old style /user/twitter/edent
		if("twitter" == $provider) {
			$userID = get_user_id($provider, $username);
			$userURL = "https://twitter.com/" . $username;
			$userHTML .= "Twitter user <a href=\"{$userURL}\">{$username}</a>";
		} else {
			$userHTML = "Invalid User";
		}
	}
?>
</hgroup>

<?php 
	echo "<h3>{$userHTML}</h3>";
?>
<div id="map"></div>
<div id="benchImage"></div>

<div id="search-results">

	<?php
		echo get_user_bench_list_html($userID); 
	?>
</div>

<div class="button-bar">
	<form action="/bench/" method="post">
		<input id="random" name="random" value="random" type="hidden" />
		<input type="submit" class="hand-drawn" value="Show me a random bench" />
		<a href="/add/" class="hand-drawn">Add bench</a>
	</form>
</div>
<br>
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
<script src="/data.json/?userID=<?php echo $userID; ?>" type="text/javascript"></script>

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

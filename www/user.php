<?php
	include("header.php");

	$provider = $params[2];
	$username = $params[3];
	$name = htmlspecialchars($username, ENT_HTML5, "UTF-8", false);
	$nameURL = "https://".htmlspecialchars($provider, ENT_HTML5, "UTF-8", false) . ".com/{$name}";
?>
</hgroup>

<?php 
	echo "<h3>Benches added or edited by @<a href='{$nameURL}'>{$name}</a></h3>";
?>
<div id="map"></div>
<div id="benchImage"></div>

<div id="search-results">

	<?php
		echo get_user_bench_list_html($provider, $username); 
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
<script src="/data.json/?provider=<?php echo $provider; ?>&amp;user=<?php echo $name; ?>" type="text/javascript"></script>

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

<script>
for (var i = 0; i < benches.features.length; i++) {
	var bench = benches.features[i];
	var lat = bench.geometry.coordinates[1];
	var longt = bench.geometry.coordinates[0];
	var benchID = bench.id;
	var title = bench.properties.popupContent;
	var marker = L.marker(new L.LatLng(lat, longt), {  benchID: benchID, draggable: false });

	marker.bindPopup(title);
	markers.addLayer(marker);
}

map.addLayer(markers);
</script>
<?php
	include("footer.php");

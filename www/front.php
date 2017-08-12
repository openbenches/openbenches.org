<?php
	include("header.php");

	echo "<h2>" . number_format(get_bench_count()) . " benches added</h2>";
?>
	<div>
		<div id='map'></div>
		<div id='benchImage' ></div>
	</div>
	<br>
	<form action="/search/" enctype="multipart/form-data" method="get">
		<?php
			echo $error_message;
		?>
		<h2>Search for an inscription</h2>
		<div>
			<input type="search" id="inscription" name="search" value="<?php echo htmlspecialchars($query); ?>"><input type="submit" value="Search inscriptions" />
		</div>
		<br>
	</form>
	<div class="button-bar">
		<a href="add.php" class="hand-drawn">Add a new bench</a>
	</div>
<script src="geojson.php?cache=<?php echo rand(); ?>" type="text/javascript"></script>

<?php echo get_map_javascript(); ?>

<script>
map.on("moveend", function () {
	var stateObj = { x: "y" };
	history.pushState(stateObj, "Open Benches", "/#" +
		map.getCenter().lat.toPrecision(7) + "/" +
		map.getCenter().lng.toPrecision(7) + "/" +
		map.getZoom().toString()
	);
});


markers.on('click', function (bench) {
	var xhr = typeof XMLHttpRequest != 'undefined' ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
	xhr.open('get', 'benchimage/'+bench.layer["options"]["benchID"], true);
	xhr.onreadystatechange = function() {
		if (xhr.readyState == 4 && xhr.status == 200) {
			document.getElementById("benchImage").innerHTML = xhr.responseText;
		}
	}
	xhr.send();
});

for (var i = 0; i < benches.features.length; i++) {
	var bench = benches.features[i];
	var title = bench.properties.popupContent;
	var lat = bench.geometry.coordinates[1];
	var longt = bench.geometry.coordinates[0];
	var benchID = bench.id;
	// console.log('bench ' + benchID);
	var marker = L.marker(new L.LatLng(lat, longt), {  benchID: benchID });

	marker.bindPopup(title);
	markers.addLayer(marker);
}

map.addLayer(markers);

</script>
<?php
	include("footer.php");

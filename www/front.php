<?php
	include("header.php");
?>
	<div>
		<div id='map'></div>
		<div id='benchImage' ></div>
	</div>
	<div class="button-bar">
		<a href="/api/v1.0/data.json/?truncated=false&format=raw&media=true" class="button buttonColour"><strong>💾</strong> Download GeoJSON</a>
	</div>
	<?php
		include("searchform.php");
	?>
<script src="/api/v1.0/data.json/?truncated=true"></script>

<?php echo get_map_javascript(16.3, 0, "2"); ?>

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

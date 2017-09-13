<?php
	include("header.php");

	echo '<h2 itemprop="description">A map of ' . number_format(get_bench_count()) . " memorial benches - added by people like you</h2>";
?>
</hgroup>
<div class="button-bar">
	<a href="/add/" class="hand-drawn">Add bench</a>
	<span class="hand-drawn" onclick="geoFindMe()" id="gpsButton">Show benches near me</span>
</div>

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
			<input type="search" class="search" id="inscription" name="search" value="<?php echo htmlspecialchars($query); ?>">
			<input type="submit" class="hand-drawn" value="Search inscriptions" />
		</div>
		<br>
	</form>
	<div class="button-bar">
		<a href="/bench?random=true" class="hand-drawn">Show me a random bench</a>
		<a href="/add/" class="hand-drawn">Add bench</a>
	</div>
<script src="/data.json/" type="text/javascript"></script>

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
function geoFindMe() {
	var output = document.getElementById("gpsButton");

	var gpsIcon = L.icon({
		iconUrl: '/images/gps.png',
		iconSize: [200, 200],
	});

	if (!navigator.geolocation){
		output.innerHTML = "GPS is not supported by your device";
		return;
	}

	function success(position) {
		var latitude  = position.coords.latitude;
		var longitude = position.coords.longitude;

		output.innerHTML = 'Update my location';
		L.marker([latitude, longitude], {opacity:0.5, icon: gpsIcon}).addTo(map);
		map.setView([latitude, longitude], 10);
	}

	function error() {
		output.innerHTML = "Unable to retrieve your location";
	}

	output.innerHTML = "Locating…";

	navigator.geolocation.getCurrentPosition(success, error);
}
</script>
<?php
	include("footer.php");

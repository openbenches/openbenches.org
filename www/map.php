<?php
	include("header.php");
?>
	<div id="row1">
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

<script>
var map = L.map('map').setView([54.5,-4], 5);
// L.tileLayer.provider('Stamen.Watercolor').addTo(map);
L.tileLayer('https://api.mapbox.com/styles/v1/mapbox/outdoors-v10/tiles/256/{z}/{x}/{y}?access_token=pk.eyJ1IjoiZWRlbnQiLCJhIjoiY2o0dmVnZjVhMHA1MDMzcWp4YmtzcWNsbiJ9.DIgG0nrOK4bnswj2RFfLgQ', {
	minZoom: 4,
	maxZoom: 18,
	attribution: 'Map data © <a href="https://openstreetmap.org">OpenStreetMap</a> contributors, ' +
		'<a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
		'Imagery © <a href="https://mapbox.com">Mapbox</a>',
	id: 'mapbox.light'
}).addTo(map);

var markers = L.markerClusterGroup({
	maxClusterRadius: 30
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

<?php
	include("header.php");
	if ($_GET["benchID"]) {
		$benchID = $_GET["benchID"];
	} else {
		$benchID = $params[2];
	}

	list ($benchID, $benchLat, $benchLong, $benchInscription, $published) = get_bench_details($benchID);

?>


	<div id="row1">
		<div id="benchInscription"><?php echo nl2br($benchInscription); ?></div>
		<div id='benchImage'><?php echo get_image($benchID, true); echo get_user_from_bench($benchID); ?></div>
		<div id='map' class="hand-drawn" ></div>
	</div>
	<div class="button-bar">
		<a href="/add" class="hand-drawn">Add a new bench</a>
	</div>
<script src="/geojson/<?php echo $benchID; ?>" type="text/javascript"></script>

<script>
var bench   = benches.features[0];
var newLat  = bench.geometry.coordinates[1];
var newLong = bench.geometry.coordinates[0];
var title = bench.properties.popupContent;

// var description = document.getElementById('benchInscription');
// description.style.display = 'block';
// description.innerHTML  = title;
// description.innerHTML += '<br>Longitude: ' + newLong;
// description.innerHTML += '<br>Latitude: ' + newLat;


var map = L.map('map').setView([newLat,newLong], 16);

L.tileLayer('https://api.mapbox.com/styles/v1/mapbox/light-v9/tiles/256/{z}/{x}/{y}@2x?access_token=pk.eyJ1IjoiZWRlbnQiLCJhIjoiY2o0dmVnZjVhMHA1MDMzcWp4YmtzcWNsbiJ9.DIgG0nrOK4bnswj2RFfLgQ', {
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

for (var i = 0; i < benches.features.length; i++) {
	var bench = benches.features[i];
	var lat = bench.geometry.coordinates[1];
	var longt = bench.geometry.coordinates[0];
	var benchID = bench.id;
	var marker = L.marker(new L.LatLng(lat, longt), {  benchID: benchID, draggable: false });

	marker.bindPopup(title);
	markers.addLayer(marker);
}

map.addLayer(markers);
</script>
<?php
	include("footer.php");

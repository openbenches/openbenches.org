<?php
	include("header.php");
?>


	<div id="row1">
		<div id='map' class="hand-drawn" ></div>
		<div id='benchImage' ></div>
	</div>
	<div class="button-bar">
		<a href="add.php" class="hand-drawn">Add a new bench</a>
	</div>
<script src="geojson.php?cache=<?php echo rand(); ?>" type="text/javascript"></script>

<script>
var map = L.map('map').setView([51.386115477613764,-2.349201108417759], 6);
// L.tileLayer.provider('Stamen.Watercolor').addTo(map);
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

markers.on('click', function (bench) {
	var xhr = typeof XMLHttpRequest != 'undefined' ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
	xhr.open('get', 'benchimage.php?benchID='+bench.layer["options"]["benchID"], true);
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

// function onEachFeature(feature, layer) {
// 	var popupContent = "";
//
// 	if (feature.properties && feature.properties.popupContent) {
// 		popupContent += feature.properties.popupContent;
// 	}
//
// 	layer.bindPopup(popupContent);
// 	layer.on('popupopen', function(){
// 		var xhr = typeof XMLHttpRequest != 'undefined' ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
// 		xhr.open('get', 'benchimage.php?benchID='+feature.id, true);
// 		xhr.onreadystatechange = function() {
// 			if (xhr.readyState == 4 && xhr.status == 200) {
// 				document.getElementById("benchImage").innerHTML = xhr.responseText;
// 			}
// 		}
// 		xhr.send();
// 	});
// }



// L.geoJSON(benches, {
//
// 	style: function (feature) {
// 		return feature.properties && feature.properties.style;
// 	},
//
// 	onEachFeature: onEachFeature,
//
// 	pointToLayer: function (feature, latlng) {
// 		return L.circleMarker(latlng, {
// 			radius: 5,
// 			fillColor: "#ff7800",
// 			color: "#000",
// 			weight: 1,
// 			opacity: 1,
// 			fillOpacity: 0.8
// 		});
// 	}
// }).addTo(map);
</script>
<?php
	include("footer.php");

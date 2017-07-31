<?php
session_start();
require_once ("config.php");
require_once ("mysql.php");
require_once ("functions.php");

//	Start the normal page
include("header.php");

$error_message = "";

//	edit/123/qwertyiop
$benchID = $params[2];

if (null != $params[3]) {
	$key = urldecode($params[3]);
} else {
	$key =         $_POST['key'];
	$inscription = $_POST['inscription'];
	$latitude =    $_POST['newLatitude'];
	$longitude =   $_POST['newLongitude'];
	$published =   $_POST['published'];

	$valid = hash_equals(EDIT_SALT . $key, crypt($benchID,EDIT_SALT));

	if ($valid) {
		edit_bench($latitude, $longitude, $inscription, $benchID, $published=="true");
		//	Send the user to the bench's page
		header("Location: /bench/{$benchID}");
	}
}


$valid = hash_equals(EDIT_SALT . $key, crypt($benchID,EDIT_SALT));

if (!$valid) {
	$error_message .= "<h2>Invalid Hash</h2>";
} else {
	list ($benchID, $benchLat, $benchLong, $benchInscription, $published) = get_bench_details($benchID);
}

?>
	<br>
	<form action="/edit/<?php echo $benchID; ?>" enctype="multipart/form-data" method="post">
		<h2>Edit A Bench</h2>
		<?php
			echo $error_message;
		?>
		<a href="<?php echo $image_url; ?>">
			<img src="<?php echo $image_url; ?>/512" width="512" />
		</a>
		<div>
			<?php echo get_media_types_html(); ?>
		</div>
		<div>
			<label for="inscription">Change Inscription?</label><br>
			<textarea id="inscription" name="inscription" cols="40" rows="6"><?php echo $benchInscription; ?></textarea>
		</div>

		<div style="clear:both;">
			<h3>Drag pin to change bench location</h3>
			<div id='map' class="hand-drawn" ></div>
		</div>

		<div style="clear:both;">
			<input type="text"   id="coordinates"  value="<?php echo $benchLat; ?>,<?php echo $benchLong; ?>" disabled="true" />
			<input type="hidden" id="newLongitude" name="newLongitude" value="<?php echo $benchLong; ?>"/>
			<input type="hidden" id="newLatitude"  name="newLatitude"  value="<?php echo $benchLat;  ?>"/>
		</div>

		<br>
		<input type="radio" id="publishedTrue"  name="published" value="true" checked>
			<label for="publishedTrue">Published</label>
		<br>
		<input type="radio" id="publishedFalse" name="published" value="false">
			<label for="publishedFalse">Delete</label>
		<input type="hidden" name="key" value="<?php echo $key; ?>"/>
		<br>
		<input type="submit" value="Submit Edits" />

	</form>
	<br>
	<br>
	<div class="button-bar">
		<a href="/" class="hand-drawn">Go Home</a>
	</div>

<script src="/geojson/<?php echo $benchID; ?>" type="text/javascript"></script>

<script>
var bench = benches.features[0];
var newLat = bench.geometry.coordinates[1];
var newLong = bench.geometry.coordinates[0];

var coordinates = document.getElementById('coordinates');
var longitude = document.getElementById('newLongitude');
var latitude = document.getElementById('newLatitude');

// coordinates.value = newLat + ',' + newLong;
// longitude.value = newLong;
// latitude.value =  newLat;

var inscription = document.getElementById('inscription');
// Remove the <br>
var parser = new DOMParser;
var dom = parser.parseFromString(
    '<!doctype html><body>' + bench.properties.popupContent,
    'text/html');
var decodedString = dom.body.textContent;
// inscription.value = bench.properties.popupContent.replace(/<br\s*\/?>/mg,"");
// inscription.value = decodedString;

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
	var bench   = benches.features[i];
	var title   = bench.properties.popupContent;
	var lat     = bench.geometry.coordinates[1];
	var longt   = bench.geometry.coordinates[0];
	var benchID = bench.id;
	// console.log('bench ' + benchID);
	var marker = L.marker(new L.LatLng(lat, longt), {  benchID: benchID, draggable: true });

	marker.bindPopup(title);

	marker.on('dragend', function(event){
		console.log(event);
		newLat =  event.target._latlng.lat.toPrecision(7);
		newLong = event.target._latlng.lng.toPrecision(7);
		coordinates.value = newLat + ',' + newLong;
		longitude.value = newLong;
		latitude.value =  newLat;
	});

	markers.addLayer(marker);
}

map.addLayer(markers);

</script>
<?php
	include("footer.php");

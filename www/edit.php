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
	twitter_login();
}

$twitter = get_twitter_details();

if(isset($_POST['key'])) {
	$key =         $_POST['key'];
	$inscription = $_POST['inscription'];
	$latitude =    $_POST['newLatitude'];
	$longitude =   $_POST['newLongitude'];
	$published =   $_POST['published'];

	$valid = hash_equals(EDIT_SALT . $key, crypt($benchID,EDIT_SALT));

	if ($valid && (null != $twitter)) {

		$userID = insert_user("twitter", $twitter[0], $twitter[1]);
		
		edit_bench($latitude, $longitude, $inscription, $benchID, $published=="true", $userID);
		
		//	Add photos
		$image1 = $image2 = $image3 = $image4 = true;
		if ($_FILES['userfile1']['tmp_name'])
		{	//	Has a photo been posted?
			$image1 = save_image($_FILES['userfile1'], $_POST['media_type1'], $benchID, $userID);
		}
		if ($_FILES['userfile2']['tmp_name'])
		{
			$image2 = save_image($_FILES['userfile2'], $_POST['media_type2'], $benchID, $userID);
		}
		if ($_FILES['userfile3']['tmp_name'])
		{
			$image3 = save_image($_FILES['userfile3'], $_POST['media_type3'], $benchID, $userID);
		}
		if ($_FILES['userfile4']['tmp_name'])
		{
			$image4 = save_image($_FILES['userfile4'], $_POST['media_type4'], $benchID, $userID);
		}

		if($image1 === true && $image2 === true && $image3 === true && $image4 === true){
			//	All images were successfully added
			//	Send the user to the bench's page
			header("Location: /bench/{$benchID}");
		} else {
			//	Build up the error message
			if ($image1 !== true) {$error_message .= $image1;}
			if ($image2 !== true) {$error_message .= $image2;}
			if ($image3 !== true) {$error_message .= $image3;}
			if ($image4 !== true) {$error_message .= $image4;}
		}
	}
}

$valid = hash_equals(EDIT_SALT . $key, crypt($benchID,EDIT_SALT));
if (!$valid && null == $twitter[1]) {
	$error_message .= "<h3>Invalid Edit URL</h3>";
} else {
	list ($benchID, $benchLat, $benchLong, $benchAddress, $benchInscription, $published) = get_bench_details($benchID);
}

if ($valid) {
	$info = "Your bench has been added! <a href='/bench/{$benchID}'>View your bench</a>.
	<br>
	You can edit your inscription, change location, or add more photos if you need to.
	<br>
	Or <a href='/add/'>Add a new bench</a>";
}

if($twitter[1] != null){
	$info = "You are logged in as @{$twitter[1]}<br>
	You can edit this bench's inscription, change location, or add more photos.";
}

?>
	<br>
	<form action="/edit/<?php echo $benchID . "/" . urlencode($key); ?>" enctype="multipart/form-data" method="post">
		<h2>Edit A Bench</h2>
		<?php echo $info; ?>
		<?php 
			if($error_message != "") {
				echo $error_message;
				include("footer.php");
				die();
			} ?>
		<div style="clear:both;">
			<h3>Drag pin to change bench location, then press "Save Changes"</h3>
			<div id='map' class="hand-drawn" ></div>
		</div>

		<div style="clear:both;">
			<input type="text"   id="coordinates"  value="<?php echo $benchLat; ?>,<?php echo $benchLong; ?>" disabled="true" />
			<input type="hidden" id="newLongitude" name="newLongitude" value="<?php echo $benchLong; ?>"/>
			<input type="hidden" id="newLatitude"  name="newLatitude"  value="<?php echo $benchLat;  ?>"/>
			<input type="submit" value="Save Changes" />
		</div>

		<div id='benchImage'>
			<?php echo get_image_html($benchID); ?>
		</div>
		
		<h3>Add more images?</h3>
		<div id="photo1" class="photo-group" style="display: block;">
			<fieldset>
				<legend>Geotagged Photo</legend>
				<input id="photoFile1" name="userfile1" type="file" accept="image/jpeg" />
				<div id="photoPreview1" style="display: none;"></div>
				<label for="media_type1">This photo is a:</label>
				<?php
					echo get_media_types_html("1");
				?>
			</fieldset>
		</div>&nbsp;
		<div id="photo2" class="photo-group" style="display: none;">
			<fieldset>
				<legend>Optional Photo</legend>
				<input id="photoFile2" name="userfile2" type="file" accept="image/jpeg" />
				<div id="photoPreview2" style="display: none;"></div>
				<label for="media_type2">This photo is a:</label>
				<?php
					echo get_media_types_html("2");
				?>
			</fieldset>
		</div>&nbsp;
		<div id="photo3" class="photo-group" style="display: none;">
			<fieldset>
				<legend>Optional Photo</legend>
				<input id="photoFile3" name="userfile3" type="file" accept="image/jpeg" />
				<div id="photoPreview3" style="display: none;"></div>
				<label for="media_type3">This photo is a:</label>
				<?php
					echo get_media_types_html("3");
				?>
			</fieldset>
		</div>
		<div id="photo4" class="photo-group" style="display: none;">
			<fieldset>
				<legend>Optional Photo</legend>
				<input id="photoFile4" name="userfile4" type="file" accept="image/jpeg" />
				<div id="photoPreview4" style="display: none;"></div>
				<label for="media_type4">This photo is a:</label>
				<?php
					echo get_media_types_html("4");
				?>
			</fieldset>
		</div>
		<br>

		<div>
			<label for="inscription">Change Inscription?</label><br>
			<textarea id="inscription" name="inscription" cols="40" rows="6"><?php echo $benchInscription; ?></textarea>
		</div>

		<br>
		<input type="radio" id="publishedTrue"  name="published" value="true" checked>
			<label for="publishedTrue">Published</label>
		<br>
		<input type="radio" id="publishedFalse" name="published" value="false">
			<label for="publishedFalse">Delete</label>

		<input type="hidden" name="key" value="<?php echo crypt($benchID,EDIT_SALT); ?>"/>
		<br>&nbsp;
		<br>&nbsp;
		<input type="submit" value="Save Changes" />

	</form>

<script src="/data.json/?bench=<?php echo $benchID; ?>" type="text/javascript"></script>

<?php echo get_map_javascript($benchLat, $benchLong, "16"); ?>

<script>
var bench = benches.features[0];
var newLat = bench.geometry.coordinates[1];
var newLong = bench.geometry.coordinates[0];

var coordinates = document.getElementById('coordinates');
var longitude = document.getElementById('newLongitude');
var latitude = document.getElementById('newLatitude');

var inscription = document.getElementById('inscription');
// Remove the <br>
var parser = new DOMParser;
var dom = parser.parseFromString(
	'<!doctype html><body>' + bench.properties.popupContent,
	'text/html');
var decodedString = dom.body.textContent;

for (var i = 0; i < benches.features.length; i++) {
	var bench   = benches.features[i];
	var title   = bench.properties.popupContent;
	var lat     = bench.geometry.coordinates[1];
	var longt   = bench.geometry.coordinates[0];
	var benchID = bench.id;
	var marker = L.marker(new L.LatLng(lat, longt), {  benchID: benchID, draggable: true });

	marker.bindPopup(title);

	marker.on('dragend', function(event){
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
<script src="/libs/load-image/load-image.all.min.js"></script>
<script type="text/javascript">
	var previewWidth = 400;

	document.getElementById('photoFile1').onchange = function (e) {
		var preview1 = document.getElementById("photoPreview1");
		//	If a photo was added already, remove it.
		while (preview1.hasChildNodes()) {
			preview1.removeChild(preview1.lastChild);
		}
		//	Display the element
		preview1.style.display = "block";
		//	Add a quick canvas to the screen showing the image
		var loadingImage = loadImage(
			e.target.files[0],
			function (img) { preview1.appendChild(img); },
			{ maxWidth: previewWidth, canvas: true}
		);
		if (!loadingImage) {}

		//	Check for GPS data
		var exifdata = loadImage.parseMetaData(
			e.target.files[0],
			function (data) {
				if (!data.imageHead) {
					return;
				}
				if ( typeof data.exif == 'undefined' ) {
					alert("EXIF Warning! No GPS tags detected in photo.\nPlease check your camera's settings or add a different photo.");
				} else if (data.exif.get("GPSLongitude") == null) {
					alert("Warning! No GPS tags detected in photo.\nPlease check your camera's settings or add a different photo.");
				}
			}
		);
		document.getElementById('photo2').style.display = "block";
	};
	
	document.getElementById("photoFile2").onchange = function (e) {
		var preview2 = document.getElementById("photoPreview2");
		//	If a photo was added already, remove it.
		while (preview2.hasChildNodes()) {
			preview2.removeChild(preview2.lastChild);
		}
		//	Display the element
		preview2.style.display = "block";
		//	Add a quick canvas to the screen showing the image
		var loadingImage = loadImage(
			e.target.files[0],
			function (img) { preview2.appendChild(img); },
			{ maxWidth: previewWidth, canvas: true}
		);
		if (!loadingImage) {}
		//	Show the next upload box
		document.getElementById('photo3').style.display = "block";
	}
	
	document.getElementById("photoFile3").onchange = function (e) {
		var preview3 = document.getElementById("photoPreview3");
		//	If a photo was added already, remove it.
		while (preview3.hasChildNodes()) {
			preview3.removeChild(preview3.lastChild);
		}
		//	Display the element
		preview3.style.display = "block";
		//	Add a quick canvas to the screen showing the image
		var loadingImage = loadImage(
			e.target.files[0],
			function (img) { preview3.appendChild(img); },
			{ maxWidth: previewWidth, canvas: true}
		);
		if (!loadingImage) {}
		//	Show the next upload box
		document.getElementById('photo4').style.display = "block";
	}
	
	document.getElementById("photoFile4").onchange = function (e) {
		var preview4 = document.getElementById("photoPreview4");
		//	If a photo was added already, remove it.
		while (preview4.hasChildNodes()) {
			preview4.removeChild(preview4.lastChild);
		}
		//	Display the element
		preview4.style.display = "block";
		//	Add a quick canvas to the screen showing the image
		var loadingImage = loadImage(
			e.target.files[0],
			function (img) { preview4.appendChild(img); },
			{ maxWidth: previewWidth, canvas: true}
		);
		if (!loadingImage) {}
	}
</script>
<?php
	include("footer.php");

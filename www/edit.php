<?php
if(!isset($_SESSION)) { session_start(); }
require_once ("config.php");
require_once ("mysql.php");
require_once ("functions.php");


//	edit/123/qwertyiop
$benchID = $params[2];

//	If the user isn't logged in, force them to
[$user_provider, $user_providerID, $user_name] = get_user_details(true);
if(null == $user_provider) {
	$_SESSION['edit_bench_id'] = $benchID;
	header('Location: ' . "https://{$_SERVER['HTTP_HOST']}/login/{$benchID}/");
	return null;
}

$error_message = "";

if(isset($_POST['key'])) {
	$key =         urldecode($_POST['key']);
	$inscription = $_POST['inscription'];
	$latitude =    $_POST['newLatitude'];
	$longitude =   $_POST['newLongitude'];
	$published =   $_POST['published'];

	$valid = hash_equals($key, get_edit_key($benchID));

	if ($valid) {

		if (null == $user_provider) {
			$userID = insert_user("anon", $_SERVER['REMOTE_ADDR'], date(DateTime::ATOM));
		} else {
			$userID = insert_user($user_provider, $user_providerID, $user_name);
		}

		list ($oldBenchID, $oldBenchLat, $oldBenchLong, $oldBenchAddress, $oldBenchInscription, $oldPublished) = get_bench_details($benchID);

		edit_bench($latitude, $longitude, $inscription, $benchID, $published=="true", $userID);

		$newImages = 0;
		//	Add photos
		$image1 = $image2 = $image3 = $image4 = true;
		if ($_FILES['userfile1']['tmp_name'])
		{	//	Has a photo been posted?
			$image1 = save_image($_FILES['userfile1'], $_POST['media_type1'], $benchID, $userID);
			$newImages++;
		}
		if ($_FILES['userfile2']['tmp_name'])
		{
			$image2 = save_image($_FILES['userfile2'], $_POST['media_type2'], $benchID, $userID);
			$newImages++;
		}
		if ($_FILES['userfile3']['tmp_name'])
		{
			$image3 = save_image($_FILES['userfile3'], $_POST['media_type3'], $benchID, $userID);
			$newImages++;
		}
		if ($_FILES['userfile4']['tmp_name'])
		{
			$image4 = save_image($_FILES['userfile4'], $_POST['media_type4'], $benchID, $userID);
			$newImages++;
		}

		mail(NOTIFICATION_EMAIL,
			"Edit to Bench {$benchID} by {$user_name}",
			"New Images: {$newImages}\n".
			"New: {$inscription}\n".
			"Old: {$oldBenchInscription}\n".
			"New: {$latitude},{$longitude}\n".
			"Old: {$oldBenchLat},{$oldBenchLong}\n".
			"New Published: {$published}\n".
			"Old Published: {$oldPublished}\n".

			"By {$user_provider} {$user_name}\n".
			"https://{$_SERVER['SERVER_NAME']}/bench/{$benchID}"
		);

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

//	Start the normal page
include("header.php");

if (null == $user_providerID) {
	$error_message .= "<h3>Invalid Edit URL</h3>";
} else {
	list ($benchID, $benchLat, $benchLong, $benchAddress, $benchInscription, $published) = get_bench_details($benchID);
}

if($user_provider != null){
	$info = "You are logged in as \"{$user_name}\" from " . ucfirst($user_provider) ."<br>
	You can edit this bench's inscription, change location, or add more photos.";
}

?>

	<br>
	<form action="/edit/<?php echo $benchID . "/"; ?>" enctype="multipart/form-data" method="post">
		<h2>Edit A Bench</h2>
		<?php echo $info; ?>
		<?php
			if($error_message != "") {
				echo $error_message;
				include("footer.php");
				return null;
			} ?>
		<div style="clear:both;">
			<h3>Drag pin to change bench location, then press "Save Changes"</h3>
			<div id='map' class="hand-drawn" ></div>
		</div>

		<div style="clear:both;">
			<input type="text"   id="coordinates"  value="<?php echo $benchLat; ?>,<?php echo $benchLong; ?>" disabled="true" />
			<input type="hidden" id="newLongitude" name="newLongitude" value="<?php echo $benchLong; ?>"/>
			<input type="hidden" id="newLatitude"  name="newLatitude"  value="<?php echo $benchLat;  ?>"/>
			<input type="submit" class='button buttonColour' value="💾 Save Changes" />
		</div>&nbsp;

		<div>
			<label for="inscription">Change Inscription?</label><br>
			<textarea id="inscription" name="inscription" cols="40" rows="6"><?php echo $benchInscription; ?></textarea>
		</div>&nbsp;

		<div id='benchImage'>
			<?php echo get_image_html($benchID); ?>
		</div>

		<h3>Add more images?</h3>
		<div id="photo1" class="photo-group">
			<div class="file_input_button">
				<div id="photoPreview1" style="display: none;"></div>
				<div id="upload-prompt1" class="upload-prompt">
					<span class="upload-copy">
						📷 Upload a geotagged photograph of the bench
						<br>
						<small>Please make sure the inscription is legible and well framed.</small>
					</span>
				</div>
				<input type="file" name="userfile1" id="photoFile1" accept="image/jpeg;capture=camera">
			</div>
			<div>
				<label for="media_type1">This photo is a:</label>
				<?php echo get_media_types_html("1"); ?>
			</div>
		</div>
		<div id="photo2" class="photo-group" style="display: none;">
			<div class="file_input_button">
				<div id="photoPreview2" style="display: none;"></div>
				<div id="upload-prompt2" class="upload-prompt">
					<span class="upload-copy">
						📷 Optional photo of same bench
					</span>
				</div>
				<input type="file" name="userfile2" id="photoFile2" accept="image/jpeg;capture=camera">
			</div>
			<div>
				<label for="media_type2">This photo is a:</label>
				<?php echo get_media_types_html("2"); ?>
			</div>
		</div>&nbsp;
		<div id="photo3" class="photo-group" style="display: none;">
			<div class="file_input_button">
				<div id="photoPreview3" style="display: none;"></div>
				<div id="upload-prompt3" class="upload-prompt">
					<span class="upload-copy">
						📷 Optional photo of same bench
					</span>
				</div>
				<input type="file" name="userfile3" id="photoFile3" accept="image/jpeg;capture=camera">
			</div>
			<div>
				<label for="media_type3">This photo is a:</label>
				<?php echo get_media_types_html("3"); ?>
			</div>
		</div>&nbsp;
		<div id="photo4" class="photo-group" style="display: none;">
			<div class="file_input_button">
				<div id="photoPreview4" style="display: none;"></div>
				<div id="upload-prompt4" class="upload-prompt">
					<span class="upload-copy">
						📷 Optional photo of same bench
					</span>
				</div>
				<input type="file" name="userfile4" id="photoFile4" accept="image/jpeg;capture=camera">
			</div>
			<div>
				<label for="media_type4">This photo is a:</label>
				<?php echo get_media_types_html("4"); ?>
			</div>
		</div>&nbsp;
		<br>
		<input type="radio" id="publishedTrue"  name="published" value="true" checked>
			<label for="publishedTrue" class="checkable">Publish this bench</label>
		<br>
		<input type="radio" id="publishedFalse" name="published" value="false">
			<label for="publishedFalse" class="checkable">⚠️ Delete this bench ⚠️</label>

		<input type="hidden" name="key" value="<?php echo urlencode(get_edit_key($benchID)); ?>"/>
		<br>&nbsp;
		<div class="button-bar">
			<input type="submit" class='button buttonColour' value="💾 Save Changes" />
		</div>
	</form>

<script src="/api/v1.0/data.json/?bench=<?php echo $benchID; ?>" type="text/javascript"></script>

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
<script src="/libs/load-image.5.16.0/load-image.all.min.js"></script>
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
		//	Remove the upload text
		var u1 = document.getElementById("upload-prompt1");
		u1.style.display = "none";
		//	Add a quick canvas to the screen showing the image
		var loadingImage = loadImage(
			e.target.files[0],
			function (img) { preview1.appendChild(img); },
			{ maxWidth: previewWidth, canvas: true, orientation: true}
		);
		if (!loadingImage) {}

		//	Check for GPS data
		// var exifdata = loadImage.parseMetaData(
		// 	e.target.files[0],
		// 	function (data) {
		// 		if (!data.imageHead) {
		// 			return;
		// 		}
		// 		var gpsInfo = data.exif && data.exif.get('GPSInfo');
		// 		// console.log(gpsInfo);
		//
		// 		if ( typeof gpsInfo == 'undefined' ) {
		// 			alert("EXIF Warning! No GPS tags detected in photo.\nPlease check your camera's settings or add a different photo.");
		// 			return;
		// 		} else if (gpsInfo.get("GPSLongitude") == null) {
		// 			alert("Warning! No GPS tags detected in photo.\nPlease check your camera's settings or add a different photo.");
		// 			return;
		// 		}
		// 	}
		// );
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
		//	Remove the upload text
		var u2 = document.getElementById("upload-prompt2");
		u2.style.display = "none";
		//	Add a quick canvas to the screen showing the image
		var loadingImage = loadImage(
			e.target.files[0],
			function (img) { preview2.appendChild(img); },
			{ maxWidth: previewWidth, canvas: true, orientation: true}
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
		//	Remove the upload text
		var u3 = document.getElementById("upload-prompt3");
		u3.style.display = "none";
		//	Add a quick canvas to the screen showing the image
		var loadingImage = loadImage(
			e.target.files[0],
			function (img) { preview3.appendChild(img); },
			{ maxWidth: previewWidth, canvas: true, orientation: true}
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
		//	Remove the upload text
		var u4 = document.getElementById("upload-prompt4");
		u4.style.display = "none";
		//	Add a quick canvas to the screen showing the image
		var loadingImage = loadImage(
			e.target.files[0],
			function (img) { preview4.appendChild(img); },
			{ maxWidth: previewWidth, canvas: true, orientation: true}
		);
		if (!loadingImage) {}
	}
</script>
<?php
	include("footer.php");

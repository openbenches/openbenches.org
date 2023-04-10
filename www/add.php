<?php
if(!isset($_SESSION)) { session_start(); }
require_once ("config.php");
require_once ("mysql.php");
require_once ("functions.php");

$page_title = "- Add new bench";
//	Start the normal page
include("header.php");
?>

<?php
	[$user_provider, $user_providerID, $user_name, $avatar] = get_user_details(false);

	if(null == $user_provider) {
		$login_html = "<a href='/login/' class='button buttonColour'>👤 Sign in</a><br>or be <strong>anonymous</strong>.";
	} else {
		if (null == $avatar){
			$avatar = get_user_avatar($user_provider, $user_providerID, $user_name);
		}
		$login_html = "You are logged in as <img src='{$avatar}' class='avatar'> $user_name from ";
		$login_html .= ucfirst($user_provider);
	}

	echo "<p>{$login_html}</p>";
?>
<?php
	echo (isset($error_message) ? "{$error_message}" : "");
?>
	<form id="fileform" action="/upload.php" enctype="multipart/form-data" method="post" onsubmit="true;">
		<div>Select a photo of the bench's inscription and we'll try to auto-detect the text.<br>
		</div>

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


		<div id="inscriptionBox" style="">
			<label for="inscription" id="message"></label><br>
			<textarea id="inscription" name="inscription" disabled cols="40" rows="6" placeholder="In loving memory of
Buffy Anne Summers
She saved the world.
A lot..." required></textarea>
		</div>
		<div id="tagInput" style="">
			<label for="tag_multiple">
				Choose some tags:
				<select class="js-tags-multiple form-control"
				        name="tags[]" id="tag_multiple"
				        multiple="multiple" form="fileform"></select>
			</label>
		</div>
		<div id="map-hidden" style="clear:both;display: none;">
			<h3>Drag pin if you need to adjust the bench's location</h3>
			<div id='map' class="hand-drawn" ></div>
		</div>
		<div id="latlong-hidden" style="clear:both;display: none;">
			<input type="text"   id="coordinates"                      value="" disabled="true" />
			<input type="hidden" id="newLongitude" name="newLongitude" value=""/>
			<input type="hidden" id="newLatitude"  name="newLatitude"  value=""/>
		</div>&nbsp;
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
		<div id="progressInfo" style="display:none;">
			<label for="progressBar">Upload progress:<br></label>
			<progress id="progressBar" value="0" max="100"></progress>
			<h3 id="status"></h3>
			<p id="loaded_n_total"></p>
		</div>
	</form>
	<div class="button-bar" id="buttonBar" style="display: none;">
		Please <a href="#inscriptionBox">check the inscription</a> before sharing.<br>
		<input class="button buttonColour" type="submit" name="submitButton" id="submitButton" value="📷 Share Bench"/>
	</div>
	<small id="cc-small">By adding a bench, you agree that you own the copyright of the photo and that you are making it freely available under the <a href="https://creativecommons.org/licenses/by-sa/4.0/">Creative Commons Attribution-ShareAlike 4.0 International (CC BY-SA 4.0) license</a>.<br>
		This means other people can use the photo and its data without having to ask permission, but they will have to give <a href="https://creativecommons.org/licenses/by-sa/4.0/legalcode#s3">appropriate credit</a>. Thanks!<br>
		See our <a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/blog/privacy/">privacy policy</a> to understand how your photo's data is used.
	</small>
	<script src="/libs/jquery.3.6.0/jquery-3.6.0.min.js"></script>
	<script src="/libs/vision/vision.js.php?cache=2023-04-09"></script>
	<script src="/libs/load-image.5.16.0/load-image.all.min.js"></script>
	<script src="/libs/select2.4.0.13/select2.min.js"></script>
	<script>
	$(document).ready(function() {
		$('.js-tags-multiple').select2({
			data: tags,
			closeOnSelect: false,
			placeholder: "tag your bench up"
		});
	});
	</script>
	<script src="/api/v1.0/tags.json/"></script>

	<script type="text/javascript">
		var previewWidth = 800;
		var map = null;

		document.getElementById("photoFile1").onchange = function (e) {
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
			$("#photo2").show();
			//	Check for GPS data
			var exifdata = loadImage.parseMetaData(
				e.target.files[0],
				function (data) {
					// console.log('Original image head: ', data.imageHead);
					// console.log('Exif data: ', data.exif);
					if (!data.imageHead) {
						return;
					}

					var gpsInfo = data.exif && data.exif.get('GPSInfo');
					// console.log(gpsInfo);

					if ( typeof gpsInfo == 'undefined' ) {
						alert("EXIF Warning! No GPS tags detected in photo.\nPlease check your camera's settings or add a different photo.");
						return;
					} else if (gpsInfo.get("GPSLongitude") == null) {
						alert("Warning! No GPS tags detected in photo.\nPlease check your camera's settings or add a different photo.");
						return;
					}
					// upload the photo for text detection
					uploadFiles(e);

					var exifLong    = gpsInfo.get("GPSLongitude");
					var exifLongRef = gpsInfo.get("GPSLongitudeRef");
					var exifLat     = gpsInfo.get("GPSLatitude");
					var exifLatRef  = gpsInfo.get("GPSLatitudeRef");

					//	Correct for negative values
					if (exifLatRef == "S") {
						var latitude = (exifLat[0]*-1) + (( (exifLat[1]*-60) + (exifLat[2]*-1) ) / 3600);
					} else {
						var latitude = exifLat[0] + (( (exifLat[1]*60) + exifLat[2] ) / 3600);
					}

					if (exifLongRef == "W") {
						var longitude = (exifLong[0]*-1) + (( (exifLong[1]*-60) + (exifLong[2]*-1) ) / 3600);											} else {
						var longitude = exifLong[0] + (( (exifLong[1]*60) + exifLong[2] ) / 3600);
					}
					//	Show the map
					$("#map-hidden").show();
					$("#latlong-hidden").show();

					var OpenStreetMap_Mapnik = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
						minZoom: 2,
						maxNativeZoom: 19,
						maxZoom: 22,
						attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
						id: 'osm.mapnik'
					});

					var ESRI_Satellite = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}.jpeg?token=<?php echo ESRI_API_KEY; ?>', {
						minZoom: 2,
						maxNativeZoom: 19,
						maxZoom: 22,
						attribution: '© <a href="https://www.esri.com/">i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community</a>',
						id: 'esri.satellite'
					});

					if (map != null){
						map.remove();
					}
					map = L.map('map');
					// Centre the map
					map.setView([latitude, longitude], 19);
					var baseMaps = {
						"Mapnik": OpenStreetMap_Mapnik,
						"Satellite View": ESRI_Satellite
					};

					OpenStreetMap_Mapnik.addTo(map);

					L.control.layers(baseMaps).addTo(map);

					var marker = L.marker([latitude, longitude], { draggable: true }).addTo(map);

					//	Get the coordinates to add to map
					var coordinates  = document.getElementById("coordinates");
					var newLatitude  = document.getElementById("newLatitude");
					var newLongitude = document.getElementById("newLongitude");
					coordinates.value  = latitude.toPrecision(7) + ',' + longitude.toPrecision(7);
					newLongitude.value = longitude;
					newLatitude.value  = latitude;
					//	If the marker is dragged, update the coordinates
					marker.on('dragend', function(event){
						newLat =  event.target._latlng.lat.toPrecision(7);
						newLong = event.target._latlng.lng.toPrecision(7);
						coordinates.value  = newLat + ',' + newLong;
						newLongitude.value = newLong;
						newLatitude.value  =  newLat;
					});
				}
			);
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
			document.getElementById("photo3").style.display = "block";
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
			document.getElementById("photo4").style.display = "block";
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
		//	Disable button once clicked & let user know that the media are being uploaded
		$("#submitButton").on('click', function() {
			if ( $("#inscription")[0].textLength > 0) {
				$("#submitButton").prop( "disabled", true );
				$("#submitButton").prop( "value",   "Uploading!" );
				uploadFile();
			}
			else {
				alert("OpenBenches is only for benches with a memorial inscription.");
			}
		});

		function uploadFile() {
			//	Show the progress bar
			$("#progressInfo").show();
			//	Prepare the data
			var formdata = new FormData();
			formdata.append("userfile1",    $("#photoFile1").prop('files')[0]);
			formdata.append("media_type1",  $("#media_type1").val());

			formdata.append("userfile2",    $("#photoFile2").prop('files')[0]);
			formdata.append("media_type2",  $("#media_type2").val());

			formdata.append("userfile3",    $("#photoFile3").prop('files')[0]);
			formdata.append("media_type3",  $("#media_type3").val());

			formdata.append("userfile4",    $("#photoFile4").prop('files')[0]);
			formdata.append("media_type4",  $("#media_type4").val());

			formdata.append("inscription",  $("#inscription").val());
			formdata.append("newLongitude", $("#newLongitude").val());
			formdata.append("newLatitude",  $("#newLatitude").val());

			formdata.append("tags[]",       $("#tag_multiple").val());

			//	Send the file
			var ajax = new XMLHttpRequest();
			ajax.upload.addEventListener("progress", progressHandler);
			ajax.addEventListener("load",  completeHandler);
			ajax.addEventListener("error", errorHandler);
			ajax.addEventListener("abort", abortHandler);
			ajax.open("POST", "/upload.php");
			ajax.send(formdata);
		}

		function progressHandler(event) {
			$("#loaded_n_total").html("Uploaded <output>" + event.loaded.toLocaleString() + " bytes of " + event.total.toLocaleString() + "</output>");
			var percent = (event.loaded / event.total) * 100;
			$("#progressBar").val(Math.round(percent));
			$("#status").html(Math.round(percent) + "% uploaded");

			if (100 == Math.round(percent)) {
				$("#status").html("Upload complete. Redirecting you.");
			}
		}

		function completeHandler(event) {
			var reply = event.target.responseText;
			if (isNaN(reply)) {
				$("#status").html(reply);
			} else {
				$("#status").html("Redirecting you.");
				window.location.replace("/bench/"+reply);
			}
		}

		function errorHandler(event) {
			$("#status").html("Upload Failed");
		}

		function abortHandler(event) {
			$("#status").html("Upload Aborted");
		}
	</script>
<?php
	include("footer.php");

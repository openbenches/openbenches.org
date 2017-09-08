<?php
session_start();
require_once ("config.php");
require_once ("mysql.php");
require_once ("functions.php");

//	Get the inscription, either to add to database, or recover in case of error
$inscription = $_POST['inscription'];
$error_message = "";

if (null == $inscription) {
	$error_message .= "<h3>Please type in the text of the inscription</h3>";
} else if ($_FILES['userfile1']['tmp_name'])
{	//	Has a photo been posted?
	$filename = $_FILES['userfile1']['tmp_name'];
	$sha1 = sha1_file ($filename);	//	For tweeting
	
	$domain = $_SERVER['SERVER_NAME'];

	$mediaURLs = array();
	$mediaURLs[] = "https://{$domain}/image/{$sha1}/1024";

	if (duplicate_file($filename))
	{
		$error_filename = $_FILES['userfile1']['name'];
		$error_message .= "<h3>{$error_filename} already exists in the database</h3>";
	} else {
		$location = get_image_location($filename);

		//	If there is a GPS tag on the photo
		if (false != $location)
		{
			//	Add the user to the database
			$twitter = get_twitter_details();
			
			if (null == $twitter[1]) {
				$userID = insert_user("anon", $_SERVER['REMOTE_ADDR'], date(DateTime::ATOM));
			} else {
				$userID = insert_user("twitter", $twitter[0], $twitter[1]);
			}

			$media_type = $_POST['media_type1'];

			//	Insert Bench
			$benchID = insert_bench($location["lat"],$location["lng"], $inscription, $userID);

			//	Save the Image
			save_image($_FILES['userfile1'], $media_type, $benchID, $userID);

			//	Save other images
			if ($_FILES['userfile2']['tmp_name'])
			{
				$sha1 = sha1_file($_FILES['userfile2']['tmp_name']);
				save_image($_FILES['userfile2'], $_POST['media_type2'], $benchID, $userID);
				$mediaURLs[] = "https://{$domain}/image/{$sha1}/1024";
			}
			if ($_FILES['userfile3']['tmp_name'])
			{
				$sha1 = sha1_file($_FILES['userfile3']['tmp_name']);
				save_image($_FILES['userfile3'], $_POST['media_type3'], $benchID, $userID);
				$mediaURLs[] = "https://{$domain}/image/{$sha1}/1024";
			}
			if ($_FILES['userfile4']['tmp_name'])
			{
				$sha1 = sha1_file($_FILES['userfile4']['tmp_name']);
				save_image($_FILES['userfile4'], $_POST['media_type4'], $benchID, $userID);
				$mediaURLs[] = "https://{$domain}/image/{$sha1}/1024";
			}

			//	Drop us an email
			$key = urlencode(get_edit_key($benchID));
			mail(NOTIFICATION_EMAIL,
				"Bench {$benchID}",
				"{$inscription}\nhttps://{$domain}/bench/{$benchID}\n\n" .
				"Edit: https://{$domain}/edit/{$benchID}/{$key}/"
			);

			//	Send the user to the bench's page
			header("Location: /edit/{$benchID}/{$key}/");
			
			//	Tweet the bench
			try {
				tweet_bench($benchID, $mediaURLs, $inscription, $location["lat"], $location["lng"], "CC BY-SA 4.0");
			} catch (Exception $e) {
				var_export($e);
				die();
			}
			
			die();
		} else {
			$error_message .= "<h3>No location metadata found in image</h3>";
		}		
	}
} else if (null != $inscription) {
	//	If a photo hasn't been posted, recover the inscription and show an error
	$error_message .= "<h3>Ooops! Looks like you didn't add a photo</h3>";
}

//	Start the normal page
include("header.php");

$twitter_name = get_twitter_details()[1];
if(null == $twitter_name) {
	$login_html = "You are not logged in. That's cool. You can post anonymously, or <a href='/login/'>sign in with Twitter</a>.";	
} else {
	$login_html = "You are logged in as @{$twitter_name}";
}

	echo "<p>{$login_html}</p>";
?>
	<form action="/add.php" enctype="multipart/form-data" method="post" onsubmit="submitButton.disabled = true; return true;">
		<h2>Add A Bench</h2>
		All you need to do is type in what is written on the bench and add a photo.
		The photo <em>must</em> have GPS information included.
		<?php
			echo $error_message;
		?>
		<label for="inscription">Inscription:</label><br>
		<textarea id="inscription" name="inscription" cols="40" rows="6"
			placeholder="In loving memory of 
Buffy Anne Summers 
She saved the world 
A lot... "><?php echo $inscription; ?></textarea>

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
		<input class="hand-drawn" type="submit" name="submitButton" value="Share Bench" />
	</form>
	<br>&nbsp;
	<small>By adding a bench, you agree that you own the copyright of the photo and that you are making it freely available under the
		<a href="https://creativecommons.org/licenses/by-sa/4.0/">Creative Commons Attribution-ShareAlike 4.0 International (CC BY-SA 4.0) license</a>.
		<br>
		This means other people can use the photo and its data without having to ask permission. Thanks!
		<br>
		See our <a href="https://www.openbenches.org/blog/privacy/">privacy policy</a> to understand how your photo's data is used.
	</small>
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

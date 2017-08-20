<?php
session_start();
require_once ("config.php");
require_once ("mysql.php");
require_once ("functions.php");

function duplicate_file($filename) {
	$sha1 = sha1_file($filename);
	$photo_full_path = get_path_from_hash($sha1, true);
	
	// echo "{$photo_path} and {$photo_full_path}";
	// die();
	
	//	Does this photo already exit?
	if(file_exists($photo_full_path)){
		return true;
	}
	return false;
}

function save_image($filename, $media_type, $benchID, $userID) {
	$sha1 = sha1_file($filename);
	$photo_full_path = get_path_from_hash($sha1, true);
	$photo_path      = get_path_from_hash($sha1, false);


	//	Check to see if this has the right EXIF tags for a photosphere
	if (is_photosphere($filename)) {
		$media_type = "360";
	} else if ("360" == $media_type){
		//	If it has been miscategorised, remove the media type
		$media_type = null;
	}

	//	Move media to the correct location
	if (!is_dir($photo_path)) {
		mkdir($photo_path, 0777, true);
	}
	$moved = move_uploaded_file($filename, $photo_full_path);

	//	Add the media to the database
	if ($moved){
		$mediaID = insert_media($benchID, $userID, $sha1, "CC BY-SA 4.0", null, $media_type);
	} else {
		echo "Unable to move {$filename} to {$photo_full_path} - bench {$benchID} user {$userID} media {$media_type}";
		die();
	}

}

//	Get the inscription, either to add to database, or recover in case of error
$inscription = $_POST['inscription'];
$error_message = "";

if (null == $inscription) {
	$error_message .= "<h3>Please type in the text of the inscription</h3>";
} else if ($_FILES['userfile1']['tmp_name'])
{	//	Has a photo been posted?
	$filename = $_FILES['userfile1']['tmp_name'];
	$sha1 = sha1_file ($filename);	//	For tweeting

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
			$userID = insert_user("anon", $_SERVER['REMOTE_ADDR'], date(DateTime::ATOM));

			$media_type = $_POST['media_type1'];

			//	Insert Bench
			$benchID = insert_bench($location["lat"],$location["lng"], $inscription, $userID);

			//	Save the Image
			save_image($filename, $media_type, $benchID, $userID);

			//	Save other images
			if ($_FILES['userfile2']['tmp_name'])
			{
				save_image($_FILES['userfile2']['tmp_name'], $_POST['media_type2'], $benchID, $userID);
			}
			if ($_FILES['userfile3']['tmp_name'])
			{
				save_image($_FILES['userfile3']['tmp_name'], $_POST['media_type3'], $benchID, $userID);
			}
			if ($_FILES['userfile4']['tmp_name'])
			{
				save_image($_FILES['userfile4']['tmp_name'], $_POST['media_type4'], $benchID, $userID);
			}

			//	Drop us an email
			$key = urlencode(get_edit_key($benchID));
			mail(NOTIFICATION_EMAIL,
				"Bench {$benchID}",
				"{$inscription}\nhttps://openbenches.org/bench/{$benchID}\n\n" .
				"Edit: https://openbenches.org/edit/{$benchID}/{$key}/"
			);

			//	Tweet the bench
			try {
				tweet_bench($benchID, $sha1, $inscription, $location["lat"], $location["lng"], "CC BY-SA 4.0");
			} catch (Exception $e) {
			}
			

			//	Send the user to the bench's page
			header("Location: /edit/{$benchID}/{$key}/");
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
				<img   id="photoPreview1" src="#" alt="Your 1st photo" style="display: none;"/>
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
				<img   id="photoPreview2" src="#" alt="Your 2nd photo" style="display: none;" />
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
				<img   id="photoPreview3" src="#" alt="Your 3rd photo" style="display: none;" />
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
				<img   id="photoPreview4" src="#" alt="Your last photo" style="display: none;" />
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
	This means other people can use the photo and its data without having to ask permission. Thanks!</small>
	<script src="/libs/exifjs/exif.min.js"></script>
	<script type="text/javascript">
		var previewWidth = 200;

		document.getElementById("photoFile1").onchange = function (e) {
			var reader = new FileReader();
			var preview1 = document.getElementById("photoPreview1");
			//	Remove any existing image
			preview1.style.display = "none";
			reader.onload = function (e) {
				// get loaded data and render thumbnail.
				preview1.style.display = "block";
				preview1.src = e.target.result;
				preview1.width = previewWidth;
			};
			// read the image file as a data URL.
			reader.readAsDataURL(this.files[0]);
			//	Check if GPS Tag exists. First photo only.
			EXIF.getData(e.target.files[0], function() {
				if( (EXIF.getTag(this, "GPSLongitude") == null)) {
					alert("Warning! No GPS tags detected in photo.\nPlease check your camera's settings or add a different photo.");
				}
			});
			//	Show the next upload box
			document.getElementById('photo2').style.display = "block";
		}
		
		document.getElementById("photoFile2").onchange = function () {
			var reader = new FileReader();
			var preview2 = document.getElementById("photoPreview2");
			preview2.style.display = "none";
			reader.onload = function (e) {
				// get loaded data and render thumbnail.
				preview2.style.display = "block";
				preview2.src = e.target.result;
				preview2.width = previewWidth;
			};
			// read the image file as a data URL.
			reader.readAsDataURL(this.files[0]);
			//	Show the next upload box
			document.getElementById('photo3').style.display = "block";
		}
		
		document.getElementById("photoFile3").onchange = function () {
			var reader = new FileReader();
			var preview3 = document.getElementById("photoPreview3");
			preview3.style.display = "none";
			
			reader.onload = function (e) {
				// get loaded data and render thumbnail.
				preview3.style.display = "block";
				preview3.src = e.target.result;
				preview3.width = previewWidth;
			};
			// read the image file as a data URL.
			reader.readAsDataURL(this.files[0]);
			//	Show the next upload box
			document.getElementById('photo4').style.display = "block";
		}
		
		document.getElementById("photoFile4").onchange = function () {
			var reader = new FileReader();
			var preview4 = document.getElementById("photoPreview4");
			preview4.style.display = "none";
			reader.onload = function (e) {
				// get loaded data and render thumbnail.
				preview4.style.display = "block";
				preview4.src = e.target.result;
				preview4.width = previewWidth;
			};
			// read the image file as a data URL.
			reader.readAsDataURL(this.files[0]);
		}
	</script>
<?php
	include("footer.php");

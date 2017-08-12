<?php
session_start();
require_once ("config.php");
require_once ("mysql.php");
require_once ("functions.php");

//	Start the normal page
include("header.php");

function save_image($filename, $media_type, $benchID, $userID) {
	$sha1 = sha1_file ($filename);

	$directory = substr($sha1,0,1);
	$subdirectory = substr($sha1,1,1);
	$photo_path = "photos/".$directory."/".$subdirectory."/";
	$photo_full_path = $photo_path.$sha1.".jpg";

	//	Does this photo already exit?
	if(file_exists($photo_full_path)){
		$error_message .= "<h3>That photo already exists in the database</h3>";
	} else {
		if (!is_dir($photo_path)) {
			mkdir($photo_path, 0777, true);
		}

		//	Check to see if this has the right EXIF tags for a photosphere
		if (is_photosphere($filename)) {
			$media_type = "360";
		} else if ("360" == $media_type){
			//	If it has been miscategorised, remove the media type
			$media_type = null;
		}

		//	Add the media to the database
		if (null != $benchID){
			$mediaID = insert_media($benchID, $userID, $sha1, "CC BY-SA 4.0", null, $media_type);
		}

		//	Move media to the correct location
		if (null != $mediaID){
			move_uploaded_file($filename, $photo_path.$sha1.".jpg");
		}
	}
}

//	Get the inscription, either to add to database, or recover in case of error
$inscription = $_POST['inscription'];
$error_message = "";

if (null == $inscription) {
	$error_message .= "<h3>Please type in the text of the inscription.</h3>";
}

//	Has a photo been posted?
if ($_FILES['userfile1']['tmp_name'])
{
	$filename = $_FILES['userfile1']['tmp_name'];
	$sha1 = sha1_file ($filename);
	$media_type = $_POST['media_type1'];

	$location = get_image_location($filename);

	//	If there is a GPS tag on the photo
	if (false != $location)
	{
		//	Add the user to the database
		$userID = insert_user("anon", $_SERVER['REMOTE_ADDR'], date(DateTime::ATOM));

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
			"{$inscription} https://openbenches.org/bench/{$benchID} from " . $_SERVER['REMOTE_ADDR'] .
			" - edit https://openbenches.org/edit/{$benchID}/{$key}/"
		);

		//	Tweet the bench
		tweet_bench($benchID, $sha1, $inscription, $location["lat"], $location["lng"], "CC BY-SA 4.0");

		//	Send the user to the bench's page
		header("Location: /edit/{$benchID}/{$key}/");
		die();
	} else {
		$error_message .= "<h3>No location metadata found in image</h3>";
	}
} else if (null != $inscription) {
	//	If a photo hasn't been posted, recover the inscription and show an error
	$error_message .= "<h3>Ooops! Looks like you didn't add a photo.</h3>";
}
?>
	<br>
	<form action="add.php" enctype="multipart/form-data" method="post" onsubmit="submitButton.disabled = true; return true;">
		<h2>Add A Bench</h2>
		All you need to do is type in what is written on the bench and add a photo.
		The photo <em>must</em> have GPS information included.
		<?php
			echo $error_message;
		?>
		<label for="inscription">Inscription:</label><br>
		<textarea id="inscription" name="inscription" cols="40" rows="6"><?php echo $inscription; ?></textarea>
		<br>&nbsp;<br>&nbsp;

		<div id="photo1" style="display: block;">
			<fieldset>
				<legend>Geotagged Photo</legend>
				<input id="photoFile1" name="userfile1" type="file" accept="image/jpg, image/jpeg" />
				<br>&nbsp;<br>&nbsp;
				<label for="media_type1">This photo is a:</label>
				<?php
					echo get_media_types_html("1");
				?>
			</fieldset>
		</div><br>&nbsp;
		<div id="photo2" style="display: none;">
			<fieldset>
				<legend>Optional Photo</legend>
				<input id="photoFile2" name="userfile2" type="file" accept="image/jpg, image/jpeg" />
				<br>&nbsp;<br>&nbsp;
				<label for="media_type2">This photo is a:</label>
				<?php
					echo get_media_types_html("2");
				?>
			</fieldset>
		</div><br>&nbsp;
		<div id="photo3" style="display: none;">
			<fieldset>
				<legend>Optional Photo</legend>
				<input id="photoFile3" name="userfile3" type="file" accept="image/jpg, image/jpeg" />
				<br>&nbsp;<br>&nbsp;
				<label for="media_type3">This photo is a:</label>
				<?php
					echo get_media_types_html("3");
				?>
			</fieldset>
		</div><br>&nbsp;
		<div id="photo4" style="display: none;">
			<fieldset>
				<legend>Optional Photo</legend>
				<input id="photoFile4" name="userfile4" type="file" accept="image/jpg, image/jpeg" />
				<br>&nbsp;<br>&nbsp;
				<label for="media_type4">This photo is a:</label>
				<?php
					echo get_media_types_html("4");
				?>
			</fieldset>
		</div>
		<input class="hand-drawn" type="submit" name="submitButton" value="Share Bench" />
	</form>
	<br>&nbsp;
	<small>By adding a bench, you agree that you own the copyright of the photo and that you are making it freely available under the
	<a href="https://creativecommons.org/licenses/by-sa/4.0/">Creative Commons Attribution-ShareAlike 4.0 International (CC BY-SA 4.0) license</a>.
	<br>
	This means other people can use the photo and its data without having to ask permission. Thanks!</small>
	<script type="text/javascript">
		document.getElementById('photoFile1').onchange = function() { document.getElementById('photo2').style.display = "block";}
		document.getElementById('photoFile2').onchange = function() { document.getElementById('photo3').style.display = "block";}
		document.getElementById('photoFile3').onchange = function() { document.getElementById('photo4').style.display = "block";}
	</script>
<?php
	include("footer.php");

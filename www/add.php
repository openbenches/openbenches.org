<?php
session_start();
require_once ("config.php");
require_once ("mysql.php");
require_once ("functions.php");

//	Start the normal page
include("header.php");

//	Get the inscription, either to add to database, or recover in case of error
$inscription = $_POST['inscription'];
$error_message = "";

if (null == $inscription) {
	$error_message .= "<h3>Please type in the text of the inscription.</h3>";
}

//	Has a photo been posted?
if ($_FILES['userfile']['tmp_name'])
{
	$filename = $_FILES['userfile']['tmp_name'];
	$sha1 = sha1_file ($filename);

	$location = get_image_location($filename);

	//	If there is a GPS tag on the photo
	if (false != $location)
	{
		$directory = substr($sha1,0,1);
		$subdirectory = substr($sha1,1,1);
		$photo_path = "photos/".$directory."/".$subdirectory."/";
		$photo_full_path = $photo_path.$sha1.".jpg";

		//	Does this photo already exit?
		if(file_exists($photo_full_path)){
			$error_message .= "<h3>That photo already exists in the database</h3>";
		}	else {
			if (!is_dir($photo_path)) {
				mkdir($photo_path, 0777, true);
			}

			//	Add the user to the database
			$userID = insert_user("anon", $_SERVER['REMOTE_ADDR'], date(DateTime::ATOM));

			//	Insert Bench
			$benchID = insert_bench($location["lat"],$location["lng"], $inscription, $userID);

			//	Add the media to the database
			if (null != $benchID){
				$mediaID = insert_media($benchID, $userID, $sha1);
			}

			//	Move media to the correct location
			if (null != $mediaID){
				move_uploaded_file($_FILES['userfile']['tmp_name'], $photo_path.$sha1.".jpg");
				//	Drop us an email
				mail(NOTIFICATION_EMAIL,
					"Bench {$benchID}",
					"{$inscription} https://openbenches.org/bench/{$benchID} from " . $_SERVER['REMOTE_ADDR']);

				//	Tweet the bench
	 			tweet_bench($benchID, $sha1, $inscription, $location["lat"], $location["lng"], "CC BY-SA 4.0");

				//	Send the user to the bench's page
				header("Location: bench/{$benchID}");
				die();
			}
		}
	} else {
		$error_message .= "<h3>No location metadata found in image</h3>";
	}
} else if (null != $inscription) {
	//	If a photo hasn't been posted, recover the inscription and show an error
	$error_message .= "<h3>Ooops! Looks like you didn't add a photo.</h3>";
}
?>
	<br>
	<form action="add.php" enctype="multipart/form-data" method="post">
		<h2>Add A Bench</h2>
		All you need to do is type in what is written on the bench and add a photo.
		The photo <em>must</em> have GPS information included.
		<?php
			echo $error_message;
		?>
		<div>
			<label for="inscription">Inscription:</label><br>
			<textarea id="inscription" name="inscription" cols="40" rows="6"><?php echo $inscription; ?></textarea>
		</div>
		<div>
			<label for="photo">Geotagged Photo:</label>
			<input id="photo" name="userfile" type="file" accept="image/jpg,image/jpeg" />
		</div>
		<br>
		<input type="submit" value="Share Bench" />
		<br>
		By adding a bench, you agree that you own the copyright of the photo and that you are making it freely available under the
		<a href="https://creativecommons.org/licenses/by-sa/4.0/">Creative Commons Attribution-ShareAlike 4.0 International (CCBY-SA 4.0) license</a>.
		<br>
		This means other people can use the photo without having to ask permission. Thanks!
	</form>
	<br>
	<br>
	<div class="button-bar">
		<a href="/" class="hand-drawn">Go Home</a>
	</div>
<?php
	include("footer.php");

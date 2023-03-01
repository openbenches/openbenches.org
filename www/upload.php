<?php
if(!isset($_SESSION)) { session_start(); }
require_once ("config.php");
require_once ("mysql.php");
require_once ("functions.php");

//	What has been POSTed to us?
//	Get the inscription, either to add to database, or recover in case of error

$error_message = "";

$inscription = $_POST['inscription'];
$lat = $_POST['newLatitude'];
$lng = $_POST['newLongitude'];

$response = array("Open" => "Benches");

//	Get any tags
if (isset($_POST['tags'])){
	$sentTags = $_POST['tags'][0];
	$tags = explode(",", $sentTags);
} else {
	$tags = null;
}


if ($_FILES['userfile1']['tmp_name'])
{	//	Has a photo been posted?
	$filename = $_FILES['userfile1']['tmp_name'];
	$sha1 = sha1_file ($filename);

	//	For tweeting
	$domain = $_SERVER['SERVER_NAME'];
	$mediaURLs = array();
	$mediaURLs[] = "https://{$domain}/image/{$sha1}/1024";

	if (duplicate_file($filename))
	{
		$error_filename = $_FILES['userfile1']['name'];
		$error_message .= "{$error_filename} already exists in the database.<br /><a href=\"/add\">Please reload this page and try a different photo</a>";
	} else {
		//	Does the first file have a GPS location?
		$location = get_image_location($filename);

		//	If there is a GPS tag on the photo
		if (false != $location)
		{
			//	Add the user to the database
			[$user_provider, $user_providerID, $user_name] = get_user_details(true);
			if (null == $user_provider) {
				$userID = insert_user("anon", $_SERVER['REMOTE_ADDR'], date(DateTime::ATOM));
			} else {
				$userID = insert_user($user_provider, $user_providerID, $user_name);
			}

			//	Insert Bench
			$benchID = insert_bench($lat, $lng, $inscription, $userID);
			//	Send the user to the bench's page
			// $response["redirect"] = $benchID;
			// echo json_encode($response);
			echo $benchID;

			//	Save the Image
			$media_type = $_POST['media_type1'];
			save_image($_FILES['userfile1'], $media_type, $benchID, $userID);

			//	Save other images
			if (!empty($_FILES['userfile2']['tmp_name']))
			{
				$sha1 = sha1_file($_FILES['userfile2']['tmp_name']);
				save_image($_FILES['userfile2'], $_POST['media_type2'], $benchID, $userID);
				$mediaURLs[] = "https://{$domain}/image/{$sha1}/1024";
				$mediaFiles[] = get_path_from_hash($sha1,true);
			}
			if (!empty($_FILES['userfile3']['tmp_name']))
			{
				$sha1 = sha1_file($_FILES['userfile3']['tmp_name']);
				save_image($_FILES['userfile3'], $_POST['media_type3'], $benchID, $userID);
				$mediaURLs[] = "https://{$domain}/image/{$sha1}/1024";
				$mediaFiles[] = get_path_from_hash($sha1,true);
			}
			if (!empty($_FILES['userfile4']['tmp_name']))
			{
				$sha1 = sha1_file($_FILES['userfile4']['tmp_name']);
				save_image($_FILES['userfile4'], $_POST['media_type4'], $benchID, $userID);
				$mediaURLs[] = "https://{$domain}/image/{$sha1}/1024";
				$mediaFiles[] = get_path_from_hash($sha1,true);
			}

			 if (!empty($tags)){
			 	save_tags($benchID, $tags);
			 }

			//	Drop us an email
			$key = urlencode(get_edit_key($benchID));
			$photos = "";
			foreach($mediaURLs as $img){
				$photos .= $img."\n";
			}
			$duplicate_count = get_duplicates_count($inscription) - 1;
			$soundex = get_soundex($inscription);

			mail(NOTIFICATION_EMAIL,
				"Bench {$benchID}",
				"Possible duplicates {$duplicate_count}\n" .
				"{$inscription}\nhttps://{$domain}/bench/{$benchID}\n\n" .
				"Edit: https://{$domain}/edit/{$benchID}/{$key}/\n\n" .
				"From: {$user_provider} {$user_name}\n\n".
				"Duplicates: https://{$domain}/search/?soundex={$soundex}\n\n".
				$photos
			);

			//	Tweet the bench
			try {
				tweet_bench($benchID, $mediaURLs, $inscription, $lat, $lng, "CC BY-SA 4.0", $user_provider, $user_name);
			} catch (Exception $e) {
				// var_export($e);
				return null;
			}

			//	Post the bench to Mastodon
			try {
				mastodon_bench($benchID, $inscription, "CC BY-SA 4.0", $user_provider, $user_name);
			} catch (Exception $e) {
				// var_export($e);
				return null;
			}

			return null;
		} else {
			$error_message .= "No location metadata found in image.<br /><a href=\"/add\">Please reload this page and try a different photo</a>";
		}
	}
} else {
	$error_message .= "Ooops! Looks like you didn't add a photo.<br /><a href=\"/add\">Please reload this page and try a different photo</a>";

	$error_message .= "<pre>" . var_export($_POST, true) . "\n\n" .var_export($_FILES, true) . "</pre>";

	$response["error"] = $error_message;
}

echo $error_message;

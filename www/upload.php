<?php
// header('Content-Type: application/json');

session_start();
require_once ("config.php");
require_once ("mysql.php");
require_once ("functions.php");

//	What has been POSTed to us?
//	Get the inscription, either to add to database, or recover in case of error
$inscription = $_POST['inscription'];
$lat = $_POST['newLatitude'];
$lng = $_POST['newLongitude'];

$response = array("Open" => "Benches");
$error_message = "";

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
		$error_message .= "{$error_filename} already exists in the database";
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
			$benchID = insert_bench($lat,$lng, $inscription, $userID);
			//	Send the user to the bench's page
			// $response["redirect"] = $benchID;
			// echo json_encode($response);
			echo $benchID;
			
			//	Save the Image
			$media_type = $_POST['media_type1'];
			save_image($_FILES['userfile1'], $media_type, $benchID, $userID);

			//	Save other images
			if ($_FILES['userfile2']['tmp_name'])
			{
				$sha1 = sha1_file($_FILES['userfile2']['tmp_name']);
				save_image($_FILES['userfile2'], $_POST['media_type2'], $benchID, $userID);
				$mediaURLs[] = "https://{$domain}/image/{$sha1}/1024";
				$mediaFiles[] = get_path_from_hash($sha1,true);
			}
			if ($_FILES['userfile3']['tmp_name'])
			{
				$sha1 = sha1_file($_FILES['userfile3']['tmp_name']);
				save_image($_FILES['userfile3'], $_POST['media_type3'], $benchID, $userID);
				$mediaURLs[] = "https://{$domain}/image/{$sha1}/1024";
				$mediaFiles[] = get_path_from_hash($sha1,true);
			}
			if ($_FILES['userfile4']['tmp_name'])
			{
				$sha1 = sha1_file($_FILES['userfile4']['tmp_name']);
				save_image($_FILES['userfile4'], $_POST['media_type4'], $benchID, $userID);
				$mediaURLs[] = "https://{$domain}/image/{$sha1}/1024";
				$mediaFiles[] = get_path_from_hash($sha1,true);
			}
			

			//	Drop us an email
			$key = urlencode(get_edit_key($benchID));
			$photos = "";
			foreach($mediaURLs as $img){
				$photos .= $img."\n";
			}
			mail(NOTIFICATION_EMAIL,
				"Bench {$benchID}",
				"{$inscription}\nhttps://{$domain}/bench/{$benchID}\n\n" .
				"Edit: https://{$domain}/edit/{$benchID}/{$key}/\n\n" .
				$photos
			);

			//	Tweet the bench
			try {
				tweet_bench($benchID, $mediaURLs, $inscription, $lat, $lng, "CC BY-SA 4.0");
			} catch (Exception $e) {
				// var_export($e);
				die();
			}
			
			//	Mastodon Toot the bench
			// try {
			// 	toot_bench($benchID, $mediaFiles, $inscription, "CC BY-SA 4.0");
			// } catch (Exception $e) {
			// 	var_export($e);
			// 	die();
			// }

			die();
		} else {
			$error_message .= "No location metadata found in image";
		}
	}
} else {
	$error_message .= "Ooops! Looks like you didn't add a photo";
	$response["error"] = $error_message;
}

// echo json_encode($response);
echo $error_message;
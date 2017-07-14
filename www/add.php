<?php
session_start();
require_once ('config.php');
require_once ("mysql.php");
require_once ("functions.php");

require_once ('codebird.php');
\Codebird\Codebird::setConsumerKey(OAUTH_CONSUMER_KEY, OAUTH_CONSUMER_SECRET);

$cb = \Codebird\Codebird::getInstance();

if (! isset($_SESSION['oauth_token'])) {
	// get the request token
	$reply = $cb->oauth_requestToken([
		'oauth_callback' => 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']
	]);

	// store the token
	$cb->setToken($reply->oauth_token, $reply->oauth_token_secret);
	$_SESSION['oauth_token'] = $reply->oauth_token;
	$_SESSION['oauth_token_secret'] = $reply->oauth_token_secret;
	$_SESSION['oauth_verify'] = true;

	// redirect to auth website
	$auth_url = $cb->oauth_authorize();
	header('Location: ' . $auth_url);
	die();

} elseif (isset($_GET['oauth_verifier']) && isset($_SESSION['oauth_verify'])) {
	// verify the token
	$cb->setToken($_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);
	unset($_SESSION['oauth_verify']);

	// get the access token
	$reply = $cb->oauth_accessToken([
		'oauth_verifier' => $_GET['oauth_verifier']
	]);

	// store the token (which is different from the request token!)
	$_SESSION['oauth_token'] = $reply->oauth_token;
	$_SESSION['oauth_token_secret'] = $reply->oauth_token_secret;

	// send to same URL, without oauth GET parameters
	header('Location: ' . basename(__FILE__));
	die();
}

// assign access token on each page load
$cb->setToken($_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);

$reply = (array) $cb->account_verifyCredentials();

//	If the authorization hasn't worked, clear the session variables and start again
if($reply["errors"]) {
	$_SESSION['oauth_token'] = null;
	$_SESSION['oauth_token'] = null;
	$_SESSION['oauth_token_secret'] = null;
	$_SESSION['oauth_verify'] = null;
	// send to same URL, without oauth GET parameters
	header('Location: ' . basename(__FILE__));
	die();
}

//	Get the user's ID & name
$id_str = $reply["id_str"];
$screen_name = $reply["screen_name"];

//	Add the user to the database
$userID = insert_user("twitter", $id_str, $screen_name);

//	Start the normal page
include("header.php");
//	Has a photo been posted?
if ($_FILES['userfile']['tmp_name'])
{
	$inscription = $_POST['inscription'];
	$filename = $_FILES['userfile']['tmp_name'];
	$sha1 = sha1_file ($filename );

	$location = get_image_location($filename);

	if (false != $location)
	{
		$directory = substr($sha1,0,1);
		$subdirectory = substr($sha1,1,1);
		$photo_path = "photos/".$directory."/".$subdirectory."/";
		$photo_full_path = $photo_path.$sha1.".jpg";

		if(file_exists($photo_full_path)){
			echo "<h2>That photo already exists in the database</h2>";
		}	else {
			if (!is_dir($photo_path)) {
				mkdir($photo_path, 0777, true);
			}

			$benchID = insert_bench($location["lat"],$location["lng"], $inscription, $userID);

			if (null != $benchID){
				$mediaID = insert_media($benchID, $userID, $sha1);
			}
			if (null != $mediaID){
				move_uploaded_file($_FILES['userfile']['tmp_name'], $photo_path.$sha1.".jpg");
				echo "Added! {$benchID} at ". $location["lat"] . "," . $location["lng"] .
						" and media {$mediaID} with sha1 {$sha1} from twitter/{$id_str}";
				echo "<br><img width='480' src=\"" . $photo_full_path . "\" />";

				mail(NOTIFICATION_EMAIL, "Bench {$benchID}", "{$inscription} https://openbenches.org/{$photo_full_path}");

				header("Location: bench.php?benchID={$benchID}");
				die();
			}
		}


	} else {
		echo "<h2>No location metadata found in image</h2>";
	}
}
?>
	<br>
	<form action="add.php" enctype="multipart/form-data" method="post">
		<h2>Add A Bench</h2>
		Hello <?php echo "user {$screen_name} (twitter/{$id_str})." ?><br>
		All you need to do is type in what is written on the bench and add a photo.
		The photo <em>must</em> have GPS information included.
		<div>
			<label for="inscription">Inscription:</label><br>
			<textarea id="inscription" name="inscription" cols="40" rows="6"></textarea>
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

	</form>
	<br>
	<br>
	<div class="button-bar">
		<a href="/" class="hand-drawn">Go Home</a>
	</div>
<?php
	include("footer.php");

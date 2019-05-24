<?php
if(!isset($_SESSION)) { session_start(); }
require_once ('config.php');
require_once ("mysql.php");
require_once ("functions.php");

//	Start the normal page
include("header.php");
//	Has a photo been posted?
if ($_GET["flickrID"])
{
	$flickrID = $_GET["flickrID"];
	$flickrKey = FLICKR_API_KEY;
	$flickrAPI = "https://api.flickr.com/services/rest/?method=flickr.photos.getInfo&api_key={$flickrKey}&format=json&nojsoncallback=1&photo_id={$flickrID}";

	$flickrJSON = file_get_contents($flickrAPI);
	$flickrData = json_decode($flickrJSON);

	$lat =       $flickrData->{"photo"}->{"location"}->{"latitude"};
	$long =      $flickrData->{"photo"}->{"location"}->{"longitude"};
	$farm =      $flickrData->{"photo"}->{"farm"};
	$server =    $flickrData->{"photo"}->{"server"};
	$id =        $flickrData->{"photo"}->{"id"};
	$owner =     $flickrData->{"photo"}->{"owner"}->{"nsid"};
	$secret =    $flickrData->{"photo"}->{"secret"};
	$o_secret =  $flickrData->{"photo"}->{"originalsecret"};
	$license =   $flickrData->{"photo"}->{"license"};
	$title =     $flickrData->{"photo"}->{"title"}->{"_content"};
	$description=$flickrData->{"photo"}->{"description"}->{"_content"};
	$import = "https://www.flickr.com/photos/{$owner}/{$id}";

	//	Get the largest scaled image which is not the original
	$flickrSizeAPI = "https://api.flickr.com/services/rest/?method=flickr.photos.getSizes&api_key={$flickrKey}&format=json&nojsoncallback=1&photo_id={$flickrID}";
	$flickrSizeJSON = file_get_contents($flickrSizeAPI);
	$flickrSizeData = json_decode($flickrSizeJSON);
	$sizes = $flickrSizeData->{"sizes"}->{"size"};
	end($sizes);
	$size = prev($sizes);
	$large = $size->{"source"};
	$b64 = base64_encode(file_get_contents($large));


	switch ($license) {
		case 0:
			$license = "All Rights Reserved";
			break;
		case 1:
			$license = "CC BY-NC-SA 2.0";
			break;
		case 2:
			$license = "CC BY-NC 2.0";
			break;
		case 3:
			$license = "CC BY-NC-ND 2.0";
			break;
		case 4:
			$license = "CC BY 2.0";
			break;
		case 5:
			$license = "CC BY-SA 2.0";
			break;
		case 6:
			$license = "CC BY-ND 2.0";
			break;
		case 7:
			$license = "PD";
			break;
		case 8:
			$license = "USG";
			break;
	}

	$original = "https://farm{$farm}.staticflickr.com/{$server}/{$id}_{$o_secret}_o.jpg";
	// $large = "https://farm{$farm}.staticflickr.com/{$server}/{$id}_{$secret}_h.jpg";
	echo '<form action="flickr.php" enctype="multipart/form-data" method="post">';
	echo "<a class='hand-drawn' name='detectButton' id='detectButton' onclick='sendURLToCloudVision(\"{$b64}\")'>Detect Text</a>";
	echo '<code style="white-space:pre" id="message"></code>';
	echo "<textarea name='inscription' id='inscription' cols='70' rows='6'>{$title} {$description}</textarea><br>";
	echo '<input type="submit" value="Share Bench" /><br>';
	echo "<a target='_blank' href='{$original}'><img id='flickrimg' src='{$large}' width='512'/></a><br>";
	echo "<input type='text' name='image'   size='60' value='{$original}'><br>";
	echo "<input type='text' name='lat'     size='60' value='{$lat}'><br>";
	echo "<input type='text' name='long'    size='60' value='{$long}'><br>";
	echo "<input type='text' name='license' size='60' value='{$license}'><br>";
	echo "<input type='text' name='import'  size='60' value='{$import}'><br>";
	echo "</form>";
?>
<script src="/libs/jquery.3.3.1/jquery-3.3.1.min.js"></script>
<script src="/libs/vision/key.js"></script>
<script>
var CV_URL = 'https://vision.googleapis.com/v1/images:annotate?key=' + window.apiKey;

function sendURLToCloudVision (content) {
	console.log("sending this url" + content);
	// Strip out the file prefix when you convert to json.
	var request = {
		requests: [{
			image: {
				content: content
			},
			features: [{
				type: 'TEXT_DETECTION'
			}]
		}]
	};

	$('#message').text('Scanning for text...');
	console.log("posting " + request);
	$.post({
		url: CV_URL,
		data: JSON.stringify(request),
		contentType: 'application/json'
	}).fail(function (jqXHR, textStatus, errorThrown) {
		$('#message').text('ERRORS: ' + textStatus + ' ' + errorThrown);
	}).done(displayJSON);
}

function displayJSON (data) {
	//	Get the text
	console.log("display");
	console.log(data);
	var contents = data.responses[0].fullTextAnnotation.text;
	//	Add the detected inscription
	$('#inscription').val(contents);

	var evt = new Event('results-displayed');
	evt.results = contents;
	document.dispatchEvent(evt);
}
</script>
<?php
	// var_export($flickrData->{"photo"});
	die();
} elseif ($_POST != null) {
	$inscription = $_POST['inscription'];
	$imageURL = $_POST['image'];
	$import = $_POST['import'];
	$license = $_POST["license"];
	$lat = $_POST["lat"];
	$long = $_POST["long"];
	$filename =  "photos/tmp/" . (microtime(true) * 1000);
	$photo = file_put_contents($filename, file_get_contents($imageURL));
	$sha1 = sha1_file ($filename);

	$directory = substr($sha1,0,1);
	$subdirectory = substr($sha1,1,1);
	$photo_path = "photos/".$directory."/".$subdirectory."/";
	$photo_full_path = $photo_path.$sha1.".jpg";

	if(file_exists($photo_full_path)){
		echo "<h2>That photo already exists in the database</h2>";
		echo "{$photo_full_path}";
	}	else {
		if (!is_dir($photo_path)) {
			mkdir($photo_path, 0777, true);
		}

		$benchID = insert_bench($lat, $long, $inscription, 1);

		if (null != $benchID){
			$mediaID = insert_media($benchID, 1, $sha1, $license, $import);
		}
		if (null != $mediaID){
			//	Move the file
			rename($filename, $photo_full_path);

			// Send email
			// mail(NOTIFICATION_EMAIL, "Bench {$benchID}", "{$inscription} https://openbenches.org/{$photo_full_path}");

			//	Tweet the bench
 			tweet_bench($benchID, $sha1, $inscription, $lat, $long, $license);

			//	Send the user to the bench's page
			header("Location: bench/{$benchID}");
			die();
		}
	}
}

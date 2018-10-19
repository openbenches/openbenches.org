<?php
session_start();
require_once ('config.php');
require_once ("mysql.php");
require_once ("functions.php");

//	Start the normal page
include("header.php");
//	Which iamge
if ($_GET["wikiID"])
{
	$wikiID = $_GET["wikiID"];
	$wikiAPI = "https://tools.wmflabs.org/magnus-toolserver/commonsapi.php?meta&image={$wikiID}";

	$ch = curl_init(); 
	curl_setopt($ch, CURLOPT_URL, $wikiAPI);
	curl_setopt($ch, CURLOPT_USERAGENT, "OpenBenches.org" );
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$wikiXML = curl_exec($ch);
	curl_close($ch);

	$wikiData = simplexml_load_string($wikiXML)[0];

	$lat =       $wikiData->{"file"}->{"location"}->{"lat"};
	$long =      $wikiData->{"file"}->{"location"}->{"lon"};
	
	$license =   $wikiData->{"licenses"}->{"license"}->{"name"};
	
	$original =  $wikiData->{"file"}->{"urls"}->{"file"};
	$import =    $wikiData->{"file"}->{"urls"}->{"description"};
	$sha1 =      $wikiData->{"file"}->{"sha1"};

	//	https://commons.wikimedia.org/wiki/Commons:FAQ#What_are_the_strangely_named_components_in_file_paths.3F
	//	https://stackoverflow.com/questions/33689980/get-thumbnail-image-from-wikimedia-commons
	//	https://upload.wikimedia.org/wikipedia/commons/thumb/a/a8/File_name.jpg/200px-File_name.jpg
	$file = $wikiData->{"file"}->{"name"};
	$file = str_replace(" ", "_",$file);
	$md5 = md5($file);
	$path1 = substr($md5, 0, 1);
	$path2 = substr($md5, 0, 2);
		
	$thumbnail = "https://upload.wikimedia.org/wikipedia/commons/thumb/{$path1}/{$path2}/{$file}/512px-{$file}";
	$large =     "https://upload.wikimedia.org/wikipedia/commons/thumb/{$path1}/{$path2}/{$file}/1024px-{$file}";
	
	$b64 = base64_encode(file_get_contents($large));

	switch ($license) {
		case "CC-Zero":
			$license = "CC Zero";
			break;
		case "CC-BY-1.0":
			$license = "CC BY 1.0";
			break;
		case "CC-BY-2.0":
			$license = "CC BY 2.0";
			break;
		case "CC-BY-3.0":
			$license = "CC BY 3.0";
			break;
		case "CC-BY-4.0":
			$license = "CC BY 4.0";
			break;
		case "CC-BY-SA-1.0":
			$license = "CC BY-SA 1.0";
			break;
		case "CC-BY-SA-2.0":
			$license = "CC BY-SA 2.0";
			break;
		case "CC-BY-SA-3.0":
			$license = "CC BY-SA 3.0";
			break;
		case "CC-BY-SA-4.0":
			$license = "CC BY-SA 4.0";
			break;
	}

	echo '<form action="wikipedia.php" enctype="multipart/form-data" method="post">';
	echo "<a class='hand-drawn' name='detectButton' id='detectButton' onclick='sendURLToCloudVision(\"{$b64}\")'>Detect Text</a>";
	echo '<code style="white-space:pre" id="message"></code>';
	echo "<textarea name='inscription' id='inscription' cols='70' rows='6'></textarea><br>";
	echo '<input type="submit" value="Share Bench" /><br>';
	echo "<a target='_blank' href='{$original}'><img id='wikiimg' src='{$thumbnail}' width='512'/></a><br>";
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
	die();
} elseif ($_POST != null) {
	$inscription = $_POST['inscription'];
	$imageURL    = $_POST['image'];
	$import      = $_POST['import'];
	$license     = $_POST["license"];
	$lat         = $_POST["lat"];
	$long        = $_POST["long"];
	$filename =  "photos/tmp/" . (microtime(true) * 1000);
	$photo = file_put_contents($filename, file_get_contents($imageURL));
	$sha1  = sha1_file ($filename);

	$directory    = substr($sha1,0,1);
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

		$benchID = insert_bench($lat, $long, $inscription, 2);

		if (null != $benchID){
			$mediaID = insert_media($benchID, 2, $sha1, $license, $import);
		}
		if (null != $mediaID){
			//	Move the file
			rename($filename, $photo_full_path);

			// Send email
			mail(NOTIFICATION_EMAIL, "Bench {$benchID}", "{$inscription} https://openbenches.org/{$photo_full_path}");

			//	Tweet the bench
 			tweet_bench($benchID, $sha1, $inscription, $lat, $long, $license);

			//	Send the user to the bench's page
			header("Location: bench/{$benchID}");
			die();
		}
	}
}

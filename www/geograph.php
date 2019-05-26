<?php
if(!isset($_SESSION)) { session_start(); }
require_once ("config.php");
require_once ("mysql.php");
require_once ("functions.php");

//	Start the normal page
include("header.php");

//	Has a photo been posted?
if ($_GET["url"])
{
	$url = $_GET["url"];
	$ggJSON = file_get_contents("https://api.geograph.org.uk/api/oembed?format=json&url={$url}");
	$ggData = json_decode($ggJSON);

	$title = nl2br($ggData->title);
	$description = nl2br($ggData->description);


	$long  = $ggData->geo->long;
	$lat   = $ggData->geo->lat;

	//	https://t0.geograph.org.uk/stamp.php?id=3697075&title=on&gravity=SouthEast&hash=a08becc6&large=1&pointsize=24&download=0

	//	Get the data needed to build the image URL
	$photo = $ggData->url;
	$path  = parse_url($ggData->url)["path"];
	$pathArray = explode("/", parse_url($ggData->url)["path"]);
	$filename = end($pathArray);
	$plainFile = explode(".", $filename)[0];
	$fileArray = explode("_", $plainFile);
	$id   = $fileArray[0];
	$hash = $fileArray[1];

	$license = $ggData->license_url;

	$url   = $ggData->web_page;


	//	Get the largest image
        	       // https://t0.geograph.org.uk/stamp.php?id=5983414&title=on&gravity=SouthEast&hash=09b272ba&large=1&pointsize=24
	$original = "https://t0.geograph.org.uk/stamp.php?id={$id}&title=on&gravity=SouthEast&hash={$hash}";
	$large    = $ggData->url;

	//	Encode for sending to cloud vision
	$b64 = base64_encode(file_get_contents($large));

	//	UI for checking
	echo '<form action="geograph.php" enctype="multipart/form-data" method="post">';
	echo "{$title}<br/>{$description}<br/>";
	echo "<a class='button' name='detectButton' id='detectButton' onclick='sendURLToCloudVision(\"{$b64}\")'>Detect Text</a>";
	echo '<code style="white-space:pre" id="message"></code>';
	echo "<textarea name='inscription' id='inscription' cols='70' rows='6'></textarea><br>";
	echo "<a target='_blank' href='{$original}'><img src='{$original}' width='1024'/></a><br>";
	echo get_media_types_html();
	echo $license;
	echo "<select name='license'>";
		echo "<option value='CC BY-SA 2.0'>CC BY-SA 2.0</option>";
	echo "</select>";
	echo "<input type='text' name='id'  size='60' value='{$id}'><br>";
	echo "<div>
		<div id='map'></div>
		<div id='benchImage' ></div>
	</div>";
	echo '<input type="submit" value="Share Bench" /><br>';
	echo "</form>";
	echo "<script src='/data.json/?longitude={$long}&latitude={$lat}&radius=10000'></script>";

?>
<script src="/libs/jquery.3.3.1/jquery-3.3.1.min.js"></script>
<script src="/libs/vision/key.js"></script>

<?php echo get_map_javascript($lat, $long, "17"); ?>

<script>
map.on("moveend", function () {
	var stateObj = { x: "y" };
	history.pushState(stateObj, "Open Benches", "/#" +
		map.getCenter().lat.toPrecision(7) + "/" +
		map.getCenter().lng.toPrecision(7) + "/" +
		map.getZoom().toString()
	);
});

markers.on('click', function (bench) {
	var xhr = typeof XMLHttpRequest != 'undefined' ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
	xhr.open('get', '/benchimage/'+bench.layer["options"]["benchID"], true);
	xhr.onreadystatechange = function() {
		if (xhr.readyState == 4 && xhr.status == 200) {
			document.getElementById("benchImage").innerHTML = xhr.responseText;
		}
	}
	xhr.send();
});

for (var i = 0; i < benches.features.length; i++) {
	var bench = benches.features[i];
	var lat = bench.geometry.coordinates[1];
	var longt = bench.geometry.coordinates[0];
	var benchID = bench.id;
	var title = bench.properties.popupContent + "<br><a href='/bench/"+bench.id+"/'>View details</a>";

	// console.log('bench ' + benchID);
	var marker = L.marker(new L.LatLng(lat, longt), {  benchID: benchID });

	marker.bindPopup(title);
	markers.addLayer(marker);
}

map.addLayer(markers);
</script>

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
	$id = $_POST['id'];

	$ggJSON = file_get_contents("https://api.geograph.org.uk/api/oembed?format=json&url=http%3A%2F%2Fwww.geograph.org.uk%2Fphoto%2F{$id}");
	$ggData = json_decode($ggJSON);

	$long  = $ggData->geo->long;
	$lat   = $ggData->geo->lat;

	//	https://t0.geograph.org.uk/stamp.php?id=3697075&title=on&gravity=SouthEast&hash=a08becc6&large=1&pointsize=24&download=0

	//	Get the data needed to build the image URL
	$photo = $ggData->url;
	$path  = parse_url($ggData->url)["path"];
	$pathArray = explode("/", parse_url($ggData->url)["path"]);
	$filename = end($pathArray);
	$plainFile = explode(".", $filename)[0];
	$fileArray = explode("_", $plainFile);
	$id   = $fileArray[0];
	$hash = $fileArray[1];

	$license = $ggData->license_url;

	$url   = $ggData->web_page;
	$import = $ggData->web_page;

	//	Get the largest image
	$original = "https://t0.geograph.org.uk/stamp.php?id={$id}&title=on&gravity=SouthEast&hash={$hash}";

	$license = $_POST["license"];
	$mediaType = $_POST["media_type"];


	$filename =  "photos/tmp/" . (microtime(true) * 1000);
	$photo = file_put_contents($filename, file_get_contents($original));
	$sha1 = sha1_file($filename);

	$directory = substr($sha1,0,1);
	$subdirectory = substr($sha1,1,1);
	$photo_path = "photos/".$directory."/".$subdirectory."/";
	$photo_full_path = $photo_path.$sha1.".jpg";

	//	geograph.org.uk's user id in database
	$userID = 4;

	if(file_exists($photo_full_path)){
		echo "<h2>That photo already exists in the database</h2>";
		echo "{$photo_full_path}";
	}	else {
		if (!is_dir($photo_path)) {
			mkdir($photo_path, 0777, true);
		}

		$benchID = insert_bench($lat, $long, $inscription, $userID);
		if (null != $benchID){
			$mediaID = insert_media($benchID, $userID, $sha1, $license, $import, $mediaType);
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

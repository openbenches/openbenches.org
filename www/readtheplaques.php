<?php
if(!isset($_SESSION)) { session_start(); }
require_once ("config.php");
require_once ("mysql.php");
require_once ("functions.php");

//	Start the normal page
include("header.php");
//	Has a photo been posted?
if ($_GET["plaqueURL"])
{
	$plaqueURL = $_GET["plaqueURL"];
	$rtpJSON = file_get_contents("https://readtheplaque.com/geojson/{$plaqueURL}");
	$rtpData = json_decode($rtpJSON);

	$long  = $rtpData->geometry->coordinates[0];
	$lat   = $rtpData->geometry->coordinates[1];

	$photo = $rtpData->properties->img_url_tiny;
	$url   = "https://readtheplaque.com" . $rtpData->properties->title_page_url;

	$photo = str_replace("http://", "https://", $photo);

	//	Get the largest image
	//	Documentation: https://sites.google.com/site/picasaresources/Home/Picasa-FAQ/google-photos-1/how-to/how-to-get-a-direct-link-to-an-image
	$original = str_replace("=s100-c", "=s0", $photo);
	$large    = str_replace("=s100-c", "=s2048", $photo);

	//	Encode for sending to cloud vision
	$b64 = base64_encode(file_get_contents($large));

	//	UI for checking
	echo '<form action="readtheplaques.php" enctype="multipart/form-data" method="post">';
	echo "<iframe width='1024' height='512' src='{$url}'></iframe><br>";
	echo "<a class='button' name='detectButton' id='detectButton' onclick='sendURLToCloudVision(\"{$b64}\")'>Detect Text</a>";
	echo '<code style="white-space:pre" id="message"></code>';
	echo "<textarea name='inscription' id='inscription' cols='70' rows='6'></textarea><br>";
	echo "<a target='_blank' href='{$original}'><img src='{$large}' width='1024'/></a><br>";
	echo get_media_types_html();
	echo "<select name='license'>";
		echo "<option value='CC BY 4.0'>CC BY 4.0</option>";
		echo "<option value='CC BY-SA 2.0'>CC BY-SA 2.0</option>";
	echo "</select>";
	echo "<input type='text' name='plaqueURL'  size='60' value='{$plaqueURL}'><br>";
	echo "<div>
		<div id='map'></div>
		<div id='benchImage' ></div>
	</div>";
	echo '<input type="submit" value="Share Bench" /><br>';
	echo "</form>";
	echo "<script src='/api/v1.0/data.json/?longitude={$long}&latitude={$lat}&radius=10000'></script>";

?>
<script src="/libs/jquery.3.3.1/jquery-3.3.1.min.js"></script>
<script src="/libs/vision/key.js"></script>

<?php echo get_map_javascript($lat, $long, "14"); ?>

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

	$rtpJSON = file_get_contents("https://readtheplaque.com/geojson/".$_POST["plaqueURL"]);
	$rtpData = json_decode($rtpJSON);

	$long  = $rtpData->geometry->coordinates[0];
	$lat   = $rtpData->geometry->coordinates[1];

	$img   = $rtpData->properties->img_url_tiny;
	$import= "https://readtheplaque.com" . $rtpData->properties->title_page_url;

	$img = str_replace("http://", "https://", $img);

	//	Get the largest image
	//	Documentation: https://sites.google.com/site/picasaresources/Home/Picasa-FAQ/google-photos-1/how-to/how-to-get-a-direct-link-to-an-image
	$imageURL = str_replace("=s100-c", "=s0", $img);

	$license = $_POST["license"];
	$mediaType = $_POST["media_type"];


	$filename =  "photos/tmp/" . (microtime(true) * 1000);
	$photo = file_put_contents($filename, file_get_contents($imageURL));
	$sha1 = sha1_file($filename);

	$directory = substr($sha1,0,1);
	$subdirectory = substr($sha1,1,1);
	$photo_path = "photos/".$directory."/".$subdirectory."/";
	$photo_full_path = $photo_path.$sha1.".jpg";

	//	readtheplaque's user id in database
	$userID = 3;

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

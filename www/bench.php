<?php
require_once ('config.php');
require_once ('mysql.php');
require_once ('functions.php');

include("header.php");

if (!$published) {
	//	Bench was deleted
	//	Dealth with in 404.php
	return null;
}

if ($benchAddress == null){
	$benchAddress = update_bench_address($benchID, $benchLat, $benchLong);
}

//	Format the address
$locations = explode("," , $benchAddress);
$locations = array_reverse( $locations );

$location_html = "";
$location_link = "/location";
$location_html_array = [];
foreach ($locations as $location) {
	if (null != $location) {
		$location_link .= "/" . urldecode(trim($location));
		$location_html_array[] = "<a href=\"$location_link\">" . htmlspecialchars( urldecode($location) ). "</a>";
	}
}
$location_html_array = array_reverse( $location_html_array );
$benchAddress = implode(", " , $location_html_array);



if (!$present) {
	//	Has the bench been removed
	$benchInscription = "<em>This bench has been removed from this physical location.</em><br><del>{$benchInscription}</del>";
}
?>

	<div itemscope itemtype="http://schema.org/Place">
		<h2  id="benchInscription" itemprop="description"><?php echo nl2br($benchInscription); ?></h2>
		<?php echo get_image_html($benchID); ?>
		<div itemprop="geo"                           itemscope itemtype="http://schema.org/GeoCoordinates">
			<div id="address"       itemprop="address" itemscope itemtype="http://schema.org/PostalAddress"><?php echo $benchAddress; ?></div>
			<div id="map"></div>
			<meta                   itemprop="latitude"  content="<?php echo $benchLat;  ?>" />
			<meta                   itemprop="longitude" content="<?php echo $benchLong; ?>" />
		</div>
	</div>

	<div id="taglist">
		<?php
		$tags = get_tags_from_bench($benchID);
		if(!empty($tags)){
			echo "<ul>";
			echo    "<li class='pseudo button'>Tags:</li>";

			foreach ($tags as $tag) {
				echo "<li class='pseudo button'><a href='/tag/{$tag}/'>#{$tag}</a></li>";
			}
			echo "</ul>";
		}
		?>
	</div>

	<div class="button-bar">
		<a class="button buttonColour" href="/#<?php echo $benchLat ?>/<?php echo $benchLong ?>/16"><strong>🌍</strong> Benches near this</a>
		<a href="/add" class="button buttonColour"><strong>+</strong> Add new bench</a>
		<a href="/edit/<?php echo $benchID; ?>" class="button buttonColour"><strong>✏️</strong> Edit this bench</a>
		<a href="/api/v1.0/data.json/?truncated=false&format=raw&media=true&bench=<?php echo $benchID; ?>" class="button buttonColour"><strong>💾</strong> Download GeoJSON</a>

	</div>
	<?php
		include("sharing.php");
		include("searchform.php");
	?>
	<div id="comments">
		<script>
			var idcomments_acct = '2c821c4a265bb30b50b1127cf2b99934';
			var idcomments_post_id;
			var idcomments_post_url;
		</script>
		<span id="IDCommentsPostTitle" style="display:none"></span>
		<script src='https://www.intensedebate.com/js/genericCommentWrapperV2.js'></script>
	</div>

<script src="/api/v1.0/data.json/?bench=<?php echo $benchID; ?>"></script>

<?php echo get_map_javascript($benchLat, $benchLong, "16"); ?>

<script>
for (var i = 0; i < benches.features.length; i++) {
	var bench = benches.features[i];
	var lat = bench.geometry.coordinates[1];
	var longt = bench.geometry.coordinates[0];
	var benchID = bench.id;
	var title = bench.properties.popupContent;
	var marker = L.marker(new L.LatLng(lat, longt), {  benchID: benchID, draggable: false });

	marker.bindPopup(title);
	markers.addLayer(marker);
}

map.addLayer(markers);
</script>
<?php
	include("footer.php");

<?php
require_once ('config.php');
require_once ('mysql.php');
require_once ('functions.php');

include("header.php");

if ($benchAddress == null){
	$benchAddress = update_bench_address($benchID, $benchLat, $benchLong);
}

if(!$published) {
	header("HTTP/1.1 404 Not Found");
	include("404.php");
	die();
}
?>
	<div itemscope itemtype="http://schema.org/Place">
		<h2  id="benchInscription" itemprop="description"><?php echo nl2br($benchInscription); ?></h2>
		<?php echo get_image_html($benchID); echo get_user_from_bench($benchID); ?>
		<div itemprop="geo"                           itemscope itemtype="http://schema.org/GeoCoordinates">
			<div id="address"       itemprop="address" itemscope itemtype="http://schema.org/PostalAddress"><?php echo $benchAddress; ?></div>
			<div id="map"></div>
			<meta                   itemprop="latitude"  content="<?php echo $benchLat;  ?>" />
			<meta                   itemprop="longitude" content="<?php echo $benchLong; ?>" />
		</div>
	</div>
	<div id="comments">
		<script>
		var idcomments_acct = '2c821c4a265bb30b50b1127cf2b99934';
		var idcomments_post_id;
		var idcomments_post_url;
		</script>
		<span id="IDCommentsPostTitle" style="display:none"></span>
		<script type='text/javascript' src='https://www.intensedebate.com/js/genericCommentWrapperV2.js'></script>
	</div>
	<div class="button-bar">
		<a href="/add" class="hand-drawn">Add a new bench</a>
	</div>
<script src="/data.json/?bench=<?php echo $benchID; ?>" type="text/javascript"></script>

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

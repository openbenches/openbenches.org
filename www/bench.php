<?php
	include("header.php");
	if ($_GET["benchID"]) {
		$benchID = $_GET["benchID"];
	} else {
		$benchID = $params[2];
	}

	list ($benchID, $benchLat, $benchLong, $benchInscription, $published) = get_bench_details($benchID);

?>


	<div id="row1">
		<div id="benchInscription"><?php echo nl2br($benchInscription); ?></div>
		<div id='benchImage'><?php echo get_image_html($benchID); echo get_user_from_bench($benchID); ?></div>
		<div id='map'></div>
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
<script src="/geojson/<?php echo $benchID; ?>" type="text/javascript"></script>

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

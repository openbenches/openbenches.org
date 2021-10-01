<form action="/search/" enctype="multipart/form-data" method="get">
	<?php
		echo ( isset($error_message) ? $error_message : "");
	?>
	<div class="searchbox">
		<input type="search" class="search" id="inscription" name="search"
			placeholder="in loving memory of"
			aria-label="Search"
			value="<?php echo ( isset($query) ? htmlspecialchars($query) : "") ; ?>">
		<button type="submit" class="button buttonColour"><strong>🔎</strong> Search inscriptions</button>
	</div>
</form>
<div class="button-bar">
	<form action="/bench/" method="post">
		<input id="random" name="random" value="random" type="hidden" />
		<button type="submit" class="button buttonColour"><strong>🔀</strong> Random bench</button>
		<span class="button buttonColour" onclick="geoFindMe()" id="gpsButton"><strong>📍</strong> Benches near me</span>
	</form>
</div>

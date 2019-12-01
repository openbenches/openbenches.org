<form action="/search/" enctype="multipart/form-data" method="get">
	<?php
		echo ( isset($error_message) ? $error_message : "");
	?>
	<div>
		<input type="search" class="search" id="inscription" name="search"
			placeholder="in loving memory of"
			aria-label="Search"
			value="<?php echo ( isset($query) ? htmlspecialchars($query) : "") ; ?>">
		<br>
		<input type="submit" class="button buttonColour" value="🔎 Search inscriptions" />
	</div>
</form>
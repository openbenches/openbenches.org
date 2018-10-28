<?php
	include("header.php");

?>
</hgroup>

<div id="leaderboard-results">
	<h3>Most added and edited benches</h3>
	<?php echo get_leadboard_benches_html(); ?>
	<h3>Most added photos</h3>
	<?php echo get_leadboard_media_html(); ?>
</div>

<div class="button-bar">
	<form action="/bench/" method="post">
		<input id="random" name="random" value="random" type="hidden" />
		<input type="submit" class="hand-drawn" value="Show me a random bench" />
		<a href="/add/" class="hand-drawn">Add bench</a>
	</form>
</div>
<br>
<form action="/search/" enctype="multipart/form-data" method="get">
	<?php
		echo $error_message;
	?>
	<h2>Search for an inscription</h2>
	<div>
		<input type="search" id="inscription" name="search" class="search" value="<?php echo htmlspecialchars($query); ?>">
		<input type="submit" class="hand-drawn" value="Search inscriptions" />
	</div>
</form>
<?php
	include("footer.php");

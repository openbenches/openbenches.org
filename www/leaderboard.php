<?php
	$page_title = "- Leaderboard";
	include("header.php");
?>

<div id="leaderboard-results">
	<h3>Most added and edited benches</h3>
	<?php echo get_leadboard_benches_html(); ?>
	<h3>Most added photos</h3>
	<?php echo get_leadboard_media_html(); ?>
</div>

<?php
	include("footer.php");

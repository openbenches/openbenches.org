<?php
$broken_images = array("f0ce9dd7f357bebaf86609fec57b48394385da0b",
                       "5bcbaa4e7f2e30810c2bb81125b57dbcd957577f");
$broken_image = $broken_images[array_rand($broken_images,1)];
?>
<h2>404 - Bench Not Found</h2>
<img src="/image/<?echo $broken_image; ?>/600" class="proxy-image" alt="Photograph of a bench with a removed plaque">
<form action="/search/" enctype="multipart/form-data" method="get">
	<h2>Search for an inscription</h2>
	<div>
		<input type="search" id="inscription" name="search" class="search" value="">
		<input type="submit" class="hand-drawn" value="Search inscriptions" />
	</div>
</form>
<?php
	include("footer.php");

<?php

$URL = urlencode("https://" . $_SERVER['SERVER_NAME'] .  $_SERVER['REQUEST_URI']);

$facebook  = "https://www.facebook.com/sharer/sharer.php?u={$URL}";
$twitter   = "https://twitter.com/intent/tweet?url={$URL}&via=openbenches";
$pinterest = "https://pinterest.com/pin/create/button/?url={$URL}";
$email     = "mailto:?&body={$URL}";
$whatsapp  = "https://api.whatsapp.com/send?text={$URL}";
$telegram  = "https://telegram.me/share/url?url={$URL}";
?>
<button class="button buttonColour" type="button" id="shareButton">📤 Share this bench</button>

<fieldset id="sharing" style="display:none;">

	<a href="<?php echo $facebook; ?>" target="_blank">
		<img src="/images/svg/facebook.svg" class="share" alt="Share on Facebook"/>
	</a>

	<a href="<?php echo $twitter; ?>" target="_blank">
		<img src="/images/svg/twitter.svg" class="share" alt="Share on Twitter"/>
	</a>

	<a href="<?php echo $pinterest; ?>" target="_blank">
		<img src="/images/svg/pinterest.svg" class="share" alt="Share on Pinterest"/>
	</a>

	<a href="<?php echo $whatsapp; ?>" target="_blank">
		<img src="/images/svg/whatsapp.svg" class="share" alt="Share on Whatsapp"/>
	</a>

	<a href="<?php echo $email; ?>" target="_blank">
		<img src="/images/svg/gmail.svg" class="share" alt="Share on email"/>
	</a>

	<a href="<?php echo $telegram; ?>" target="_blank">
		<img src="/images/svg/telegram.svg" class="share" alt="Share on Telegram"/>
	</a>

</fieldset>


<script>
document.getElementById("shareButton").addEventListener('click', event => {
	if (navigator.share) {
		navigator.share({
			url: ''
		}).then(() => {
			console.log('Thanks for sharing!');
		})
		.catch(console.error);
	} else {
		console.log('No native sharing!');
		document.getElementById("shareButton").style.display="none";
		document.getElementById("sharing").style.display="block";
	}
});
</script>

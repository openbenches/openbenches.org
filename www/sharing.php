<?php 

$URL = urlencode("https://" . $_SERVER['SERVER_NAME'] .  $_SERVER['REQUEST_URI']);

$facebook  = "https://www.facebook.com/sharer/sharer.php?u={$URL}";
$twitter   = "https://twitter.com/intent/tweet?url={$URL}&via=openbenches";
$gplus     = "https://plus.google.com/share?url={$URL}";
$pinterest = "https://pinterest.com/pin/create/button/?url={$URL}";
$email     = "mailto:?&body={$URL}";
$whatsapp  = "https://api.whatsapp.com/send?text={$URL}";
$telegram  = "https://telegram.me/share/url?url={$URL}";
$reddit    = "https://reddit.com/submit?url={$URL}";
?>
<fieldset id="sharing">
	<legend>Share this bench</legend>
	
	<a href="<?php echo $facebook; ?>">
		<img src="/images/svg/facebook.svg" class="share" alt="Share on Facebook"/>
	</a>
	
	<a href="<?php echo $twitter; ?>">
		<img src="/images/svg/twitter.svg" class="share" alt="Share on Twitter"/>
	</a>
	
	<a href="<?php echo $pinterest; ?>">
		<img src="/images/svg/pinterest.svg" class="share" alt="Share on Pinterest"/>
	</a>

	<a href="<?php echo $whatsapp; ?>">
		<img src="/images/svg/whatsapp.svg" class="share" alt="Share on Whatsapp"/>
	</a>
	
	<a href="<?php echo $gplus; ?>">
		<img src="/images/svg/google_plus.svg" class="share" alt="Share on Google Plus"/>
	</a>

	<a href="<?php echo $email; ?>">
		<img src="/images/svg/gmail.svg" class="share" alt="Share on email"/>
	</a>
	
	<a href="<?php echo $telegram; ?>">
		<img src="/images/svg/telegram.svg" class="share" alt="Share on Telegram"/>
	</a>

	<a href="<?php echo $reddit; ?>">
		<img src="/images/svg/reddit.svg" class="share" alt="Share on Reddit"/>
	</a>

</fieldset>
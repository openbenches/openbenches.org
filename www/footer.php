<footer>
	<hr>
	<a href="/"><img src="/images/openbencheslogo.svg" alt="Homepage" id="homeIcon"></a> |
	<a href="/blog/about/">About</a> |
	<a href="/leaderboard/">Leader Board</a> |
	<span itemscope itemtype="https://schema.org/Organization">
	  	<a itemprop="sameAs" href="https://twitter.com/openbenches">Twitter</a> |
	  	<a itemprop="sameAs" href="https://github.com/openbenches/openbenches.org">GitHub</a>
	</span>
	<br>
	<a itemprop="license"
		rel="license"
		href="https://creativecommons.org/licenses/by-sa/4.0/"><img src="/images/cc/cc-by-sa.svg" id="cc-by-sa-logo" alt="Creative Commons Attribution Share-alike"/></a>
	<br>
		Made with 💖 in Oxford by<br>
	<a itemprop="creator" href="https://shkspr.mobi/blog">Terence Eden</a> and
	<a itemprop="creator" href="https://mymisanthropicmusings.org.uk/">Elizabeth Eden</a>.
</footer>
<script>
	if('serviceWorker' in navigator) {
		navigator.serviceWorker
			.register('/sw.js')
			.then(function() { console.log("Service Worker Registered"); });
	}
</script>
</body>
</html>

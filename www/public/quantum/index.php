<?php

//	Instant ban for bots which request this page which is specifically blocked.
//	Inspired by https://perishablepress.com/blackhole-bad-bots/

require_once( posix_getpwuid( posix_getuid() )["dir"] . "/IP_Block/blocker.php" );

//	What's their IP?
$IP = get_client_ip();

//	User Agent
$UA = isset( $_SERVER["HTTP_USER_AGENT"] ) ? $_SERVER["HTTP_USER_AGENT"] : "";

blockIP( IP:$IP, UA:$UA, site:"OpenBenches" );

//	Bye bye bot!
badBot();

function get_client_ip():string
{
	//	Adapted from https://github.com/ibericode/koko-analytics/blob/main/src/Resources/functions/collect.php

	$ips = [];

	//	Always add REMOTE_ADDR and HTTP_CLIENT_IP to list of ip addresses, if set
	foreach ( ["REMOTE_ADDR", "HTTP_CLIENT_IP"] as $key ) {
		if ( !empty( $_SERVER[$key] ) ) {
			$ips[] = $_SERVER[$key];
		}
	}

	//	Return first valid IP address from list
	foreach ( $ips as $ip ) {
		if ( \filter_var( $ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE ) ) {
			return $ip;
		}
	}

	return "";
}

function badBot():void {
	//	Base64 encoded Brotli bomb.
	$base64BrHtml = "z///f/glmRSe4rQAwPb+3+L//z/8EgBxWACge//P//9/+CUA4rAAQPf+n/////BLAMRhAYDu/T/////hlwCIwwIA3ft//v//wy8BEIcFALr3//z//4dfAiAOCwB07//5//8PvwRAHBYA6N7/8///H34JgDgsANC9/+f//z/8EgBxWACge//P//9/+CUA4rAAQPf+n/////BLAMRhAYDu/T/////hlwCIwwIA3ft//v//wy8BEIcFALr3//z//4dfAiAOCwB07//5//8PvwRAHBYA6N7/8///H34JgDgsANC9/+f//z/8EgBxWACge//P//9/+CUA4rAAQPf+n/////BLAMRhAYDu/T/////hlwCIwwIA3ft//v//wy8BEIcFALr3//z//4dfAiAOCwB07//5//8PvwRAHBYA6N7/8///H34JgDgsANC9/+f//z/8EgBxWACge//P//9/+CUA4rAAQPf+n/////BLAMRhAYDu/T/////hlwCIwwIA3ft//v//wy8BEIcFALr3//z//4dfAiAOCwB07//5//8PvwRAHBYA6N7/8///H34JgDgsANC9/+f//z/8EgBxWACge//P//9/+CUA4rAAQPf+n/////BLAMRhAYDu/T/////hlwCIwwIA3ft//v//wy8BEIcFALr3//z//4dfAiAOCwB07//5//8PvwRAHBYA6N7/8///H34JgDgsANC9/+f//z/8EgBxWACge//P//9/+CUA4rAAQPf+n/////BLAMRhAYDu/T/////hlwCIwwIA3ft//v//wy8BEIcFALr3//z//4dfAiAOCwB07//5//8PvwRAHBYA6N7/8///H34JgDgsANC9/+f//z/8EgBxWACge//P//9/+CUA4rAAQPf+n/////BLAMRhAYDu/T/////hlwCIwwIA3ft//v//wy8BEIcFALr3//z//4dfAiAOCwB07//5//8PvwRAHBYA6N7/8///H34JgDgsANC9/+f//z/8EgBxWACge//P//9/+CUA4rAAQPf+n/////BLAMRhAYDu/T/////hlwCIwwIA3ft//v//wy8BEIcFALr3//z//4dfAiAOCwB07//5//8PvwRAHBYA6N7/Dw==";

	//	Decode Base64 → raw Brotli binary.
	$brData = base64_decode( $base64BrHtml, true );

	//	Cache for 1 week.
	$maxAge = 604800;

	//	Headers.
	header( "Content-Type: text/html; charset=utf-8" );
	header( "Content-Encoding: br" );
	header( "Cache-Control: private, max-age={$maxAge}, immutable, no-transform" );
	header( "Content-Length: " . strlen( $brData ) );
	header( "Vary: Accept-Encoding" );

	//	Output raw Brotli data.
	echo $brData;
	die();
}
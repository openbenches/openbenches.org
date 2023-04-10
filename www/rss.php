<?php
require_once ('mysql.php');

$howManyBenches=30; # Positive integer. The RSS feed contains this many images of benches

$imgHeight=300; # Positive integer. Height, in pixels, of images (if) included in rss feed

# build up the XML as a string then spew it all out at once
# this allows east changing of where the XML goes, e.g. to stdout
# or written to a file

$buildDate = date(DATE_RSS);

$rssXML = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
  <channel>
    <title>OpenBenches.org</title>
    <description>An open data repository for memorial benches</description>
    <link>https://{$_SERVER['HTTP_HOST']}/</link>
    <image>
      <url>https://{$_SERVER['HTTP_HOST']}/images/icons/icon-512x512.png</url>
      <title>OpenBenches.org</title>
      <link>https://{$_SERVER['HTTP_HOST']}/</link>
    </image>
    <atom:link href="https://{$_SERVER['HTTP_HOST']}/rss" rel="self" type="application/rss+xml" />
    <lastBuildDate>{$buildDate}</lastBuildDate>
    <language>en-gb</language>

EOT;

$items = get_rss($howManyBenches);

foreach ($items as $item) {
	$benchID = $item["benchID"];
	$benchInscription = $item["benchInscription"];
	$sha1 = $item["sha1"];
	$benchAddress = $item["benchAddress"];
	$benchAdded = $item["benchAdded"];
	
	$rssXML.="<item>\n";
	$rssXML.=	"<title>Bench {$benchID}</title>\n";

	$rssXML.=	"<description><![CDATA[";
	$rssXML.=		$benchInscription;
	$rssXML.=		"<br>\n";

	foreach ($sha1 as $images) {
		$rssXML.=	"<img src=\"https://{$_SERVER['HTTP_HOST']}/image/{$images}/{$imgHeight}\" height=\"{$imgHeight}\">\n";
		$rssXML.=	"<br>\n";
	}

	$rssXML.=		$benchAddress;
	$rssXML.=	"]]></description>\n";

	$rssXML.=	"<link>https://{$_SERVER['HTTP_HOST']}/bench/{$benchID}</link>\n";
	$rssXML.=	"<guid isPermaLink=\"true\">https://{$_SERVER['HTTP_HOST']}/bench/{$benchID}</guid>\n";
	$rssXML.=	"<pubDate>".date(DATE_RSS, strtotime($benchAdded))."</pubDate>\n";
	$rssXML.="</item>\n";
}

# finish off the XML
$rssXML.="</channel>\n</rss>";

header('Content-Type: application/rss+xml; charset=utf-8');
echo $rssXML;
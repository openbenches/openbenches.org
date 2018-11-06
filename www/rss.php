<?php

require_once ('config.php');
require_once ('mysql.php');
require_once ('functions.php');


# BEGIN values to maybe tweak

$howManyBenches=20; # Positive integer. The RSS feed contains this many benches

$includeImages=true; # Boolean. Whether to include images in the RSS feed

$imgHeight=300; # Positive integer. Height, in pixels, of images (if) included in rss feed

$base64Images=false; # Boolean. *** You almost certainly don't want this *** If true, images will be base64 encoded and included in the xml as data uris. Pros: Images display instantly when rss feed is loaded rather than there being a delay every time it's loaded whilst the server generates them from scratch; If the xml is written to a static file with a cronjob, the images are generated once regardless of how many times that version of the xml is requested. Cons: Greatly increases size of the xml file; Not all rss readers support images included in this way but I already wrote the code before discovering that so it remains as curiosity and warning.

# END values to maybe tweak

# build up the XML as a string then spew it all out at once
# this allows east changing of where the XML goes, e.g. to stdout
# or written to a file

$theXML = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<?xml-stylesheet title="XSL_formatting" type="text/xsl" href="/shared/bsp/xsl/rss/nolsol.xsl"?>
<rss  version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
  <channel>
    <title>OpenBenches.org</title>
    <description>An open data repository for memorial benches</description>
    <link>https://openbenches.org/</link>
    <image>
      <url>https://openbenches.org/images/icons/icon-512x512.png</url>
      <title>OpenBenches.org</title>
      <link>https://openbenches.org/</link>
    </image>
    <atom:link href="https://openbenches.org/rss" rel="self" type="application/rss+xml" />
    <lastBuildDate>Thu, 25 Oct 2018 19:30:54 GMT</lastBuildDate>
    <language>en-gb</language>
EOT;


$get_benches = $mysqli->prepare("select benchID from benches where published = true order by added desc limit {$howManyBenches}");
$get_benches->execute();
$get_benches->bind_result($benchID);
$rssBenches=array();
while($get_benches->fetch()) {
  array_push($rssBenches,$benchID);

}


foreach ($rssBenches as $rssBenchID) {

  $get_benches = $mysqli->prepare("select b.benchID, b.address, b.inscription, b.added, group_concat(sha1)  from benches as b inner join media as m on b.benchID = m.benchID where b.benchID = ?");
  $get_benches->bind_param('i',$rssBenchID);
  $get_benches->execute();
  $get_benches->bind_result($benchID, $address, $inscription, $added, $shas);


# loop through benches and build up XML
while($get_benches->fetch()) {
  $theXML.="<item>";
  $theXML.="<title>Bench {$benchID}</title>";


  $theXML.="<description><![CDATA[";
  $theXML.=$inscription;
  $theXML.="<br>";

  if ($includeImages===true) {

    $shasums=explode(",", $shas);

    if ($base64Images===true) {

      foreach ($shasums as $sha1) {
        $data=base64_encode(file_get_contents("https://openbenches.org/image/{$sha1}/{$imgHeight}"));
        $theXML.="<img src=\"data:image/jpeg;base64,{$data}\">";
        $theXML.="<br>";
      }

    } else {

      foreach ($shasums as $sha1) {
        $theXML.="<img src=\"https://openbenches.org/image/{$sha1}/{$imgHeight}\">";
        $theXML.="<br>";
      }

    }
  }

  $theXML.=$address;
  $theXML.="]]></description>";

  $theXML.="<link>https://openbenches.org/bench/{$benchID}</link>";
  $theXML.="<guid isPermaLink=\"true\">https://openbenches.org/bench/{$benchID}</guid>";
  $theXML.="<pubDate>".date('r', strtotime($added))."</pubDate>";
  $theXML.="</item>";

}

}

# finish off the XML
$theXML.="</channel></rss>";

echo $theXML;
?>

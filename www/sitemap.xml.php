<?php
require_once ('mysql.php');

global $mysqli;

$get_ids = $mysqli->prepare(
	"SELECT benchID FROM benches
	WHERE published = true
	LIMIT 0 , 10000");

$get_ids->execute();

/* bind result variables */
$get_ids->bind_result($benchID);

$sitemap = '<?xml version="1.0" encoding="UTF-8"?>';
$sitemap .='<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

while($get_ids->fetch()){
	$sitemap .= "<url><loc>https://openbenches.org/bench/{$benchID}</loc></url>\n";
}

$sitemap .= "</urlset>";

$get_ids->close();

header("Content-type: text/xml");
echo $sitemap;

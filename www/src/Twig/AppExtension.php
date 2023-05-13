<?php
// src/Twig/AppExtension.php
namespace App\Twig;

use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Tools\DsnParser;

class AppExtension extends AbstractExtension
{	
	public function getFunctions()
	{
		return [
			new TwigFunction('number_of_benches', [$this, 'get_number_of_benches']),
			new TwigFunction('cached_date', [$this, 'get_date']),
			new TwigFunction('map_javascript', [$this, 'map_javascript']),
		];
	}

	public function get_number_of_benches() {

		$cache = new FilesystemAdapter($_ENV["CACHE"] . "count_cache");

		$value = $cache->get('number_of_benches', function (ItemInterface $item) {
			$item->expiresAfter(300);
			$dsnParser = new DsnParser();
			$connectionParams = $dsnParser->parse( $_ENV['DATABASE_URL'] );

			$conn = DriverManager::getConnection($connectionParams);

			$count = $conn->fetchOne("SELECT COUNT(*) FROM `benches` WHERE `published` = true");

			return $count;
		});

		return number_format($value);
	}

	public function get_date() {
		$cache = new FilesystemAdapter("date_cache");
		$value = $cache->get('cached_date', function (ItemInterface $item) {
			$item->expiresAfter(5);
			$date = date("Y-m-d H:i:s");

			return $date;
		});

		return $value;
	}

	public function map_javascript( string $api_url="", string $api_query="",$lat = "16.3", $long="0", $zoom = "2", $draggable = "false" ) {
		$esri_api    = $_ENV['ESRI_API_KEY'];
		$thunder_api = $_ENV['THUNDERFOREST_API_KEY'];
		$api_url .= $api_query;
		$mapJavaScript = <<<EOT
<script>

	// Set up tile layers
	var Stadia_Outdoors = L.tileLayer('https://tiles.stadiamaps.com/tiles/outdoors/{z}/{x}/{y}{r}.png', {
		minZoom: 2,
		maxNativeZoom: 19,
		maxZoom: 22,
		attribution: 'Map data © <a href="https://stadiamaps.com/">Stadia Maps</a>, © <a href="https://openmaptiles.org/">OpenMapTiles</a> © <a href="http://openstreetmap.org">OpenStreetMap</a> contributors',
		id: 'stadia.outdoors'
	});

	var OpenStreetMap_Mapnik = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
		minZoom: 2,
		maxNativeZoom: 19,
		maxZoom: 22,
		attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
		id: 'osm.mapnik'
	});

	var ESRI_Satellite = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}.jpeg?token=$esri_api', {
		minZoom: 2,
		maxNativeZoom: 19,
		maxZoom: 22,
		attribution: '© <a href="https://www.esri.com/">i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community</a>',
		id: 'esri.satellite'
	});

	var Thunderforest = L.tileLayer('https://tile.thunderforest.com/outdoors/{z}/{x}/{y}.png?apikey=$thunder_api', {
		minZoom: 2,
		maxNativeZoom: 19,
		maxZoom: 22,
		attribution: '© <a href="https://www.thunderforest.com/">Thunderforest</a>',
		id: 'thunderforest'
	});

	//	Settings for map		
	var map = L.map('map', {
		sleepNote: false, 
		sleepOpacity: 1,
		minZoom: 2,
		maxZoom: 19,
	})

	//	Placeholder for last (or only) marker
	var marker;
	
	//	Load benches from API
	async function load_benches() {
		let url = '$api_url';
		const response = await fetch(url)
		var benches_json = await response.json();
		return benches_json;
	}

	async function main() {
		var benches = await load_benches();

		//	Set up clustering
		var markers = L.markerClusterGroup({
			maxClusterRadius: 29,
			disableClusteringAtZoom: 17
		});

		markers.on('click', function (bench) {
			//	Placeholder. Used to display images
		});

		//	Add pop-up to markers
		for (var i = 0; i < benches.features.length; i++) {
			var bench = benches.features[i];
			var lat = bench.geometry.coordinates[1];
			var longt = bench.geometry.coordinates[0];
			var benchID = bench.id;
			var title = bench.properties.popupContent + "<br><a href='/bench/"+bench.id+"/'>View details</a>";
		
			marker = L.marker(new L.LatLng(lat, longt), {  benchID: benchID, draggable: $draggable });
		
			marker.bindPopup(title);
			markers.addLayer(marker);
		}

		//	If this is editable
		if ( $draggable == true ) {
			var coordinates = document.getElementById('coordinates');
			var longitude   = document.getElementById('newLongitude');
			var latitude    = document.getElementById('newLatitude');

			marker.on('dragend', function(event){
				newLat =  event.target._latlng.lat.toPrecision(7);
				newLong = event.target._latlng.lng.toPrecision(7);
				coordinates.value = newLat + ',' + newLong;
				longitude.value   = newLong;
				latitude.value    = newLat;
			});
		}

		//	Add the clusters to the map
		map.addLayer(markers);

		
		//	Add the tiles layers
		var baseMaps = {
			"Map View": Stadia_Outdoors,
			"Mapnik": OpenStreetMap_Mapnik,
			"Satellite View": ESRI_Satellite,
			"Outdoors Map": Thunderforest
		};
	
		// Rotate between mapping providers depending on date
		var day = new Date().getDate();
		if (day % 2 == 1) {
			Stadia_Outdoors.addTo(map);
		} else {
			OpenStreetMap_Mapnik.addTo(map);
		}
	
		L.control.layers(baseMaps).addTo(map);
	
		//	Cluster options
		var markers = L.markerClusterGroup({
			maxClusterRadius: 29,
			disableClusteringAtZoom: 17
		});

		//	View of the map
		map.setView([{$lat}, {$long}], {$zoom});

	}
	
	//	Change URl bar to show current location (frontpage only)
	map.on("load", function () {
		if (window.location.hash != "") {
			if(window.location.hash.indexOf("/") > -1)
			{
				var hashArray = window.location.hash.substr(1).split("/");
				if(hashArray.length >= 2)
				{
					var hashLat = hashArray[0];
					var hashLng = hashArray[1];
					var hashZoom = 16; if(hashArray[2] != void 0){hashZoom = hashArray[2];}
					map.setView([hashLat, hashLng], hashZoom);
				}
			}
		}
	});

	main();
</script>
EOT;
	
		echo $mapJavaScript;
	}
}
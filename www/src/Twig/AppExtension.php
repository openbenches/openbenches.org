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
			new TwigFunction('number_of_benches_raw', [$this, 'get_number_of_benches_raw']),
			new TwigFunction('cached_date',       [$this, 'get_date']),
			new TwigFunction('map_javascript',    [$this, 'map_javascript']),
			new TwigFunction('media_types',       [$this, 'get_media_types']),
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

	public function get_number_of_benches_raw() {

		$cache = new FilesystemAdapter($_ENV["CACHE"] . "count_raw_cache");

		$value = $cache->get('number_of_benches_raw', function (ItemInterface $item) {
			$item->expiresAfter(300);
			$dsnParser = new DsnParser();
			$connectionParams = $dsnParser->parse( $_ENV['DATABASE_URL'] );

			$conn = DriverManager::getConnection($connectionParams);

			$count = $conn->fetchOne("SELECT COUNT(*) FROM `benches` WHERE `published` = true");

			return $count;
		});

		return $value;
	}

	public function get_media_types() {
		
		$cache = new FilesystemAdapter($_ENV["CACHE"] . "cache_media_types" );
		$cache_name = "media_types";
		$cachedResult = $cache->get( $cache_name, function (ItemInterface $item) { 
			//	Cache length in seconds
			$item->expiresAfter(600);

			$dsnParser = new DsnParser();
			$connectionParams = $dsnParser->parse( $_ENV['DATABASE_URL'] );
			$conn = DriverManager::getConnection($connectionParams);
			$queryBuilder = $conn->createQueryBuilder();

			$queryBuilder
				->select("shortName", "longName")
				->from("media_types")
				->orderBy("displayOrder", 'ASC');
			$results = $queryBuilder->executeQuery();
			
			$media_types = array();
			while ( ( $row = $results->fetchAssociative() ) !== false) {
				//	Add the details to the array
				$media_types[] = array(
					"shortName" => $row["shortName"],
					"longName"  => $row["longName"],
				);
			}
			return $media_types;

		});

		return $cachedResult;
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

	public function map_javascript( 
			$api = "", 
			$api_query = "", 
			$lat  = "15", 
			$long = "0",
			$zoom = "0", 
			$draggable = false, 
			$bb_n = "_", 
			$bb_e = null, 
			$bb_s = null, 
			$bb_w = null,  ) {
		//	Get the layer key
		$arcgis_key     = $_ENV['ARCGIS_KEY'];
		//	What data is being requested?
		$api_url = $api . $api_query;
		if ( null == $lat ) {
			$lat  = "0";
			$long = "0";
			$zoom = "0";
		}
		$mapJavaScript = <<<EOT
<script>

	//	API to be called
	var api_url = "{$api_url}";
	var lat  = {$lat};
	var long = {$long};
	var zoom = {$zoom};

	//	Placeholder for last (or only) marker
	var marker;

	//	Prevent world wrapping
	const bounds = [
		//	Crop off the Poles
        [-179, -70], // Southwest coordinates
        [ 180,  70] // Northeast coordinates
    ];

	 // Define the styles to switch between
    const style1 = "https://tiles.openfreemap.org/styles/liberty";
    const style2 = "https://basemapstyles-api.arcgis.com/arcgis/rest/services/styles/v2/styles/osm/hybrid/?token={$arcgis_key}";

	//	Initialise the map
	const map = new maplibregl.Map({
		container: 'map',
		style: style1,
		center: [long, lat], // world
		zoom: zoom, // Lower numbers zoomed out
		maxBounds: bounds, // Sets bounds as max
		pitch: 5,	//	small amount of tilt
	});

	//	Disable map rotation using touch rotation gesture
	map.touchZoomRotate.disableRotation();

	//	Add zoom and rotation controls to the map.
	map.addControl(
		new maplibregl.NavigationControl({
			visualizePitch: true,
			showZoom: true,
			showCompass: true
		})
	);

	//	Add geolocations control to the map.
	map.addControl(
		new maplibregl.GeolocateControl({
			positionOptions: {
				enableHighAccuracy: true
			},
		trackUserLocation: true,
		showUserHeading: true
	}));

	//	Load benches from API
	async function load_benches() {
		if (api_url == '') {
			//	No search set - use TSV for speed
			let url = '/api/benches.tsv';
			const response = await fetch(url)
			var benches_text = await response.text();
			var rows = benches_text.split(/\\n/);
			var benches_json = {'features':[]};
			//	Convert the TSV to GeoJSON
			for( let i = 1; i < rows.length; i++ ){
				let cols = rows[i].split(/\\t/);
				benches_json.features.push(
					{
						'id':cols[0],
						'type':'Feature',
						'properties': {
							'popupContent':cols[3]
						},
						'geometry': {
							'type':'Point',
							'coordinates':[cols[1],cols[2]]
						}
					}
				);
			}
			return benches_json;
		} else if (api_url == "_add_") {
			return null;
		} else {
			let url = "{$api_url}";
			const response = await fetch(url)
			var benches_json = await response.json();
			return benches_json;
		}
	}

	
		//	Asynchronous function to add custom layers and sources
    async function addCustomLayersAndSources() {
		//	Get the data
		var benches_data = await load_benches();
		//	Load the GeoJSON
		if (!map.getSource('benches')) {
			map.addSource('benches', {
				type: 'geojson',
				data: benches_data,
				cluster: true,
				clusterMaxZoom: 17, // Max zoom to cluster points on
				clusterRadius: 50 // Radius of each cluster when clustering points (defaults to 50)
			});
		}

		//	Custom bench marker
		if ( map.listImages().includes("openbench-icon") == false ) {
			image = await map.loadImage('/public/images/icons/marker.png');
			map.addImage('openbench-icon', image.data);
		}

		//	Add the clusters
		if (!map.getLayer('clusters')) {
			map.addLayer({
				id: 'clusters',
				type: 'circle',
				source: 'benches',
				filter: ['has', 'point_count'],
				paint: {
					// Use step expressions (https://maplibre.org/maplibre-style-spec/#expressions-step)
					// with three steps to implement three types of circles:
					//   * Blue, 20px circles when point count is less than 100
					//   * Yellow, 30px circles when point count is between 100 and 750
					//   * Pink, 40px circles when point count is greater than or equal to 750
					'circle-color': [
						'step',
						['get', 'point_count'], '#51bbd655',
						100,					'#f1f07555',
						750,					'#f28cb155'
					],
					'circle-radius': [
						'step',
						['get', 'point_count'], 20,
						100,					30,
						750,					40
					],
					'circle-stroke-width': [
						'step',
						['get', 'point_count'], 1,
						100,                    1,
						750,                    1
					],
					'circle-stroke-color': [
						'step',
						['get', 'point_count'],	'#000',
						100,                    '#000',
						750,                    '#000'
					],
				}
			});

			//	Show number of benches in each cluster
			map.addLayer({
				id: 'cluster-count',
				type: 'symbol',
				source: 'benches',
				filter: ['has', 'point_count'],
				layout: {
					'text-field': '{point_count_abbreviated}',
					'text-font': ['Noto Sans Regular'],
					'text-size': 25
				}
			});

			//	Show individual benches
			map.addLayer({
				id: 'unclustered-point',
				source: 'benches',
				filter: ['!', ['has', 'point_count']],
				//type: 'circle',
				//paint: {
				//	'circle-color': '#03ace9',
				//	'circle-radius': 7,
				//	'circle-stroke-width': 2,
				//	'circle-stroke-color': '#000'
				//}
				type: 'symbol',
				layout: {
					"icon-overlap": "always",
					'icon-image': 'openbench-icon',  // Use the PNG image
					'icon-size': .1              // Adjust size if necessary
				}
			});
		}

		//	Inspect a cluster on click
		map.on('click', 'clusters', async (e) => {
			const features = map.queryRenderedFeatures(e.point, {
				layers: ['clusters']
			});
			const clusterId = features[0].properties.cluster_id;
			const zoom = await map.getSource('benches').getClusterExpansionZoom(clusterId);
			console.log("Zoom " + zoom);
			//	Items in the cluster
			const leaves = await map.getSource('benches').getClusterLeaves(clusterId, 10, 0);
			map.easeTo({
				center: features[0].geometry.coordinates,
				zoom
			});

			//	If at a high zoom the cluster hasn't split, the benches may have the exact same co-ordinates.
			if ( zoom >= 17 ) {
				//	Generate a pop-up with all the benches' information & links
				var html = "<h3>Multiple Benches</h3><hr>";
				leaves.forEach(function(leaf) {
        			html +=	leaf.properties.popupContent;
        			html +=	"<br><a href='https://openbenches.org/bench/" +	leaf.id	+ "'>openbenches.org/bench/" + leaf.id + "</a><hr>";
				});

				new maplibregl.Popup({closeButton: false})
					.setLngLat( features[0].geometry.coordinates.slice() )
					.setHTML( html )
					.addTo(map);
			}
		});

		// When a click event occurs on a feature in
		// the unclustered-point layer, open a popup at
		// the location of the feature, with
		// description HTML from its properties.
		map.on('click', 'unclustered-point', (e) => {
			const coordinates = e.features[0].geometry.coordinates.slice();
		
			inscription = e.features[0].properties.popupContent;
			link = e.features[0].id
			link = "openbenches.org/bench/" + link;

			// Ensure that if the map is zoomed out such that
			// multiple copies of the feature are visible, the
			// popup appears over the copy being pointed to.
			while (Math.abs(e.lngLat.lng - coordinates[0]) > 180) {
				coordinates[0] += e.lngLat.lng > coordinates[0] ? 360 : -360;
			}

			new maplibregl.Popup({closeButton: false})
				.setLngLat(coordinates)
				.setHTML( inscription + '<br><a href="https://' + link + '">' + link + '</a>')
				.addTo(map);
		});

		//	Pointer on clusters
		map.on('mouseenter', 'clusters', () => {
			map.getCanvas().style.cursor = 'pointer';
		});
		map.on('mouseleave', 'clusters', () => {
			map.getCanvas().style.cursor = '';
		});

		//	Pointer on individual points
		map.on('mouseenter', 'unclustered-point', () => {
			map.getCanvas().style.cursor = 'pointer';
		});
		map.on('mouseleave', 'unclustered-point', () => {
			map.getCanvas().style.cursor = '';
		});

		// remove distracting POIs
		if (map.getLayer("poi_r20")) {
			map.removeLayer("poi_r20");
		}
		if (map.getLayer("poi_r1")) {
			map.removeLayer("poi_r1");
		}
		if (map.getLayer("poi_transit")) {
			map.removeLayer("poi_transit");
		}
	}

	//	Start by drawing the map
	map.on('load', async () => {
		await addCustomLayersAndSources();
	});

	//	If the style changes, redraw the map
	map.on('styledata', async () => {
		await addCustomLayersAndSources();
	});

	//	Add swap basemap button
	function addStyleButton(map) {
		class StyleButton {
			onAdd(map) {
				const div = document.createElement("div");
				div.className = "maplibregl-ctrl maplibregl-ctrl-group";
				div.innerHTML = `<button id="toggle-style" class="maplibregl-ctrl-terrain" type="button" title="Change map style" aria-label="Change map style"><span class="maplibregl-ctrl-icon" aria-hidden="true"></span></button>`;
				return div;
			}
		}
		const styleButton = new StyleButton();
		map.addControl(styleButton, "top-right");
	}
	addStyleButton(map);

	// Variable to keep track of current style
	let currentStyle = style1;

	// Toggle style function
	document.getElementById('toggle-style').addEventListener('click', () => {
		currentStyle = currentStyle === style1 ? style2 : style1;
		map.setStyle(currentStyle);
	});

	//	Set bounding box, if any
	var bb_n = "{$bb_n}";
	if ( bb_n !== "_" ) {
			map.fitBounds( [ [ {$bb_w}, {$bb_s} ], [ {$bb_e},{$bb_n} ] ] );
	}
	
</script>
EOT;
	
		echo $mapJavaScript;
	}
}
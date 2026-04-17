<?php
namespace App\Controller;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Bundle\SecurityBundle\Security;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use App\Service\MediaFunctions;
use App\Service\UserFunctions;
use App\Service\SearchFunctions;
use App\Service\TagsFunctions;

use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Tools\DsnParser;

use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

class TestingController extends AbstractController
{
	public function __construct(
		         Security $security,
		     ) {
		     }
	
	public function test_date() {
	$cache = new FilesystemAdapter("testdate_cache");
	$value = $cache->get('cached_date', function (ItemInterface $item) {
		$item->expiresAfter(5);
		$date = date("Y-m-d H:i:s");

		return $date;
	});

	return $value;
}

	#[Route(['/private',"/private/anon"], name: 'private')]
	public function private(Request $request): Response {
		$user = $this->getUser() ?? $_SESSION["_sf2_attributes"]["auth0_session"]["user"] ?? array();

		return new Response(
			'<html><body><pre>' . 
			print_r($user, true) .
			'</pre> Cache time '. $this->test_date() .' <a href="/logout">Logout</a></body></html>'
	  );
	}

	#[Route(["/admin/no-mediatypes"], name: 'mediatypes')]
	public function mediatypes(Request $request): Response {

		$dsnParser = new DsnParser();
		$connectionParams = $dsnParser->parse( $_ENV['DATABASE_URL'] );
		$conn = DriverManager::getConnection($connectionParams);

		$queryBuilder = $conn->createQueryBuilder();
		$queryBuilder
			->select("benches.benchID")
			->from("benches")
			->innerJoin('benches', 'media', 'media', 'benches.benchID = media.benchID')
			->where("media.media_type IS NULL AND benches.published = 1")
			->orderBy("benches.benchID", 'DESC')
			->groupBy("benches.benchID")
			;
		$results = $queryBuilder->executeQuery();

		//	Loop through the results to create an array of media
		$benches_array = array();
		while ( ( $row = $results->fetchAssociative() ) !== false) {
			//	Add the details to the array
			$benches_array[$row["benchID"]] = array(
				"benchID"     => $row["benchID"],
				"inscription" => $row["inscription"],
				"address"     => $row["address"],
			);
		}

		//	Render the page
		return $this->render('nomedia.html.twig', [
			"query"         => "Benches without Media Types",
			"count"         => sizeof($benches_array),
			"benches"       => $benches_array,
		]);
	}

	#[Route(["/admin/upsidedown"], name: 'upsidedown')]
	public function upsidedown(Request $request) {

		// echo "f3bb6a0513d91551ea227a874368a60c3cd751db"; die();
		try {
			$imagick = new \Imagick(realpath("/home/openbenc/public_html/public/photos/f/3/f3bb6a0513d91551ea227a874368a60c3cd751db.jpg"));
		} catch (Exception $e) {
			$refer = $_SERVER["HTTP_REFERER"];
			echo "Image error! {$imagePath} - from {$refer} - {$e}";
			$imagick->clear();
		}
	
		//	Some phones (mostly iPhones) have rotated images
		//	Use the EXIF to correct
		//	http://php.net/manual/en/imagick.getimageorientation.php#111448
		$orientation = $imagick->getImageOrientation();
	
		switch($orientation) {
			case \imagick::ORIENTATION_BOTTOMRIGHT:
				$imagick->rotateimage("#000", 180); // rotate 180 degrees
			break;
	
			case \imagick::ORIENTATION_RIGHTTOP:
				$imagick->rotateimage("#000", 90); // rotate 90 degrees CW
			break;
	
			case \imagick::ORIENTATION_LEFTBOTTOM:
				$imagick->rotateimage("#000", -90); // rotate 90 degrees CCW
			break;
		}
	
		try {
			//	Set the orientation - otherwise it will appear rotated on some browsers
			$imagick->setImageOrientation(\imagick::ORIENTATION_TOPLEFT);
	
			//	Set the quality
			$imagick->setImageCompressionQuality(85);
	
			//	Progressive image for slower connections
			$imagick->setInterlaceScheme(\imagick::INTERLACE_PLANE);
		} catch (Exception $e) {
			$refer = $_SERVER["HTTP_REFERER"];
			echo "Image error! {$imagePath} - from {$refer} - size={$size} {$e}";
			$imagick->clear();
		}
		
		//	Send the image to the browser
		header("Content-Type: image/jpeg");
		ob_clean();	//	http://codeblog.vurdalakov.net/2013/01/solution-php-echo-function-or-print.html
		echo $imagick->getImageBlob();
		$imagick->clear();
	}
}
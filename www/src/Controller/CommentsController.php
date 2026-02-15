<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Tools\DsnParser;

class CommentsController extends AbstractController
{
	#[Route('/comments', name: 'comments_list')]
	public function comments_list(): Response {

		$commenticsConfig = $_SERVER["DOCUMENT_ROOT"] . "/public/commentics/config.php";

		if ( file_exists( $commenticsConfig ) ) {
			require_once $commenticsConfig;
		} else {
			return $this->render('comments.html.twig', [
				"comments"       => []
			]);
		}

		// @phpstan-ignore constant.notFound, constant.notFound, constant.notFound, constant.notFound, constant.notFound (In the separate commentics/config.php) 
		$commenticsDB = "mysqli://" . CMTX_DB_USERNAME . ":" . CMTX_DB_PASSWORD . "@" . CMTX_DB_HOSTNAME . ":" . CMTX_DB_PORT . "/" . CMTX_DB_DATABASE . "?&charset=utf8mb4";

		$dsnParser = new DsnParser();
		$connectionParams = $dsnParser->parse( $commenticsDB );
		$conn = DriverManager::getConnection($connectionParams);

		$sql = "SELECT   p.url AS page_id, 
					     c.comment
				FROM     comments c
				JOIN     pages p
				ON       c.page_id = p.id
				WHERE    c.is_approved = 1  
				ORDER BY c.date_added 
				DESC 
				LIMIT 1000;";

		$stmt = $conn->prepare($sql);
		$results = $stmt->executeQuery();
		$results_array = $results->fetchAssociative();

		$comments_array = array();
		while ( ( $row = $results->fetchAssociative() ) !== false) {
			$bench_id = array_last( explode( separator:"/", string:$row["page_id"]) );
			//	Add the details to the array
			$comments_array[$row["page_id"]] = array(
				"page_id"     => $row["page_id"],
				"comment"     => $row["comment"],
				"bench_id"    => $bench_id
			);
		}

		//	Render the page
		return $this->render('comments.html.twig', [
			"comments"       => $comments_array,
		]);
	}
}
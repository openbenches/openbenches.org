<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Request;

use App\Service\MediaFunctions;
use App\Service\BenchFunctions;
use App\Service\UserFunctions;

class EditController extends AbstractController
{
	#[Route('/edit/{bench_id}', name: 'edit')]
	public function edit(int $bench_id): Response {

		$user = $this->getUser();

		if( isset( $user ) ) {
			$username   = $user->getNickname();
			$avatar     = $user->getPicture();
			$provider   = explode("|", $user->getUserIdentifier())[0];
			$providerID = explode("|", $user->getUserIdentifier())[1];	
		} else {
			die();
		}

		$benchFunctions = new BenchFunctions();
		$bench = $benchFunctions->getBench($bench_id);

		if ( isset($bench["bench_id"]) ) {
			return $this->render("edit.html.twig", [
				"benchID"    => $bench["bench_id"],
				"inscription" => $bench["inscription"],
				"longitude"   => $bench["longitude"],
				"latitude"    => $bench["latitude"],
				"medias"      => $bench["medias"],
				"username"   => $username,
				"avatar"     => $avatar,
				"provider"   => $provider,
				"providerID" => $providerID,
				"tags_json"  => "",
			]);
		}
	}

	#[Route("/update", name: "update")]
	public function update(): Response {
		// Returns the bench ID if successful, or an error message if not. 
		//	POST'd data
		$inscription = $request->request->get( "inscription" );
		$longitude   = $request->request->get( "newLongitude" );
		$latitude    = $request->request->get( "newLatitude" );
		$published   = $request->request->get( "published" );

		$user = $this->getUser();

		if( isset( $user ) ) {
			

			$userFunctions = new UserFunctions();
			$userID = $userFunctions->addUser( $provider, $providerID, $username );

		} else {

		}
	}

}
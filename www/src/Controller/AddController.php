<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Request;

use App\Service\TagsFunctions;

use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Tools\DsnParser;

class AddController extends AbstractController
{
	#[Route(["/add", "/add/logged_in"], name: 'add')]
	public function add(): Response {

		$user = $this->getUser();

		if( isset( $user ) ) {
			$username   = $user->getNickname();
			$avatar     = $user->getPicture();
			$provider   = explode("|", $user->getUserIdentifier())[0];
			$providerID = explode("|", $user->getUserIdentifier())[1];	
		} else {

			$username   = null;
			$avatar     = null;
			$provider   = null;
			$providerID = null;
		}

		$tagsFunctions = new TagsFunctions();
		$tags_array = $tagsFunctions->getTagsNames();

		return $this->render('add.html.twig', [
			"username"   => $username,
			"avatar"     => $avatar,
			"provider"   => $provider,
			"providerID" => $providerID,
			"tags_array" => $tags_array,
		]);
	}

	#[Route(["/tags"], name: "tags")]
	public function tags() {
		$tagsFunctions = new TagsFunctions();
		$tagsFunctions->getTags();
	}

}
<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Request;

use App\Service\UserFunctions;

class LeaderboardController extends AbstractController
{
	#[Route(["/leaderboard", "/contributions", "/contributors"], name: "leaderboard")]
	public function leaderboard(): Response {
		$userFunctions  = new UserFunctions();

		$contributors = $userFunctions->getContributorsStatistics();

		return $this->render("leaderboard.html.twig", [
			"contributors" => $contributors
		]);
	}
}
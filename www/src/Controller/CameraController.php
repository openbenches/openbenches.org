<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Request;

use App\Service\CameraFunctions;

class CameraController extends AbstractController
{
	#[Route("/cameras", name: "stats_make")]
	public function stats_make(): Response {
		$request = Request::createFromGlobals();

		$cameraFunctions = new CameraFunctions();
		$results_array = $cameraFunctions->getMakes();

		//	Render the page
		return $this->render('makes.html.twig', [
			'makes' => $results_array,
		]);
	}

	#[Route("/cameras/{make}", name: "stats_model")]
	public function stats_model($make): Response {
		$request = Request::createFromGlobals();

		$cameraFunctions = new CameraFunctions();
		$results_array = $cameraFunctions->getModels($make);

		//	Render the page
		return $this->render('models.html.twig', [
			"make"   => $make,
			'models' => $results_array,
		]);
	}
}
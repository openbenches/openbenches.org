<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


class BaseController extends AbstractController
{
	#[Route('/', name: 'base')]
	public function base(): Response {
		return $this->render('index.html.twig');
	}

	#[Route('/colophon', name: 'colophon')]
	public function colophon(): Response {

		return $this->render('colophon.html.twig');
	}

	#[Route(['/support', '/sponsor'], name: 'support')]
	public function support(): Response {

		return $this->render('support.html.twig');
	}

	#[Route(["/offline", "/offline.php"], name: "offline")]
	public function offline(): Response {

		return $this->render('offline.html.twig');
	}
}
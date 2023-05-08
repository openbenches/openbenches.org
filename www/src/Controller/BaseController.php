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
}
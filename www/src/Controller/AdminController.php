<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class AdminController extends AbstractController
{
	//	URls which are only hit by bots.
	#[Route([
		"/ipfs/{hash}",
		"/api/v1.0/data.json/",
		"/ClickIT",
		"/https://images.weserv.nl/",
		"/js/pannellum/pannellum.htm"
	], name: "admin_block_bot")]
	public function admin_block_bot() {
		//	Redirect to bot blocker
		$redirect_url = "/quantum/index.php?cache=" . rand( 0, 1024 ); 
		return $this->redirect( $redirect_url, 301 );	
	}
}
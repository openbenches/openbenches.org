<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Request;

use App\Service\MediaFunctions;
use App\Service\UserFunctions;
use App\Service\BenchFunctions;

class UserController extends AbstractController
{
	#[Route("/user", name: "no_user")]
	public function no_user(): Response {
		return $this->render("user.html.twig", [
			"user_id" => "NO USER",
		]);
	}

	#[Route('/user/{user_id}', name: 'show_user')]
	public function show_user(int $user_id): Response {
		$request = Request::createFromGlobals();
		//	/user/1234?page=2&count=5
		$get_page      = $request->query->get("page");
		$get_count     = $request->query->get("count");

		//	Page
		if( isset( $get_page ) ){
			$get_page = (int)$get_page;
		} else {
			$get_page = 0;
		}

		// Items per page
		if( isset( $get_count ) ){
			$get_count = (int)$get_count;
		} else {
			$get_count = 20;
		}

		//	Pagination for the database
		$first = $get_page * $get_count;
		$last  = $get_count;

		$mediaFunctions = new MediaFunctions();
		$userFunctions  = new UserFunctions();

		$results_array = $userFunctions->getUserDetails( $user_id );

		if (false != $results_array) {
			$provider   = $results_array["provider"];
			$providerID = $results_array["providerID"];
			$name       = $results_array["name"];

			$avatar_url    = $userFunctions->getUserAvatar( $provider, $providerID, $name );
			$user_url      = $userFunctions->getUserURL( $provider, $providerID, $name );

			//	Get the benches associated with this user
			$benches_array = $userFunctions->getUserBenches( $user_id, $first, $last );
			$benches_count = $userFunctions->getUserBenchCount( $user_id);

			//	Pagination for the UI
			if( $get_page > 0 ) {
				$previous_page = $get_page - 1;
				$previous_url = "?page={$previous_page}&count={$get_count}";
			} else {
				$previous_url = null;
			}

			if( ( $benches_count > ( ($get_page * $get_count) + $get_count ) ) ) {
				$next_page = $get_page + 1;
				$next_url = "?page={$next_page}&count={$get_count}";
			} else {
				$next_url = null;
			}

			//	Render the page
			return $this->render('user.html.twig', [
				"user_id" => $user_id,
				"user_name"    => $name,
				"avatar_url" => $avatar_url,
				"user_external_url" => $user_url,
				"user_provider" => $provider,
				"benches_count" => $benches_count,
				"benches" => $benches_array,
				"next_url" => $next_url,
				"previous_url" => $previous_url,
				
			]);
		} else {
			//	Generate an HTTP 404 response
			throw $this->createNotFoundException("The user does not exist");
		}

		return $this->render('user.html.twig', [
			'user_id' => $user_id,
		]);
	}

	#[Route('/user/{user_service}/{user_name}', name: 'show_service_user')]
	public function show_service_user($user_service, $user_name): Response {
		$request = Request::createFromGlobals();
		//	/user/twitter/edent?page=2&count=5
		$get_page      = $request->query->get("page");
		$get_count     = $request->query->get("count");

		//	Page
		if( isset( $get_page ) ){
			$get_page = (int)$get_page;
		} else {
			$get_page = 0;
		}

		// Items per page
		if( isset( $get_count ) ){
			$get_count = (int)$get_count;
		} else {
			$get_count = 20;
		}

		//	Pagination for the database
		$first = $get_page * $get_count;
		$last  = $get_count;

		$mediaFunctions = new MediaFunctions();
		$userFunctions  = new UserFunctions();

		$results_array = $userFunctions->getUserDetailsFromSocial( $user_service, $user_name );

		if (false != $results_array) {
			$providerID = $results_array["providerID"];
			$user_id     = $results_array["userID"];
			$provider   = $user_service;
			$name       = $user_name;

			$avatar_url    = $userFunctions->getUserAvatar( $provider, $providerID, $name );
			$user_url      = $userFunctions->getUserURL( $provider, $providerID, $name );

			//	Get the benches associated with this user
			$benches_array = $userFunctions->getUserBenches( $user_id, $first, $last );
			$benches_count = $userFunctions->getUserBenchCount( $user_id);

			//	Pagination for the UI
			if( $get_page > 0 ) {
				$previous_page = $get_page - 1;
				$previous_url = "?page={$previous_page}&count={$get_count}";
			} else {
				$previous_url = null;
			}

			if( ( $benches_count > ( ($get_page * $get_count) + $get_count ) ) ) {
				$next_page = $get_page + 1;
				$next_url = "?page={$next_page}&count={$get_count}";
			} else {
				$next_url = null;
			}

			//	Render the page
			return $this->render('user.html.twig', [
				"user_id" => $user_id,
				"user_name"    => $name,
				"avatar_url" => $avatar_url,
				"user_external_url" => $user_url,
				"user_provider" => $provider,
				"benches_count" => $benches_count,
				"benches" => $benches_array,
				"next_url" => $next_url,
				"previous_url" => $previous_url,
				
			]);
		} else {
			//	Generate an HTTP 404 response
			throw $this->createNotFoundException("The user does not exist");
		}

		return $this->render('user.html.twig', [
			'user_id' => $user_id,
		]);
	}
}
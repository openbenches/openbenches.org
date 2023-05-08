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
			//	404 Page
			$benchFunctions = new BenchFunctions();
			$image_url = $benchFunctions->get404();

			//	Render the page
			return $this->render('404.html.twig', [
				'inscription' => "User Not Found!",
				'url' => $image_url,
			]);
		}

		return $this->render('user.html.twig', [
			'user_id' => $user_id,
		]);
	}

	#[Route('/user/{user_service}/{user_id}', name: 'show_service_user')]
	public function show_service_user($user_service, $user_id): Response {
		return $this->render('user.html.twig', [
			'user_id'      => $user_id,
			'user_service' => $user_service,
		]);
	}

	#[Route(['/private',"/private/anon"], name: 'private')]
	public function private(Request $request): Response {
		/*	GitHub
		[nickname] => edent
		[name] => Terence Eden
		[picture] => https://avatars.githubusercontent.com/u/837136?v=4
		[sub] => github|837136
		
		//	Facebook
		[nickname] => Terry Eden
		[name] => Terry Eden
		[picture] => https://platform-lookaside.fbsbx.com/platform/profilepic/?asid=10155803408156218&height=50&width=50&ext=1685951063&hash=AeR7J0qSHt2PDPiN5Qw
		[sub] => facebook|10155803408156218

		//	LinkedIn
		[nickname] => Terence Eden
		[name] => Terence Eden
		[picture] => https://media.licdn.com/dms/image/C4E03AQEX11qBnAo43A/profile-displayphoto-shrink_800_800/0/1517677542848?e=1688601600&v=beta&t=3W9PZznmaizTxVvrf8YaYnkhZ6CPpI4MpzCwCXLIERI
		[sub] => linkedin|aFAjXfpLd-

		//	Twitter
		[nickname] => edent
		[name] => Terence Eden
		[picture] => https://pbs.twimg.com/profile_images/1623225628530016260/SW0HsKjP_normal.jpg
		[sub] => twitter|14054507

		//	WordPress returns PII in the name field - discard it
		[nickname] => edent
		[name] => REALEMAIL@WHATEVER.DOT
		[picture] => https://2.gravatar.com/avatar/b7a57850e02cd7b11a026390aaa400bd?s=96&d=https%3A%2F%2F2.gravatar.com%2Favatar%2Fad516503a11cd5ca435acc9bb6523536%3Fs%3D96&r=X
		[sub] => wordpress|4658229
		*/
		
		if( isset($_SESSION) ) {
			// var_dump($_SESSION);
		}
		$user = $this->getUser();
		// if ($user) {
		// 	echo "TRRRRUE";
		// 	var_dump($user);
		// } else {
		// 	echo "FALSE";
		// 	var_dump($user);
		// }
		// die();
		return new Response(
			'<html><body><pre>' . 
				print_r($this->getUser(), true) . 
				$this->getUser()->getNickname() . "\n" .
				$this->getUser()->getPicture() . "\n" .
				$this->getUser()->getName() . "\n" .
				$this->getUser()->getUserIdentifier() . "\n" .
				print_r($this->getUser()->getRoles(), true) . "\n" .
				
				'</pre> <a href="/logout">Logout</a></body></html>'
	  );
	}
}
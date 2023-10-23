<?php

namespace Soerenengels\Steady;

use Exception;
use Soerenengels\Steady\User;
use Soerenengels\Steady\Subscription;
use Soerenengels\Steady\Endpoint;
use Kirby\Toolkit\Cookie;
use Kirby\Toolkit\Uuid;
use Kirby\Http\Remote;
use Kirby\Cms\Users;

interface OAuthInterface
{
	public function user(): User;
	public function subscription(): Subscription;
	public function url(): string;
	public function processCallback(string $state, string $code): bool;
	public function requestAccessToken();
	public function logout();
}


/** Enables an OAuth Flow for Steady */
class OAuth implements OAuthInterface
{
	private string $authorization_code;
	private AccessToken $token;
	private ?User $current_user;
	private ?Subscription $current_subscription;

	public function __construct(
		private string $client_id,
		private string $client_secret,
		private string $redirect_uri,
		private Steady $steady
	) {
		$this->steady = $steady;
	}

	/**
	 * Send authenticated GET request
	 * to Steady API Endpoint $endpoint
	 * with current access token
	 *
	 * @param Endpoint $endpoint OAuth Endpoint Enum
	 * @return Remote response
	 */
	private function get(Endpoint $endpoint): Remote
	{
		if (($token = $this->token)?->isExpired()) {
			// GET new token
			throw new Exception('Error: No valid access token.');
		}

		$response = $this->steady->get(
			$endpoint,
			['Authorization: Bearer ' . $token->value()]
		);

		return $response;
	}

	/**
	 * Returns info about the current subscription
	 * for the current user. If the user has no subscription,
	 * or it has expired, the data attribute of the response is null.
	 */
	public function subscription(): Subscription
	{
		if (
			($subscription = $this->current_subscription) !== null
		) return $subscription;
		$response = $this->get(Endpoint::OAUTH_CURRENT_SUBSCRIPTION);
		return $this->current_subscription = new Subscription($response->json());
	}

	/**
	 * Returns info about the current user
	 */
	public function user(): User
	{
		if (($user = $this->current_user) !== null) return $user;
		// check if access token exists
		$this->checkCookie();
		$response = $this->get(Endpoint::OAUTH_CURRENT_USER);
		return $this->current_user = new User($response);
	}

	/**
	 * Create User Authorization Url
	 * to let user grant Authorization
	 * to Application and call createVerification()
	 * to store verification in Session
	 * @return string HTML anchor string
	 */
	public function url(): string
	{
		$url = Endpoint::OAUTH_AUTHORIZATION->url() .
			'?response_type=code' .
			'&client_id=' .
			$this->client_id .
			'&redirect_uri=' .
			$this->redirect_uri .
			'&scope=read' .
			'&state=' .
			$this->setVerification();
		return $url;
	}

	/**
	 * Create a random String
	 * and save it to session_state
	 * @return string random string to verify request
	 */
	private static function setVerification(): string
	{
		$verification = Uuid::generate();
		kirby()->session()->set([
			'steady_state' => $verification
		]);
		return $verification;
	}

	/**
	 * Validate $state string against
	 * steady_state session value and
	 * reset steady_state session
	 *
	 * @param string $state state received from api
	 * @return bool true if state is valid
	 */
	private static function checkVerification(string $state): bool
	{
		$session_state = kirby()->session()->pull('steady_state');
		return $state === $session_state;
	}

	/**
	 * Process the Response scheduled at the Redirect Uri
	 * after granting Authorization by User
	 *
	 * @param string $state state string to verify
	 * @param string $code authorization code
	 * @return bool state response equals verfification string
	 */
	public function processCallback(string $state, string $code): bool
	{
		// Check if callback $state is equal to steady_state session
		if ($this->checkVerification($state)) {
			// Set authorization code for access token request
			$this->authorization_code = $code;
			// Request access token
			$this->requestAccessToken();
			// Redirect to page set in after login option
			$url = kirby()->option('soerenengels.steady.oauth.after-login');
			Response::go($url);
			return true;
		}

		throw new Exception('Processing callback failed.');
		return false;
	}

	/**
	 * Get Steady Access Token
	 *
	 * sets $this->access_token
	 *
	 * @param string $grant_type 'authorization_code'|'refresh_token'
	 * @return ?AccessToken access_token or null
	 */
	public function token(
		$grant_type = 'authorization_code'
	): ?AccessToken {
		$data = [
			'client_id' => $this->client_id,
			'client_secret' => $this->client_secret,
			'grant_type' => $grant_type,
			'redirect_uri' => $this->redirect_uri
		];
		if ($grant_type == 'authorization_code') {
			$data[] = [
				'code' => $this->authorization_code
			];
		} else {
			$data[] = [
				'refresh_token' => $this->token->refresh_token()
			];
		}

		$response = $this->steady->post(
			Endpoint::OAUTH_ACCESS_TOKEN,
			$data
		);

		if ($response->code() != 201) return null;
		return $this->token = new AccessToken($response, $this);
	}

	/**
	 * Check for an steady access token in Cookies
	 * @return bool false, if any access token in cookies
	 */
	public function checkCookie(): bool
	{
		$access_token = Cookie::get('steady_access_token');
		if (!$access_token) return false;
		// if access_token is about to expire
		// update access token
		$this->access_token = $this->access_token->update();
		return true;
	}

	/**
	 * Get refresh token
	 * @return ?string returns refresh token or null
	 */
	private function getRefreshToken(): ?string
	{
		if(!($email = $this->user()->email)) return null;
		if(!($user = kirby()->user($email))) return null;
		$refresh_token = $user->content()->refresh_token();
		return $refresh_token;
	}

	/**
	 * Refresh Current Users Access Token
	 */
	public function updateAccessToken(): AccessToken
	{
		$response = $this->token(
			'refresh_token',
			$this->refresh_token
		);
		return new AccessToken($response);
	}

	/**
	 * Reset Object properties and Cookies
	 * */
	public function logout(): true {
		Cookie::remove('steady_access_token');
		// TODO: reset refresh token in user

		$this->verification = null;
		$this->authorization_code = null;
		$this->access_token = null;
		$this->current_user = null;
		$this->current_subscription = null;
		return true;
	}

	/**
	 * Return Kirby User
	 *  */
	public function getKirbyUser(string $email): \Kirby\Cms\User
	{
		$kirby = kirby();
		$item = new User([]);

		if (($user = $kirby->user($email))->exists()) return $user;

		$user = $kirby->users()->create([
			'name'      => $item->first_name . ' ' . $item->last_name,
			'email'     => $item->email,
			'role'      => 'newsletter_subscriber',
			'content'   => [
				'opted_in_at' => $item->opted_in_at
			]
		]);
		return $user;
	}

	/* public static function createKirbyUsersFrom($array): Users {
		foreach ($array as $item) {
			try {
				$user = kirby()->users()->create([
					'name'      => $item->first_name . ' ' . $item->last_name,
					'email'     => $item->email,
					'role'      => 'newsletter_subscriber',
					'content'   => [
						'opted_in_at' => $item->opted_in_at
					]
				]);

				echo 'The user "' . $user->name() . '" has been created';

			} catch(Exception $e) {

				echo 'The user could not be created';
				echo $e->getMessage();

			}
		}
		return kirby()->users()->role('newsletter_subscribers');
	} */
}




/**
 * Step by Step: OAuth2
 * 1. OAuth->createAuthorizationLink()
 * 2. user clicks and gets redirected to steady
 * 3. user logs in and clicks button to grant access
 * 4. steady redirects user to redirect_uri
 * 5. OAuth->processRedirectUri($state, $authorization_code)
 * 6. OAuth->requestAccessToken() send post request to request access token
 * 7. Steady sends response to redirect uri
 */

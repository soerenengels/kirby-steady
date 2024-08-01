<?php

namespace Soerenengels\Steady;

use Exception;
use Soerenengels\Steady\User;
use Soerenengels\Steady\Subscription;
use Soerenengels\Steady\Endpoint;
use Kirby\Uuid\Uuid;
use Kirby\Http\Remote;
use Kirby\Cms\User as KirbyUser;
use Kirby\Data\Yaml;
use Kirby\Cms\App as Kirby;


/**
 * OAuth Class
 *
 * Enables an OAuth Flow for Steady
 *
 * @method user() Return current User or null
 * @method subscription() Return current Subscription or null
 * @method string url() Return authorization url
 */
class OAuth
{
	private string $authorization_code;
	private AccessToken $token;
	private ?User $current_user; // TODO: remove
	private ?Subscription $current_subscription; // TODO: remove

	/**
	 * @param string $client_id Steady OAuth Client Id
	 * @param string $client_secret Steady OAuth Client Secret
	 * @param string $redirect_uri url to redirect after authorization
	 * @param ?Steady $steady Steady instance
	 * @param ?Kirby $kirby Kirby instance
	 */
	public function __construct(
		private string $client_id,
		private string $client_secret,
		private string $redirect_uri,
		private ?Steady $steady = null,
		private ?Kirby $kirby = null
	) {
		$this->steady = $steady ?? steady();
		$this->kirby = $kirby ?? kirby();
	}

	/**
	 * Send authenticated GET request
	 * to Steady API Endpoint $endpoint
	 * with current access token
	 *
	 * @param Endpoint $endpoint OAuth Endpoint Enum
	 * @return \stdClass|array|null parsed Response data
	 */
	public function get(Endpoint $endpoint, ?string $token = null)
	{
		/* if (($token = $this->token)?->isExpired()) {
			// GET new token
			throw new Exception('Error: No valid access token.');
		} */

		$response = $this->steady->get(
			$endpoint,
			[
				'Authorization: Bearer ' . $token,
				'Accept: application/vnd.api+json'
			]
		);
		return $response;
	}

	/**
	 * Returns info about the current subscription
	 * for the current user. If the user has no subscription,
	 * or it has expired, the data attribute of the response is null.
	 */
	public function subscription()//: ?Subscription
	{
		// Return Subscription if set
		if (($subscription = $this->current_subscription ?? null)) return $subscription;
		// Check if there is a token, otherwise return null
		//if (!($token = $this->token ?? null)) return $token;
		// Request Subscription data
		if((!$this->isLoggedIn()) || (!($token = $this->getAccessToken()))) return null;
		$response = $this->get(Endpoint::OAUTH_CURRENT_SUBSCRIPTION, $this->getAccessToken());
		// and set current_subscription
		return $response;
		return $this->current_subscription = new Subscription($response['data']);
	}

	/**
	 * Returns info about the current user
	 */
	public function user(): mixed//?User
	{
		// Return User if already requested
		if (($user = $this->current_user ?? null)) return $user;
		// check if access token exists
		//$this->checkCookie();
		if((!$this->isLoggedIn()) || (!($token = $this->getAccessToken()))) return null;
		$response = $this->get(Endpoint::OAUTH_CURRENT_USER, $token);

		return $this->current_user = new User($response['data']);
	}

	/**
	 * Get Access Token
	 * @return ?string access token or null
	 */
	public function getAccessToken(): ?string
	{
			return kirby()->user()?->content()->steady_access_token()->yaml()['access_token'] ?? null;
	}

	/**
	 * Create User Authorization Url
	 *
	 * __Step 1__ to let user grant the Website
	 * Authorization to their Steady account.
	 * Calls setVerification() to store a
	 * verification string in Session
	 * @return string url string
	 */
	public function url(): string
	{
		return Endpoint::OAUTH_AUTHORIZATION->url() .
			'?response_type=code' .
			'&client_id=' . $this->client_id .
			'&redirect_uri=' . $this->redirect_uri .
			'&scope=read' .
			'&state=' . $this->setVerification();
	}

	/**
	 * Create a random verification String
	 *
	 * __Step 2__ to prepare request verification
	 * a random string is saved to session
	 * @return string random string to verify request
	 */
	private function setVerification(): string
	{
		// Create verification string
		$verification = Uuid::generate();
		// Set Verification string in $session
		$this->kirby->session()->set('soerenengels.steady.state', $verification);
		return $verification;
	}

	/**
	 * Validate $state string
	 *
	 * __Step 4:__ We check if the state value
	 * passed in the callback is equal to the
	 * session value, while cleaning the session
	 *
	 * @param string $state state received from api
	 * @return bool true if state is valid
	 */
	private function isVerified(string $state): bool
	{
		return $state == $this->kirby->session()->pull('soerenengels.steady.state');
	}

	/**
	 * Process the Response scheduled at the Redirect Uri
	 *
	 * __Step 3:__ After the User granted Authorization by logging
	 * in to Steady and granting access to the Website, the User
	 * receives an authorization Code and the verification string.
	 * The authorization code is used to request an Access Token.
	 *
	 * @param string $state state string to verify
	 * @param string $code authorization code
	 * @return bool state response equals verfification string
	 */
	public function processCallback(string $state, string $code): void
	{
		// Check if $state from callback is the same
		// as we have set to session
		if (!$this->isVerified($state)) throw new Exception('State is not verified.');

		// Request access token
		$token = $this->token('authorization_code', $code);

		// Redirect to referrer or after login page
		go($this->getReferrer());
	}


	/**
	 * Get Steady Access Token
	 *
	 * __Step 5:__ It's time for an access token.
	 * With some data in exchange we request
	 * an access token. The request response gets
	 * stored in a new AccessToken object.
	 *
	 * @param string $grant_type 'authorization_code'|'refresh_token'
	 * @return ?AccessToken access_token or null
	 */
	public function token(
		$grant_type = 'authorization_code',
		$token = null
	): mixed/* |?AccessToken */ {
		$data = [
			'client_id' => $this->client_id,
			'client_secret' => $this->client_secret,
			'grant_type' => $grant_type,
			'redirect_uri' => $this->redirect_uri,
			'code' => $token ?? $this->authorization_code
		];
		/* if ($grant_type == 'authorization_code') { */
			/* $data += [

			]; */
		/* } else {
			$data[] = [
				'refresh_token' => $token ?? $this->getRefreshToken()
			];
		} */


		/* $response = $this->steady->post(
			Endpoint::OAUTH_ACCESS_TOKEN,
			$data
		); */
		$remote = Remote::request(Endpoint::OAUTH_ACCESS_TOKEN->url(), [
			'method' => 'POST',
			'headers' => [
				'Accept: application/json'
			],
			'data' => $data,
		]);
		$access_token = new AccessToken($remote, $this);
		return true;
		/*
		F::write(kirby()->root('logs') . '/log.txt', $response->toString());
		return $response;
		/* } catch (Exception $e) {
			$e->getMessage();
			return 'error?';
			return null;
		} */
	}

	/**
	 * Get refresh token
	 * @return ?string returns refresh token or null
	 */
	/* private function getRefreshToken(): ?string
	{
		if (!($email = $this->user()->email)) return null;
		if (!($user = kirby()->user($email))) return null;
		$refresh_token = $user->content()->refresh_token();
		return $refresh_token;
	} */

	/**
	 * Refresh Current Users Access Token
	 */
	/* public function updateAccessToken(): AccessToken
	{
		$response = $this->token(
			'refresh_token',
			$this->refresh_token
		);
		return new AccessToken($response);
	} */

	/**
	 * Reset Object properties and Cookies
	 * */
	public function logout(): true
	{
		/* Cookie::remove('steady_access_token');
		// TODO: reset refresh token in user

		$this->verification = null;
		$this->authorization_code = null;
		$this->access_token = null;
		$this->current_user = null;
		$this->current_subscription = null; */
		return true;
	}

	/**
	 * Return Kirby User
	 * @param string $id Steady User Id
	 * @return KirbyUser|null
	 *  */
	public function findUserBySteadyId(string $id): ?KirbyUser
	{
		return $this->kirby->users()->findBy('steady_id', $id);
	}

	public function createUser(Remote $response): KirbyUser {
		$data = json_decode($response->content());
		return $this->kirby->impersonate('kirby', function() use ($data) {
			return $this->kirby->users()->create([
				'name'      => $data->info->{'first-name'} . ' ' . $data->info->{'last-name'},
				'email'     => $data->info->email,
				'role'      => 'steady',
				'content'   => [
					'steady_id' => $data->info->id,
					'steady_access_token' => Yaml::encode($data),
				]
			]);
		});
	}

	/**
	 * Check if User is logged in
	 *
	 * @return bool true if user is logged in
	 */
	public function isLoggedIn(): bool {
		$kirbyUserIsLoggedIn = $this->kirby->user() ? true : false;
		$accessTokenIsSet = $this->getAccessToken() ? true : false;
		$accessTokenIsNotExpired = $this->token->isExpired() ? false : true;
		return $kirbyUserIsLoggedIn && $accessTokenIsSet && $accessTokenIsNotExpired;
	}

	/**
	 * Set Session with Referrer
	 */
	public function setReferrer(?string $url = null): void {
		$referrer = $url ?? $this->kirby->request()->referrer();
		$this->kirby->session()->set('soerenengels.steady.referrer', $referrer);
	}

	/**
	 * Get Referrer from Session
	 *
	 * and delete the Session afterwards
	 *
	 * @return string $url Referrer string
	 */
	public function getReferrer(): string {
		return $this->kirby->session()->pull('soerenengels.steady.referrer') ?? $this->kirby->option('soerenengels.steady.oauth.after-login') ?? '/';
	}
}

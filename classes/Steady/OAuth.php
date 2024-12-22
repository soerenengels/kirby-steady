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
use Kirby\Http\Cookie;

/**
 * OAuth Class
 *
 * Enable Steady OAuth Flow for your Website
 * @phpstan-import-type SteadyEntityResponse from Steady
 * @phpstan-import-type SteadyResponse from Steady
 * @phpstan-import-type SteadyCollectionResponse from Steady
 */
class OAuth
{
	/**
	 * @param string $client_id Steady OAuth Client Id
	 * @param string $client_secret Steady OAuth Client Secret
	 * @param string $redirect_uri url to redirect after authorization
	 * @param ?Steady $steady Steady instance
	 * @param ?Kirby $kirby Kirby instance
	 */
	public function __construct(
		public string $client_id,
		public string $client_secret,
		public string $redirect_uri,
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
	 * @return SteadyResponse|\stdClass|null parsed Response data
	 */
	public function get(
		Endpoint $endpoint,
		?string $token = null
	): array|\stdClass|null
	{
		/* if (($token = $this->token)?->isExpired()) {
			// GET new token
			throw new Exception('Error: No valid access token.');
		} */

		$response = $this->steady?->get(
			$endpoint,
			[
				'Authorization: Bearer ' . $this->token()->accessToken(),
				'Accept: application/vnd.api+json'
			]
		);
		return $response;
	}

	/**
	 * Returns info about the current subscription
	 * for the current user. If the user has no subscription,
	 * or it has expired, the data attribute of the response is null.
	 *
	 * @return ?Subscription $subscription Return Subscription or null, when no subscription
	 */
	public function subscription(): ?Subscription
	{
		try {
			if(!$this->token()->accessToken()) throw new Exception('Error: User is not logged in.');
			/** @var \stdClass|null|array{data: SteadyEntityResponse|null, included?: SteadyCollectionResponse } $response */
			$response = $this->get(Endpoint::OAUTH_CURRENT_SUBSCRIPTION);
			if (
				!is_array($response)
				) throw new Exception('Error: No subscription data in response.');
			if ($response['data'] == null) throw new Exception('Error: No response from Steady API.');
			$data = $response['data'];
			$included = isset($response['included']) ? $response['included'] : [];
			return new Subscription($data, $included);
		} catch (Exception $e) {
			//$e->getMessage(); // TODO: handle exception
			return null;
		}
	}

	/**
	 * Return current user
	 *
	 * when the (Kirby) user is logged in
	 *
	 * @return ?User $user Logged in Steady User
	 */
	public function user(): ?User
	{
		try {
			if(!$this->token()->accessToken()) throw new Exception('Error: User is not logged in.');
			/** @var \stdClass|null|array{data: SteadyEntityResponse } $response */
			$response = $this->get(Endpoint::OAUTH_CURRENT_USER);
			if ($response === null) throw new Exception('Error: No response from Steady API.');
			if (
				!is_array($response)
			) throw new Exception('Error: No subscription data in response.');
			return new User($response['data']);
		} catch (Exception $e) {
			//echo $e->getMessage(); // TODO: handle exception
			return null;
		}
	}

	/**
	 * Create User Authorization Url
	 *
	 * __Step 1__ to let user grant the Website
	 * Authorization to their Steady account.
	 * Calls setVerification() to store a
	 * verification string in Session
	 *
	 * @param null|string|false $referrer url to redirect after authorization, false to skip, null to use current url
	 * @return string url string
	 */
	public function url(null|string|bool $referrer = null): string
	{
		if (is_null($referrer) || is_string($referrer)) $this->setReferrer($referrer);
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
		$this->kirby?->session()->data()->set('soerenengels.steady.state', $verification);
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
		return $state == $this->kirby?->session()->data()->pull('soerenengels.steady.state');
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
	 */
	public function processCallback(string $state, string $code): void
	{
		// Check if $state from callback is the same
		// as we have set to session
		if (!$this->isVerified($state)) throw new Exception('State is not verified.');

		// Request access token
		$response = $this->token()->request(token: $code);

		// Save Token
		$this->token()->save($response);

		// Redirect to referrer or after login page
		go($this->getReferrer());
	}

	/**
	 * Access Tokens
	 *
	 * @return Token Token abstraction
	 */
	public function token(): Token {
		return new Token($this);
	}

	/**
	 * Logout corresponding Kirby User
	 */
	public function logout(): void
	{
		$this->token()->flush();
	}

	/**
	 * Check if User is logged in
	 *
	 * @return bool true if user is logged in
	 */
	public function isLoggedIn(): bool {
		return $this->user() ? true : false;
	}

	/**
	 * Check if User is Member
	 *
	 * @return bool true if user is member
	 */
	public function isMember(): bool {
		return $this->subscription() ? true : false;
	}

	/**
	 * Set Session with Referrer
	 */
	public function setReferrer(
		?string $url = null
	): void {
		$referrer = $url ?? $this->kirby?->request()->url();
		$this->kirby?->session()->data()->set('soerenengels.steady.referrer', $referrer);
	}

	/**
	 * Get Referrer from Session
	 * and delete Session afterwards
	 *
	 * @return string $url Referrer string
	 */
	public function getReferrer(): string {
		/** @var string $referrer */
		$referrer = $this->kirby?->session()->data()->pull('soerenengels.steady.referrer') ?? $this->kirby?->option('soerenengels.steady.oauth.after-login') ?? '/';
		return $referrer;
	}
}

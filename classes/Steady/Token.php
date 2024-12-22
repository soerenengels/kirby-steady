<?php
namespace Soerenengels\Steady;
use Kirby\Http\Remote;
use Kirby\Toolkit\Date;
use Kirby\Data\Data;
use Kirby\Data\Yaml;
use DateInterval;
use Kirby\Http\Cookie;

/**
 * Token
 *
 * Handle Steady Access and Refresh Tokens
 */
class Token
{
	/**
	 * @param ?OAuth $oauth Instance of OAuth object
	 */
	public function __construct(
		private ?OAuth $oauth = null
	) {
		$this->oauth = $oauth ?? steady()->oauth();
	}

	/**
	 * Return Access Token
	 *
	 * and update if necessary
	 * @return ?string $refreshToken Refresh Token or null when expired
	 */
	public function accessToken(): ?string {
		// TODO: refresh token when necessary
		/** @var ?string $token */
		$token = Cookie::get('steady-token', null);
		return $token;
	}

	/**
	 * Get Access Token Expiration Time
	 *
	 * @return Date Date object when Access Token expires
	 */
	private function expiresAt(): ?Date
	{
		/** @var ?string $timestamp */
		$timestamp = Cookie::get('steady-token-expires', null);
		return $timestamp !== null ? new Date($timestamp) : null;
	}

	/**
	 * Is Access Token Expired
	 *
	 * @return bool true if token is expired
	 */
	public function expired(): bool
	{
		if(!($expirationDate = $this->expiresAt())) return true;
		return Date::now()->isAfter($expirationDate);
	}

	/**
	 * Return Refresh Token
	 *
	 * @return ?string $refreshToken Refresh Token
	 */
	public function refreshToken(): ?string {
		/** @var ?string $token */
		$token = Cookie::get('steady-refresh-token', null);
		return $token;
	}

	/**
	 * Request Steady Access Token
	 *
	 * __Step 5:__ It's time for an access token.
	 * With some data in exchange we request
	 * an access token. The request response
	 * gets returned.
	 *
	 * @param 'authorization_code'|'access_token'|'refresh_token' $grant_type
	 * @param string $token
	 */
	public function request(
		string $token,
		string $grant_type = 'authorization_code'
	): \Kirby\Http\Remote {
		// Setup Payload
		if($this->oauth === null) throw new \Exception('No OAuth instance provided.');
		$key = $grant_type === 'refresh_token' ? 'refresh_token' : 'code';
		$data = [
			'client_id' => $this->oauth->client_id,
			'client_secret' => $this->oauth->client_secret,
			'grant_type' => $grant_type,
			'redirect_uri' => $this->oauth->redirect_uri,
			$key => $token
		];

		// TODO: TRY / CATCH
		// Send Request for Token
		$response = Remote::request(Endpoint::OAUTH_ACCESS_TOKEN->url(), [
			'method' => 'POST',
			'headers' => [
				'Accept: application/json'
			],
			'data' => $data,
		]);
		return $response;
	}

	/**
	 * Save Access Token
	 * to Kirby User File
	 *
	 * __Step 6:__ Let's store the access token.
	 * If a user with the Steady User Id exists,
	 * store token data in the corresponding Kirby
	 * User file. Otherwise create a new Kirby User
	 * to store the token data and login that User.
	 *
	 * @param Remote $response Steady Access Token Response
	 * @return bool Boolean that states if saving the token was successful
	 * */
	public function save(Remote $response): bool
	{
		if($this->oauth === null) throw new \Exception('No OAuth instance provided.');
		$content = $response->content();
		if (!$content) return false;

		/** @var object{
		 * 		access_token: string,
		 * 		refresh_token?: string,
		 * 		token_type: string,
		 * 		expires_in: int,
		 * 		refresh_token_expires_in?: int,
		 * 		scope: string,
		 * 		info: object{
		 * 			id: string,
		 * 			first-name: string,
		 * 			last-name: string,
		 * 			email: string
		 * 		}
		 * 	} $data*/
		$data = json_decode($content, false);

		// Save Data to Cookie
		Cookie::set('steady-token', $data->access_token, [
			'expires' => $data->expires_in / 60
		]);
		Cookie::set(
			'steady-token-expires',
			(string) Date::now()->add(new DateInterval('PT' . $data->expires_in . 'S'))->timestamp(),
			[
				'expires' => $data->expires_in / 60
			]
		);
		if(isset($data->refresh_token) && isset($data->refresh_token_expires_in)) {
			Cookie::set('steady-refresh-token', $data->refresh_token, [
				'expires' => $data->refresh_token_expires_in / 60
			]);
		}

		return true;
	}

	/**
	 * Update Token
	 *
	 * @return bool Boolean that states if refreshing the tokens was successful
	 */
	public function update(): bool {
		if(($token = $this->refreshToken()) === null) return false;
		try {
			$response = $this->request(grant_type: 'access_token', token: $token);
			return $this->save($response);
		} catch (\Exception $e) {
			return false;
		}
	}

	/**
	 * Alias for accessToken method
	 *
	 * @return ?string $accessToken Access Token
	 */
	public function value(): ?string
	{
		return $this->accessToken();
	}

	public function flush(): void
	{
		Cookie::remove('steady-token');
		Cookie::remove('steady-token-expires');
		Cookie::remove('steady-refresh-token');
	}

}

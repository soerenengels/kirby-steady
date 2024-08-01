<?php
namespace Soerenengels\Steady;
use Kirby\Http\Remote;
use Kirby\Toolkit\Date;
use Kirby\Data\Yaml;
use DateInterval;

/** AccessToken representation
 *
 * Handle Steady Access and Refresh Tokens
 */
class AccessToken
{
	private OAuth $oauth;

	public function __construct(
		?OAuth $oauth = null
	) {
		$this->oauth = $oauth ?? steady()->oauth();
	}

	/**
	 * Return Access Token
	 *
	 * and update if necessary
	 * @return ?string $refreshToken Refresh Token
	 */
	public function accessToken(): ?string {
		if($this->isExpired()) {
			$this->update();
		}
		return kirby()->user()?->content()->steady_access_token()->yaml()['access_token'] ?? null;
	}

	private function expiresIn(): DateInterval
	{
		$timestamp = kirby()->user()?->content()->steady_access_token()->yaml()['expires_in'] ?? 604800;
		return new DateInterval('PT' . $timestamp . 'S');
	}

	public function isExpired(): bool
	{
		$expirationDate = $this->scheduledAt()->add($this->expiresIn());
		return Date::now()->isAfter($expirationDate);
	}

	/**
	 * Return Refresh Token
	 *
	 * @return ?string $refreshToken Refresh Token
	 */
	public function refreshToken(): ?string {
		return kirby()->user()?->content()->steady_access_token()?->yaml()['refresh_token'] ?? null;
	}

	/**
	 * Refresh Token is Expired
	 *
	 * @return bool $isExpired
	 */
	private function refreshTokenIsExpired(): bool {
		// TODO: Logic for refresh token expiration
		return false;
	}

	/**
	 * Request Steady Access Token
	 *
	 * __Step 5:__ It's time for an access token.
	 * With some data in exchange we request
	 * an access token. The request response
	 * gets returned.
	 *
	 * @param 'authorization_code'|'refresh_token' $grant_type
	 * @param string $token
	 */
	public function request(
		string $grant_type = 'authorization_code',
		string $token = null
	) {
		// Setup Payload
		$data = [
			'client_id' => $this->oauth->client_id,
			'client_secret' => $this->oauth->client_secret,
			'grant_type' => $grant_type,
			'redirect_uri' => $this->oauth->redirect_uri
		];
		$data += ['code' => $token ?? $this->oauth->authorization_code];
		// TODO: Refresh Token
		$data += ['refresh_token' => $this->refreshToken()];
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
	 * */
	public function save(Remote $response)
	{
		$data = json_decode($response->content());
		if (($user = $this->oauth->findUserBySteadyId($data->info->id))) {

			kirby()->impersonate('kirby', function () use ($user, $data) {

				$data->{'scheduled_at'} = \Kirby\Toolkit\Date::now()->getTimestamp();
				$user->update([
					'steady_access_token' => Yaml::encode($data)
				]);
			});
		} else {
			$user = $this->oauth->createUser($response);
		}
		$user->loginPasswordless();
		return true;
	}

	private function scheduledAt(): ?\Kirby\Toolkit\Date
	{
		return new Date(
			kirby()->user()?->content()->steady_access_token()->yaml()['scheduled_at']
		) ?? null;
	}

	/**
	 * Update Current Users Access Token
	 *
	 * @return null|bool $isSuccess Boolean that states if refreshing the token was successful
	 */
	public function update(): null|bool {
		if($this->refreshTokenIsExpired()) return null;
		try {
			$response = $this->request('access_token', $this->refreshToken());
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

}

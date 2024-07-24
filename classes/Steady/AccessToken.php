<?php
namespace Soerenengels\Steady;
use Kirby\Http\Remote;
use Kirby\Toolkit\Date;
use Kirby\Data\Yaml;

/** AccessToken representation
 *
 * and __Step 6__ of the OAuth flow
 */
class AccessToken
{
	private OAuth $oauth;
	private string $access_token;
	private string $refresh_token;
	private string $token_type;
	private int $expires_in;
	private string $scope;
	private mixed $info;
	private Date $scheduled_at;

	public function __construct(Remote $response, ?OAuth $oauth = null)
	{
		$this->oauth = $oauth ?? steady()->oauth();
		$this->init($response);
	}

	private function init(Remote $response)
	{
		// Parse response
		$data = json_decode($response->content());

		// If kirby user with steady id exists, update user access token
		if (($user = $this->oauth->findUserBySteadyId($data->info->id))) {
			$this->storeAccessToken($user, $data);
		} else { // Create new kirby user
			$user = $this->oauth->createUser($response);
		}
		$user->loginPasswordless();
	}

	/**
	 * Store Refresh token in Kirby User File
	 */
	public function storeAccessToken(\Kirby\Cms\User $user, $data)
	{
		kirby()->impersonate('kirby', function () use ($user, $data) {

			$data->{'scheduled_at'} = \Kirby\Toolkit\Date::now()->getTimestamp();
			$user->update([
				'steady_access_token' => Yaml::encode($data)
			]);
		});
	}

	/**
	 * Refresh Current Users Access Token
	 */
	/* public function update(): AccessToken
	{
		$response = $this->oauth->requestAccessToken(
			'refresh_token',
			$this->refresh_token
		);
		return new AccessToken($response);
	} */
	private function scheduledAt(): ?\Kirby\Toolkit\Date {
		return new Date(
			kirby()->user()->content()->steady_access_token()->yaml()['scheduled_at']
		) ?? null;
	}

	private function expiresIn(): DateInterval {
		$timestamp = kirby()->user()->content()->steady_access_token()->yaml()['expires_in'] ?? 604800;
		return new DateInterval('PT' . $timestamp . 'S');
	}

	public function isExpired(): bool
	{
		$expirationDate = $this->scheduledAt()->add($this->expiresIn());
		return Date::now()->isAfter($expirationDate);
	}

	public function value(): string
	{
		return $this->access_token;
	}
}

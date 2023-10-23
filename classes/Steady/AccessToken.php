<?php

namespace Soerenengels\Steady;

use Kirby\Http\Remote;
use Kirby\Toolkit\Date;

class AccessToken
{
	private OAuth $oauth;
	private string $access_token;
	private string $refresh_token;
	private string $token_type;
	private int $expires_in;
	private string $scope;
	private User $info;
	private Date $scheduled_at;

	public function __construct(array $response)
	{
		$this->init($response);
		$this->setCookie();
		$this->storeRefreshToken();
	}

	private function init(Remote $response)
	{
		[
			'access_token' => $this->access_token,
			'refresh_token' => $this->refresh_token,
			'token_type' => $this->token_type,
			'expires_in' => $this->expires_in,
			'scope' => $this->scope,
			'info' => $info
		] = $response->json();
		$this->info = new User($info);
		$this->scheduled_at = new Date();
	}

	/**
	 * Store the Access Token as Cookie
	 */
	public function setCookie(): bool
	{
		return Cookie::set(
			'steady_access_token',
			$this->access_token,
			[
				'lifetime' => $this->expires_in,
				'domain' => site()->url(),
				'secure' => true,
				'sameSite' => true
			]
		);
	}

	/**
	 * Store Refresh token in Kirby User File
	 */
	public function storeRefreshToken(): bool
	{
		$user = kirby()
			->users()
			->findBy('email', $this->info->email);

		try {
			$user->update([
				'steady_refresh_token' => $this->refresh_token
			]);
			return true;
		} catch (Exception $e) {
			echo 'The refresh token could not be saved to user.';
			echo $e->getMessage();
			return false;
		}
	}

	/**
	 * Reset Refresh Token
	 */
	public function resetRefreshToken() {
		$user = kirby()
			->users()
			->findBy('email', $this->info->email);

		try {
			$user->update([
				'steady_refresh_token' => ''
			]);
			return true;
		} catch (Exception $e) {
			echo 'The refresh token could not be reset.';
			echo $e->getMessage();
			return false;
		}
	}

	/**
	 * Refresh Current Users Access Token
	 */
	public function update(): AccessToken
	{
		$response = $this->oauth->requestAccessToken(
			'refresh_token',
			$this->refresh_token
		);
		return new AccessToken($response);
	}

	public function isExpired(): bool
	{
		// TODO
		return true;
	}

	public function value(): string
	{
		return $this->access_token;
	}


}

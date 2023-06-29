<?php

namespace Soerenengels\Steady;
use Soerenengels\Steady\User;

interface UsersInterface {
	public function find(string $id): User|false;
}

/**
 * Users Class
 * derived from object included in Steady API response
 * see https://developers.steadyhq.com/#subscriptions
 *
 * @param array $data array of associative user array
 *
 * @author Sören Engels <mail@soerenengels.de>
 * @see https://github.com/soerenengels/kirby-steady
 */
class Users implements UsersInterface
{

	/** @var array array of User */
	public array $users;

	public function __construct(
		array $data
	) {
		foreach ($data as $user) {
			$this->users[] = new User($user);
		};
	}

	public function find(string $id): User|false {
		$result = array_filter($this->users, function(User $user) use ($id) {
			return $user->id == $id;
		});
		return count($result) > 0 ? $result[0] : false;
	}
}

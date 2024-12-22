<?php

namespace Soerenengels\Steady;
use Kirby\Toolkit\Date;

/**
 * Steady User
 *
 * Access Steady User with magic methods
 *
 * @method string id() user id
 * @method 'user'|'newsletter_subscriber' type() type of user
 * @method string email() users email
 * @method ?string first_name() users first name
 * @method ?string last_name() users last name
 * @method ?bool has_password() boolean if the user has set a password for their account
 * @method ?Date opted_in_at() datetime converted to Kirby\Toolkit\Date when the newsletter subscriber clicked the opt-in link in the email
 * @method ?string avatar_url() url for Steady user avatar e.g. "https://assets.steadyhq.com/gfx/defaults/user/avatar.png?auto=format&crop=faces&fit=crop&fm=png&h=200&mask=ellipse&w=200"
 *
 * @see https://developers.steadyhq.com/#subscriptions
 */
class User extends Entity
{
	protected array $properties = [
		'id',
		'type',
		'email',
		'first_name',
		'last_name',
		'has_password',
		'opted_in_at',
		'avatar_url'
	];

	/**
	 * Full name of User
	 *
	 * @return ?string $fullname Concat first and last name if both are set
	 */
	public function fullname(): ?string {
		if (
			(!($firstName = $this->first_name())) ||
			(!($lastName = $this->last_name()))
		) return null;
		return "$firstName $lastName";
	}

	/**
	 * Full name or Email of User
	 */
	public function nameOrEmail(): string {
		return $this->fullname() ?? $this->email();
	}
}

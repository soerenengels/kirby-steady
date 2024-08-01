<?php

namespace Soerenengels\Steady;
use Kirby\Toolkit\Date;

/**
 * Generic Steady User Class
 * as object included in Steady API response
 * see https://developers.steadyhq.com/#subscriptions
 *
 * @param array $data associative array
 */
class User
{

	/** @var string user id */
	public string $id;
	/** @var UserType $type type of user: USER|NEWSLETTER_SUBSCRIBER*/
	public UserType $type;
	/** @var string users email */
	public string $email;
	/** @var string users first name */
	public string $first_name;
	/** @var string users last name */
	public string $last_name;
	/** @var bool $has_password boolean if the user has set a password for their account */
	public bool $has_password;
	/** @var Date datetime converted to Kirby\Toolkit\Date when the newsletter subscriber clicked the opt-in link in the email */
	public Date $opted_in_at;

	/** @var string url for Steady user avatar
	 * e.g. "https://assets.steadyhq.com/gfx/defaults/user/avatar.png?auto=format&crop=faces&fit=crop&fm=png&h=200&mask=ellipse&w=200"
	 */
	public string $avatar_url;

	public function __construct(
		array $data
	) {
		[
			'id' => $id,
			'type' => $type,
			'attributes' => $attributes
		] = $data + [null, null, 'attributes' => []]; // + defaults
		$this->id = $id;
		$this->type = UserType::tryFrom($type);
		foreach ($attributes as $key => $value) {
			$key = str_replace('-', '_', $key);
			if ($key == 'opted_in_at') {
				$value = new Date($value);
			}
			$this->{$key} = $value;
		};
	}

	/**
	 * Return full name
	 *
	 * @return ?string $fullname Concat first and last name if both are set
	 */
	public function fullname(): ?string {
		if((!$this->first_name) || (!$this->last_name)) return null;
		return $this->first_name . ' ' . $this->last_name;
	}
}

<?php

namespace Soerenengels\Steady;
use Kirby\Toolkit\Date;

/**
 * Generic Steady User Class
 * as object included in Steady API response
 * see https://developers.steadyhq.com/#subscriptions
 *
 * @param array $data associative array
 * @method string id() user id
 * @method UserType type() type of user: USER|NEWSLETTER_SUBSCRIBER
 * @method string email() users email
 * @method string first_name() users first name
 * @method string last_name() users last name
 * @method bool has_password() boolean if the user has set a password for their account
 * @method Date opted_in_at() datetime converted to Kirby\Toolkit\Date when the newsletter subscriber clicked the opt-in link in the email
 * @method string avatar_url() url for Steady user avatar e.g. "https://assets.steadyhq.com/gfx/defaults/user/avatar.png?auto=format&crop=faces&fit=crop&fm=png&h=200&mask=ellipse&w=200"
 */
class User
{
	use toArrayTrait;

	private readonly string $id;
	private readonly UserType $type;
	private readonly string $email;
	private readonly string $first_name;
	private readonly string $last_name;
	private readonly bool $has_password;
	private readonly Date $opted_in_at;
	private readonly string $avatar_url;

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

	public function __call($name, $arguments)
	{
		$properties = get_class_vars($this::class);
		if (!in_array($name, array_keys($properties))) {
			throw new \BadMethodCallException();
		}
		return $this->$name;
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

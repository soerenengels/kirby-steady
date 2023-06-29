<?php

namespace Soerenengels\Steady;

/**
 * User Class
 * as object included in Steady API response
 * see https://developers.steadyhq.com/#subscriptions
 *
 * @param array $data associative array
 *
 * @author Sören Engels <mail@soerenengels.de>
 * @version 1.0
 * @see https://github.com/soerenengels/kirby-steady
 */
class User
{

	/** @var string user id */
	public string $id;

	/** @var string type 'user' */
	public string $type;

	/** @var string users email */
	public string $email;

	/** @var string users first name */
	public string $first_name;

	/** @var string users last name */
	public string $last_name;

	/** @var string url for Steady user avatar
	 * e.g. "https://assets.steadyhq.com/gfx/defaults/user/avatar.png?auto=format&crop=faces&fit=crop&fm=png&h=200&mask=ellipse&w=200"
	 */
	public string $avatar_url;

	public function __construct(
		object $data
	) {
		[
			'id' => $id,
			'type' => $type,
			'attributes' => $attributes
		] = $data;
		$this->id = $id;
		$this->type = $type;
		foreach ($attributes as $key => $value) {
			$key = str_replace('-', '_', $key);
			$this->{$key} = $value;
		};
	}
}

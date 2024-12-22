<?php

namespace Soerenengels\Steady;
use Soerenengels\Steady\User;

/**
 * Steady Users
 *
 * @see https://developers.steadyhq.com/#newsletter-subscribers
 * @see https://developers.steadyhq.com/#subscriptions
 *
 * @property User[] $items array of User objects
 * @extends Collection<User>
 */
class Users extends Collection
{
	protected const DEFAULT_SORT_PARAM = 'email';
	protected const ENTITY_CLASS = User::class;

}

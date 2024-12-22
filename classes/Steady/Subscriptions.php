<?php
namespace Soerenengels\Steady;
use Soerenengels\Steady\Subscription;

/**
 * Steady Subscriptions
 *
 * @see https://developers.steadyhq.com/#subscriptions
 * @extends Collection<Subscription>
 */
class Subscriptions extends Collection
{
	protected const ENTITY_CLASS = Subscription::class;
	protected const DEFAULT_SORT_PARAM = 'inserted_at';
}

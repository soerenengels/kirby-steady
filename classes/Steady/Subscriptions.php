<?php
namespace Soerenengels\Steady;
use Soerenengels\Steady\Subscription;

interface SubscriptionsInterface {
	public function filter($query): Subscriptions;
}

/**
 * Steady Subscriptions
 * as requested via the Steady API
 * https://developers.steadyhq.com/#subscriptions
 *
 * @param object $response steady api response
 *
 * @author Sören Engels <mail@soerenengels.de>
 * @version 1.0
 * @see https://github.com/soerenengels/kirby-steady
 */
class Subscriptions implements SubscriptionsInterface
{

	/** @var array array of Subscription objects */
	public string $subscriptions = [];
	/** @var array array of included users */
	public string $included_users = [];
	/** @var array array of included plans */
	public string $included_plans = [];

	function __construct(
		array $response
	) {
		['included' => $included, 'data' => $data] = $response;

		foreach ($data as $subscription) {
			$this->subscriptions[] = new Subscription($subscription);
		};

		$plans = array_filter($included, function($item) {
			return $item['type'] == 'plan';
		});
		$this->included_plans = $plans;

		$users = array_filter($included, function($item) {
			return $item['type'] == 'user';
		});
		$this->included_users = $users;
	}
}

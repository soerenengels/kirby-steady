<?php
namespace Soerenengels\Steady;
use Soerenengels\Steady\Subscription;
use Kirby\Toolkit\A;

/**
 * Steady Subscriptions
 * as requested via the Steady API
 * https://developers.steadyhq.com/#subscriptions
 *
 * @param object $response steady api response
 * @method count(): returns int
 * @method find(string $id): returns ?Subscription
 * @method filter(\Closure $filter): returns Subscriptions object
 * @method list(): returns array of Subscription objects
 */
class Subscriptions
{

	use hasItems, FindTrait, CountTrait, FactoryTrait, FilterTrait;

	/** @var array array of included users */
	public array $included_users = [];
	/** @var array array of included plans */
	public array $included_plans = [];

	function __construct(
		array $response
	) {
		['included' => $included, 'data' => $data] = $response;

		foreach ($data as $subscription) {
			$this->items[] = new Subscription([
				'data' => $subscription,
				'included' => $included
			]);
		};

		// filter included data for plan data
		$plans = array_filter($included, function($item) {
			return $item['type'] == 'plan';
		});
		$this->included_plans = $plans;

		// filter included data for users data
		$users = array_filter($included, function($item) {
			return $item['type'] == 'user';
		});
		$this->included_users = $users;
	}

	public function toArray() {
		return A::map($this->list(), function($item) {
			return [
				'data' => $item,
				'plan' => $item->plan(),
				'subscriber' => $item->subscriber(),
				'gifter' => $item->gifter()
			];
		});
	}

}

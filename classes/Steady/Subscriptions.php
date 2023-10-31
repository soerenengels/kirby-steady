<?php
namespace Soerenengels\Steady;
use Soerenengels\Steady\Subscription;

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

	/** @var Subscription[] array of Subscription objects */
	public array $subscriptions = [];
	/** @var array array of included users */
	public array $included_users = [];
	/** @var array array of included plans */
	public array $included_plans = [];

	function __construct(
		array $response
	) {
		['included' => $included, 'data' => $data] = $response;

		foreach ($data as $subscription) {
			$this->subscriptions[] = new Subscription($subscription);
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

	/**
	 * Returns total Subscription objects
	 */
	public function count(): int {
		return count($this->list());
	}

	/**
	 * Filters Subscriptions by $attribute and $value
	 * @param \Closure $filter custom filter function
	 * @return Subscriptions returns new and filtered Subscriptions object
	 */
	public function filter(\Closure $filter): Subscriptions {
		$filtered_subscriptions = array_filter(
			$this->list(),
			$filter
		);
		return self::factory($filtered_subscriptions);
	}

	/**
	 * Create Subscriptions object from array of Subscription objects
	 * @param Subscription[] $list array of Subscription objects
	 * @return Subscriptions
	 */
	public static function factory(array $list): Subscriptions {
		$subscriptions = new Subscriptions([]);
		$subscriptions->subscriptions = $list;
		return $subscriptions;
	}

	/**
	 * Find Subscription by $id
	 * @param string $id plan id
	 * @return ?Subscription returns Subscription or null
	 */
	public function find(string $id): ?Subscription {
		return array_reduce(
			$this->list(),
			function (?Subscription $carry, ?Subscription $item) use ($id) {
				return $carry ?? ($item->id === $id ? $item : $carry);
			},
			null
		);
	}

	/**
	 * Returns array of Subscription objects
	 * @return Subscription[]
	 */
	public function list(): array {
		return $this->subscriptions;
	}

}

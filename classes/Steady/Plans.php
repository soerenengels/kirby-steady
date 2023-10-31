<?php
namespace Soerenengels\Steady;


/**
 * Plans Class
 * as requested via Steady API
 * https://developers.steadyhq.com/#plans
 *
 * @param object $data Steady API Response
 * @method int count() returns total Plan Objects
 * @method ?Plan find(string $id) returns Plan or null
 * @method Plans filter(\Closure $filter) Returns new and filtered Plans Object
 * @method Plan[] list() Returns array of Plan Objects
 */
class Plans
{
	/** @var Plan[] array of Plan objects */
	private array $plans = [];

	function __construct(array $data) {
		foreach ($data as $plan) {
			$this->plans[] = new Plan($plan);
		};
	}

	/**
	 * Returns total Plan objects
	 */
	public function count(): int {
		return count($this->list());
	}

	/**
	 * Filters Plans by $filter Closure
	 * @param \Closure $filter custom filter function
	 * @return Plans returns new and filtered Plans object
	 */
	public function filter(\Closure $filter): Plans {
		$filtered_plans = array_filter(
			$this->list(),
			$filter
		);
		return self::factory($filtered_plans);
	}

	/**
	 * Create Plans object from array of Plan objects
	 * @param Plan[] $list array of Plan objects
	 * @return Plans
	 */
	public static function factory(array $list): Plans {
		$plans = new Plans([]);
		$plans->plans = $list;
		return $plans;
	}

	/**
	 * Find Plan by $id
	 * @param string $id plan id
	 * @return ?Plan returns Plan or null
	 */
	public function find(string $id): ?Plan {
		return array_reduce(
			$this->list(),
			function (?Plan $carry, ?Plan $item) use ($id) {
				return $carry ?? ($item->id === $id ? $item : $carry);
			},
			null
		);
	}

	/**
	 * Returns array of Plan objects
	 * @return Plan[]
	 */
	public function list(): array {
		return $this->plans;
	}
}

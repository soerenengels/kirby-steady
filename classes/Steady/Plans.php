<?php
namespace Soerenengels\Steady;

interface PlansInterface {
	public function find(string $id): ?Plan;
	public function count(): int;
}

/**
 * Plans
 * as requested via Steady API
 * https://developers.steadyhq.com/#plans
 * Returns an array of SteadyPlan Objects
 *
 * @param object $data steady api response
 *
 * @author Sören Engels <mail@soerenengels.de>
 * @version 1.0
 * @see https://github.com/soerenengels/kirby-steady
 */
class Plans implements PlansInterface
{
	/** @var array array of SteadyPlan objects */
	public array $plans = [];

	function __construct(
		array $data
	) {
		foreach ($data as $plan) {
			$this->plans[] = new Plan($plan);
		};
	}

	/**
	 * Finds a plan by id
	 * @param string $id plan id
	 * @return ?Plan returns Plan or null
	 */
	public function find(string $id): ?Plan {
		$result = array_filter($this->plans, function(Plan $plan) use ($id) {
			return $plan->id == $id;
		});
		return count($result) > 0 ? current($result) : null;
	}

	/**
	 * Returns number of plans
	 */
	public function count():int {
		return count($this->plans);
	}
}

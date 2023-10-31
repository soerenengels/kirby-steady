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
	 * Returns array of Plan objects
	 * @return Plan[]
	 */
	public function count():int {
		return count($this->plans);
	}
}

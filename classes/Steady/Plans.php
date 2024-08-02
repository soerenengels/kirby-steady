<?php

namespace Soerenengels\Steady;

/**
 * Plans Class
 * as requested via Steady API
 * https://developers.steadyhq.com/#plans
 *
 * @method int count() returns total Plan Objects
 * @method ?Plan find(string $id) returns Plan or null
 * @method Plans filter(\Closure $filter) Returns new and filtered Plans Object
 * @method Plan[] list() Returns array of Plan Objects
 */
class Plans implements \IteratorAggregate
{
	use hasItems, FactoryTrait, FilterTrait, FindTrait, CountTrait, toArrayTrait;

	/** @param array $data Steady API Response */
	function __construct(
		array $data
	) {
		foreach ($data as $plan) {
			$this->items[] = new Plan($plan);
		};
	}

	public function sort(): static {
		usort($this->items, function ($a, $b) {
			return $a->monthly_amount <=> $b->monthly_amount;
		});
		return $this;
	}

}

<?php

namespace Soerenengels\Steady;

trait FilterTrait {
	/**
	 * Filters items by $filter Closure
	 * @param \Closure $filter custom filter function
	 * @return static returns new and filtered Object
	 */
	public function filter(\Closure $filter): static {
		$filtered_items = array_filter(
			$this->items,
			$filter
		);
		return self::factory($filtered_items);
	}
}

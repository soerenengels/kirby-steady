<?php

namespace Soerenengels\Steady;

trait FindTrait {
	/**
	 * Find item by $id
	 * @param string $id item id
	 * @return ?object returns item or null
	 */
	public function find(string $id): ?object {
		return array_reduce(
			$this->items,
			function (?object $carry, ?object $item) use ($id) {
				return $carry ?? ($item->id === $id ? $item : $carry);
			},
			null
		);
	}
}

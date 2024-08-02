<?php
namespace Soerenengels\Steady;

trait CountTrait {
	/**
	 * Returns total amount of objects
	 */
	public function count(): int {
		return count($this->items);
	}

	/**
	 * Returns first item
	 */
	public function first() {
		return $this->items[0];
	}

	/**
	 * Returns last item
	 */
	public function last() {
		return end($this->items);
	}
}

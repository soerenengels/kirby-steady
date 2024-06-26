<?php
namespace Soerenengels\Steady;

trait CountTrait {
	/**
	 * Returns total amount of objects
	 */
	public function count(): int {
		return count($this->items);
	}
}

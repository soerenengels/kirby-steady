<?php

namespace Soerenengels\Steady;

trait FactoryTrait {
	/**
	 * Create new Oject from array of children Class objects
	 * @param array $array list of children Class objects
	 * @return static
	 */
	public static function factory(array $array = []): static {
		$object = new self([]);
		$object->items = $array;
		return $object;
	}
}

<?php
namespace Soerenengels\Steady;

/**
 * @method array toArray() transform object to array
 */
trait toArrayTrait {

	/**
	 * @param mixed $value
	 */
	public function toArray(mixed $value = null): array {
		$result = [];
		$object = $value ?? $this;

		foreach ($object as $key => $value) {
			if ( // Object has toArray method
				is_object($value) &&
				method_exists($value, 'toArray')
			) {
				$result[$key] = $value->toArray();
			} elseif (is_array($value)) {
				$result[$key] = array_map(function ($item) {
					return is_object($item) || is_array($item) ? $this->toArray($item) : $item;
				}, $value);
			} else {
				$result[$key] = $value;
			}
		}
		return $result;
	}

}

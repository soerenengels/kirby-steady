<?php
namespace Soerenengels\Steady;

trait hasItems {

	/** @var array $items array of children Classes */
	private array $items = [];

	/**
	 * List items as Array
	 * @return array of children Classes
	 */
	public function list(): array {
		return $this->items;
	}
}

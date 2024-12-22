<?php

namespace Soerenengels\Steady;
use Soerenengels\Steady\User;
use Soerenengels\Steady\Plan;
use Soerenengels\Steady\Subscription;
use Soerenengels\Steady\Entity;

/**
 * Steady Collection
 *
 * @template T of Entity
 * @implements \IteratorAggregate<T>
 *
 * @see \Soerenengels\Steady\Subscriptions
 * @see \Soerenengels\Steady\Users
 * @see \Soerenengels\Steady\Plans
 * @see \Soerenengels\Steady\Widgets
 *
 * @phpstan-import-type SteadyEntityResponse from Steady
 * @phpstan-import-type SteadyCollectionResponse from Steady
 * @phpstan-import-type SteadyResponse from Steady
 */
abstract class Collection implements \IteratorAggregate
{

	/** @var string DEFAULT_SORT_PARAM Default parameter for sort function */
	protected const DEFAULT_SORT_PARAM = 'id';

	/** @var class-string<T> ENTITY_CLASS Class string for Item generation */
	protected const ENTITY_CLASS = Entity::class;

	/** @var array<T> $items Array of Collection items */
	protected array $items = [];

	/**
	 * Constructor
	 *
	 * @param SteadyCollectionResponse $data
	 * @param array<SteadyEntityResponse> $included
	 * */
	public function __construct(
		array $data,
		array $included = []
	) {
		// Create items from data
		foreach ($data as $d) {
			$this->items[] = $this->create(static::ENTITY_CLASS, $d, $included);
		}
	}

	/**
	 * Returns total
	 * amount of objects in Collection
	 */
	public function count(): int
	{
		return count($this->items);
	}

	/**
	 * Create new Object from array of attributes
	 *
	 * @phpstan-import-type SteadyEntityReponse from Collection
	 * @param class-string<T> $classname Classname
	 * @param SteadyEntityResponse|WidgetType $data
	 * @param array<SteadyEntityResponse> $included
	 * @return T
	 */
	public function create($classname, $data, $included = []) {
		if (!class_exists($classname)) {
			throw new \Exception('Class ' . $classname . ' does not exist.');
		}
		if (!($data instanceof WidgetType)) {
			$object = new $classname($data, $included);
		} else {
			$object = new Widget($data);
		}
		assert($object instanceof $classname);
		return $object;
	}

	/**
	 * Create new Oject from array of children objects
	 *
	 * @param T[] $array List of children objects
	 */
	public static function factory(array $array = []): static
	{
		$object = new (static::class)([]);
		$object->items = $array;
		return $object;
	}

	/**
	 * Filters items by $filter Closure
	 *
	 * @param \Closure $filter Custom filter function
	 * @return static New and filtered Object
	 */
	public function filter(\Closure $filter): static
	{
		$filtered_items = array_filter(
			$this->items,
			$filter
		);
		return self::factory($filtered_items);
	}

	/**
	 * Find item by $id
	 *
	 * @param string $id Entity id
	 * @return ?T $item Item or null
	 */
	public function find(string $id)
	{
		/**
		 * @param ?T $carry
		 * @param T $item
		 */
		$closure = function (
			$carry,
			$item
		) use ($id) {
			if (!($item instanceof Entity)) {
				throw new \InvalidArgumentException('Item is not an instance of Entity');
			}
			return $carry ?? ($item->id() === $id ? $item : $carry);
		};

		/** @var ?T $item */
		$item = array_reduce($this->items, $closure);
		return $item;
	}

	/**
	 * Get first item of Collection
	 *
	 * @return T
	 */
	public function first()
	{
		return $this->items[0];
	}

	/**
	 * Make Collection iterable
	 * */
	public function getIterator(): \Traversable
	{
		return new \ArrayIterator($this->items);
	}

	/**
	 * Get last item of Collection
	 * @return ?T
	 */
	public function last()
	{
		return $this->items[-1] ?? null;
	}

	/**
	 * List all items
	 *
	 * @return T[] Array of children objects
	 */
	public function list(): array
	{
		return $this->items;
	}

	/**
	 * Sort items by $sort Closure
	 *
	 * @param ?callable $sort (optional) Custom sort function
	 * @return static
	 */
	public function sort(?callable $sort = null): static
	{
		if (!$sort) return $this->sortBy();
		usort($this->items, $sort);
		return $this;
	}

	/**
	 * Sort items by $key
	 *
	 * @param string $key (optional) Sort by Collection parameter key or DEFAULT_SORT_PARAM
	 */
	public function sortBy(?string $key = null): static
	{
		$key = $key ?? self::DEFAULT_SORT_PARAM;
		/**
		 * @param T $a
		 * @param T $b */
		$sort = function ($a, $b) use ($key) {
			return $a->$key() <=> $b->$key();
		};
		usort($this->items, $sort);
		return $this;
	}

	/**
	 * Return Array
	 *
	 * @return array<string|int, array<mixed>>
	 */
	public function toArray(): array {
		$data = [];

		$data = array_map(function ($item) {
			return $item->toArray();
		}, $this->items);

		return $data;
	}
}

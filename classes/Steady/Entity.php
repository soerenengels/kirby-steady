<?php

namespace Soerenengels\Steady;
use Kirby\Toolkit\Date;

/**
 * Entity of a Collection
 *
 * @method string	id() Id of Entity
 * @method string type() Type of Entity
 * @phpstan-import-type SteadyEntityResponse from Steady
 * @phpstan-import-type SteadyCollectionResponse from Steady
 */
class Entity {
	/** @var array<string> DATE_TYPED_PROPERTIES Array of properties that should be typed as \Kirby\Toolkit\Date */
	private const DATE_TYPED_PROPERTIES = ['inserted_at', 'updated_at', 'countdown_ends_at', 'opted_in_at', 'cancelled_at', 'trial_ends_at', 'active_from', 'expires_at'];

	/** @var array<string> $properties Callable Properties */
	protected array $properties = ['id', 'type'];

	/** @var string $id Entity Id */
	protected readonly string $id;

	/** @var string $type Entity Type */
	protected readonly string $type;

	/** @var array<string,string|int|bool|null> $attributes Entity Attributes */
	protected readonly array $attributes;

	/**
	 * @var array<string, array{data: array<string>}> $relationships */
	protected readonly array $relationships;

	/**
	 * Constructor
	 *
	 * @param SteadyEntityResponse $data Raw data array from API response
	 * @param SteadyCollectionResponse $included (optional)
	 *
	 * */
	public function __construct(
		protected array $data,
		protected array $included = []
	) {
		// Setup id, type, attributes and relationships (if available)
		$this->id = $this->data['id'];
		$this->type = $this->data['type'];
		$this->attributes = $this->data['attributes'] ?? [];
		$this->relationships = $this->data['relationships'] ?? [];
	}

	/**
	 * Magic method to get properties
	 *
	 * @param string $name Property name
	 * @param mixed $arguments Arguments
	 * @return string|int|bool|Date|null
	 */
	public function __call(string $name, mixed $arguments): string|int|bool|Date|null
	{
		// Throw error if method does not exist
		if (!in_array($name, $this->properties)) {
			throw new \BadMethodCallException();
		}

		// Return value of identifiers
		if (in_array($name, ['id', 'type'])) {
			/** @var string $value */
			$value = $this->{$name};
			return $value;
		}

		// Get value from attributes
		$key = str_replace('_', '-', $name);
		$value = $this->attributes[$key] ?? throw(new \Exception('Attribute not found: ' . $key .  json_encode($this->attributes)));

		// Cast date values to Kirby\Toolkit\Date class
		if (
			in_array($name, self::DATE_TYPED_PROPERTIES) &&
			(
				is_int($value) ||
				is_string($value)
			)
		) {
			$value = new Date($value);
		}

		return $value;
	}

	/**
	 * Convert Entity to array
	 *
	 * @return array<string, mixed> Entity data
	 */
	public function toArray(): array {
		$data = [];
		foreach ($this->properties as $property) {
			try {
				$value = $this->$property();
				if ($value instanceof Date) {
					$value = $value->toString();
				}
				$data[$property] = $value;
			} catch (\Exception $e) {
				// Skip property if it does not exist
				// TODO: handle error?
			}
		}
		foreach(['included', 'relationships'] as $attribute) {
			if (isset($this->$attribute) && !empty($this->$attribute)) {
				$data[$attribute] = $this->$attribute;
			}
		}
		return $data;
	}
}

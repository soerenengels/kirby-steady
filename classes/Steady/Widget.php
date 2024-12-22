<?php

namespace Soerenengels\Steady;
use Soerenengels\Steady\Widgets;

/**
 * Widget Class
 */
class Widget extends Entity
{

	public function __construct(
		protected WidgetType $enum
	) {
		parent::__construct([
			'id' => $this->enum->value,
			'type' => $this->enum->value,
			'attributes' => []
		]);
	}

	public function title(): string {
		return $this->enum->title();
	}

	public function id(): string {
		return $this->id;
	}

	public function icon(): string {
		return $this->enum->icon();
	}

	/**
	 * Returns true if Widget is activated in Steady backend
	 */
	public function enabled(): bool
	{
		$needle = $this->enum->js() . 'Active":true';
		$haystack = Widgets::getWidgetLoaderContent();

		// Check if $needle is contained in $haystack
		$result = (strstr(
			$haystack,
			$needle
		) ?
			true :
			false
		);

		return $result;
	}

	/**
	 * Returns true if Widget is able to be loaded
	 *
	 * Requirements:
	 * 1. Plugins 'widget' option is set to true AND
	 * 2. Widget is activated in Steady backend
	 */
	public function isActive(): bool
	{
		return (
			kirby()->option('soerenengels.steady.widget') &&
			$this->enabled()
		);
	}

	/**
	 * Returns array representation of Widget
	 *
	 * @return array<string, string|bool>
	 */
	public function toArray(): array {
		return [
			'title' => $this->title(),
			'type' => $this->type(),
			'js' => $this->enum->js(),
			'enabled' => $this->enabled(),
			'isActive' => $this->isActive()
		];
	}

	public function toReport(): Report {
		return new Report(
			$this->title(),
			($this->enabled() ? '✓' : '✕'),
			'Steady',
			steady()->widgets()?->enabled() ? ($this->enabled() ? 'positive' : 'info') : ($this->enabled() ? 'notice' : 'default'),
			'https://steadyhq.com/de/backend/publications/' . steady()->publication()->id() . '/integrations/' . $this->type() . '/edit',
			$this->icon()
		);
	}
}

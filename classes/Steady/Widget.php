<?php

namespace Soerenengels\Steady;
use Soerenengels\Steady\Widgets;

/**
 * Widget Class
 * @method isActive
 * @method title
 * @method enabled
 */
class Widget
{

	public function __construct(
		public WidgetType $type
	) {}

	public function title(): string {
		return $this->type->title();
	}

	/**
	 * Returns true if Widget is activated in Steady backend
	 */
	public function enabled(): bool
	{
		$needle = $this->type->js() . 'Active":true';
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
}

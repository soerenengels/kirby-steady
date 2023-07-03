<?php

namespace Soerenengels\Steady;

interface WidgetInterface
{
	public function isActive(): bool;
}

class Widget implements WidgetInterface
{

	public function __construct(
		public WidgetType $type,
		private Widgets $parent
	) {
	}

	/**
	 * Returns true if Widget is activated in Steady backend
	 */
	public function enabled(): bool
	{
		$needle = $this->type->value . 'Active":true';
		$haystack = $this->parent::getWidgetLoaderContent();

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
	 * - If plugins 'widget' option is set to true
	 * - AND widget is activated in Steady backend
	 */
	public function isActive(): bool
	{
		return ($this->parent::enabled() &&
			$this->enabled()
		);
	}
}

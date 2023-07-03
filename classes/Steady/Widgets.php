<?php

namespace Soerenengels\Steady;

use Soerenengels\Steady\WidgetType;
use Soerenengels\Steady\Widget;
use Kirby\Http\Remote;

interface WidgetsInterface
{
	public static function enabled(): bool;

	public function list(): array;
	public function adblock(): Widget;
	public function floatingButton(): Widget;
	public function paywall(): Widget;
}

/**
 * Give access to the Widgets via methods
 *
 * @method adblock
 * @method floatingButton
 * @method paywall
 * @method list
 * @method enabled
 */
class Widgets implements WidgetsInterface
{

	/** @var array $widgets array of Widget objects */
	private array $widgets = [];

	public function __construct()
	{
		// Create a Widget in $widgets array for each WidgetType
		foreach (WidgetType::cases() as $type) {
			$this->widgets[] = [
				$type->value => new Widget($type, $this)
			];
		}
	}

	/**
	 * Enabled method
	 * @return boolean $enabled if plugins 'widget' option is set to true
	 */
	public static function enabled(): bool
	{
		// TODO: use parent->instance
		return kirby()->option('soerenengels.steady.widget');
	}

	public static function getWidgetLoaderContent(): string
	{
		// TODO: use parent
		// TODO: cache
		$url = steady()->publication()->widget_loader_url;
		$request = Remote::get($url);
		$content = $request->content();
		return $content;
	}

	/**
	 * list method
	 * @return array $widgets array of all widgets
	 */
	public function list(): array
	{
		return $this->widgets;
	}

	/* TODO: refactor to use public function __call() */

	public function adblock(): Widget
	{
		return $this->widgets[WidgetType::ADBLOCK->value];
	}

	public function floatingButton(): Widget
	{
		return $this->widgets[WidgetType::ADBLOCK->value];
	}

	public function paywall(): Widget
	{
		return $this->widgets[WidgetType::ADBLOCK->value];
	}
}

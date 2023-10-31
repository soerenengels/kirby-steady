<?php

namespace Soerenengels\Steady;

use Soerenengels\Steady\WidgetType;
use Soerenengels\Steady\Widget;
use Kirby\Http\Remote;

/**
 * Give access to the Widgets via methods
 *
 * @param Steady $steady instance of Steady Class
 * @method Widget adblock() returns Widget Object
 * @method Widget floatingButton() returns Widget Object
 * @method Widget paywall() returns Widget Object
 * @method array list(): Widget returns Widget Object
 * @method bool enabled() returns widget config value
 */
class Widgets
{

	/** @var array $widgets array of Widget objects */
	private array $widgets = [];

	/** @var Steady $steady Steady parent instance */
	private Steady $steady;

	/**
	 * @param Steady $steady
	 */
	public function __construct($steady)
	{
		$this->steady = $steady ?? steady();
		// Create array of Widgets from WidgetType
		$this->widgets = array_map(
				function(WidgetType $type) {
					return new Widget($type);
				},
				WidgetType::cases()
		);
	}

	// Creates methods named after WidgetType values
	/**
	 * @method Widget adblock()
	 * @method Widget checkout()
	 * @method Widget floatingButton()
	 * @method Widget paywall()
	 */
	public function __call(string $name, $arguments): ?Widget
	{
		// Check if method name is a WidgetType value and return Widget Object
		if(!($type = WidgetType::tryFrom($name))) return null;
		return array_reduce(
			$this->list(),
			function(?Widget $carry, ?Widget $widget) use ($type) {
				return $carry ?? ($widget->type == $type ? $widget : $carry);
			},
			null
		);
	}

	/**
	 * Check if widgets are enabled in config
	 * @return boolean $enabled true if plugins 'widget' option is set to true
	 */
	public static function enabled(): bool
	{
		return kirby()->option('soerenengels.steady.widget');
	}

	/**
	 * Request Steadys Javascript Code for Widget
	 * @return string $content javscript content string
	 */
	public static function getWidgetLoaderContent(): string
	{
		$steady = steady();
		$url = $steady->publication()->js_widget_url;
		// TODO: use parent
		$cache = $steady->cache;
		return $cache->getOrSet('js-widget-url', function() use ($url) {
			$request = Remote::get($url);
			return $request->content();
		}, 1);
	}

	/**
	 * Return Widget objects as array
	 * @return Widget[] array of Widget objects
	 */
	public function list(): array
	{
		return $this->widgets;
	}

}

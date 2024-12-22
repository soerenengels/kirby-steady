<?php

namespace Soerenengels\Steady;

use Soerenengels\Steady\Widget;
use Kirby\Exception\Exception;
use Kirby\Http\Remote;

/**
 * Give access to the Widgets via methods
 *
 * @method Widget adblock() returns Widget Object
 * @method Widget floatingButton() returns Widget Object
 * @method Widget paywall() returns Widget Object
 * @method Widget[] list(): Widget returns Widget Object
 * @method bool enabled() returns widget config value
 *
 * @property Widget[] $items array of User objects
 * @extends Collection<Widget>
 */
class Widgets extends Collection
{

	// TODO: remove this and constructor
	/* * @var Steady $steady Steady instance */
	//private Steady $steady;

	/**
	 * @param array<Widget> $items array of Widget objects
	 * */
	public function __construct(
		protected array $items = []//,
		//$steady = null
	)
	{
		//$this->steady = $steady ?? steady();
	}

	/**
	 * Magic Method to call WidgetType methods
	 */
	public function __call(string $name, mixed $arguments): ?Widget
	{
		// Check if method name is a WidgetType value and return Widget Object
		return $this->find($name);
		/* if (!($type = WidgetType::tryFrom($name))) return null;
		return array_reduce(
			$this->list(),
			function (?Widget $carry, ?Widget $widget) use ($type) {
				return $carry ?? ($widget?->type() == $type ? $widget : $carry);
			},
			null
		); */
	}

	/**
	 * Check if widgets are enabled in kirby config
	 *
	 * @return boolean $enabled true if plugins 'widget' option is set to true
	 */
	public static function enabled(): bool
	{
		/** @var bool $enabled */
		$enabled = kirby()->option('soerenengels.steady.widget');
		return $enabled;
	}

	/**
	 * Returns true if any widget is active
	 */
	public function isEnabledInSteady(): bool
	{
		$enabledWidgets = $this->filter(function (Widget $widget) {
			return $widget->enabled();
		});
		return $enabledWidgets->count() > 0;
	}

	/**
	 * Request Steadys Javascript Code for Widget
	 *
	 * @return string $script javscript content string
	 */
	public static function getWidgetLoaderContent(): string
	{
		$steady = steady();
		try {
			$url = $steady->publication()->js_widget_url();
			// TODO: use parent
			$cache = $steady->cache;
			/** @var string $widgetLoaderContent */
			$widgetLoaderContent = $cache->getOrSet('js-widget-url', function () use ($url) {
				$request = Remote::get($url);
				return $request->content();
			}, 1);
			return $widgetLoaderContent;
		} catch (Exception $e) {
			return "\/* Error: Could not load the Steady Widget Loader \*/\n" . $e->getMessage();
		}
	}

	/**
	 * Return \<script> tag with Steady Javascript Widget Loader
	 */
	public function snippet(): \Kirby\Template\Snippet|string|null
	{
		return snippet('components/steady/widget');
	}

	/**
	 * Returns array of Widget Reports
	 *
	 * @return array<array<string, string|int|null>> $widgetReports
	 */
	public function toReports(): array
	{
		$widgetReports = [];
		$widgetsEnabled = $this->enabled();
		$widgetConfigReport = new Report(
			'Kirby: config.php',
			"Widgets",
			($widgetsEnabled ? 'Enabled' : 'Disabled'),
			($widgetsEnabled ? 'positive' : 'negative'),
			icon: 'cog'
		);
		$widgetReports[] = $widgetConfigReport->toArray();
		foreach ($this->list() as $widget) {
			$widgetReports[] = $widget->toReport()->toArray();
		}
		return $widgetReports;
	}
}

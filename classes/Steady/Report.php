<?php
namespace Soerenengels\Steady;

/**
 * @param string $label required Each report must have a label
 * @param string $value required Each report must have a value
 * @param string|null $info The info text is shown below the value and is optional. It can be fully customized.
 * @param string|null $theme You can colorize the info value with the theme option. Available themes are:
 * * positive (green)
 * * negative (red)
 * * notice (orange)
 * * info (blue)
 * @param string|null $link Reports can have a link to a source or more details. Links can be absolute or relative.
 * @param string|null $string Reports can have an icon.
 * @method toArray() returns array for Stats report
*/
class Report {
	function __construct(
		public string $label,
		public string|int $value,
		public ?string $info = null,
		public ?string $theme = 'default',
		public ?string $link = null,
		public ?string $icon = null,
	) {}

	/**
	 * Returns array of Report parameters
	 * enabling to render Stats report
	 * @see https://getkirby.com/docs/reference/panel/sections/stats
	 */
	public function toArray(): array {
		return [
			'label' => $this->label,
			'value' => $this->value,
			'info' => $this?->info,
			'theme' => $this?->theme,
			'link' => $this?->link,
			'icon' => $this?->icon
		];
	}
}

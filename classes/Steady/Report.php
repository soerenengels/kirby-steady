<?php
namespace Soerenengels\Steady;

interface ReportInterface {
	public function render();
}

/**
 * @param string $label Each report must have a label
 * @param string $value Each report must have a value
 * @param string|null $info The info text is shown below the value and is optional. It can be fully customized.
 * @param string|null $theme You can colorize the info value with the theme option. Available themes are:
 * * positive (green)
 * * negative (red)
 * * notice (orange)
 * * info (blue)
 * @param string|null $link Reports can have a link to a source or more details. Links can be absolute or relative.
 *
 * @see https://getkirby.com/docs/reference/panel/sections/stats
*/

class Report implements ReportInterface {
	function __construct(
		public string $label,
		public string|int $value,
		public ?string $info = null,
		public ?string $theme = 'default',
		public ?string $link = null,
	) {}

	/**
	 * returns Object to render Stats report
	 */
	public function render() {
		return [
			'label' => $this->label,
			'value' => $this->value,
			'info' => $this->info,
			'theme' => $this->theme,
			'link' => $this->link
		];
	}
}

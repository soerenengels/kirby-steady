<?php

use Soerenengels\Steady\Steady;
use Kirby\Panel\Panel;
use Kirby\Toolkit\I18n;

/** @var Steady $steady */

return [
	'steady' => function () {
		return [
			'icon' => 'steady',
			'label' => I18n::translate('soerenengels.steady'),
			'link' => 'steady',
			'menu' => true,
			// TODO: Use V5 buttons
			'dialogs' => require dirname(__DIR__) . '/dialogs/index.php',
			'drawers' => require dirname(__DIR__) . '/drawers/index.php',
			'dropdowns' => require dirname(__DIR__) . '/dropdowns/index.php',
			'views' => [
				[
					'pattern' => 'steady',
					'action' => function () {
						// Default route: forward to insights tab
						Panel::go('steady/insights');
					}
				],
				require dirname(__DIR__) . '/views/steady.php',
			]
		];
	}
];

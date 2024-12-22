<?php

use Kirby\Toolkit\I18n;
use Soerenengels\Steady\Steady;
/** @var Steady $steady */
return [
	'pattern' => 'steady/create',
	'action'  => function () use ($steady) {

		return [
			[
				'text'   => I18n::translate('soerenengels.steady.podcast'),
				'icon'   => 'audio',
				'link' => 'drawers/steady/post/new'
			],
			[
				'text'   => I18n::translate('soerenengels.steady.post'),
				'icon' => 'open', // 'page',
				'link' => 'https://steadyhq.com/de/backend/publications/' . $steady->publication()->id(). '/posts/new'
			],
			[
				'text'   => I18n::translate('soerenengels.steady.plan'),
				'icon' => 'open', // 'store',
				'link' => 'https://steadyhq.com/de/backend/publications/' . $steady->publication()->id(). '/plans/new#plan_form'
			],
			[
				'text'   => I18n::translate('soerenengels.steady.drip'),
				'icon' => 'open', // 'email',
				'link' => 'https://steadyhq.com/de/backend/publications/' . $steady->publication()->id(). '/drip_campaigns/new'
			],
			"-",
			[
				'text'   => I18n::translate('soerenengels.steady'),
				'icon'   => 'steady',
				'link' => 'https://steadyhq.com/de/backend/publications',
			]
		];
	}
];
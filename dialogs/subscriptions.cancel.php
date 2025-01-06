<?php
use Kirby\Toolkit\I18n;
use Soerenengels\Steady\Steady;
/** @var Steady $steady */

return [
	'pattern' => 'steady/subscriptions/cancel/(:any)',
	'load' => function () {
		return [
			'component' => 'k-remove-dialog',
			'props' => [
				'text' => I18n::translate('soerenengels.steady.subscriptions.cancel.question')
			]
		];
	},
	'submit' => function (string $id) {
		try {
			steady()->subscriptions()->find($id)?->cancel();
		} catch (Exception $e) {
			return [
				'event' => 'steady.subscriptions.cancel.error',
				'data'  => [
					'id' => $id,
					'error' => $e->getMessage()
				],
			];
		}
		return [
			'event' => 'steady.subscriptions.cancelled',
			'data'  => [
				'id' => $id
			],
		];
	}
];

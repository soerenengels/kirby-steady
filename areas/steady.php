<?php

use Kirby\Panel\Panel;
use Soerenengels\Steady\Steady;

/** @var Steady $steady */

return [
	'steady' => function () use ($steady) {
		return [
			'icon' => 'steady',
			'label' => t('soerenengels.steady', 'Steady'),
			'link' => 'steady',
			'menu' => true,
			'dialogs' => [
				'steady/subscriptions/cancel/(:any)' => [
					'load' => function () {
						return [
							'component' => 'k-remove-dialog',
							'props' => [
								'text' => t('soerenengels.steady.subscriptions.cancel.question')
							]
						];
					},
					'submit' => function ($id) use ($steady) {
						try {
							$steady->subscriptions()->cancel($id);
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
				]
			],
			'views' => [
				[
					'pattern' => 'steady',
					'action' => function () {
						// Default route: forward to insights
						Panel::go('steady/insights');
					}
				],
				[
					'pattern' => 'steady/(:any)',
					'action' => function (string $tab = 'insights') use ($steady) {
						$reports = $steady->reports( // TODO: default reports
							'newsletter_subscribers',
							'members',
							'revenue'
						);

						return [
							'component' => 'k-steady-view',
							'title' => t('soerenengels.steady', 'Steady'),
							'breadcrumb' => [
								[
									'label' => t("soerenengels.steady.$tab", $tab),
									'link' => 'steady/' . $tab
								]
							],
							'props' => [
								'newsletterSubscribers' => $steady->newsletter_subscribers()->toArray(),
								'plans' => $steady->plans()->toArray(),
								'plugin' => kirby()->plugin('soerenengels/steady')->toArray(),
								'publication' => $steady->publication()->toArray() ?? [],
								'reports' => $reports ?? [],
								'subscriptions' => $steady->subscriptions()->toArray(),
								'subtab' => get('tab', null),
								'tab' => $tab,
								'widgets' => ($widgets = $steady->widgets())->toReports(),
								'widgetsEnabled' => $widgets->enabled(),
								'widgetsWarning' => $widgets->isWarning()
							]
						];
					}
				]
			]
		];
	}
];

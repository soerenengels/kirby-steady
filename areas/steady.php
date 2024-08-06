<?php
use Soerenengels\Steady\Steady;
use Kirby\Panel\Panel;
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
						$widgets = steady()->widgets();
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
								'views' => [
									'insights' => [
										'icon' => 'chart',
										'permission' => kirby()->user()->role()->permissions()->for('soerenengels.steady', 'insights')
									],
									'widgets' => [
										'icon' => $widgets->enabled() ? 'toggle-on' : 'toggle-off',
										'permission' => kirby()->user()->role()->permissions()->for('soerenengels.steady', 'widgets')
									],
									'users' => [
										'icon' => 'users',
										'permission' => kirby()->user()->role()->permissions()->for('soerenengels.steady', 'users')
									],
									'debug' => [
										'icon' => 'code',
										'permission' => kirby()->user()->role()->permissions()->for('soerenengels.steady', 'debug')
									]
								],
								'plans' => $steady->plans()->toArray(),
								'plugin' => kirby()->plugin('soerenengels/steady')->toArray(),
								'publication' => $steady->publication()->toArray(),
								'reports' => $steady->reports(),
								'subscriptions' => $steady->subscriptions()->toArray(),
								'subtab' => get('tab', null),
								'tab' => $tab,
								'widgets' => $widgets->toReports(),
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

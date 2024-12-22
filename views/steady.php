<?php

use Kirby\Toolkit\I18n;
use Kirby\Toolkit\A;
/** @var Soerenengels\Steady\Steady $steady */

return [
	'pattern' => 'steady/(:any)',
	'action' => function (string $tab = 'insights') use ($steady) {
		$widgets = steady()->widgets();
		$data = [
			'newsletterSubscribers' => $steady->newsletter_subscribers()->toArray(),
			'plans' => $steady->plans()->toArray(),
			'plugin' => kirby()->plugin('soerenengels/steady')?->toArray(),
			'publication' => $steady->publication()->toArray(),
			'reports' => $steady->reports(),
			'subscriptions' => $steady->subscriptions()->toArray(),
			'subscribers' => $steady->newsletter_subscribers()->toArray(),
			'members' => $steady->subscriptions()->toArray(),
			'widgets' => $widgets?->toReports(),
			'widgetsEnabled' => $widgets?->enabled(),
			'widgetsWarning' => $widgets?->isEnabledInSteady()
		];

		/** @var array<array{name: string, label: string, icon: string, link: string, permission: bool}> $tabs */
		$tabs = [
			[
				'name' => 'insights',
				'label' => I18n::translate('soerenengels.steady.insights'),
				'icon' => 'chart',
				'link' => 'steady/insights',
				'permission' => kirby()->user()?->role()->permissions()->for('soerenengels.steady', 'insights')
			],
			[
				'name' => 'users',
				'label' => I18n::translate('soerenengels.steady.users'),
				'icon' => 'users',
				'link' => 'steady/users',
				'permission' => kirby()->user()?->role()->permissions()->for('soerenengels.steady', 'users')
			],
			[
				'name' => 'plans',
				'label' => I18n::translate('soerenengels.steady.plans'),
				'icon' => 'store',
				'link' => 'steady/plans',
				'permission' => kirby()->user()?->role()->permissions()->for('soerenengels.steady', 'plans')
			],
			[
				'name' => 'settings',
				'label' =>  I18n::translate('soerenengels.steady.settings'),
				'icon' => 'settings', //$widgets->enabled() ? 'toggle-on' : 'toggle-off',
				'link' => 'steady/settings',
				'permission' => kirby()->user()?->role()->permissions()->for('soerenengels.steady', 'settings')
			],
			[
				'name' => 'debug',
				'label' => I18n::translate('soerenengels.steady.debug'),
				'icon' => 'code',
				'link' => 'steady/debug',
				'permission' => (kirby()->user()?->role()->permissions()->for('soerenengels.steady', 'debug') && option('debug'))
			]
		];
		$tabs = A::filter(
			$tabs,
			function($tab) {
				/** @var array{name: string, label: string, icon: string, link: string, permission: bool} $tab */
				return $tab['permission'];
			}
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
				'data' => $data,
				'tab' => $tab,
				'tabs' => $tabs
			]
		];
	}
];

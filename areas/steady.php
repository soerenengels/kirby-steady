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
						// Default route forwards to insights
						Panel::go('steady/insights');
					}
				],
				[
					'pattern' => 'steady/(:any)',
					'action' => function (string $tab = 'insights') use ($steady) {

						// Setup
						$publication = $steady->publication();
						$reports = $steady->reports(
							'newsletter_subscribers',
							'members',
							'revenue'
						);
						$widgets = $steady->widgets();
						$widgetsEnabled = $widgets->enabled();
						$widgetReports[] = [
							'info' => ($widgetsEnabled ? 'Enabled' : 'Disabled'),
							'label' => 'Kirby: config.php',
							'theme' => $widgetsEnabled ? 'positive' : 'negative',
							'value' => "Widgets"
						];
						$widgetsWarning = false;
						foreach ($widgets->list() as $key => $widget) {
							$widgetReports[] = [
								'info' =>  $widget->enabled() ? '✓' : '✕',
								'value' => $widget->title(),
								'theme' => $widgetsEnabled ? ($widget->enabled() ? 'positive' : 'info') : ($widget->enabled() ? 'notice' : 'default'),
								'link' => 'https://steadyhq.com/de/backend/publications/' . $publication->id . '/integrations/' . $widget->type->value . '/edit',
								'label' => 'Steady'
							];
							$widgetsWarning = $widgetWarning ?? ($widgetsWarning === $widget->isActive());
						}



						// Steady: API
						$subscriptions = $steady->subscriptions()->list();
						$plans = $steady->plans()->list();
						$newsletterSubscribers = $steady->newsletter_subscribers()->list();


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
								'reports' => $reports ?? [],
								'widgets' => $widgetReports ?? [],
								'tab' => $tab,
								'subtab' => get('tab', null),
								'widgetsEnabled' => $widgetsEnabled ?? null,
								'widgetsWarning' => $widgetsWarning ?? null,
								'publication' => $publication,
								'plans' => $plans ?? [],
								'subscriptions' => $subscriptions ?? [],
								'newsletterSubscribers' => $newsletterSubscribers ?? [],
								'plugin' => kirby()->plugin('soerenengels/steady')->toArray()
							]
						];
					}
				]
			]
		];
	}
];

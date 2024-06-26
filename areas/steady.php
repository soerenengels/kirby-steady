<?php

use Kirby\Panel\Panel;
use Soerenengels\Steady\Steady;
/** @var Steady $steady */

return [
	'steady' => function () use ($steady) {
		return [
			'icon' => 'steady',
			'label' => 'Steady',
			'link' => 'steady',
			'menu' => false,
			'views' => [
				[
					'pattern' => 'steady',
					'action' => function () {
						// Default route forwards to stats
						Panel::go('steady/stats');
					}
				],
				[
					'pattern' => 'steady/(:any)',
					'action' => function (string $tab = 'stats') use ($steady) {

						// Setup
						$publication = $steady->publication();

						// Steady: Stats
						if ($tab == 'stats') {
							$reports = $steady->reports('newsletter_subscribers', 'members', 'revenue');
						}
						// Steady: Widgets
						if ($tab == 'widgets') {
							$widgets = $steady->widgets();
							$widgetsEnabled = $widgets->enabled();
							$widgetReports[] = [
								'info' => ($widgetsEnabled ? 'Enabled' : 'Disabled'),
								'label' => 'Kirby: config.php',
								'theme' => $widgetsEnabled ? 'positive' : 'negative',
								//'label' => "option('soerenengels.steady.widgets')",
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

						}

						// Steady: API
						if ($tab == 'api') {
							$plans = $steady->plans()->list();
							$subscriptions = $steady->subscriptions()->list();
							$newsletterSubscribers = $steady->newsletter_subscribers()->filter(function ($user) {
								return rand(1, 100) == 1;
							})->list();
						}

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
								'reports' => $reports ?? [],
								'plugin' => kirby()->plugin('soerenengels/steady')->toArray()
							]
						];
					}
				]
			]
		];
	}
];

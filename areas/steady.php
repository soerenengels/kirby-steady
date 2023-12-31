<?php

use Soerenengels\Steady\Steady;
/** @var Steady $steady */

return [
	'steady' => function () use ($steady) {
		return [
			'icon' => 'steady',
			'label' => 'Steady',
			'link' => 'steady/stats',
			'menu' => false,
			'views' => [
				[
					'pattern' => 'steady/(:any)',
					'action' => function ($tab) use ($steady) {

						// Steady: Stats
						$title = 'Stats';
						$publication = $steady->publication();
						$reports = [
							$steady->report('newsletter_subscribers'),
							$steady->report('members'),
							$steady->report('revenue')
						];

						// Steady: Widgets
						if ($tab == 'widgets') {
							$title = 'Widgets';
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
									'theme' => $widgetsEnabled ? ($widget->enabled() ? 'positive': 'info') : ($widget->enabled() ? 'notice' : 'default'),
									'link' => 'https://steadyhq.com/de/backend/publications/' . $publication->id . '/integrations/' . $widget->type->value . '/edit',
									'label' => 'Steady'
								];
								$widgetsWarning = $widgetWarning ?? ($widgetsWarning === $widget->isActive());
							}

						}

						// Steady: API
						if ($tab == 'api') {
							$title = 'API';
							$plans = $steady->plans()->list();
							$subscriptions = $steady->subscriptions()->list();
							$newsletterSubscribers = $steady->newsletter_subscribers()->filter(function ($user) {
								return rand(1,100) == 1;
							})->list();
						}

						return [
							'component' => 'k-steady-view',
							'title' => 'Steady',
							'breadcrumb' => [
								[
									'label' => $title,
									'link' => 'steady/' . $tab
								]
								],
							'props' => [
								'title' => $title,
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

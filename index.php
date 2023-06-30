<?php

/**
 * Steady meets Kirby Plugin
 *
 * Easily request and work with the Steady API
 * in Kirby CMS
 *
 * @package   Steady meets Kirby
 * @version   1.0
 * @author    Sören Engels <mail@soerenengels.com>
 * @link      https://github.com/soerenengels/kirby-steady
 * @copyright Sören Engels
 * @license   https://opensource.org/licenses/MIT
 */

use Kirby\Cms\App as Kirby;
use Soerenengels\Steady\Steady;

load([
	'Soerenengels\\Steady\\Report' => __DIR__ . '/classes/Steady/Report.php',
	'Soerenengels\\Steady\\MembersReport' => __DIR__ . '/classes/Steady/MembersReport.php',
	'Soerenengels\\Steady\\NewsletterSubscribersReport' => __DIR__ . '/classes/Steady/NewsletterSubscribersReport.php',
	'Soerenengels\\Steady\\MonthlyRevenueReport' => __DIR__ . '/classes/Steady/MonthlyRevenueReport.php',
	'Soerenengels\\Steady\\Publication' => __DIR__ . '/classes/Steady/Publication.php',
	'Soerenengels\\Steady\\NewsletterSubscriber' => __DIR__ . '/classes/Steady/NewsletterSubscriber.php',
	'Soerenengels\\Steady\\NewsletterSubscribers' => __DIR__ . '/classes/Steady/NewsletterSubscribers.php',
	'Soerenengels\\Steady\\Plans' => __DIR__ . '/classes/Steady/Plans.php',
	'Soerenengels\\Steady\\Plan' => __DIR__ . '/classes/Steady/Plan.php',
	'Soerenengels\\Steady\\Subscription' => __DIR__ . '/classes/Steady/Subscription.php',
	'Soerenengels\\Steady\\Subscriptions' => __DIR__ . '/classes/Steady/Subscriptions.php',
	'Soerenengels\\Steady\\Steady' => __DIR__ . '/classes/Steady.php',
]);

function steady() {
	return site()->steady();
}

Kirby::plugin('soerenengels/steady', [
	'blueprints' => [
		'sections/stats/steady' => function($site) {
			return require __DIR__ . '/blueprints/sections/stats/steady.php';
		}
	],
	'cache' => function () {
		return [
			kirby()->option('soerenengels.steady.cache') => true
		];
	},
	'options' => [
		'cache' => 'steady-api', // name for cache in /site/cache folder
		'api-token' => '...', // your personal steady api token, get it from: https://steadyhq.com/backend/publications/default/integrations/api/edit when you are logged in
		'widget' => false
	],
	'siteMethods' => [
		'steady' => function () {
			return new Steady(option('soerenengels.steady.api-token'));
		}
	],
	'snippets' => [
		'components/steady/plan' => require __DIR__ . '/snippets/components/steady/plan.php',
		'components/steady/plans' => require __DIR__ . '/snippets/components/steady/plans.php',
		'components/steady/widget' => require __DIR__ . '/snippets/components/steady/widget.php',
	],
	'translations' => [
		'en' => require __DIR__ . '/translations/en.php',
		'de' => require __DIR__ . '/translations/de.php'
	]
]);

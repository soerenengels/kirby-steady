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
	'Soerenengels\\Steady\\Plans' => __DIR__ . '/classes/Steady/Plans.php',
	'Soerenengels\\Steady\\Plan' => __DIR__ . '/classes/Steady/Plan.php',
	'Soerenengels\\Steady\\Subscription' => __DIR__ . '/classes/Steady/Subscription.php',
	'Soerenengels\\Steady\\Subscriptions' => __DIR__ . '/classes/Steady/Subscriptions.php',
	'Soerenengels\\Steady\\Steady' => __DIR__ . '/classes/Steady/Steady.php',
	'Soerenengels\\Steady\\Widgets' => __DIR__ . '/classes/Steady/Widgets.php',
	'Soerenengels\\Steady\\Widget' => __DIR__ . '/classes/Steady/Widget.php',
	'Soerenengels\\Steady\\WidgetType' => __DIR__ . '/classes/Steady/WidgetType.php',
	'Soerenengels\\Steady\\User' => __DIR__ . '/classes/Steady/User.php',
	'Soerenengels\\Steady\\UserType' => __DIR__ . '/classes/Steady/UserType.php',
	'Soerenengels\\Steady\\Users' => __DIR__ . '/classes/Steady/Users.php',
	'Soerenengels\\Steady\\Endpoint' => __DIR__ . '/classes/Steady/Endpoint.php'
]);

$steady = new Steady(option('soerenengels.steady.token'));
function steady()
{
	return new Steady();
}

Kirby::plugin('soerenengels/steady', [
	'areas' => require __DIR__ . '/areas/steady.php',
	'blueprints' => [
		'blocks/steady_paywall' => require __DIR__ . '/blueprints/blocks/steady/paywall.php',
		'blocks/steady_plans' => __DIR__ . '/blueprints/blocks/steady/plans.php',
		'sections/stats/steady' => require __DIR__ . '/blueprints/sections/stats/steady.php'
	],
	'cache' => true,
	'cache.widget' => true,
	'options' => [
		'token' => '...', // REQUIRED: Steady REST API-Token
		'widget' => false, // OPTIONAL: indicate use of Steady Javascript widget
	],
	'siteMethods' => [
		'steady' => function (): Steady {
			return new Steady(option('soerenengels.steady.token'));
		}
	],
	'snippets' => [
		'components/steady/plan' => __DIR__ . '/snippets/components/steady/plan.php',
		'components/steady/plans' => __DIR__ . '/snippets/components/steady/plans.php',
		'components/steady/widget' => __DIR__ . '/snippets/components/steady/widget.php',
		'blocks/steady_paywall' => __DIR__ . '/snippets/blocks/steady/paywall.php',
		'blocks/steady_plans' => __DIR__ . '/snippets/blocks/steady/plans.php',
	],
	'translations' => [
		'en' => require __DIR__ . '/translations/en.php',
		'de' => require __DIR__ . '/translations/de.php'
	]
]);

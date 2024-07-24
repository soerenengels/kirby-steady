<?php

/**
 * Steady meets Kirby Plugin
 *
 * Easily request and work with the Steady API
 * in Kirby CMS
 *
 * @package   Steady meets Kirby
 * @author    Sören Engels <mail@soerenengels.com>
 * @link      https://github.com/soerenengels/kirby-steady
 * @copyright Sören Engels
 * @license   https://opensource.org/licenses/MIT
 */

use Kirby\Cms\App as Kirby;
use Soerenengels\Steady\Steady;

@include_once __DIR__ . '/vendor/autoload.php';

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
		'sections/stats/steady' => require __DIR__ . '/blueprints/sections/stats/steady.php',
		'users/steady' => __DIR__ . '/blueprints/users/steady.yml'
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

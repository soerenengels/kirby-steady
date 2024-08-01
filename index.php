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
		'widget' => false, // OPTIONAL: indicate use of Steady Javascript widget,
		'oauth' => [
			'client' => [
				'id' => '...',
				'secret' => '...',
			],
			'blueprint' => 'client.yml', // OPTIONAL: path to blueprint for user creation
			'redirect-uri' => site()->url() . '/oauth/steady/callback',
			'after-login' => 'url'
		]
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
		'en' => require_once __DIR__ . '/translations/en.php',
		'de' => require_once __DIR__ . '/translations/de.php'
	],
	'routes' => function () {
		if (!kirby()->option('soerenengels.steady.oauth.after-login') || !kirby()->option('soerenengels.steady.oauth')) return null;
		return [
			[
				'pattern' => 'oauth/steady/callback',
				'action'  => function (): void {
					steady()
						->oauth()
						->processCallback(
							get('state'),
							get('code')
						);
				},
			],
			[
				'pattern' => 'oauth/steady/authorize',
				'action'  => function (): void {
					steady()->oauth()->setReferrer();
					go(steady()->oauth()->url());
				},
			],
			[
				'pattern' => 'oauth/steady/logout',
				'action'  => function (): void {
					steady()->oauth()->logout();
					go('/steady');
				},
			],
		];
	},
]);

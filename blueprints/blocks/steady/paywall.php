<?php

use Kirby\Toolkit\I18n;
use Soerenengels\Steady\Steady;

/** @var Steady $steady */
$isActive = $steady->widgets()?->paywall()->isActive();

$placeholder = [
	'url' => 'https://steadyhq.com/en/backend/publications/' . $steady->publication()->id() . '/integrations/paywall/edit'
];
return [
	'name' => I18n::translate('steady.paywall.block'),
	'icon' => 'lock',
	'wysiwyg' => true,
	'preview' => 'fields',
	'fields' => [
		'steady_paywall_info' => [
			'label' => false,
			'type' => 'info',
			'theme' => ($isActive ? 'none' : 'notice'),
			'text' =>  I18n::template('soerenengels.steady.blueprints.paywall.' . ($isActive ? 'text' : 'text-with-warning'), null, $placeholder)
		],
		'steady_paywall_small_toggle' => [
			'label' => I18n::translate('steady.paywall.customize'),
			'type' => 'toggle',
			'text' => [
				I18n::translate('steady.no'),
				I18n::translate('steady.yes')
			],
		],
		'utm_campaign' => [
			'label' => I18n::translate('steady.paywall.utm_campaign'),
			'type' => 'text',
			'placeholder' => 'utm_campaign'
		]
	]
];

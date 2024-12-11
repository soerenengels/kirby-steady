<?php
$isActive = $steady->widgets()->paywall()->isActive();

$placeholder = [
	'url' => 'https://steadyhq.com/en/backend/publications/' . $steady->publication()->id() . '/integrations/paywall/edit'
];
return [
	'name' => 'Steady: Paywall',
	'icon' => 'lock',
	'wysiwyg' => true,
	'preview' => 'fields',
	'fields' => [
		'steady_paywall_info' => [
			'label' => false,
			'type' => 'info',
			'theme' => ($isActive ? 'none' : 'notice'),
			'text' =>  tt(($isActive ? 'soerenengels.steady.blueprints.paywall.text' : 'soerenengels.steady.blueprints.paywall.text-with-warning'), null, $placeholder)
		],
		'steady_paywall_small_toggle' => [
			'label' => 'Do you want to show a smaller version of the Paywall?',
			'type' => 'toggle',
			'text' => [
				'No', 'Yes'
			],
		],
		'utm_campaign' => [
			'label' => 'UTM Campaign',
			'type' => 'text',
			'placeholder' => 'utm_campaign'
		]
	]
];

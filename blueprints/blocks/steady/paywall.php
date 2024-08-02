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
	]
];

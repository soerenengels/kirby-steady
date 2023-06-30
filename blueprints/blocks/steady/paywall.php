<?php
$steady = steady();
$widget = $steady->widget();
$placeholder = [
	'url' => 'https://steadyhq.com/en/backend/publications/' . $steady->publication()->id . '/integrations/paywall/edit'
];
return [
	'name' => 'Steady: Paywall',
	'icon' => '✋',
	'wysiwyg' => true,
	'preview' => 'fields',
	'fields' => [
		'steady_paywall_info' => [
			'label' => false,
			'type' => 'info',
			'theme' => ($widget ? 'none' : 'notice'),
			'text' =>  tt(($widget ? 'soerenengels.steady.blueprints.paywall.text' : 'soerenengels.steady.blueprints.paywall.text-with-warning'), null, $placeholder)
		],
	]
];

<?php
use Kirby\Toolkit\I18n;
use Soerenengels\Steady\Plan;

$steady = steady();
$publication = $steady->publication();
$plans = $steady->plans();
$placeholder = [
	'url' => 'https://steadyhq.com/en/backend/publications/' . $publication->id() . '/plans'
];
$options = [];
$published_plans = $plans->filter(fn ($plan) => $plan->state() == "published");
foreach ($published_plans as $plan) {
	$options[] = [
		'text' => $plan->name(),
		'value' => $plan->id()
	];
}
return [
	'name' => 'Steady: ' . t('soerenengels.steady.blueprints.plans.name'),
	'icon' => 'cart',
	'wysiwyg' => true,
	'preview' => 'fields',
	'fields' => [
		'steady_plans_info' => [
			'label' => false,
			'type' => 'info',
			'theme' => 'none',
			'text' => I18n::template('soerenengels.steady.blueprints.plans.info.text', null, $placeholder)
		],
		'steady_plans_customize' => [
			'label' => I18n::translate('soerenengels.steady.blueprints.plans.customize.label'),
			'type' => 'toggle',
			'text' => [
				I18n::translate('soerenengels.steady.blueprints.plans.customize.false'),
				I18n::translate('soerenengels.steady.blueprints.plans.customize.true')
			]
		],
		'steady_plans' => [
			'label' => I18n::translate('soerenengels.steady.blueprints.plans.choice.label'),
			'type' => 'multiselect',
			'min' => '1',
			'max' => $plans->count(),
			'help' => t('soerenengels.steady.blueprints.plans.choice.help'),
			'when' => [
				'steady_plans_customize' => true
			],
			'options' => $options
		]
	]

];

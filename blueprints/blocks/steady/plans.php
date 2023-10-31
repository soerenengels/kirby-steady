<?php
$steady = steady();
$publication = $steady->publication();
$plans = $steady->plans();
$placeholder = [
	'url' => 'https://steadyhq.com/en/backend/publications/' . $publication->id . '/plans'
];
$options = [];
$published_plans = $plans->filter(fn ($plan) => $plan->state == "published");
foreach ($published_plans->list() as $plan) {
	$options[] = [
		'text' => $plan->name,
		'value' => $plan->id
	];
}
return [
	'name' => 'Steady: ' . $publication->title . ' ' . t('soerenengels.steady.blueprints.plans.name'),
	'icon' => '📣',
	'wysiwyg' => true,
	'preview' => 'fields',
	'fields' => [
		'steady_plans_info' => [
			'label' => false,
			'type' => 'info',
			'theme' => 'none',
			'text' => tt('soerenengels.steady.blueprints.plans.info.text', null, $placeholder)
		],
		'steady_plans_customize' => [
			'label' => t('soerenengels.steady.blueprints.plans.customize.label'),
			'type' => 'toggle',
			'text' => [
				t('soerenengels.steady.blueprints.plans.customize.false'),
				t('soerenengels.steady.blueprints.plans.customize.true')
			]
		],
		'steady_plans' => [
			'label' => t('soerenengels.steady.blueprints.plans.choice.label'),
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

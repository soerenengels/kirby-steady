<?php
use Kirby\Toolkit\I18n;
return [
	'pattern' => 'steady/plan/(:any)',
	'load'  => function (string $id) {
		$plan = steady()->plans()->find($id);
		return [
			'component' => 'k-form-drawer',
			'props' => [
				'icon' => 'store',
				'title' => I18n::translate('soerenengels.steady.plan') . ': ' . ($plan?->name() ?? ''),
				'options' => [
					[
						'text' => I18n::translate('soerenengels.steady.edit'),
						'icon' => 'edit',
						'link' => 'https://steadyhq.com/de/backend/publications/' . steady()->publication()->id() . '/plans/' .( $plan?->id() ?? '') . '/edit'
					]
				],
				'fields' => [
					'high_res_image_url' => [
						'label' => I18n::translate('soerenengels.steady.image'),
						'type' => 'text',
						'disabled' => true,
					],
					'inserted_at' => [
						'label' => I18n::translate('soerenengels.steady.inserted_at'),
						'type' => 'date',
						'disabled' => true,
						'width' => '1/2',
					],
					'updated_at' => [
						'label' => I18n::translate('soerenengels.steady.updated_at'),
						'type' => 'date',
						'disabled' => true,
						'width' => '1/2',
					],
					'name' => [
						'label' => I18n::translate('soerenengels.steady.title'),
						'type' => 'text',
						'disabled' => true,
					],
					'annual_amount' => [
						'label' => I18n::translate('soerenengels.steady.annual_amount'),
						'type' => 'number',
						'disabled' => true,
						'after' => $plan?->currency(),
						'width' => '1/3',
					],
					'monthly_amount' => [
						'label' => I18n::translate('soerenengels.steady.monthly_amount'),
						'type' => 'number',
						'disabled' => true,
						'after' => $plan?->currency(),
						'width' => '1/3',
					],
					'state' => [
						'label' => I18n::translate('soerenengels.steady.state'),
						'type' => 'tags',
						'disabled' => true,
						'width' => '1/3',
					],
					'benefits' => [
						'label' => I18n::translate('soerenengels.steady.benefits'),
						'type' => 'textarea',
						'disabled' => true,
					],
					'ask_for_shiping_address' => [
						'label' => I18n::translate('soerenengels.steady.ask_for_shiping_address'),
						'type' => 'toggle',
						'disabled' => true,
						'width' => '1/4',
					],
    			"goal_enabled" => [
						'label' => I18n::translate('soerenengels.steady.goal_enabled'),
						'type' => 'toggle',
						'disabled' => true,
						'width' => '1/4',
					],
					"countdown_enabled" => [
						'label' => I18n::translate('soerenengels.steady.countdown_enabled'),
						'type' => 'toggle',
						'disabled' => true,
						'width' => '1/4',
					],
					"hidden" => [
						'label' => I18n::translate('soerenengels.steady.hidden'),
						'type' => 'toggle',
						'disabled' => true,
						'width' => '1/4',
					],
					"giftable" => [
						'label' => I18n::translate('soerenengels.steady.giftable'),
						'type' => 'toggle',
						'disabled' => true,
						'width' => '1/4',
					]
				],
				'value' => [
					'name' => $plan?->name(),
					'benefits' => $plan?->benefits(),
					'annual_amount' => $plan?->annual_amount() / 100,
					'monthly_amount' => $plan?->monthly_amount() / 100,
					'state' => [$plan?->state()],
					'ask_for_shiping_address' => $plan?->ask_for_shiping_address(),
					'goal_enabled' => $plan?->goal_enabled(),
					'countdown_enabled' => $plan?->countdown_enabled(),
					'hidden' => $plan?->hidden(),
					'giftable' => $plan?->giftable(),
					'high_res_image_url' => $plan?->high_res_image_url(),
					'inserted_at' => $plan?->inserted_at()->toString(),
					'updated_at' => $plan?->updated_at()->toString(),
				]
			]
		];
	},
	'submit' => function () {
		return [
			'event' => 'steady.plan.closed',
		];
	}
];

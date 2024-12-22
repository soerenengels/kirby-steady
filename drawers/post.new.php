<?php

use Kirby\Toolkit\I18n;
use Kirby\Uuid\Uuid;

return [
	'pattern' => 'steady/post/new',
	'load'  => function () {
		// Get all plans for access control field options
		$plans_for_access_control = steady()->plans_for_access_control();
		$plans = [];
		foreach ($plans_for_access_control as $plan) {
			$plans[] = [
				'value' => $plan->id(),
				'text' => $plan->name()
			];
		}

		return [
			'component' => 'k-form-drawer',
			'props' => [
				'icon' => 'audio',
				'title' => I18n::translate('soerenengels.steady.drawer.podcast.create'),
				'id' => Uuid::generate(),
				'options' => [
					[
						'text' => I18n::translate('soerenengels.steady.draft.save'),
						'icon' => 'draft',
						'click' => [
							'event' => 'steady.post',
							'data' => [
								'message' => I18n::translate('soerenengels.steady.drawer.podcast.success.message'),
							]
						]
					],
					[
						'text' => I18n::translate('soerenengels.steady.cancel'),
						'icon' => 'cancel',
						'link' => 'https://steadyhq.com/de/backend/publications/' . steady()->publication()->id() . '/posts/new'
					]
				],
				'fields' => [
					'audio' => [
						'label' => I18n::translate('soerenengels.steady.drawer.podcast.file'),
						'type' => 'files',
						'width' => '1/2',
						'max' => 1,
						'uploads' => [
							'parent' => 'site',
							'accept' => 'audio/*'
						]
					],
					'teaser_image' => [
						'label' => I18n::translate('soerenengels.steady.drawer.podcast.teaser_image'),
						'type' => 'files',
						'width' => '1/2',
						'max' => 1,
						'uploads' => [
							'parent' => 'site',
							'accept' => 'image/*'
						]
					],
					'title' => [
						'label' => I18n::translate('soerenengels.steady.drawer.podcast.title'),
						'type' => 'text'
					],
					'description' => [
						'label' => I18n::translate('soerenengels.steady.drawer.podcast.description'),
						'type' => 'text'
					],

					'content' => [
						'label' => I18n::translate('soerenengels.steady.drawer.podcast.content'),
						'type' => 'textarea'
					],
					'publish_at' => [
						'label' => I18n::translate('soerenengels.steady.drawer.podcast.publish_at'),
						'type' => 'date',
						'help' => I18n::translate('soerenengels.steady.drawer.podcast.publish_at.help'),
						'width' => '2/3'
					],
					'distribute_as_email' => [
						'label' => I18n::translate('soerenengels.steady.drawer.podcast.distribute_as_email'),
						'help' => I18n::translate('soerenengels.steady.drawer.podcast.distribute_as_email.help'),
						'type' => 'toggles',
						'width' => '1/3',
						'grow' => true,
						'reset' => true,
						'options' => [
							[
								'value' => 'email',
								'text' => I18n::translate('soerenengels.steady.drawer.podcast.distribute_as_email.option'),
								'icon' => 'check'
							]
						]
					],
					'restrict_to_plan_ids' => [
						'label' => I18n::translate('soerenengels.steady.drawer.podcast.restrict_to_plan_ids'),
						'help' => I18n::translate('soerenengels.steady.drawer.podcast.restrict_to_plan_ids.help'),
						'type' => 'multiselect',
						'options' => steady()->plans_for_access_control()->toOptions()
					]
				]
			]
		];
	},
	'submit' => function () {
		// TODO: Implement submit
		return [
			'event' => 'steady.post',
			'data' => [
				'message' => I18n::translate('soerenengels.steady.drawer.podcast.success.message'),
			]
		];
	}
];

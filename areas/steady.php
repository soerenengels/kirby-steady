<?php
return [
	'steady' => function () {
		return [
			'icon' => 'steady',
			'label' => 'Steady',
			'link' => 'steady',
			'menu' => false,
			'views' => [
				[
					'pattern' => 'steady',
					'action' => function () {
						return [
							'component' => 'k-steady-view',
							'title' => 'Steady',
							'props' => []
						];
					}
				]
			]
		];
	}
];

<?php
return [
	'pattern' => 'steady/info',
	'action'  => function () {
		return [
			[
				'text'   => 'Docs',
				'icon'   => 'book',
				'link' => 'https://kirby-steady.soerenengels.de',
			],
			[
				'text'   => 'Github',
				'icon'   => 'github',
				'link' => 'https://github.com/soerenengels/kirby-steady',
			],
		];
	}
];

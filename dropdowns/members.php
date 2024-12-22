<?php
use Soerenengels\Steady\Steady;
/** @var Steady $steady */
return [
	'pattern' => 'steady/members/(:any)',
	'action' => function(string $id) use ($steady) {
		$publication_id = $steady->publication()->id();
		return [
			[
				'text'   => 'Auf Steady anzeigen',
				'icon'   => 'open',
				'link' => "https://steadyhq.com/de/backend/publications/$publication_id/members/$id",
			],
			[
				'text'   => 'Nachricht senden',
				'icon'   => 'email',
				'link' => "https://steadyhq.com/de/backend/publications/$publication_id/messages#/$publication_id/$id",
			],
			'-',
			[
				'text'   => 'Abo kündigen',
				'icon'   => 'cancel',
				'theme' => 'red',
				'dialog' => "steady/subscriptions/cancel/$id",
			]
		];
	}
];

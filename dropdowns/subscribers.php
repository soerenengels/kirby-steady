<?php
use Soerenengels\Steady\Steady;
/** @var Steady $steady */
return [
	'pattern' => 'steady/subscribers/(:any)',
	'action' => function(string $id) {
		$steady = steady();
		$publication_id = $steady->publication()->id();
		$subscription = $steady->newsletter_subscribers()->find($id);
		$email = $subscription ? esc($subscription->email(), 'url') : '';
		return [
			[
				'text'   => 'Auf Steady anzeigen',
				'icon'   => 'open',
				'link' => "https://steadyhq.com/de/backend/publications/$publication_id/subscribers?search=$email",
			]
		];
	}
];

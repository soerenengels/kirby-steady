<?php

return [
	'type' => 'stats',
	'label' => t('soerenengels.steady.blueprints.section.label'),
	'size' => 'huge',
	'reports' => [
		'members' => 'site.steady.report("members")',
		'subscribers' => 'site.steady.report("newsletter_subscribers")',
		'revenue' => 'site.steady.report("revenue")'
	]
];

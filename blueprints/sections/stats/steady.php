<?php

return [
	'type' => 'stats',
	'label' => t('soerenengels.steady.blueprints.section.label'),
	'size' => 'huge',
	'reports' => [
		'site.steady.report("members")',
		'site.steady.report("newsletter_subscribers")',
		'site.steady.report("revenue")'
	]
];

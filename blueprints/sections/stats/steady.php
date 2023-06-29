<?php
use Soerenengels\Steady\MembersReport;
use Soerenengels\Steady\NewsletterSubscribersReport;
use Soerenengels\Steady\MonthlyRevenueReport;

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

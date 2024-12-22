<?php
namespace Soerenengels\Steady;
use Soerenengels\Steady\Report;

class MonthlyRevenueReport extends Report {
	public function __construct() {
		$publication = steady()->publication();
		/** @var string $label */
		$label = t('soerenengels.steady.reports.revenue.label');
		$currency = '€';

		$this->label = $label;
		$this->value = $publication->monthly_amount() / 100 . '' . $currency;
		$this->info = null;
		$this->theme = 'default';
		$this->link = 'https://steadyhq.com/de/backend/publications/default/analytics';
		$this->icon = 'money';
	}
}

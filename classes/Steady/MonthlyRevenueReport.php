<?php
namespace Soerenengels\Steady;
use Soerenengels\Steady\Report;

class MonthlyRevenueReport extends Report {
	public function __construct() {
		$steady = steady();
		$revenue = $steady->publication()->monthly_amount / 100;
		$currency = '€';
		$this->label = t('soerenengels.steady.reports.revenue.label');
		$this->value = $revenue . '' . $currency;
		$this->info = null;
		$this->theme = 'default';
		$this->link = 'https://steadyhq.com/de/backend/publications/' . $steady->publication()->id . '/analytics';
		$this->icon = 'money';
	}
}

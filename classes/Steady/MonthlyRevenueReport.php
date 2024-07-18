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
		$this->info = (
			$revenue >=2600 ? t('soerenengels.steady.reports.revenue.info.much') : (
				$revenue > 520 ? t('soerenengels.steady.reports.revenue.info.more') : (
					$revenue > 40 ? t('soerenengels.steady.reports.revenue.info.some') : (
						$revenue > 1 ? t('soerenengels.steady.reports.revenue.info.few') : t('soerenengels.steady.reports.revenue.info.none')
					)
				)
			)
		);
		$this->theme = 'default';
		$this->link = 'https://steadyhq.com/de/backend/publications/' . $steady->publication()->id . '/analytics';
		$this->icon = 'money';
	}
}

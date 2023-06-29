<?php
namespace Soerenengels\Steady;
use Soerenengels\Steady\Steady;
use Soerenengels\Steady\Report;

class NewsletterSubscribersReport extends Report {
	public function __construct() {
		$steady = steady();
		$new_subscriptions = $steady->newsletter_subscribers()->filter()->count();
		$theme = $new_subscriptions > 1 ? 'positive' : ($new_subscriptions > 0 ? 'default' : 'notice');
		$this->label = t('soerenengels.steady.reports.newsletter.label');
		$this->value = $steady->newsletter_subscribers()->count();
		$this->info = ($new_subscriptions > 0 ? ($new_subscriptions > 1 ? $new_subscriptions . t('soerenengels.steady.reports.newsletter.info.plural') : t('soerenengels.steady.reports.newsletter.info.singular')) : t('soerenengels.steady.reports.newsletter.info.zero'));
		$this->theme = $theme;
		$this->link = $steady->publication()->campaign_page_url;
	}
}

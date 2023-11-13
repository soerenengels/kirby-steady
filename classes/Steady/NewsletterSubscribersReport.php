<?php
namespace Soerenengels\Steady;
use Soerenengels\Steady\Report;
use Soerenengels\Steady\Steady;

class NewsletterSubscribersReport extends Report {
	public function __construct() {
		// Setup
		$steady = steady();
		$value = $steady->newsletter_subscribers()->count();
		$new_subscriptions = $steady->newsletter_subscribers()->filter(function($item) {
			return $item->opted_in_at->compare()->days <= 30;
		})->count();
		$indicator = $new_subscriptions > 0 ? '+' : '±';
		$info = $indicator . ($new_subscriptions > 0 ? ($new_subscriptions > 1 ? $new_subscriptions . t('soerenengels.steady.reports.newsletter.info.plural') : t('soerenengels.steady.reports.newsletter.info.singular')) : t('soerenengels.steady.reports.newsletter.info.zero'));
		$theme = $new_subscriptions > 1 ? 'positive' : ($new_subscriptions > 0 ? 'default' : 'notice');

		// Assignment
		$this->label = t('soerenengels.steady.reports.newsletter.label');
		$this->value = $value;
		$this->info = $info;
		$this->theme = $theme;
		$this->link = 'https://steadyhq.com/de/backend/publications/' . $steady->publication()->id . '/subscribers';
		$this->icon = 'email';
	}
}

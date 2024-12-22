<?php
namespace Soerenengels\Steady;
use Soerenengels\Steady\Report;
use Soerenengels\Steady\Steady;
use Soerenengels\Steady\User;
use Kirby\Panel\Panel;

class NewsletterSubscribersReport extends Report {
	public function __construct() {
		// Setup
		$subscribers = steady()->newsletter_subscribers();

		/** @var string $label */
		$label = t('soerenengels.steady.reports.newsletter.label', 'Subscribers');
		$value = $subscribers->count();

		// New subscribers
		$newSubscribers = $subscribers->filter(
			// TODO: Add Typehint
			function(User $item) {
				return $item->opted_in_at()?->compare()->days <= 30;
			}
		)->count();
		/** @var string $info */
		$info = tc('soerenengels.steady.reports.newsletter.info', $newSubscribers);

		$link = kirby()->user()?->role()->permissions()->for('soerenengels.steady', 'users') ?
			Panel::url('steady/users', ['query' => ['tab' => 'subscribers']]) :
			'https://steadyhq.com/de/backend/publications/' . steady()->publication()->id() . '/subscribers';

		// Assignment
		$this->label = $label;
		$this->value = $value;
		$this->info = $info;
		$this->theme = $newSubscribers >= 1 ? 'positive' : 'default';
		$this->link = $link;
		$this->icon = 'email';
	}
}

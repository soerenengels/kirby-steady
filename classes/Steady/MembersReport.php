<?php
namespace Soerenengels\Steady;
use Soerenengels\Steady\Report;
use Kirby\Panel\Panel;

/**
 * Steady Members Report
 */
class MembersReport extends Report {
	public function __construct()
	{
		$steady = steady();

		$new_members = count(array_filter($steady->subscriptions()->list(), function(Subscription $item) {
			return $item->inserted_at()->compare()->days <= 30;
		}));

		/** @var string $label */
		$label = t('soerenengels.steady.reports.members.label');
		/** @var string $info */
		$info = tc('soerenengels.steady.reports.members.info', $new_members);
		$link = kirby()->user()?->role()->permissions()->for('soerenengels.steady', 'users') ?
			Panel::url('steady/users', ['query' => ['tab' => 'members']]) :
			'https://steadyhq.com/de/backend/publications/' . $steady->publication()->id() . '/members';

		$this->label = $label;
		$this->value = $steady->publication()->members_count();
		$this->info = $info;
		$this->theme = $new_members > 1 ? 'positive' : 'default';
		$this->link = $link;
		$this->icon = 'users';
	}
}

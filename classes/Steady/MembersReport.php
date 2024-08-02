<?php
namespace Soerenengels\Steady;
use Soerenengels\Steady\Report;

/**
 * SteadyMembersReport extends Report class
 * to Render a default Steady member report
 */
class MembersReport extends Report {
	public function __construct()
	{
		$steady = steady();
		$new_members = count(array_filter($steady->subscriptions()->list(), function(Subscription $item) {
			return $item->inserted_at()->compare()->days <= 30;
		}));
		$indicator = $new_members > 0 ? '+' : '±';
		$info = ($new_members > 0 ? ($new_members > 1 ? $indicator . $new_members . t('soerenengels.steady.reports.members.info.plural') : $indicator . t('soerenengels.steady.reports.members.info.singular')) : t('soerenengels.steady.reports.members.info.zero'));
		$theme = $new_members > 1 ? 'positive' : ($new_members > 0 ? 'default' : 'default');
		$this->label = t('soerenengels.steady.reports.members.label');
		$this->value = $steady->publication()->members_count();
		$this->info = $info;
		$this->theme = $theme;
		$this->link = 'https://steadyhq.com/de/backend/publications/' . $steady->publication()->id() . '/members';
		$this->icon = 'users';
	}
}

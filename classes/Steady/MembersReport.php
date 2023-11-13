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
		$this->label = t('soerenengels.steady.reports.members.label');
		$this->value = $steady->publication()->members_count;
		$this->info = t('soerenengels.steady.reports.members.info.default'); // TODO: inserted_at compare members with Time X
		$this->theme = 'info'; // TODO: choose theme based on comparison
		$this->link = 'https://steadyhq.com/de/backend/publications/' . $steady->publication()->id . '/members';
		$this->icon = 'users';
	}
}

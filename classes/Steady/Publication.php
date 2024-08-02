<?php

namespace Soerenengels\Steady;

use Kirby\Toolkit\Date;

/**
 * Publication Class
 * represents the API response from
 * https://developers.steadyhq.com/#publication
 *
 * @param array $data Steady API Response
 */
class Publication
{

	/** @var string the id of the the publication */
	public readonly string $id;

	/** @var string type */
	public readonly string $type;

	/** @var string the title of the publication */
	public readonly string $title;

	/** @var string the url of your steady page */
	public readonly string $campaign_page_url;

	/** @var int the members count of the publication */
	public readonly int $members_count;

	/** @var int the count of paying members of the publication */
	public readonly int $paying_members_count;

	/** @var int the count of trial members of the publication */
	public readonly int $trial_members_count;

	/** @var int the count of guest members of the publication */
	public readonly int $guest_members_count;

	/** @var int the sum of the membership fees, the publication earns in a month */
	public readonly int $monthly_amount;

	/**
	 * @deprecated Use monthly-amount instead.
	 * @var int
	 * */
	public readonly int $monthly_amount_in_cents;

	/** @var string the name of the publisher as shown on the Steady Page */
	public readonly string $editor_name;

	/** @var bool if trial memberships are enabled for the publication */
	public readonly bool $trial_period_activated;

	/** @var bool boolean if the publication has been made public */
	public readonly bool $public;

	/** @var string the url of the JS-Steady-Plugin of your publication */
	public readonly string $js_widget_url;

	/** @var Date datetime converted to Kirby\Toolkit\Date of the creation of the publication */
	public readonly Date $inserted_at;

	/** @var Date datetime converted to Kirby\Toolkit\Date when the publication was updated the last time on our system */
	public readonly Date $updated_at;

	/**
	 * @param array $data Steady API response
	 */
	public function __construct(
		array $data
	) {
		$this->id = $data['id'];
		$this->type = $data['type'];
		foreach ($data['attributes'] as $key => $value) {
			$key = str_replace('-', '_', $key);
			if ($key == 'inserted_at' || $key == 'updated_at') {
				$value = new Date($value);
			}
			$this->{$key} = $value;
		};
	}
	use toArrayTrait;
}

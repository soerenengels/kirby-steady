<?php

namespace Soerenengels\Steady;
use Kirby\Toolkit\Date;

/**
 * Implements the api response from
 * https://developers.steadyhq.com/#publication
 * as class
 *
 * @param object $data steady api response
 *
 * @author Sören Engels <mail@soerenengels.de>
 * @version 1.0
 * @see https://github.com/soerenengels/kirby-steady
 */
class Publication
{

	/** @var string the id of the the publication */
	public string $id;

	/** @var string type */
	public string $type;

	/** @var string the title of the publication */
	public string $title;

	/** @var string the url of your steady page */
	public string $campaign_page_url;

	/** @var int the members count of the publication */
	public int $members_count;

	/** @var int the count of paying members of the publication */
	public int $paying_members_count;

	/** @var int the count of trial members of the publication */
	public int $trial_members_count;

	/** @var int the count of guest members of the publication */
	public int $guest_members_count;

	/** @var int the sum of the membership fees, the publication earns in a month */
	public int $monthly_amount;

	/**
	 * @deprecated Use monthly-amount instead.
	 * @var int
	 * */
	public int $monthly_amount_in_cents;

	/** @var string the name of the publisher as shown on the Steady Page */
	public string $editor_name;

	/** @var bool if trial memberships are enabled for the publication */
	public bool $trial_period_activated;

	/** @var bool boolean if the publication has been made public */
	public bool $public;

	/** @var string the url of the JS-Steady-Plugin of your publication */
	public string $js_widget_url;

	/** @var Date datetime converted to Kirby\Toolkit\Date of the creation of the publication */
	public Date $inserted_at;

	/** @var Date datetime converted to Kirby\Toolkit\Date when the publication was updated the last time on our system */
	public Date $updated_at;


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
}

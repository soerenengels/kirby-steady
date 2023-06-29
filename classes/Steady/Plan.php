<?php
namespace Soerenengels\Steady;
use Kirby\Toolkit\Date;

/**
 * Implements the api response from
 * https://developers.steadyhq.com/#plans
 * as class
 *
 * @param array $data steady api response
 *
 * @author Sören Engels <mail@soerenengels.de>
 * @version 1.0
 * @see https://github.com/soerenengels/kirby-steady
 */
class Plan
{

	/** @var string the id of the the publication */
	public string $id;

	/** @var string type */
	public string $type;

	/** @var string draft / published / archived */
	public string $state;

	/** @var string name of the plan */
	public string $name;

	/** @var string ISO 4217 currency code of the plan, e.g. EUR / USD / SEK / ... */
	public string $currency;

	/** @var int the amount a user with a monthly contract has to pay per month */
	public int $monthly_amount;

	/**
	 * @deprecated Use monthly-amount instead.
	 * @var int
	 * */
	public int $monthly_amount_in_cents;

	/** @var int the amount a user with an annual contract has to pay per year */
	public int $annual_amount;

	/**
	 * @deprecated Use annual-amount instead.
	 * @var int
	 * */
	public int $annual_amount_in_cents;

	/** @var string|null the benefits of this plan / null */
	public ?string $benefits;

	/** @var bool boolean if we ask the user for her shipping address after she subscribed */
	public bool $ask_for_shipping_address;

	/** @var bool boolean if this plan has a goal of a certain amount of subscriptions */
	public bool $goal_enabled;

	/** @var int|null integer how many subscription should be reached if goal is enabled / null */
	public ?int $subscriptions_goal;

	/** @var int|null integer / null - maximum number of guest subscriptions associated with a subscription of this plan */
	public ?int $subscription_guests_max_count;

	/** @var bool boolean if a countdown for this plan is enabled */
	public bool $countdown_enabled;

	/** @var Date datetime converted to Kirby\Toolkit\Date when the countdown will end if it is enabled / null */
	public ?Date $countdown_ends_at;

	/** @var bool boolean if the plan is hidden */
	public bool $hidden;

	/** @var string|null plan image url / null */
	public ?string $image_url;

	/** @var Date datetime converted to Kirby\Toolkit\Date of the creation of the plan */
	public Date $inserted_at;

	/** @var Date datetime converted to Kirby\Toolkit\Date when the plan was updated the last time on our system */
	public Date $updated_at;

	/** @var bool boolean - if the plan can be gifted to another user */
	public bool $giftable;


	function __construct(
		array $data
	) {
		$this->id = $data['id'];
		$this->type = $data['type'];
		foreach ($data['attributes'] as $key => $value) {
			$key = str_replace('-', '_', $key);
			if (($key == 'inserted_at' || $key == 'updated_at' || $key == 'countdown_ends_at') && $value) {
				$value = new Date($value);
			}
			$this->{$key} = $value;
		};
	}
}

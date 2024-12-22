<?php

namespace Soerenengels\Steady;

use Kirby\Toolkit\Date;
use Kirby\Http\Url;
use Kirby\Http\Uri;
use Kirby\Http\Query;

/**
 * Plan
 *
 * @method 'plan' type() Type of the Entity
 * @method 'draft'|'published'|'archived' state() plan state: draft / published / archived
 * @method string name() name of the plan
 * @method string currency() ISO 4217 currency code of the plan, e.g. EUR / USD / SEK / ...
 * @method int monthly_amount() the amount a user with a monthly contract has to pay per month
 * @method int annual_amount() the amount a user with an annual contract has to pay per year
 * @method string|null benefits() the benefits of this plan / null
 * @method bool ask_for_shiping_address() boolean if we ask the user for her shipping address after she subscribed
 * @method bool goal_enabled() boolean if this plan has a goal of a certain amount of subscriptions
 * @method int|null subscriptions_goal() integer how many subscription should be reached if goal is enabled / null
 * @method int|null subscription_guests_max_count() integer / null - maximum number of guest subscriptions associated with a subscription of this plan
 * @method bool countdown_enabled() boolean if a countdown for this plan is enabled
 * @method Date countdown_ends_at() datetime converted to Kirby\Toolkit\Date when the countdown will end if it is enabled / null
 * @method bool hidden() boolean if the plan is hidden
 * @method string|null image_url() plan image url / null
 * @method Date inserted_at() datetime converted to Kirby\Toolkit\Date of the creation of the plan
 * @method Date updated_at() datetime converted to Kirby\Toolkit\Date when the plan was updated the last time on our system
 * @method bool giftable() boolean - if the plan can be gifted to another user
 *
 * @see https://developers.steadyhq.com/#plans
 */
class Plan extends Entity
{
	protected array $properties = [
		'id',
		'type',
		'state',
		'name',
		'currency',
		'monthly_amount',
		'annual_amount',
		'benefits',
		'ask_for_shiping_address',
		'goal_enabled',
		'subscriptions_goal',
		'subscription_guests_max_count',
		'countdown_enabled',
		'countdown_ends_at',
		'hidden',
		'image_url',
		'high_res_image_url',
		'inserted_at',
		'updated_at',
		'giftable'
	];

	/**
	 * Get high resolution image url of Plan
	 *
	 * @param int $width default: 1200
	 * @param int $height default: 600
	 * @param int $dpr default: 3
	 * @return string url of image_url in higher resolution
	 */
	public function high_res_image_url($width = 1200, $height = 600, $dpr = 3): string
	{
		if($this->image_url() === null) return ''; // TODO: handle exception
		$uri = new Uri($this->image_url());
		$query = new Query(Url::query($this->image_url()));
		$query->w = $width;
		$query->h = $height;
		$query->dpr = $dpr;
		$uri->setQuery($query);
		return $uri->toString();
	}
}

<?php
namespace Soerenengels\Steady;
use Kirby\Toolkit\Date;
use Kirby\Http\Url;
use Kirby\Http\Uri;
use Kirby\Http\Query;

/**
 * Plan
 *
 * @see https://developers.steadyhq.com/#plans
 * @param array $data steady api response
 *
 * @method string	id() the id of the the publication
 * @method string type() type
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
 * @deprecated @method int monthly_amount_in_cents() Use monthly-amount instead.
 * @deprecated @method int annual_amount_in_cents() Use annual-amount instead.
 */
#[AllowDynamicProperties]
class Plan
{
	private readonly string $id;
	private readonly string $type;
	private readonly string $state;
	private readonly string $name;
	private readonly string $currency;
	private readonly int $monthly_amount;
	private readonly int $annual_amount;
	private readonly ?string $benefits;
	private readonly bool $ask_for_shiping_address; // typo as in steady api
	private readonly bool $goal_enabled;
	private readonly ?int $subscriptions_goal;
	private readonly ?int $subscription_guests_max_count;
	private readonly bool $countdown_enabled;
	private readonly ?Date $countdown_ends_at;
	private readonly bool $hidden;
	private readonly ?string $image_url;
	private readonly Date $inserted_at;
	private readonly Date $updated_at;
	private readonly bool $giftable;
	private readonly int $annual_amount_in_cents;
	private readonly int $monthly_amount_in_cents;


	public function __construct(
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

	public function __call($name, $arguments)
	{
		$properties = get_class_vars($this::class);
		if (!in_array($name, array_keys($properties))) {
			throw new \BadMethodCallException();
		}
		return $this->$name;
	}


	public function high_res_image_url($width = 1200, $height = 600, $dpr = 3): string {
		$uri = new Uri($this->image_url());
		$query = new Query(Url::query($this->image_url()));
		$query->w = $width;
		$query->h = $height;
		$query->dpr = $dpr;
		$uri->setQuery($query);
		return $uri->toString();
	}

	use toArrayTrait;
}

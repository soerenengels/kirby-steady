<?php
namespace Soerenengels\Steady;
use Soerenengels\Steady\Plan;
use Soerenengels\Steady\User;
use Soerenengels\Steady\Endpoint;
use Kirby\Exception\Exception;
use Kirby\Toolkit\Date;
use Kirby\Toolkit\A;


/**
 * Implements the api response from
 * https://developers.steadyhq.com/#subscriptions
 * as class
 *
 * @param array $response associative array with steady api response of keys 'data' and 'includes'
 *
 * @method cancel() cancels subscription
 * @method gifter()
 * @method plan()
 * @method subscriber() return subscriber
 *
 * @method string id() Id of the the subscription
 * @method string type() Type
 * @method string id() the id of the the publication
 * @method string type() type
 * @method string state() guest / in_trial / active / not_renewing
 * @method string period() monthly / annual — the period of the contract of the user
 * @method string currency() EUR / USD / SEK
 * @method int monthly_amount() monthly amount of the associated plan (users don’t pay in states in_trial and guest)
 * @deprecated @method int monthly_amount_in_cents() Use monthly_amount() instead.
 * @method Date inserted_at() datetime converted to Kirby\Toolkit\Date of the creation of the subscription
 * @method Date updated_at() datetime converted to Kirby\Toolkit\Date when the subscription was updated the last time on our system
 * @method Date|null cancelled_at() datetime converted to Kirby\Toolkit\Date of the cancellation / null
 * @method Date|null trial_ends_at() datetime converted to Kirby\Toolkit\Date when the subscription's trial period will end or has ended / null
 * @method Date|null active_from() datetime converted to Kirby\Toolkit\Date when the subscription was paid for the first time/ null
 * @method Date|null expires_at() datetime converted to Kirby\Toolkit\Date when the subscription will expire / null
 * @method string rss_feed_url() if you use our podcast features, this is the rss-feed url with authentication for the subscriber
 * @method string utm_campaign() utm campaign parameter
 * @method bool is_gift() boolean; - if the subscription was a gift. If true, gifter information is included in the payload
 * @method string plan_id() id of steady plan
 */
class Subscription {

	private readonly string $id;
	private readonly string $type;
	private readonly string $state;
	private readonly string $period;
	private readonly string $currency;
	private readonly int $monthly_amount;
	private readonly int $monthly_amount_in_cents;
	private readonly Date $inserted_at;
	private readonly Date $updated_at;
	private readonly ?Date $cancelled_at;
	private readonly ?Date $trial_ends_at;
	private readonly ?Date $active_from;
	private readonly ?Date $expires_at;
	private readonly string $rss_feed_url;
	private readonly ?string $utm_campaign;
	private readonly bool $is_gift;
	private readonly string $plan_id;

	private array $relationships;
	private array $included;

	use toArrayTrait;

	public function __construct(
		array $response
	) {
		// setup
		[
			'data' => $data,
			'included' => $included
		] = $response;
		[
			'id' => $id,
			'type' => $type,
			'attributes' => $attributes,
			'relationships' => $relationships
		] = $data;

		// object creation
		$this->id = $id;
		$this->type = $type;
		foreach ($attributes as $key => $value) {
			$key = str_replace('-', '_', $key);
			if (
				$key == 'inserted_at' ||
				$key == 'updated_at' ||
				$key == 'cancelled_at' ||
				$key == 'trial_ends_at' ||
				$key == 'active_from' ||
				$key == 'expires_at' &&
				$value
			) {
				$value = $value ? new Date($value) : null;
			}
			try {
				$this->{$key} = $value;
			} catch (Exception $e) {
				echo $e->getMessage('Could not set property ' . $key . ' with value ' . $value . ' in Subscription object');
			}
		};

		// relations
		// relation: plan
		$this->relationships = $relationships;
		$this->included = $included;
		/* $plan_id = $relationships['plan']['data']['id'];
		$plan = array_filter($included, function($item) use ($plan_id) {
			return $item['type'] == 'plan' && $item['id'] == $plan_id;
		});
		$this->plan = new Plan($plan);

		// relation: subscriber, gifter
		$users = array_filter($included, function($item) {
			return $item['type'] == 'user';
		});
		// subscriber
		$subscriber_id = $relationships['subscriber']['data']['id'];
		$subscriber = array_filter($users, function ($user) use ($subscriber_id) {
			return $user['id'] == $subscriber_id;
		});
		$this->subscriber = $subscriber ? new User($subscriber) : null;
		// gifter
		$gifter_id = $relationships['gifter']['data']['id'];
		$gifter = array_filter($users, function ($user) use ($gifter_id) {
			return $user['id'] == $gifter_id;
		});
		$this->gifter = $gifter ? new User($gifter) : null;*/
	}

	public function __call($name, $arguments)
	{
		$properties = get_class_vars($this::class);
		if (!in_array($name, array_keys($properties))) {
			throw new \BadMethodCallException();
		}
		return $this->$name;
	}

	/**
	 * Cancel Subscription
	 * sends a POST request to steady API
	 * to cancel $this subscription
	 */
	public function cancel(): void {
		try {
			$steady = steady();
			// POST request to /subscriptions/:subscription_id/cancel
			$remote = $steady->post(Endpoint::SUBSCRIPTIONS->url() . '/' . $this->id() . '/cancel');
			// Flush steady subscriptions cache
			$steady->cache->remove(Endpoint::SUBSCRIPTIONS->value);
		} catch (Exception $e) {
			// TODO: status code 442 means subscription can't be canceled (e.g. because it already is cancelled)
			echo $e->getMessage();
		}
	}

	/**
	 * returns subscriber as array
	*/
	public function subscriber(): ?array {
		return A::find($this->included, function($item) {
			return (
				$item['type'] == 'user' &&
				$item['id'] == $this->relationships['subscriber']['data']['id']
			);
		});
	}

	/**
	 * returns gifter as User, if subscription is gifted,
	 * else null
	 */
	public function gifter(): ?array {
		if (!array_key_exists('gifter', $this->relationships)) return null;
		return A::find($this->included, function($item) {
			return (
				$item['type'] == 'user' &&
				$item['id'] == $this->relationships['gifter']['data']['id']
			);
		});
	}

	/**
	 * returns plan as Plan
	 */
	public function plan(): ?array {
		return A::find($this->included, function($item) {
			return (
				$item['type'] == 'plan' &&
				$item['id'] == $this->relationships['plan']['data']['id']
			);
		});
	}
}

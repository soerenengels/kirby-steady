<?php
namespace Soerenengels\Steady;
use Soerenengels\Steady\Plan;
use Soerenengels\Steady\User;
use Soerenengels\Steady\Endpoint;
use Kirby\Exception\Exception;
use Kirby\Toolkit\Date;
use Kirby\Toolkit\A;


/**
 * Subscription
 *
 * Access Subscription with magic methods
 *
 * @method cancel() cancels subscription
 * @method gifter()
 * @method plan()
 * @method subscriber() return subscriber
 *
 * @method 'subscription' type() Type
 * @method string state() guest / in_trial / active / not_renewing
 * @method string period() monthly / annual — the period of the contract of the user
 * @method string currency() EUR / USD / SEK
 * @method int monthly_amount() monthly amount of the associated plan (users don’t pay in states in_trial and guest)
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
 *
 * @see https://developers.steadyhq.com/#subscriptions
 */
class Subscription extends Entity {
	protected array $properties = [
		'id',
		'type',
		'state',
		'period',
		'currency',
		'monthly_amount',
		'inserted_at',
		'updated_at',
		'cancelled_at',
		'trial_ends_at',
		'active_from',
		'expires_at',
		'rss_feed_url',
		'utm_campaign',
		'is_gift',
		'plan_id'
	];

	/**
	 * Cancel Subscription
	 *
	 * Send a POST request to Steady API
	 * to cancel $this Subscription
	 *
	 * @return bool true if successful, false if not
	 */
	public function cancel(): bool {
		try {
			$steady = steady();
			// POST request to /subscriptions/:subscription_id/cancel
			$remote = $steady->post(Endpoint::SUBSCRIPTIONS->url() . '/' . $this->id() . '/cancel');
			if ($remote === null) throw new Exception('Error: No response from Steady API.');
			// TODO: if ($remote->code() == 442) throw new Exception('Error: Could not cancel subscription (e.g. because it already is cancelled).');
			// Flush steady subscriptions cache
			return $steady->cache->remove(Endpoint::SUBSCRIPTIONS->value);
		} catch (Exception $e) {
			echo $e->getMessage();
			return false;
		}
	}

	/**
	 * Subscriber of Subscription
	*/
	public function subscriber(): ?User {
		if (!array_key_exists('subscriber', $this->relationships)) return null;

		/** @var null|array{type: string, id: string, attributes: array<string, string|bool|null>} */
		$data = A::find(
			$this->included,
			function ($item) {
				/** @var array{id: string, type: string, attributes: array<string, string|bool|null>} $item */
				return (
					$item['type'] == 'user' &&
					$item['id'] == $this->relationships['subscriber']['data']['id']
				);
			}
		);

		return ($data === null) ? null : new User($data);
	}

	/**
	 * Gifter of Subscription
	 */
	public function gifter(): ?User {
		if (!array_key_exists('gifter', $this->relationships)) return null;

		/** @var null|array{type: string, id: string, attributes: array<string, string|bool|null>} */
		$data = A::find(
			$this->included,
			function($item) {
				if (!is_array($item)) return false;
				/** @var array{id: string, type: string, attributes: array<string, string|bool|null>} $item */
				return (
					$item['type'] == 'user' &&
					$item['id'] == $this->relationships['gifter']['data']['id']
				);
			}
		);

		return ($data === null) ? null : new User($data);
	}

	/**
	 * Subscribed Plan
	 */
	public function plan(): ?Plan {
		if (!array_key_exists('plan', $this->relationships)) return null;

		/** @var null|array{type: string, id: string, attributes: array<string, string|bool|null>} */
		$data = A::find(
			$this->included,
			function($value, $key, $array) {
				if (!is_array($value)) return false;
				/** @var array{id: string, type: string, attributes: array<string, string|bool|null>} $value */
				return (
					$value['type'] == 'plan' &&
					$value['id'] == $this->relationships['plan']['data']['id']
				);
			}
		);

		return ($data === null) ? null : new Plan($data);
	}
}

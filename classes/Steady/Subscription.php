<?php
namespace Soerenengels\Steady;
use Soerenengels\Steady\Plan;
use Soerenengels\Steady\User;
use Soerenengels\Steady\Endpoint;
use Kirby\Exception\Exception;
use Kirby\Toolkit\Date;

interface SubscriptionInterface {
	public function cancel(): bool;
	public function subscriber(): User|null;
	public function gifter(): User|null;
	public function plan(): Plan|null;
}

/**
 * Implements the api response from
 * https://developers.steadyhq.com/#subscriptions
 * as class
 *
 * @param array $response associative array with steady api response of keys 'data' and 'includes'
 *
 * @method cancel() cancels subscription
 * @method subscriber() returns subscriber
 * @method gifter()
 * @method plan()
 *
 * @author Sören Engels <mail@soerenengels.de>
 * @version 1.0
 * @see https://github.com/soerenengels/kirby-steady
 */
class Subscription implements SubscriptionInterface
{

	/** @var string the id of the the publication */
	public string $id;

	/** @var string type */
	public string $type;

	/** @var string guest / in_trial / active / not_renewing */
	public string $state;

	/** @var string monthly / annual — the period of the contract of the user */
	public string $period;

	/** @var string EUR / USD / SEK */
	public string $currency;

	/** @var int monthly amount of the associated plan (users don’t pay in states in_trial and guest) */
	public int $monthly_amount;

	/**
	 * @deprecated Use monthly-amount instead.
	 * @var int
	 * */
	public int $monthly_amount_in_cents;

	/** @var Date datetime converted to Kirby\Toolkit\Date of the creation of the subscription */
	public Date $inserted_at;

	/** @var Date datetime converted to Kirby\Toolkit\Date when the subscription was updated the last time on our system */
	public Date $updated_at;

	/** @var Date|null datetime converted to Kirby\Toolkit\Date of the cancellation / null */
	public ?Date $cancelled_at;

	/** @var Date|null datetime converted to Kirby\Toolkit\Date when the subscription's trial period will end or has ended / null */
	public ?Date $trial_ends_at;

	/** @var Date|null datetime converted to Kirby\Toolkit\Date when the subscription was paid for the first time/ null */
	public ?Date $active_from;

	/** @var Date|null datetime converted to Kirby\Toolkit\Date when the subscription will expire / null */
	public ?Date $expires_at;

	/** @var string if you use our podcast features, this is the rss-feed url with authentication for the subscriber */
	public string $rss_feed_url;

	/** @var bool boolean; - if the subscription was a gift. If true, gifter information is included in the payload */
	public bool $is_gift;

	/** @var string $plan_id id of steady plan */
	public string $plan_id;

	/** @var Plan included Plan */
	public Plan $plan;

	/** @var User|false if included subscriber as User, else false */
	public User|false $subscriber;

	/** @var User|false if included gifter as User, else false */
	public User|false $gifter;


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
			'relationsships' => $relationships
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
				$value = new Date($value);
			}
			$this->{$key} = $value;
		};

		// relations
		// relation: plan
		$plan_id = $relationships['plan']['data']['id'];
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
		$this->gifter = $gifter ? new User($gifter) : null;
	}

	// TODO
	/**
	 * Cancel Subscription
	 * sends a POST request to steady API
	 * to cancel $this subscription
	 * returns true in case of success
	 */
	/* public function cancel(): bool {
		$steady = steady();


		try {
			// POST request to /subscriptions/:subscription_id/cancel
			$request = $steady->post(Endpoint::SUBSCRIPTIONS . $this->id . '/cancel');
		} catch (Exception $e) {
			// status code 442 means subscription can't be canceled (e.g. because it already is cancelled)
			echo $e->getMessage();
		}
		// flush steady subscriptions cache
		$steady->cache->remove(Endpoint::SUBSCRIPTIONS);
		return true;
	} */

	/**
	 * returns subscriber as User
	*/
	public function subscriber(): ?User {
		return $this->subscriber;
	}

	/**
	 * returns gifter as User, if subscription is gifted,
	 * else null
	 */
	public function gifter(): ?User {
		return $this->gifter;
	}

	/**
	 * returns plan as Plan
	 */
	public function plan(): ?Plan {
		return $this->plan;
	}
}

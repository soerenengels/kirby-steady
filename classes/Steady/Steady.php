<?php

namespace Soerenengels\Steady;

use Soerenengels\Steady\Publication;
use Soerenengels\Steady\Plans;
use Soerenengels\Steady\Subscriptions;
use Soerenengels\Steady\NewsletterSubscribers;
use Soerenengels\Steady\Widgets;
use Soerenengels\Steady\Widget;
use Soerenengels\Steady\WidgetType;
use Kirby\Cache\Cache;
use Kirby\Http\Remote;
use Kirby\Exception\Exception;
use Kirby\Cms\App as Kirby;

interface SteadyInterface
{
	public function publication(): Publication;
	public function plans(): Plans;
	public function subscriptions(): Subscriptions;
	public function newsletter_subscribers(): NewsletterSubscribers;
	public function report(string $id): ?array;
	public function widgets(WidgetType $type): Widget|Widgets;

	public function get(Endpoint $endpoint);
	public function post(Endpoint $endpoint);
}

/**
 * Endpoint Enum.
 * BASE | PUBLICATION | PLANS | SUBSCRIPTIONS | NEWSLETTER_SUBSCRIBERS
 */
enum Endpoint: string {
	case BASE = 'https://steadyhq.com/api/v1/';
	case PUBLICATION = 'publication';
	case PLANS = 'plans';
	case SUBSCRIPTIONS = 'subscriptions';
	case NEWSLETTER_SUBSCRIBERS = 'newsletter_subscribers';
}

// TODO: better caching
/**
 * Get your steady publication data via the steady REST API.
 * @version 1.0
 *
 * @param string $api_token your secret steady token
 * @param string $cache default: 'kirby-steady'
 * @param int $cache_expiry_in_minutes default: 1440
 *
 * @author Sören Engels <mail@soerenengels.de>
 */
class Steady implements SteadyInterface
{
	protected Cache $cache;

	public function __construct(
		protected string $api_token,
		public int $cache_expiry_in_minutes = 1440,
	) {
		// TODO: option('soerenengels.kirby-steady.cache')
		$this->cache = kirby()->cache(
			'steady-api'
		);
	}


	/**
	 * Send request to Steady API $endpoint
	 *
	 * @param Endpoint $endpoint Endpoint Enum ::PUBLICATION | ::PLANS | ::SUBSCRIPTIONS | ::NEWSLETTER_SUBSCRIBERS
	 * @param string $method request method GET|POST|PUT|PATCH|DELETE|HEAD, default: GET
	 */
	public function request(Endpoint $endpoint, string $method = 'GET') {
		$url = Endpoint::BASE->value . $endpoint->value;
		$request = Remote::request($url, [
			'method'  => $method,
			'headers' => [
				'X-Api-Key: ' . $this->api_token
			]
		]);

		if ($request->code() !== 200) {
				throw new Exception('An error occurred: ' . $request->code());
		}
		return $request->json();
	}

	/**
	 * Send GET request to Steady API $endpoint
	 *
	 * @param Endpoint $endpoint ENDPOINT Enum
	 */
	public function get(Endpoint $endpoint) {
		return $this->request($endpoint);
	}

	/**
	 * Send POST request to Steady API $endpoint
	 *
	 * @param Endpoint $endpoint ENDPOINT Enum
	 */
	public function post(Endpoint $endpoint) {
		return $this->request($endpoint, 'POST');
	}

	/**
	 * Get data from cache or set it via
	 * callback function
	 *
	 * @param Endpoint $endpoint ENDPOINT Enum
	 */
	public function getData(Endpoint $endpoint)
	{
		return $this->cache->getOrSet($endpoint->value, function () use ($endpoint) {
			return $this->get($endpoint);
		});
	}

	/**
	 * Get an object for your Steady Publication
	 * @return Publication;
	 */
	public function publication(): Publication
	{
		$data = self::getData(Endpoint::PUBLICATION)['data'];
		$publication = new Publication($data);
		return $publication;
	}

	/**
	 * Returns a Plans object
	 * @return Plans;
	 */
	public function plans(): Plans
	{
		$data = self::getData(Endpoint::PLANS)['data'];
		return new Plans($data);
	}

	/**
	 * Returns a Steady Subscriptions object
	 * @return Subscriptions;
	 */
	public function subscriptions(): Subscriptions
	{
		$data = $this->getData(Endpoint::SUBSCRIPTIONS);
		return $data;
		return new Subscriptions($data);
	}

	/**
	 * Returns a NewsletterSubscribers object
	 * @return SteadyNewsletterSubscribers;
	 */
	public function newsletter_subscribers(): NewsletterSubscribers
	{
		$data = $this->getData(Endpoint::NEWSLETTER_SUBSCRIBERS)['data'];
		return new NewsletterSubscribers($data);
	}

	/**
	 * Returns Report with $id
	 * @param string $id revenue|newsletter_subscribers|members
	 * @return ?array array of rendered report or null
	 */
	public function report(string $id): ?array {
		return (
			$id == 'revenue' ? (new MonthlyRevenueReport())->render() : (
					$id == 'newsletter_subscribers' ? (new NewsletterSubscribersReport())->render() : (
							$id == 'members' ? (new MembersReport())->render() : null
					)
			)
		);
	}

	/**
	 * Widgets method
	 *
	 * Returns Widgets object or specific Widget
	 */
	public function widgets(?WidgetType $type = null): Widget|Widgets
	{
		if (!$type) return new Widgets();
		$widget = $type->value;
		return $this->widgets()->$widget();
	}
}

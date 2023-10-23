<?php

namespace Soerenengels\Steady;

use Soerenengels\Steady\Publication;
use Soerenengels\Steady\Plans;
use Soerenengels\Steady\Subscriptions;
use Soerenengels\Steady\NewsletterSubscribers;
use Soerenengels\Steady\Widgets;
use Soerenengels\Steady\Widget;
use Soerenengels\Steady\WidgetType;
use Soerenengels\Steady\Endpoint;
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

	public function get(Endpoint $endpoint,?array $headers);
	public function post(Endpoint $endpoint);
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
		private ?Kirby $kirby = null
	) {
		// TODO: option('soerenengels.kirby-steady.cache')
		$this->kirby = $kirby ?? kirby();
		$this->cache = $this->kirby->cache(
			'steady-api'
		);
	}


	/**
	 * Send request to Steady API $endpoint
	 *
	 * @param Endpoint $endpoint Endpoint Enum ::PUBLICATION | ::PLANS | ::SUBSCRIPTIONS | ::NEWSLETTER_SUBSCRIBERS
	 * @param string $method request method GET|POST|PUT|PATCH|DELETE|HEAD, default: GET
	 */
	public function request(
		Endpoint $endpoint,
		string $method = 'GET',
		?array $headers = null,
		array $data = []
	) {
		$headers = $headers ?? [
			'X-Api-Key: ' . $this->api_token
		];
		$request = Remote::request(
			$endpoint->url(),
			[
				'method'  => $method,
				'headers' => $headers,
				'data' => $data
			]
		);

		if ($request->code() !== 200) {
			throw new Exception('An error occurred: ' . $request->code());
		}
		return $request->json();
	}

	/**
	 * Send GET request to Steady API $endpoint
	 *
	 * @param Endpoint $endpoint Endpoint Enum
	 * @param ?array $headers optional for REST request
	 */
	public function get(
		Endpoint $endpoint,
		?array $headers = null
	): Remote
	{
		return $this->request(
			$endpoint,
			'GET',
			$headers
		);
	}

	/**
	 * Send POST request to Steady API $endpoint
	 *
	 * @param Endpoint $endpoint ENDPOINT Enum
	 * @param ?array $data data array to send with request
	 * @param ?array $headers headers array
	 */
	public function post(
		Endpoint $endpoint,
		?array $data = null,
		?array $headers = null
	): Remote
	{
		return $this->request(
			$endpoint,
			'POST',
			$headers,
			$data
		);
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
	public function report(string $id): ?array
	{
		$report = match ($id) {
			'revenue' => new MonthlyRevenueReport(),
			'newsletter_subscribers' => new NewsletterSubscribersReport(),
			'members' => new MembersReport(),
			default => null
		};
		return $report?->render();
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

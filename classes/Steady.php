<?php

namespace Soerenengels\Steady;

use Soerenengels\Steady\Publication;
use Soerenengels\Steady\Plans;
use Soerenengels\Steady\Subscriptions;
use Soerenengels\Steady\NewsletterSubscribers;
use Kirby\Cache\Cache;
use Kirby\Http\Remote;

interface SteadyInterface
{
	public function publication(): Publication;
	public function plans(): Plans;
	public function subscriptions(): Subscriptions;
	public function newsletter_subscribers(): NewsletterSubscribers;
	public function report(string $id): ?array;
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
	const API_ENDPOINT_BASE = 'https://steadyhq.com/api/v1/';
	const API_ENDPOINT_PUBLICATION = 'publication';
	const API_ENDPOINT_PLANS = 'plans';
	const API_ENDPOINT_SUBSCRIPTIONS = 'subscriptions';
	const API_ENDPOINT_NEWSLETTER_SUBSCRIBERS = 'newsletter_subscribers';
	protected Cache $cache;

	public function __construct(
		protected string $api_token,
		public int $cache_expiry_in_minutes = 1440,
	) {
		$this->cache = kirby()->cache(
			'steady-api'// TODO: option('soerenengels.kirby-steady.cache-name')
		);
	}

	public function fetchDataFromApi(string $endpoint)
	{
		$url = self::API_ENDPOINT_BASE . $endpoint;
		$options = ["headers" => ["X-Api-Key: " . $this->api_token]];
		$request = Remote::get($url, $options);
		if ($request->code() === 200) {
			return $request->json();
		}
	}

	/**
	 * Get data from cache or set it via
	 * callback function
	 *
	 * @param string $endpoint API_ENDPOINT constant
	 */
	public function getData(string $endpoint)
	{
		return $this->cache->getOrSet($endpoint, function () use ($endpoint) {
			return self::fetchDataFromApi($endpoint);
		});
	}

	/**
	 * Get an object for your Steady Publication
	 * @return Publication;
	 */
	public function publication(): Publication
	{
		$data = self::getData(self::API_ENDPOINT_PUBLICATION)['data'];
		$publication = new Publication($data);
		return $publication;
	}

	/**
	 * Returns a Plans object
	 * @return Plans;
	 */
	public function plans(): Plans
	{
		$data = self::getData(self::API_ENDPOINT_PLANS)['data'];
		return new Plans($data);
	}

	/**
	 * Returns a Steady Subscriptions object
	 * @return Subscriptions;
	 */
	public function subscriptions(): Subscriptions
	{
		$data = $this->getData(self::API_ENDPOINT_SUBSCRIPTIONS);
		return $data;
		return new Subscriptions($data);
	}

	/**
	 * Returns a NewsletterSubscribers object
	 * @return SteadyNewsletterSubscribers;
	 */
	public function newsletter_subscribers(): NewsletterSubscribers
	{
		$data = $this->getData(self::API_ENDPOINT_NEWSLETTER_SUBSCRIBERS)['data'];
		return new NewsletterSubscribers($data);
	}

	/**
	 * Returns Report with $id
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
}

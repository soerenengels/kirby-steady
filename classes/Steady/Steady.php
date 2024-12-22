<?php

namespace Soerenengels\Steady;

use Soerenengels\Steady\Publication;
use Soerenengels\Steady\Plans;
use Soerenengels\Steady\Subscriptions;
use Soerenengels\Steady\Widgets;
use Soerenengels\Steady\Widget;
use Soerenengels\Steady\WidgetType;
use Soerenengels\Steady\Endpoint;
use Soerenengels\Steady\OAuth;
use Kirby\Cache\Cache;
use Kirby\Http\Remote;
use Kirby\Exception\Exception;
use Kirby\Cms\App as Kirby;

/**
 * Steady Abstraction Layer
 *
 * @param string $api_token Your secret steady token
 * @param int $cache_expiry_in_minutes default: 1440
 * @author Sören Engels <mail@soerenengels.de>
 *
 * @phpstan-type SteadyEntityResponse array{
 * 	type:string,
 * 	id: string,
 * 	attributes:array<string, string|int|bool|null>,
 * 	relationships?:array<string, array{
 * 		data:array<string, string>
 * 	}>
 * }
 * @phpstan-type SteadyCollectionResponse array<SteadyEntityResponse>
 * @phpstan-type SteadyResponse array{
 * 	data: (SteadyEntityResponse|SteadyCollectionResponse),
 * 	included?: array<SteadyEntityResponse>
 * }
 * @phpstan-type SteadyAudioPost array{
 * 	audio_url: string,
 * 	title: string,
 * 	description: string,
 * 	content?: string,
 * 	teaser_image?: string,
 * 	publish_at?: string,
 * 	published_at?: string,
 * 	restrict_to_plan_ids?: array<string>,
 * 	distribute_on_steady_page?: bool,
 * 	distribute_as_email?: bool
 * }
 */
class Steady
{
	/** @var Cache $cache Access the Steady Cache */
	public Cache $cache;

	public function __construct(
		protected ?string $api_token = null,
		public int $cache_expiry_in_minutes = 1440,
		private ?Kirby $kirby = null
	) {
		$this->kirby = $kirby ?? kirby();
		/** @var string $option */
		$option = $this->kirby->option('soerenengels.steady.token');
		$this->api_token = $api_token ?? $option;
		$this->cache = $this->kirby->cache('soerenengels.steady');
	}

	/**
	 * Send GET request to Steady API $endpoint
	 *
	 * @param Endpoint $endpoint Endpoint Enum
	 * @param ?array<string> $headers optional: headers array
	 * @return SteadyResponse|\stdClass parsed Response data
	 */
	public function get(
		Endpoint $endpoint,
		?array $headers = null
	): \stdClass|array {
		$response = $this->request(
			$endpoint,
			'GET',
			$headers
		);

		if (!($json = $response->json())) throw new Exception('No data found.');
		/** @var SteadyResponse|\stdClass $json */
		return $json;
	}



	/**
	 * Get data from cache
	 * or set it, if it
	 * does not yet exist
	 * via callback function
	 *
	 * @param Endpoint::* $endpoint ENDPOINT Enum
	 * @return SteadyResponse $data
	 */
	public function getData(Endpoint $endpoint)
	{
		/** @var SteadyResponse $data */
		$data = $this->cache->getOrSet($endpoint->value, function () use ($endpoint) {
			return $this->get($endpoint);
		});
		return $data;
	}

	/**
	 * Returns Steady Newsletter Subscribers as Users object
	 * @return Users $users;
	 */
	public function newsletter_subscribers(): Users
	{
		/** @var array{data: array<SteadyEntityResponse>} $data */
		$data = $this->getData(Endpoint::NEWSLETTER_SUBSCRIBERS);
		return new Users($data['data']);
	}

	/**
	 * Steady OAuth Abstraction
	 */
	public function oauth(): ?OAuth
	{
		$this->kirby = $this->kirby ?? kirby();
		/** @var string $id */
		$id = $this->kirby->option('soerenengels.steady.oauth.client.id');
		/** @var string $secret */
		$secret = $this->kirby->option('soerenengels.steady.oauth.client.secret');
		/** @var string $redirect_uri */
		$redirect_uri = $this->kirby->option('soerenengels.steady.oauth.redirect-uri');

		if (
			!$id ||
			!$secret ||
			!$redirect_uri
		) return null;

		return new OAuth(
			$id,
			$secret,
			$redirect_uri,
			$this,
			$this->kirby
		);
	}

	/**
	 * Steady Plans
	 */
	public function plans(): Plans
	{
		/** @var array{data:array<SteadyEntityResponse>} $data */
		$data = self::getData(Endpoint::PLANS);
		return new Plans($data['data']);
	}

	public function plans_for_access_control(): Plans {
		/** @var array{data:array<SteadyEntityResponse>} $data */
		$data = self::getData(Endpoint::PLANS_FOR_ACCESS_CONTROL);
		return new Plans($data['data']);
	}

	/**
	 * Send POST request to Steady API $endpoint
	 *
	 * @param Endpoint|string $endpoint ENDPOINT Enum or url string
	 * @param array<mixed> $data data array to send with request
	 * @param ?array<string> $headers optional: headers array
	 * @return \stdClass|array<mixed>|null parsed Response data
	 */
	public function post(
		Endpoint|string $endpoint,
		array $data = [],
		?array $headers = null
	): \stdClass|array|null {
		return $this->request(
			$endpoint,
			'POST',
			$headers,
			$data
		)->json();
	}

	/**
	 * Steady Publication
	 */
	public function publication(): Publication
	{
		/** @var array{data:SteadyEntityResponse} $data */
		$data = $this->getData(Endpoint::PUBLICATION);
		return new Publication($data['data']);
	}

	/**
	 * Publish (Audio) Post
	 *
	 * @param SteadyAudioPost $data
	 * @return SteadyResponse $data
	 */
	public function publishPodcast(
		array $data
	): array {
		// SEND POST REQUEST
		/** @var SteadyResponse $response */
		$response = $this->post(
			Endpoint::AUDIO_POSTS,
			$data
		);
		return $response;
	}

	/**
	 * Returns Report with $id
	 * @param string $id revenue|newsletter_subscribers|members
	 * @return ?array<string, string|int|null> array of Report or null
	 */
	public function report(string $id): ?array
	{
		$report = match ($id) {
			'revenue' => new MonthlyRevenueReport(),
			'newsletter_subscribers' => new NewsletterSubscribersReport(),
			'members' => new MembersReport(),
			default => null
		};
		return $report?->toArray();
	}

	/**
	 * Returns array of Reports
	 * @param string ...$ids revenue|newsletter_subscribers|members
	 * @return array<array<string, mixed>> array of Reports
	 */
	public function reports(...$ids): array
	{
		$reports = [];
		$ids = empty($ids) ? ['members', 'newsletter_subscribers', 'revenue'] : $ids;
		foreach ($ids as $id) {
			$report = $this->report($id);
			if ($report) $reports[] = $report;
		}
		return $reports;
	}

	/**
	 * Request the Steady API
	 *
	 * @param Endpoint|string $endpointOrUrl Endpoint Enum ::PUBLICATION | ::PLANS | ::SUBSCRIPTIONS | ::NEWSLETTER_SUBSCRIBERS or url string
	 * @param string $method request method GET|POST|PUT|PATCH|DELETE|HEAD, default: GET
	 * @param ?array<string> $headers optional: headers array
	 * @param array<mixed> $data optional: data array to send with request
	 */
	public function request(
		Endpoint|string $endpointOrUrl,
		string $method = 'GET',
		?array $headers = null,
		array $data = []
	): Remote {

		// Set default headers with API token
		$headers = $headers ?? [
			'X-Api-Key: ' . $this->api_token
		];

		// Get url from Endpoint Enum or use url string
		$url = ($endpointOrUrl instanceof Endpoint) ? $endpointOrUrl->url() : $endpointOrUrl;

		// Request data from Steady API
		$request = Remote::request(
			$url,
			[
				'method'  => $method,
				'headers' => $headers,
				'data' => $data
			]
		);

		// Check for errors
		if ($request->code() !== 200) {
			throw new Exception('An error occurred: ' . $request->code());
		}

		return $request;
	}

	/**
	 * Subscribe to Steady Newsletter
	 *
	 * Send double opt-in email by
	 * requesting Steady API
	 *
	 * @param string $email Email to subscribe
	 * @return SteadyResponse $response
	 */
	public function subscribe(string $email): array
	{
		/** @var SteadyResponse $request */
		$request = $this->post(
			Endpoint::NEWSLETTER_SUBSCRIBE,
			[
				'email' => $email,
			]
		);
		return $request;
	}

	/**
	 * Returns a Steady Subscriptions object
	 * @return Subscriptions
	 */
	public function subscriptions(): Subscriptions
	{
		/** @var array{data:array<SteadyEntityResponse>,included: array<SteadyEntityResponse>} $data */
		$data = $this->getData(Endpoint::SUBSCRIPTIONS);
		return new Subscriptions($data['data'], $data['included']);
	}

	/**
	 * Widgets method
	 *
	 * Returns Widgets object or specific Widget
	 * @return ($type is null ? Widgets : Widget)
	 */
	public function widgets(
		?WidgetType $type = null
	): Widget|Widgets|null {
		if ($type === null) return new Widgets( // Create array of Widgets from WidgetType
			array_map(
				function (WidgetType $type) {
					return new Widget($type);
				},
				WidgetType::cases()
			)
		);
		try {
			$widgetType = $type->value;
			/** @var Widget $widget */
			$widget = $this->widgets()->$widgetType();
			return $widget;
		} catch (Exception $e) {
			// TODO: handle exception
			// $e->getMessage('widgets parameter must be of type WidgetType');
			return null;
		}
	}
}

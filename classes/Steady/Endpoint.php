<?php
namespace Soerenengels\Steady;

/**
 * Steady API Endpoint Enum
 *
 * Structure access to Steady API endpoints
 *
 * @method string name() Value of Endpoint Enum
 * @method string url() Steady API URL endpoint
 */
enum Endpoint: string {
	case PUBLICATION = 'publication';
  case PLANS = 'plans';
  case SUBSCRIPTIONS = 'subscriptions';
	case NEWSLETTER_SUBSCRIBERS = 'newsletter_subscribers';

	case NEWSLETTER_SUBSCRIBE = 'newsletter_subscribe';
	case AUDIO_POSTS = 'audio_posts';
	case PLANS_FOR_ACCESS_CONTROL = 'plans_for_access_control';

	// OAuth2
	case OAUTH_AUTHORIZATION = 'authorize';
	case OAUTH_ACCESS_TOKEN = 'token';
	case OAUTH_CURRENT_USER = 'users/me';
	case OAUTH_CURRENT_SUBSCRIPTION = 'subscriptions/me';

	/**
	 * Returns value of Endpoint Enum
	 */
	public function name(): string
  {
      return $this->value;
  }

	/**
	 * Returns url of Endpoint Enum
	 */
	public function url(): string
  {
    return match($this)
    {
			// REST
			self::PUBLICATION => 'https://steadyhq.com/api/v1/publication',
			self::PLANS => 'https://steadyhq.com/api/v1/plans',
			self::SUBSCRIPTIONS => 'https://steadyhq.com/api/v1/subscriptions',
			self::NEWSLETTER_SUBSCRIBERS => 'https://steadyhq.com/api/v1/newsletter_subscribers',

			self::NEWSLETTER_SUBSCRIBE => 'https://steadyhq.com/api/v1/newsletter_subscribers/send_double_opt_in_email',
			self::AUDIO_POSTS => 'https://steadyhq.com/api/v1/posts/audio_posts',
			self::PLANS_FOR_ACCESS_CONTROL => 'https://steadyhq.com/api/v1/posts/plans_for_access_control',

			// OAuth2
			self::OAUTH_AUTHORIZATION => 'https://steadyhq.com/oauth/authorize',
			self::OAUTH_ACCESS_TOKEN => 'https://steadyhq.com/api/v1/oauth/token',
			self::OAUTH_CURRENT_USER => 'https://steadyhq.com/api/v1/users/me',
			self::OAUTH_CURRENT_SUBSCRIPTION => 'https://steadyhq.com/api/v1/subscriptions/me',

    };
  }
}

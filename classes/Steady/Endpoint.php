<?php
namespace Soerenengels\Steady;

/**
 * Endpoint Enum
 *
 * @method name() returns value
 * @method url() returns url
 */
enum Endpoint: string {
	case PUBLICATION = 'publication';
  case PLANS = 'plans';
  case SUBSCRIPTIONS = 'subscriptions';
	case NEWSLETTER_SUBSCRIBERS = 'newsletter_subscribers';

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

			// OAuth2
			self::OAUTH_AUTHORIZATION => 'https://steadyhq.com/oauth/authorize',
			self::OAUTH_ACCESS_TOKEN => 'https://steadyhq.com/api/v1/oauth/token',
			self::OAUTH_CURRENT_USER => 'https://steadyhq.com/api/v1/users/me',
			self::OAUTH_CURRENT_SUBSCRIPTION => 'https://steadyhq.com/api/v1/subscriptions/me',
    };
  }
}

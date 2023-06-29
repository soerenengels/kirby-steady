<?php
namespace Soerenengels\Steady;
use Soerenengels\Steady\NewsletterSubscriber;

interface NewsletterSubscribersInterface
{
	public function count(): int;
	public function filter(mixed $query = null): NewsletterSubscribers;
	// FEATURE: public function add(NewsletterSubscribers|Newslettersubscriber|array $subscriber): NewsletterSubscribers;
	// FEATURE: group()/groupBy()
	// FEATURE: isEmpty()/isNotEmpty()
	// FEATURE: remove()
	// FEATURE: not()
	// FEATURE: sort()
}

/**
 * Steady Newsletter Subscribers
 * as requested via Steady API
 * https://developers.steadyhq.com/#newsletter-subscribers
 * Returns an array with all current newsletter subscribers of the publication.
 *
 * @param array $data steady api response
 *
 * @author Sören Engels <mail@soerenengels.de>
 * @version 1.0
 * @see https://github.com/soerenengels/kirby-steady
 */
class NewsletterSubscribers implements NewsletterSubscribersInterface
{
	/** @var array array of subscribers  */
	public array $newsletter_subscribers = [];


	function __construct(
		array $data
	) {
		foreach ($data as $subscription) {
			$this->newsletter_subscribers[] = new NewsletterSubscriber($subscription);
		};
	}

	public function count(): int
	{
		return count($this->newsletter_subscribers);
	}

	public function filter(mixed $query = null): NewsletterSubscribers
	{
		return new NewsletterSubscribers(array_filter($this->newsletter_subscribers, function (NewsletterSubscriber $subscriber) {
			return $subscriber->opted_in_at->compare()->days <= 30;
		}));
	}
}

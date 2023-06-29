<?php

namespace Soerenengels\Steady;
use Kirby\Toolkit\Date;

/**
 * A single Steady Newsletter Subscriber
 *
 * @param array|NewsletterSubscriber $data array or SteadyNewsletterSubscriber object from data array in response from steady api
 *
 * @author Sören Engels <mail@soerenengels.de>
 * @version 1.0
 * @see https://github.com/soerenengels/kirby-steady
 */

class NewsletterSubscriber
{

	/** @var string uid */
	public string $id;

	/** @var string type */
	public string $type;

	/** @var string email address of the newsletter subscriber */
	public string $email;

	/** @var Date datetime converted to Kirby\Toolkit\Date when the newsletter subscriber clicked the opt-in link in the email */
	public Date $opted_in_at;


	function __construct(
		array|NewsletterSubscriber $data
	) {
		if (is_object($data)) {
			$this->id = $data->id;
			$this->type = $data->type;
			$this->email = $data->email;
			$this->opted_in_at = $data->opted_in_at;
		} else {
			$this->id = $data['id'];
			$this->type = $data['type'];
			foreach ($data['attributes'] as $key => $value) {
				$key = str_replace('-', '_', $key);
				if ($key == 'opted_in_at') {
					$value = new Date($value);
				}
				$this->{$key} = $value;
			};
		}
	}
}

<?php

namespace Soerenengels\Steady;

use Kirby\Toolkit\Date;

/**
 * Publication Class
 *
 * Represent the API Response from
 * https://developers.steadyhq.com/#publication
 *
 * @param array $data Steady API Response data
 *
 * @method string	id() Id of the the publication
 * @method string	type() Type
 * @method string	title() Title of the publication
 * @method string	campaign_page_url() Url of your publications steady page
 * @method int		members_count() The members count of the publication
 * @method int		paying_members_count() the count of paying members of the publication
 * @method int		trial_members_count() the count of trial members of the publication
 * @method int		guest_members_count() the count of guest members of the publication
 * @method int		monthly_amount() the sum of the membership fees, the publication earns in a month
 * @method string	editor_name() the name of the publisher as shown on the Steady Page
 * @method bool		trial_period_activated() if trial memberships are enabled for the publication
 * @method bool		public() boolean if the publication has been made public
 * @method string	js_widget_url() the url of the JS-Steady-Plugin of your publication
 * @method Date		inserted_at() datetime converted to Kirby\Toolkit\Date of the creation of the publication
 * @method Date		updated_at() datetime converted to Kirby\Toolkit\Date when the publication was updated the last time on our system
 * @deprecated @method int monthly_amount_in_cents() Use monthly_amount() instead
 */
class Publication
{
	private readonly string $id;
	private readonly string $type;
	private readonly string $title;
	private readonly string $campaign_page_url;
	private readonly int $members_count;
	private readonly int $paying_members_count;
	private readonly int $trial_members_count;
	private readonly int $guest_members_count;
	private readonly int $monthly_amount;
	private readonly int $monthly_amount_in_cents;
	private readonly string $editor_name;
	private readonly bool $trial_period_activated;
	private readonly bool $public;
	private readonly string $js_widget_url;
	private readonly Date $inserted_at;
	private readonly Date $updated_at;



	/**
	 * @param array $data Steady API response
	 */
	public function __construct(
		array $data
	) {
		$this->id = $data['id'];
		$this->type = $data['type'];
		foreach ($data['attributes'] as $key => $value) {
			$key = str_replace('-', '_', $key);
			if ($key == 'inserted_at' || $key == 'updated_at') {
				$value = new Date($value);
			}
			$this->{$key} = $value;
		};
	}
	use toArrayTrait;

	public function __call($name, $arguments)
	{
		$properties = get_class_vars($this::class);
		if (!in_array($name, array_keys($properties))) {
			throw new \BadMethodCallException();
		}
		return $this->$name;
	}
}

<?php
namespace Soerenengels\Steady;
use Kirby\Toolkit\Date;

/**
 * Steady Publication
 *
 * @param array{'id': string, 'type': string, 'attributes': array<string, string|int|bool|null>} $data Steady API response
 *
 * @method 'publication' type() Type
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
 *
 * @see https://developers.steadyhq.com/#publication for more information
 */
class Publication extends Entity
{
	protected array $properties = [
		'id',
		'type',
		'title',
		'campaign_page_url',
		'members_count',
		'paying_members_count',
		'trial_members_count',
		'guest_members_count',
		'monthly_amount',
		'editor_name',
		'trial_period_activated',
		'public',
		'js_widget_url',
		'inserted_at',
		'updated_at'
	];
}

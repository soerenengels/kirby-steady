<?php

namespace Soerenengels\Steady;

/**
 * WidgetType Enum
 */
enum WidgetType: string
{
	case ENGAGEMENTWALL = 'engagement_wall';
	case NEWSLETTER = 'newsletter';
	case CHECKOUT = 'checkout';
	case PAYWALL = 'paywall';
	case ADBLOCK = 'adblock_detection';

	/**
	 * Returns title of WidgetType case
	 */
	public function title(): string
	{
		return match ($this) {
			self::ENGAGEMENTWALL => 'Announcement Layer',
			self::NEWSLETTER => 'Newsletter Signup Layer',
			self::CHECKOUT => 'Checkout',
			self::PAYWALL => 'Paywall',
			self::ADBLOCK => 'Adblock Detection',
		};
	}

	/**
	 * Returns identifier of WidgetType case
	 * for Steady JS Widget Loader string
	 */
	public function js(): string
	{
		return match ($this) {
			self::ENGAGEMENTWALL => 'engagementWall',
			self::CHECKOUT => 'checkout',
			self::NEWSLETTER => 'newsletter',
			self::PAYWALL => 'paywall',
			self::ADBLOCK => 'adblock',
		};
	}
}

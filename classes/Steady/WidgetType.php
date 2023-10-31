<?php

namespace Soerenengels\Steady;

/**
 * WidgetType Enum
 * @method string title() title of WidgetType
 * @method string js() js title of WidgetType
 * */
enum WidgetType: string
{
	case PAYWALL = 'paywall';
	case ADBLOCK = 'adblock_detection';
	case FLOATING_BUTTON = 'floating_button';
	case CHECKOUT = 'checkout';

	/**
	 * Returns title of WidgetType case
	 */
	public function title(): string
	{
		return match ($this) {
			self::PAYWALL => 'Paywall',
			self::ADBLOCK => 'Adblock Detection',
			self::FLOATING_BUTTON => 'Floating Button',
			self::CHECKOUT => 'Checkout'
		};
	}

	/**
	 * Returns identifier of WidgetType case
	 * for Steady JS Widget Loader string
	 */
	public function js(): string
	{
		return match ($this) {
			self::PAYWALL => 'paywall',
			self::ADBLOCK => 'adblock',
			self::FLOATING_BUTTON => 'floating_button',
			self::CHECKOUT => 'checkout'
		};
	}
}

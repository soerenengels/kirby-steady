<?php

namespace Soerenengels\Steady;

enum WidgetType: string
{
	case PAYWALL = 'paywall';
	case ADBLOCK = 'adblock';
	case FLOATING_BUTTON = 'floatingButton';
	// TODO: case CHECKOUT = 'checkout';
}

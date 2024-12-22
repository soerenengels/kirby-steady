<?php

namespace Soerenengels\Steady;

/**
 * UserType Enum
 * @property string $value UserType value
 * */
enum UserType: string
{
	case NEWSLETTER_SUBSCRIBER = 'newsletter_subscriber';
	case USER = 'user';
}

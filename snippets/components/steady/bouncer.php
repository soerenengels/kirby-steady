<?php
use Kirby\Toolkit\Html;
use Kirby\Toolkit\A;

/** @var ?array $attr */
/** @var Kirby\Template\Slots $slots */
/** @var ?string $tag */

// Setup
$oauth = steady()->oauth();
if (!$oauth) return;
$content = $oauth->isLoggedIn() ? ($oauth->isMember() ? $slots->member() : $slots->user()) : $slots->visitor();
$status = $oauth->isLoggedIn() ? ($oauth->isMember() ? 'member' : 'user') : 'visitor';
$defaultAttr = ['class' => ['k-steady-bouncer', "k-steady-$status"]];

echo Html::tag(
	$tag ?? 'section',
	[$content],
	A::merge($defaultAttr, $attr ?? [])
);

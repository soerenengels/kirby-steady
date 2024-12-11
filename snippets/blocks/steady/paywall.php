<?php use Kirby\Toolkit\Html; ?>
<?php if(!steady()->widgets()->paywall()->isActive()) return; ?>
<?= Html::tag('div', [
	'id' => 'steady_paywall',
	'style' => 'display: none;',
	'data-utm-campaign' => $block->utm_campaign()->or(''),
]) ?>

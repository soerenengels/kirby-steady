<?php

use Kirby\Cms\Block;
use Kirby\Toolkit\Html;
/** @var Block $block */
?>
<?php if(!steady()->widgets()?->paywall()->isActive()) return; ?>
<?= Html::tag('div', [
	'id' => 'steady_paywall',
	'style' => 'display: none;',
	'data-utm-campaign' => $block->utm_campaign()->or(''),
]) ?>

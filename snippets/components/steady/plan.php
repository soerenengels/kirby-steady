
<?php
use Kirby\Toolkit\I18n;
$plan = $plan ?? null;
/* Workaround, see: https://kirby.nolt.io/246 */
if (option('slugs')) {
	I18n::$locale = option('slugs');
}
if ($plan?->hidden) return; ?>
<section id="steady-plan" class="steady__plan container">
	<div class="steady__plan plan__header"><?= $plan->name ?></div>
	<div class="steady__plan plan__image">
		<img src="<?= $plan->image_url ?>" alt="">
	</div>
	<div class="steady__plan plan__benefits">
		<?= nl2br($plan->benefits) ?>
	</div>
	<div class="steady__plan plan__monthlyAmount">
		<?= $plan->monthly_amount / 100 ?> <?= $plan->currency ?>
	</div>
	<div class="steady__plan plan__annualAmount">
		<?= $plan->annual_amount / 100 ?> <?= $plan->currency ?>
	</div>
	<div class="steady__plan plan__comparison">
		<?= tt('soerenengels.steady.snippets.plan.comparison', null, ['saving' => (1 - ($plan->monthly_amount * 12 - $plan->annual_amount) / $plan->monthly_amount * 12)]) ?>
	</div>
	<?php if ($plan->goal_enabled): ?>
	<div class="steady__plan plan__goal">
		<?= $plan->subscriptions_goal ?>
	</div>
	<?php endif ?>
	<?php if ($plan->countdown_enabled): ?>
	<div class="steady__plan plan__countdown">
		<?= $plan->countdown_ends_at ?>
	</div>
	<?php endif ?>
</section>

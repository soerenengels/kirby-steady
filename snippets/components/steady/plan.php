
<?php
use Kirby\Toolkit\I18n;

$plan = $plan ?? null;
if ($plan?->hidden || !$plan) return;

/* Workaround, see: https://kirby.nolt.io/246 */
if (option('slugs')) {
	I18n::$locale = option('slugs');
}
 ?>
<section id="steady-plan" class="steady__plan container">
	<div class="steady__plan plan__image">
		<img src="<?= $plan->high_res_image_url() ?>" alt="<?= $plan->name ?>">
	</div>
	<div class="steady__plan plan__benefits">
		<?= kt($plan->benefits) ?>
	</div>
	<div class="steady__plan plan__monthlyAmount">
		<?= $plan->monthly_amount / 100 ?> <?= $plan->currency ?>
	</div>
	<div class="steady__plan plan__annualAmount">
		<?= $plan->annual_amount / 100 / 12 ?> <?= $plan->currency ?>
	</div>
	<div class="steady__plan plan__comparison">
		<?= tt('soerenengels.steady.snippets.plan.comparison', null, [
			'saving' => round(((1 - ($plan->annual_amount / ($plan->monthly_amount * 12)))) * 100),
			'currency' => $plan->currency,
			'total' => $plan->annual_amount / 100
			]) ?>
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
	<div class="steady__plan plan__cta">
		<a href="https://steadyhq.com/plans/<?= $plan->id ?>/subscribe" class="button button__dark"><?= t('soerenengels.steady.snippets.plans.cta') ?></a>
	</div>
</section>

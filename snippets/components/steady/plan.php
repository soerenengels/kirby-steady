
<?php
$plan = $plan ?? null;
if ($plan?->hidden) return; ?>
<section id="steady-plan" class="steady__plan container">
	<div class="steady__plan plan__header"><?= $plan->name ?></div>
	<div class="steady__plan plan__image">
		<img src="<?= $plan->image_url ?>" alt="">
	</div>
	<div class="steady__plan plan__benefits">
		<?= $plan->benefits ?>
	</div>
	<div class="steady__plan plan__monthlyAmount">
		<?= $plan->monthly_amount / 100 ?> <?= $plan->currency ?>
	</div>
	<div class="steady__plan plan__annualAmount">
	<?= $plan->annual_amount / 100 ?> <?= $plan->currency ?>
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

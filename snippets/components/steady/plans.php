<?php
$plans = $plans ?? null;
if(!$plans) return;
?>
<section id="steady-plans" class="steady__plans">
	<?php if ($plans): ?>
	<?php foreach ($plans as $plan) : ?>
		<?php snippet('components/steady/plan', ['plan' => $plan]) ?>
	<?php endforeach ?>
	<?php else: ?>
		<!-- Empty State -->
		<section>
			<p>No Plans yet.</p>
		</section>
	<?php endif ?>
</section>
<style>
	.steady__plans {
		display: flex;
		gap: 1em;
		font-size: .8em;
	}
	.steady__plan.container {
		flex-basis:14em;
		flex-grow: 1;
		flex-shrink: 0;
		display: grid;
		grid-template-areas:
			"name"
			"image"
			"benefits";
		grid-template-rows: auto auto 1fr auto;
	}
	.steady__plan.plan__benefits {
		margin-block: 2em;
		grid-area: benefits;
	}
	.steady__plan.plan__header {
		grid-area: name;
		font-weight: 900;
		font-size: calc(1em / 0.8);
	}
	.steady__plan.plan__image {
		grid-area: image;
	}
</style>

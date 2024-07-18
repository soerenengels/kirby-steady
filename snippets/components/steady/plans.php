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

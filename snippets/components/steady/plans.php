<?php
$plans = $plans ?? null;
if (!$plans) return;
?>
<section id="steady-plans" class="steady__plans">
	<?php if ($plans): ?>
		<section class="steady__plans__header">
			<h2><?= t('soerenengels.steady.snippets.plans.toggle') ?></h2>
			<label for="steady-toggle-plans">
				<?= t('soerenengels.steady.snippets.plans.monthly') ?>
				<input type="checkbox" id="steady-period" aria-label="Toggle Plans" name="steady-period" checked />
				<span class="slider"></span>
				<?= t('soerenengels.steady.snippets.plans.annual') ?>
			</label>
		</section>
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

<script>

</script>

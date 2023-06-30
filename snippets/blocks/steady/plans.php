<?php
$plans = steady()->plans();
if ($block->steady_plans_customize() == false) {
	$plans_ids = $block->steady_plans()->split();
	foreach ($plans_ids as $plan_id) {
		$plans[] = $plans->find($plan_id);
	}
} else {
	$plans = $plans->plans;
}
?>
<?php snippet('components/steady/plans', ['plans' => $plans ]) ?>

<?php
$plans = steady()->plans();

// Filter Plans, if Steady Plans are customized in block
if ($block->steady_plans_customize() == "true") {
	$plans = $plans->filter(function (\Soerenengels\Steady\Plan $plan) use ($block) {
		return in_array(
			$plan->id,
			$block->steady_plans()->split()
		);
	});
}

snippet('components/steady/plans', [
	'plans' => $plans->sort()->list()
]);
?>

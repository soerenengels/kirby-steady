<?php
namespace Soerenengels\Steady;

/**
 * Steady Plans
 *
 * @see https://developers.steadyhq.com/#plans
 * @extends Collection<Plan>
 */
class Plans extends Collection
{
	protected const DEFAULT_SORT_PARAM = 'monthly_amount';
	protected const ENTITY_CLASS = Plan::class;

	/**
	 * @return array<array{text: string, value: string}> Options array for Kirby Select field
	 */
	public function toOptions(): array
	{
		$options = [];
		foreach ($this->items as $plan) {
			$options[] = [
				'text' => $plan->name(),
				'value' => $plan->id()
			];
		}
		return $options;
	}
}

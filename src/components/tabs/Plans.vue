<template>
	<k-section
		:headline="panel.t('soerenengels.steady.plans')"
		:buttons="[{
					text: panel.t('soerenengels.steady.create'),
					icon: 'add',
					link: `https://steadyhq.com/de/backend/publications/${data.publication.id}/plans/new#plan_form`,
				}]"
	>
		<k-items :items="items" size="huge" layout="cards">
			<template #default>
				<k-empty v-if="items.length === 0" icon="add" layout="cards">
					{{ panel.t('soerenengels.steady.plans.create') }}
				</k-empty>
			</template>
		</k-items>
	</k-section>
</template>

<script setup>
import { computed, usePanel } from 'kirbyuse';
const panel = usePanel();
const props = defineProps({
	data: Array
});
const items = computed(() => {
	return props.data.plans.map(plan => {
		const link = `https://steadyhq.com/de/backend/publications/default/plans/${plan.id}/edit#plan_form`;
		return {
			text: plan.name,
			info: plan.monthly_amount / 100 + ' ' + plan.currency + ' ' + panel.t('soerenengels.steady.plans.per_month'),
			link: 'drawers/steady/plan/' + plan.id,
			image: {
				src: plan.high_res_image_url,
				ratio: '16/9'
			},
			options: [
				{
					text: panel.t('soerenengels.steady.edit'),
					icon: 'edit',
					link: link
				}
			]
		};
	});
});
</script>

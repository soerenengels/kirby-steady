<template>
	<div>
		<k-headline>{{
			$panel.t('soerenengels.steady.debug.headline')
		}}</k-headline>
		<k-tabs :tabs="tabs" :tab="tab" />
		<k-section>
			<k-code language="json">{{ code }}</k-code>
		</k-section>
	</div>
</template>

<script setup>
// KirbyUse Setup
import { computed, ref, usePanel } from 'kirbyuse';
const panel = usePanel();

// Props
const props = defineProps({ data: Array });

// Define tabs from views
const views = ref({
	publication: 'page',
	plans: 'store',
	subscribers: 'email',
	members: 'users',
	reports: 'chart'
});
const tabs = computed(() => {
	return Object.entries(views.value).map(([key, value]) => {
		return {
			name: key,
			label: panel.t(`soerenengels.steady.${key}`),
			icon: value,
			click: () => panel.view.open(panel.view.path, { query: { tab: key } })
		};
	});
});
// Get current tab from query OR use first tab
const tab = computed(() => panel.view.query.tab ?? Object.keys(views.value)[0]);

// Set data for display
const code = computed(() => {
	return JSON.stringify(props.data[tab.value], null, 2);
});
</script>

<style scoped>
.k-tabs {
	margin-block-end: var(--spacing-2);
}
.k-headline {
	font-size: var(--text-lg);
}
</style>

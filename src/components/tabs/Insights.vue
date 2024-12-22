<template>
	<k-grid variant="columns">
		<k-column>
			<k-section :headline="panel.t('soerenengels.steady.insights')">
				<k-stats :reports="props.data.reports" size="huge" />
			</k-section>
		</k-column>
		<k-column v-if="canDraft">
			<k-section :headline="panel.t('soerenengels.steady.drafts')">
				<k-empty @click="panel.drawer.open('steady/post/new')">{{ panel.t('soerenengels.steady.drafts.empty') }}</k-empty>
				<k-items />
			</k-section>
			<k-collection
				:name="panel.t('soerenengels.steady.drafts') + ' 2'"
				parent="site" />
		</k-column>

		</k-grid>
</template>

<script setup>
import { computed, ref, useContent, usePanel, useStore, watch } from 'kirbyuse';
const panel = usePanel();

const canDraft = computed(() => {
	panel.get('pages/steady').catch(() => {
		return false
	}).then(() => {
		return true
	});
});
const drafts = computed(() => {
	if (!canDraft.value) return [];
	panel.get('pages/steady/children', { status: 'draft' }).then((res) => res.data);
});
const props = defineProps({
	data: Array
});
console.log(panel.api.get('pages/steady/children', { status: 'draft'}));
</script>

<template>
	<k-section :headline="panel.t('soerenengels.steady.widgets.label')">
		<k-box
			v-if="showNotice"
			:text="notice"
			:theme="theme"
			icon="info"
		/>
		<k-stats
			:reports="data.widgets"
			:class="{
				'k-stats-widgets': true,
				inactive: !data.widgetsEnabled,
			}"
			size="medium"
		/>
		<k-text :html="$t('soerenengels.steady.widgets.help')" />
	</k-section>
</template>

<script setup>
import { computed, ref, useContent, usePanel, useStore, watch } from "kirbyuse";
const panel = usePanel();

const props = defineProps({
	data: Array
});

const showNotice = computed(() => {
	return showNoticeActivated || showNoticeDisabled;
});
const showNoticeActivated = computed(() => {
	return props.data.widgetsEnabled && !props.data.widgetsWarning;
});
const enabledInKirby = computed(() => props.data.widgets[0].theme === 'positive');
const enabledInSteady = computed(() => {
	const steadyWidgets = props.data.widgets
	steadyWidgets.shift()
	steadyWidgets.some(widget => widget.theme === 'positive')
});
const showNoticeDisabled = computed(() => {
	return !props.data.widgetsEnabled && props.data.widgetsWarning;
});
const notice = computed(() => {
	return panel.t(
		"soerenengels.steady.widgets.notice." +
		(showNoticeActivated ? "activated" : "disabled")
	);
});
const theme = ref(showNoticeActivated ? 'info' : 'notice');
</script>

<style lang="postcss" scoped>
.k-box {
	margin-block-end: var(--spacing-2);
}
.k-stats-widgets {
	padding-block-end: var(--spacing-4);
	&.inactive .k-stat:not(:first-child) {
		opacity: 0.5;
	}
}
</style>

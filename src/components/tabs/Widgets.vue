<template>
	<k-section :headline="$t('soerenengels.steady.widgets.label')">
		<k-box icon="info" :text="notice" :theme="showNoticeActivated ? 'info' : 'notice'" v-if="showNotice" />
		<k-stats
			:reports="widgets"
			size="medium"
			:class="{
				'k-stats-widgets': true,
				inactive: !widgetsEnabled,
			}"
		/>
		<k-text :html="$t('soerenengels.steady.widgets.help')" />
	</k-section>
</template>

<script>
export default {
	// TODO: Refactor widgets status variables
	props: {
		widgets: Array,
		widgetsEnabled: Boolean,
		widgetsWarning: Boolean,
	},
	computed: {
		showNotice() {
			return this.showNoticeActivated || this.showNoticeDisabled;
		},
		showNoticeActivated() {
			return this.widgetsEnabled && !this.widgetsWarning;
		},
		showNoticeDisabled() {
			return !this.widgetsEnabled && this.widgetsWarning;
		},
		notice() {
			return this.$t(
				"soerenengels.steady.widgets.notice." +
					(this.showNoticeActivated ? "activated" : "disabled")
			);
		},
	},
};
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

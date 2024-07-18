<template>
	<div>
		<k-headline>{{ $t("soerenengels.steady.debug.headline") }}</k-headline>
		<k-tabs :tabs="tabs" :tab="tab" />
		<k-section>
			<k-code :language="language">{{ code }}</k-code>
		</k-section>
	</div>
</template>

<script>
export default {
	props: {
		members: Object,
		plans: Array,
		publication: Object,
		reports: Array,
		subscribers: Array,
		tab: String,
	},
	data() {
		return {
			language: "json",
			views: {
				publication: "page",
				plans: "store",
				subscribers: "email",
				members: "users",
				reports: "chart",
		}
		};
	},
	computed: {
		code() {
			return this[this.tab]
		},
		tabs() {
			return Object.entries(this.views).map(([ key, value ]) => {
				return {
					name: key,
					label: this.$t(`soerenengels.steady.${key}`),
					icon: value,
					click: () => {
						this.tab = key
						// TODO: Update query parameter
					},
				};
			});
		},
	},
};
</script>

<style scoped>
.k-tabs {
	margin-block-end: var(--spacing-2);
}
.k-headline {
	font-size: var(--text-lg);
}
</style>

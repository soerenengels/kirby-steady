<template>
			<k-section :headline="$t('soerenengels.steady.insights')">
				<k-stats :reports="reports" size="huge" />
			</k-section>
</template>

<script>
export default {
	props: {
		reports: {
			type: Array,
			default: () => [],
			required: true
		}
	}
};
</script>

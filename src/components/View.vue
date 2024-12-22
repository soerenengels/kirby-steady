<template>
	<k-panel-inside class="k-steady">
		<k-header class="k-steady-header">
			{{ data.publication.title }}
			<k-button-group slot="buttons">
				<k-button
					v-if="$panel.config.debug && $panel.user.role === 'admin'"
					:title="'Version ' + data.plugin['version']"
					@click="$refs.steadydebug.toggle()"
					icon="info"
				>
					v{{ data.plugin['version'] }}
				</k-button>
				<k-button
					:dropdown="true"
					@click="$refs.steadycreate.toggle()"
					icon="edit"
					theme="purple"
					variant="filled"
				>
					{{ $panel.t('soerenengels.steady.create') }}
				</k-button>
				<k-dropdown-content
					v-if="$panel.config.debug && $panel.user.role === 'admin'"
					align-x="start"
					options="steady/info"
					ref="steadydebug"
					theme="light"
				/>
				<k-dropdown-content
					options="steady/create"
					align-x="end"
					ref="steadycreate"
					theme="light"
				/>
			</k-button-group>
		</k-header>

		<!-- Tabs -->
		<k-tabs :tabs="tabs" :tab="tab" />
		<component :is="`k-steady-tab-${tab}`" :data="data" v-if="hasAccess" />
		<k-box
			v-else
			:text="$panel.t('soerenengels.steady.tabs.denied')"
			icon="info"
		/>
	</k-panel-inside>
</template>

<script setup>
import { computed, ref, useContent, usePanel, useStore, watch } from 'kirbyuse';
const panel = usePanel();
// Define props
const props = defineProps({
	data: Array,
	tabs: Array,
	tab: String
});

const hasAccess = computed(() => {
	const currentTab = props.tabs.find(tab => tab.name === props.tab);
	return currentTab?.permission ?? false;
});

panel.events.on('steady.post', (e) => {
	panel.notification.success({message: e.data.message});
});
// TODO: Handle drawer cancel event
</script>

<style lang="postcss" scoped>
.k-steady-header {
	margin-bottom: 0;
}
.k-header-buttons {
	flex-grow: 1;
	& .k-button:first-of-type {
		margin-inline: var(--spacing-2) auto;
	}
}
</style>

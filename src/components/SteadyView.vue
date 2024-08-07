<template>
	<k-panel-inside>
		<k-view class="k-steady">
			<k-header class="k-steady-header">
				{{ publication.title }}
				<template #buttons>
					<k-button
						icon="edit"
						:link="`https://steadyhq.com/de/backend/publications/${publication.id}/posts/new`"
						>{{ $t('soerenengels.steady.create.post') }}</k-button
					>
					<k-button
						v-if="$config.debug"
						icon="github"
						:link="plugin['link']"
						:title="'Version ' + plugin['version']"
						>v{{ plugin['version'] }}</k-button
					>
					<k-button v-if="$config.debug" icon="book" :link="plugin['link']">Docs</k-button>
					<k-button icon="steady" :link="`https://steadyhq.com/de/backend/publications/${publication.id}/home`" />
				</template>
			</k-header>

			<!-- Tabs -->
			<k-tabs :tabs="tabs" :tab="tab" />
			<k-steady-tab-insights :reports="reports" v-if="tab == 'insights' && views.insights.permission" />
			<k-steady-tab-widgets
				:widgets="widgets"
				:widgetsEnabled="widgetsEnabled"
				:widgetsWarning="widgetsWarning"
				v-if="tab == 'widgets' && views.widgets.permission"
			/>
			<k-steady-tab-users
				:members="subscriptions"
				:subscribers="newsletterSubscribers"
				:publication="publication"
				:tab="subtab"
				v-if="tab == 'users' && views.users.permission"
			/>
			<k-steady-tab-debug
				:tab="subtab"
				:publication="publication"
				:plans="plans"
				:members="subscriptions"
				:subscribers="newsletterSubscribers"
				:reports="reports"
				:widgets="widgets"
				v-if="tab == 'debug' && views.debug.permission"
			/>
		</k-view>
	</k-panel-inside>
</template>

<script>
export default {
	props: {
		newsletterSubscribers: Array,
		plans: Object,
		plugin: Object,
		publication: Object,
		reports: Array,
		subscriptions: Array,
		subtab: String,
		tab: String,
		views: Object,
		widgets: Array,
		widgetsEnabled: Boolean,
		widgetsWarning: Boolean
	},
	data() {
		return {};
	},
	created() {
	},
	computed: {
		filteredSubscriptions() {
			return this.subscriptions.filter(sub => sub.monthly_amount < 500);
		},
		tabs() {
			return Object.entries(this.views)
				.filter(([key, _]) => {
					return (key !== 'debug') ? true : this.$config.debug;
				})
				.filter(([_, value]) => {
					return value.permission;
				})
				.map(([key, value]) => {
				const param = (['users', 'debug'].includes(key) ? '?tab=' : '') + (key === 'users' ? 'subscribers' : (key == 'debug' ? 'publication' : ''));
				return {
					name: key,
					label: this.$t(`soerenengels.steady.${key}`),
					icon: value.icon,
					link: `steady/${key}${param}`
				};
			});
		}
	}
};
</script>

<style lang="postcss" scoped>
#toc ol,
#toc ul {
	list-style: auto;
	margin-inline-start: var(--spacing-4);
}
.k-code {
	margin-block: var(--spacing-4);
}
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

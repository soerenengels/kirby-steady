<template>
	<div>
		<k-headline>{{ panel.t('soerenengels.steady.users.headline') }}</k-headline>
		<k-tabs class="k-tabs" :tabs="tabs" :tab="tab" />
		<k-table
			class="k-steady-users"
			:columns="columns"
			:index="false"
			:pagination="pagination"
			:rows="paginatedItems"
			@paginate="e => (page = e.page)"
		>
			<template #options="{ row }">
				<k-options-dropdown :options="options(row)" v-if="tab == 'members'" />
				<k-options-dropdown :options="options(row)" v-else />
			</template>
		</k-table>
	</div>
</template>

<script setup>
import { computed, ref, useContent, usePanel, useStore, watch } from 'kirbyuse';
const panel = usePanel();

const props = defineProps({
	data: Array
});

// Pagination
const page = ref(1);
const pagination = computed(() => {
	return {
		page: page.value ?? 1,
		details: true,
		limit: 25,
		total: rows.value.length
	};
});
const index = computed(() => {
	return (pagination.value.page - 1) * pagination.value.limit + 1;
});

const cols = ref({
	subscribers: {
		email: {
			label: panel.t('email'),
			mobile: true
		},
		opted_in_at: {
			label: panel.t('soerenengels.steady.subscribed'),
			type: 'date',
			display: 'DD.MM.YYYY',
			width: '1/4',
			align: 'right',
			mobile: true
		}
	},
	members: {
		id: {
			label: panel.t('soerenengels.steady.id')
		},
		name: {
			label: panel.t('soerenengels.steady.member'),
			type: 'text',
			width: '1/4',
			mobile: true
		},
		active_from: {
			label: panel.t('soerenengels.steady.activated'),
			type: 'date',
			display: 'DD.MM.YYYY',
			width: '1/6',
			align: 'right',
			mobile: true
		},
		state: {
			label: panel.t('soerenengels.steady.state'),
			type: 'tags',
			width: '1/8'
		},
		plan: {
			label: panel.t('soerenengels.steady.plan'),
			type: 'text',
			width: '1/6',
			mobile: true
		},
		monthly_amount: {
			label: panel.t('soerenengels.steady.monthly-amount'),
			mobile: true,
			before: '€',
			width: '1/6',
			align: 'right',
			type: 'number'
		},
		period: {
			label: panel.t('soerenengels.steady.period'),
			type: 'tags',
			width: '1/8'
		}
	}
});

// Tabs
const views = {
	members: 'users',
	subscribers: 'email'
};
const tabs = computed(() => {
	return Object.entries(views).map(([key, value]) => {
		return {
			name: key,
			label: panel.t(`soerenengels.steady.${key}`),
			icon: value,
			link: `steady/users?tab=${key}`
		};
	});
});
const tab = computed(() => panel.view.query.tab ?? Object.keys(views)[0]);

// Data
const rows = computed(() => {
	return tab.value === 'subscribers'
		? props.data.subscribers
		: props.data.members.map(row => {
				// Members
				const member = row.included.find(item => {
					return item.id === row.relationships.subscriber.data.id;
				});
				const plan = row.included.find(item => {
					return item.id === row.relationships.plan.data.id;
				});

				return {
					...row,
					id: member.id,
					monthly_amount: row.monthly_amount / 100,
					plan: plan.attributes.name,
					period: panel.t('soerenengels.steady.period.' + row.period),
					state: panel.t('soerenengels.steady.state.' + row.state),
					name:
						member.attributes['first-name'] +
						' ' +
						member.attributes['last-name']
				};
		  });
});

const columns = computed(() => {
	return cols.value[tab.value];
});

const sortedRows = computed(() => {
	return rows.value.sort((a, b) => {
		const key = a.hasOwnProperty('opted_in_at') ? 'opted_in_at' : 'active_from';
		return new Date(a[key].date) >= new Date(b[key].date);
	});
});
const paginatedItems = computed(() => {
	return (
		sortedRows.value?.slice(
			index.value - 1,
			pagination.value.limit * pagination.value.page
		) ?? []
	);
});
function options(row) {
	return `steady/${tab.value}/${row.id}`;
}

panel.events.on('steady.subscriptions.cancelled', () => {
	panel.notification.success({
		icon: 'check',
		message: panel.t('soerenengels.steady.subscriptions.cancelled'),
		type: 'success'
	});
});

/*
		// previously commented out:
		setTimeout(() => {
		window.document.querySelectorAll('.k-bubble').forEach(el => {
			console.log(el)
			if(el.querySelector('.k-bubble-text').textContent == this.$t('soerenengels.steady.state.active')) {
				el.style.setProperty('--bubble-back', 'var(--color-positive-light)')
			}
		})
		}, 500) */
</script>

<style scoped>
.k-tabs {
	margin-block-end: var(--spacing-2);
}
.k-headline {
	font-size: var(--text-lg);
}
</style>

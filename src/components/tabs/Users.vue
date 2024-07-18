<template>
	<div>
		<k-headline>{{ $t('soerenengels.steady.users.headline') }}</k-headline>
		<k-tabs class="k-tabs" :tabs="tabs" :tab="tab" />
		<k-table
			:columns="columns"
			:index="false"
			@paginate="(e) => this.page = e.page"
			:pagination="pagination"
			:rows="paginatedItems">
			<template #options="{ row }" v-if="tab == 'members'">
				<k-options-dropdown :options="options(row)" />
			</template>
		</k-table>
	</div>
</template>

<script>
export default {
	props: {
		tab: String,
		members: Array,
		subscribers: Array
	},
	data() {
		return {
			page: 1,
			views: {
				subscribers: 'email',
				members: 'users'
			},
			cols: {
				subscribers: {
					email: {
						label: this.$t('email'),
						mobile: true
					},
					opted_in_at: {
						label: this.$t('soerenengels.steady.subscribed'),
						type: 'steadyDate',
						width: '1/4',
						align: 'right',
						mobile: true
					}
				},
				members: {
					id: {
						label: this.$t('soerenengels.steady.id')
					},
					active_from: {
						label: this.$t('soerenengels.steady.activated'),
						type: 'steadyDate',
						width: '1/6',
						align: 'right',
						mobile: true
					},
					monthly_amount: {
						label: this.$t('soerenengels.steady.monthly-amount'),
						mobile: true,
						before: '€',
						width: '1/4',
						type: 'number'
					},
					period: {
						label: this.$t('soerenengels.steady.period'),
						type: 'tags',
						width: '1/8'
					},
					state: {
						label: this.$t('soerenengels.steady.state'),
						type: 'tags',
						width: '1/8'
					}
				}
			}
		};
	},
	created() {
		this.$events.on('steady.subscriptions.cancelled', () => {
			this.$panel.notification.success({
				icon: 'check',
				message: this.$t('soerenengels.steady.subscriptions.cancelled'),
				type: 'success'
			});
		});
	},
	methods: {
		options(row) {
			return [
				{
					text: this.$t('soerenengels.steady.subscriptions.cancel'),
					icon: 'cancel',

				}
			];
		}
	},
	computed: {
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
		rows() {
			return this[this.tab]?.map(row => {
				return {
					...row,
					monthly_amount: row.monthly_amount / 100
				};
			});
		},
		columns() {
			return this.cols[this.tab];
		},
		pagination() {
			return {
				page: this.page,
				details: true,
				limit: 25,
				total: this.rows?.length
			};
		},
		index() {
			return (this.pagination.page - 1) * this.pagination.limit + 1;
		},
		paginatedItems() {
			return this.rows
				.sort((a, b) => {
					const key = a.hasOwnProperty('opted_in_at')
						? 'opted_in_at'
						: 'active_from';
					return new Date(a[key].date) >= new Date(b[key].date);
				})
				.slice(this.index - 1, this.pagination.limit * this.pagination.page);
		}
	}
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

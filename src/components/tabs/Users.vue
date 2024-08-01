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
		subscribers: Array,
		publication: Object
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
					name: {
						label: this.$t('soerenengels.steady.member'),
						type: 'text',
						width: '1/4',
						mobile: true,
					},
					active_from: {
						label: this.$t('soerenengels.steady.activated'),
						type: 'steadyDate',
						width: '1/6',
						align: 'right',
						mobile: true
					},
					state: {
						label: this.$t('soerenengels.steady.state'),
						type: 'tags',
						width: '1/8'
					},
					plan: {
						label: this.$t('soerenengels.steady.plan'),
						type: 'text',
						width: '1/6',
						mobile: true
					},
					monthly_amount: {
						label: this.$t('soerenengels.steady.monthly-amount'),
						mobile: true,
						before: '€',
						width: '1/6',
						type: 'number'
					},
					period: {
						label: this.$t('soerenengels.steady.period'),
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
			const url = new URL(this.publication.campaign_page_url)
			const publicationName = url.pathname.substring(1)
			return [
				{
					text: this.$t('soerenengels.steady.open'),
					icon: 'open',
					link: `https://steadyhq.com/backend/publications/${this.publication.id}/members/${row.id}`
				},{
					text: this.$t('soerenengels.steady.message.send'),
					icon: 'email',
					link: `https://steadyhq.com/backend/publications/${this.publication.id}/messages#/${publicationName}/${row.id}`
				},{
					text: this.$t('soerenengels.steady.subscriptions.cancel'),
					icon: 'cancel',
					click: () => this.$dialog(`steady/subscriptions/cancel/${row.id}`)
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
				// Users
				if (this.tab == 'subscribers') return {
					...row
				};
				// Members
				return {
					...row.data,
					id: row.subscriber.id,
					name: row.subscriber.attributes['first-name'] + ' ' + row.subscriber.attributes['last-name'],
					plan: row.plan.attributes.name,
					state: this.$t('soerenengels.steady.state.' + row.data.state),
					monthly_amount: row.data.monthly_amount / 100
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

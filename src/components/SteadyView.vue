<template>
	<k-panel-inside>
		<k-view class="k-steady">
			<k-header class="k-steady-header">
				{{ publication.title }}
				<template #buttons>
					<k-button :title="'Version ' + plugin['version']">v{{ plugin['version'] }}</k-button>
					<k-button
						icon="github"
						:link="plugin['link']"
						>Go to Plugin Repository</k-button
					>
				</template>
			</k-header>
			<k-tabs :tabs="tabs" :tab="tab" />

			<!-- Tab: Stats -->
			<template v-if="tab == 'stats'">
				<k-grid variant="columns">
					<k-column width="1/3">
						<k-section headline="Stats">
							<k-stats :reports="reports" size="huge" class="k-system-info" />
						</k-section>
					</k-column>
					<k-column width="2/3">
						<k-section headline="How to: Use Stats in Blueprint">
							<k-text>
								The
								<k-link :to="plugin['link']"
									>{{ plugin['name'] }}</k-link
								>
								Plugin uses a <strong>site method</strong> to make the Steady
								reports available. Including the Stats in your blueprints is
								dead easy. For more information see the
								<a
									href="https://getkirby.com/docs/reference/panel/sections/stats#reports"
									>Kirby docs</a
								>.
							</k-text>
							<k-headline tag="h3">Example</k-headline>
<pre><k-code language="yml">sections:
  reportSection:
    type: stats
    label: Stats
    reports:
      - site.steady.reports("newsletter_subscribers")
      - site.steady.reports("members")
      - site.steady.reports("revenue")
</k-code></pre>
						</k-section>
					</k-column>
				</k-grid>
			</template>

			<!-- Tab: Widgets -->
			<template v-if="tab == 'widgets'">
				<k-grid variant="columns">
					<k-column width="2/3">
						<k-section headline="State of Widgets">
							<k-stats
								:reports="widgets"
								size="medium"
								:class="{
									'k-stats-widgets': true,
									inactive: !widgetsEnabled,
								}"
							/>
							<k-text
								>This section displays the
								<strong>current configuration state</strong> of the kirby-steady
								plugin option('soerenengels.steady.widget') and the individual widget
								settings in the Steady backend.</k-text
							>

							<k-box theme="notice" v-if="widgetsEnabled && widgetWarning"
								>Widgets are activated in Kirbys config.php, but none is
								activated in the Steady backend.
							</k-box>
							<k-box theme="notice" v-if="!widgetsEnabled && widgetWarning"
								>Widgets are disabled in Kirby, but activated in the Steady
								backend.</k-box
							>
						</k-section>
					</k-column>
					<k-column width="1/3">
						<k-section headline="How to: Activate the Widgets">
							<k-text
								>Click on the Widgets state to enable or disable the Widget in
								the Steady Backend.</k-text
							>
							<k-headline>Config</k-headline>
							<k-text
								>Include the `components/steady/widget`-Snippet in your websites
								head. Change the kirby config.php file to activate the snippet
								an enable the use of Steady Widgets.</k-text
							>
							<k-headline>Paywall</k-headline>
							<k-text
								>You can use the Steady: Paywall $block to enable the paywall on
								a certain webpage.</k-text
							>
							<k-headline>Adblock Detection</k-headline>
							<k-text
								>If the adblock detection is enabled in the Steady Backend and
								the Widget option is configured, the adblock detection is
								active.</k-text
							>
							<k-headline>Floating Button</k-headline>
							<k-text
								>If the floating button is enabled in the Steady Backend and the
								Widget option is configured, the floating button should be
								visible.</k-text
							>
							<k-headline>Checkout</k-headline>
							<k-text
								>Explainer on how to activate and use the Widgets (Backend,
								Config, JS, Blocks)</k-text
							>
						</k-section>
					</k-column>
				</k-grid>
			</template>

			<!-- Tab: API -->
			<template v-if="tab == 'api'">
				<k-headline>$steady</k-headline>
				<k-text
					>You can access following mehthods from the $steady object.</k-text
				>
				<k-code language="php">$steady = $site->steady();</k-code>
				<k-tabs :tabs="apiTabs" :tab="subtab" />

				<!-- Tab: API / Publication-->
				<template v-if="subtab == 'publication'">
					<k-grid variant="columns">
						<k-column width="1/3">
							<k-section headline="$steady->publication()">
								<k-text>
									Returns the
									<a
										href="https://github.com/soerenengels/kirby-steady/blob/main/classes/Steady/Publication.php"
										>\Soerenengels\Steady\Publication</a
									>
									Class.<br />Gives access to the following readonly properties.
								</k-text>
							</k-section>
						</k-column>
						<k-column width="2/3">
							<k-section headline="Example">
								<k-code language="json">{{ publication }}</k-code>
							</k-section>
						</k-column>
					</k-grid>
				</template>

				<!-- Tab: API / Plans-->
				<template v-if="subtab == 'plans'">
					<k-grid variant="columns">
						<k-column width="1/3">
							<k-section headline="$steady->plans()">
								<k-text>
									Returns the
									<a
										href="https://github.com/soerenengels/kirby-steady/blob/main/classes/Steady/Plans.php"
										>\Soerenengels\Steady\Plans</a
									>
									Class.<br /><br>
								</k-text>
								<k-text>
									Methods:
									<ul>
										<li><a href="#example-plans-count">count()</a>: int</li>
										<li>filter(\Closure $filter): Plans</li>
										<li>
											<a href="#example-plans-find">find(string $id)</a> ?Plan
										</li>
										<li>
											<k-link to="#example-plans-liste">list()</k-link>: Plan[]
										</li>
									</ul>
								</k-text>
							</k-section>
						</k-column>
						<k-column width="2/3">
							<k-section headline="Example: $steady->plans->count()">

								<k-code language="json">{{ plans.length }}</k-code>
								<k-headline tag="h3" id="example-plans-list"
									>Example: $steady->plans->list()</k-headline
								>
								<k-code language="json">{{ plans }}</k-code>
								<template v-if="plans.length">
									<k-headline tag="h3" id="example-plans-find"
										>Example: $steady->plans->find("{{
											plans[0].id
										}}")</k-headline
									>
									<k-code language="json">{{ plans[0] }} </k-code></template
								>
							</k-section>
						</k-column>
					</k-grid>
				</template>

				<!-- Tab: API / Subscriptions-->
				<template v-if="subtab == 'subscriptions'">
					<k-grid variant="columns">
						<k-column width="1/3">
							<k-section headline="$steady->subscriptions()">
								<k-text>
									Returns the
									<a
										href="https://github.com/soerenengels/kirby-steady/blob/main/classes/Steady/Subscriptions.php"
										>\Soerenengels\Steady\Subscriptions</a
									>
									Class.<br /><br>
								</k-text>
								<k-text>
									Methods:
									<ul>
										<li><a href="#example-subscriptions-count">count()</a>: int</li>
										<li>filter(\Closure $filter): Subscriptions</li>
										<li>
											<a href="#example-subscriptions-find">find(string $id)</a> ?Subscription
										</li>
										<li>
											<k-link to="#example-subscriptions-liste">list()</k-link>: Subscription[]
										</li>
									</ul>
								</k-text>
							</k-section>
						</k-column>
						<k-column width="2/3">
							<k-section headline="Example: $steady->subscriptions->count()">
								<k-code language="json">{{ subscriptions }}</k-code>
							</k-section>
							<k-section headline="Example: $steady->subscriptions->filter()">
								<k-code language="json">{{ subscriptions }}</k-code>
							</k-section>
							<k-section headline="Example: $steady->subscriptions->find()">
								<k-code language="json">{{ subscriptions }}</k-code>
							</k-section>
							<k-section headline="Example: $steady->subscriptions->list()">
								<k-code language="json">{{ subscriptions }}</k-code>
							</k-section>
						</k-column>
					</k-grid>
				</template>

				<!-- Tab: API / Newsletter Subscribers -->
				<template v-if="subtab == 'subscribers'">
					<k-grid variant="columns">
						<k-column width="1/3">
							<k-section headline="$steady->newsletter_subscribers()->list()">
								<k-text>You see a random sample of your newsletter subscribers.</k-text>
							</k-section>
						</k-column>
						<k-column width="2/3">
							<k-section headline="Example">
								<k-code language="json">{{ newsletterSubscribers }}</k-code>
							</k-section>
						</k-column>
					</k-grid>
				</template>

				<!-- Tab: API / Report-->
				<template v-if="subtab == 'report'">
					<k-grid variant="columns">
						<k-column width="1/3">
							<k-section headline="$steady->report(string $id): ?array">
								<k-text
									>Use 'revenue', 'newsletter_subscribers' or 'members' as $id
									to return a report array for a stats section.</k-text
								></k-section
							>
						</k-column>
						<k-column width="2/3">
							<k-section headline="Example">
								<k-code language="json">{{ reports }}</k-code>
							</k-section>
						</k-column>
					</k-grid>
				</template>

				<!--
					<k-code language="html"><p>Test</p></k-code>
				<k-code language="php"><?php echo "test"?></k-code> -->
			</template>

			<!-- Tab: OAUTH -->
			<template v-if="tab == 'oauth'">
				<k-grid variant="columns">
					<k-column width="1/2">
						<k-section headline="Setup">
							<k-text>If you want to use the OAuth feature of the kirby-steady plugin, you need to get the OAuth credentials from the Steady backend and add them to your sites config file.</k-text>
							<k-code language="php"
								><?php return [ 'soerenengels.steady.oauth' => [ 'key' => '...',
								'secret' => '...' ] ];</k-code
							>
						</k-section>
					</k-column>
					<k-column width="1/2">
						<k-section headline="User Flow">
							<k-text
								><ol>
									<li>To use the Steady OAuth Flow, the user needs to visit the
								`/oauth/steady/authorize` route. At this route a session cookie
								gets stored and the user will be redirected to steady, to
								authorize.</li>
								<li>When the user authorized the application, they will be redirected to the `/oauth/steady/callback` route. The verification string will be checked and the access token will be stored.</li>
								</ol>
							</k-text>
						</k-section>
					</k-column>
					<k-column width="1/2">
						<k-section headline="$steady->oauth->user()">
							<k-code language="json">{ user }</k-code>
						</k-section>
					</k-column>
					<k-column width="1/2">
						<k-section headline="$steady->oauth->subscription()">
							<k-code language="json">{ subscription }</k-code>
						</k-section>
					</k-column>
				</k-grid>
			</template>
		</k-view>
	</k-panel-inside>
</template>

<script>
export default {
	props: {
		reports: Array,
		widgets: Array,
		widgetsEnabled: Boolean,
		widgetsWarning: Boolean,
		publication: Object,
		plans: Object,
		subscriptions: Object,
		newsletterSubscribers: Object,
		tab: String,
		subtab: String,
		reports: Array,
		plugin: Object
	},
	data() {
		return {
			baseUrl: "/steady/api",
			tabs: [
				{
					name: "stats",
					label: "Stats",
					icon: "chart",
					link: "steady/stats",
				},
				{
					name: "widgets",
					label: "Widgets",
					icon: "dashboard",
					link: "steady/widgets",
				},
				{
					name: "api",
					label: "API $steady",
					icon: "code",
					link: "steady/api?tab=publication",
				},
				{
					name: "oauth",
					label: "API $oauth",
					icon: "code",
					link: "steady/oauth",
				},
			],
		};
	},
	computed: {
		apiTabs() {
			return [
				{
					name: "publication",
					label: "->publication()",
					icon: "page",
					link: this.baseUrl + "?tab=publication",
				},
				{
					name: "plans",
					label: "->plans()",
					icon: "store",
					link: this.baseUrl + "?tab=plans",
				},
				{
					name: "subscriptions",
					label: "->subscriptions()",
					icon: "money",
					link: this.baseUrl + "?tab=subscriptions",
				},
				{
					name: "subscribers",
					label: "->newsletter_subscribers()",
					icon: "users",
					link: this.baseUrl + "?tab=subscribers",
				},
				{
					name: "report",
					label: "->report($id)",
					icon: "grid-top-left",
					link: this.baseUrl + "?tab=report",
				},
			];
		},
	},
	methods: {},
};
</script>

<style lang="postcss">
.k-stats-widgets {
	padding-block: var(--spacing-4);
	&.inactive .k-stat:not(:first-child) {
		opacity: 0.5;
	}
}
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
</style>

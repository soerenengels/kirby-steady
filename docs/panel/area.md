# Area

The Steady area gives you a simple overview of your Steady stats. It displays all Steady [Reports](/panel/reports) by default, shows you which [Widgets](#widgets) are activated and gives you access to your Steady users.

::: info ☝️ Info
You have also the option to [hide the panel area or individual tabs](/get-started/config).
:::

## Insights

![Steady Panel Area: Insights Tab](/assets/screenshot-steady-insights.png)

...

## Widgets

Widgets are disabled by default. To use the Steady integrations, you have to insert the Steady snippet in your websites head element and activate the widget option of the plugin.

:::code-group
```php [template.php]
// ...
```
```php [config.php]
// ...
```
:::

![Steady Panel Area: Widgets Tab](/assets/screenshot-steady-widgets.png)

<!-- <k-column width="1/3">
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
					</k-column> -->

## Users

The Users tab gives you access to your newsletter subscribers and members. You can cancel subscriptions for your newsletter subscribers from within the panel.

![Steady Panel Area: Users Tab](/assets/screenshot-steady-users-members.png)

## Debug

When your Kirby `debug` option is set to true, you have access to the debug tab in the Steady area.

![Steady Panel Area: Debug Tab](/assets/screenshot-steady-debug-plans.png)

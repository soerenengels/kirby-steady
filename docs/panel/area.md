# Area

The Steady area gives you a simple overview of your Steady stats. It displays all Steady [Reports](/panel/reports) by default, shows you which [Widgets](#widgets) are activated and gives you access to your Steady users.

::: info ☝️ Info
You have also the option to [hide the panel area or individual tabs](/get-started/config).
:::

## Insights

![Steady Panel Area: Insights Tab](/assets/screenshot-steady-insights.png)

The Insights tab displays three reports:

1. **Newsletter Subscribers** — site.steady.report("members")
2. **Members** — site.steady.report("newsletter_subscribers")
3. **Revenue** — site.steady.report("revenue")

:::tip ☝️ Tip
You can also use the [reports in your own stats section](/panel/reports).
:::

## Widgets

Widgets are disabled by default. To use the Steady integrations, you have to insert the Steady snippet in your websites head element and activate the widget option of the plugin.

:::code-group
```php [template.php]
<head>
<?= steady()->widgets()->snippet() ?>
</head>
```
```php [config.php]
return [
  'options' => [
    // Indicate, that you use the Steady Integrations on your website.
    'soerenengels.steady.widget' => true 
  ]
];
```
:::

![Steady Panel Area: Widgets Tab](/assets/screenshot-steady-widgets.png)

The Widgets tab reflects the current configuration of your Widgets in the Steady Backend and on your site. You will be redirected to the widgets Steady settings, when you click on the corresponding stat and are logged in into Steady.

## Users

The Users tab gives you access to your newsletter subscribers and members. You can cancel subscriptions for your members from within the panel.

![Steady Panel Area: Users Tab](/assets/screenshot-steady-users-members.png)

## Debug

When your Kirby `debug` option is set to true, you have access to the debug tab in the Steady area. It gives you insights into the data retrieved from the Steady API for debugging purposes.

![Steady Panel Area: Debug Tab](/assets/screenshot-steady-debug-plans.png)

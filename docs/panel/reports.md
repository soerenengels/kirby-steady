---
outline: deep
---
# Reports

Simply add Steady reports to your panel via a the stats section or as individual reports.  
Following reports are available:

- Members
- Newsletter Subscriptions
- Monthly Revenue

This Plugin uses a **`$site` method** to make the Steady reports available. Including the Stats in your blueprints is dead easy. For more information see the [Kirby docs](https://getkirby.com/docs/reference/panel/sections/stats#reports).

## Examples

You have different options to access your Steady information in the panel.

### Section

Add a Steady reports section to your blueprint:

```yml
section:
  steady: sections/steady
```

### Report

```yml{6-12}
section:
  steady_section:
    type: stats
    label: My Custom Steady reports
    size: huge
    reports:
      # For total members report
      - site.steady.report('members')
      # For newsletter subscribers report
      - site.steady.report('newsletter_subscribers')
      # For monthly revenue report
      - site.steady.report('revenue')
```

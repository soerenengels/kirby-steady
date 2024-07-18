# Paywall

To use the Steady Paywall block, you need to **activate it** (1) in your Publications Steady settings (Integrations > Steady Paywall), **add it** (2) to your blocks fieldsets **and integrate** (3) the Steady Javascript Widget in your websites `<head>...</head>`.

## Example: Add Paywall to your blocks fieldsets

```yml
sections:
  content:
    fields:
      text:
        # ...
        type: blocks
        fieldsets:
          - steady_paywall
          - ...
```

```php
// /site/config/config.php
return [
  'soerenengels.kirby-steady.widget' => true,
]
```

::: tip
Besides using the Paywall $block, you can also insert the Paywall with the `(paywall:)` KirbyTag.

:::

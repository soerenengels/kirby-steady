# Paywall

To use the Steady Paywall block, you need to 

1. **activate it** (1) in your [Publications Steady settings](https://steadyhq.com/de/backend/publications/default/integrations/paywall/edit) (Integrations > Steady Paywall),
2. **add it** (2) to your blocks fieldsets in your blueprint 
3. **and integrate** (3) the Steady Javascript Widget in your websites `<head>...</head>`.

## Example

:::code-group
```yml [Blueprint]
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

```php [Template]
<head>
<!-- ... -->
<?= steady()->widgets()->snippet() ?>
</head>
```

```php [config.php]
// /site/config/config.php
return [
  'soerenengels.kirby-steady.widget' => true,
]
```
:::

:::tip ☝️ Tip
Besides using the Paywall $block, you can also insert the Paywall with the `(paywall:)` KirbyTag.
:::

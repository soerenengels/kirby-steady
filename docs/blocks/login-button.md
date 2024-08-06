# Login with Steady

## Snippet: Login button

If you have ...

1. set the `soerenengels.steady.widget` option to `true` in the `config.php` [options](/get-started/config) and
2. injected the Steady Widget Loader into your sites `<head>` e.g. via calling `<?= steady()->widgets()->snippet() ?>`,

you can integrate the Steady login button on your website via a snippet.

:::code-group
```php [Default]
// Default language is English
// Default size is medium
<?php snippet('components/steady/login') ?>
```

```php [Customized]
<?php snippet('components/steady/login', [
  'size' => 'small'
  'language' => 'fr'
]) ?>
```
:::

Visitors, who are not already logged into your website, see this button. After they click it, a login page opens. Visitors, who are already logged in, can logout themselves with the same button.


::: warning 🚨 Warning
Do not use the Steady login button, if you implement the [OAuth2-Flow](/oauth/index) manually. The button is programmed, that it works together with the Steady Javascript widget and shall not be used with a manual OAuth flow, as Steady states.
:::

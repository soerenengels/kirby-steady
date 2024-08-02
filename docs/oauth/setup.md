# Setup your credentials

1. Activate the OAuth flow in the Steady backend and copy and paste your credentials into your Kirby config file.
2. Add your redirect uri in the Steady backend.

:::tip ☝️ Tip
You find your Steady OAuth credentials in the [Steady backend]().
:::

:::code-group
```php [config.php]
return [
  'options' => [
    'soerenengels.steady.oauth' => [
      'client' => [
        'id' => '',
        'secret' => ''
      ]
    ]
  ]
];
```
:::

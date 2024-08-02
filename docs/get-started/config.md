---
outline: deep
---

# Configure your plugin

This page displays the plugins config options.

::: code-group
```php [config.php]
<?php
return [
  'options' => [
    'soerenengels.steady' => [

      // Required: Steady REST API token
      'token' => null, // string|null

      // Indicate use of Steady widget JavaScript 
      'widget' => false, // bool

      // Optional: Show or hide tabs individually
      'panel' => [ // array or bool, default: true
        'insights' => true,
        'widgets' => false,
        'data' => false
      ],

      // Optional: OAuth settings
      'oauth' => [ // array or bool, default: false
        'client' => [
          'id' => '...',
          'secret' => '...',
        ],
        'redirect-uri' => site()->url() . '/oauth/steady/callback',
        'after-login' => site()->url(),
        'after-logout' => site()->url()
      ]
    ]
  ]
];
```

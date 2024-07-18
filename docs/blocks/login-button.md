# Login with Steady

## Snippet: Login button

If you have set the `widget` option to `true` in the `config.php` options, you can integrate the Steady login button on your website via a snippet.

Visitors, who have not logged in on your website already, see this button. After they click it, a login page opens. Visitors, who are logged in already, can logout themselves with the same button.

### Example: Add the Steady Login button to your Website

```php
// Default: $data = ['size' => 'medium', 'language' => 'en']
snippet('components/steady/login');

// Custom
snippet('components/steady/login', [
  'size' => 'small',
  'language' => 'de'
]);
```

**Warning:** Do not use the Steady login button, if you implement the OAuth2-Flow manually. The button is programmed, that it works together with the Steady Javascript widget and shall not be used with a manual OAuth flow, as Steady states.

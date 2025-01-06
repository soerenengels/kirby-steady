![Kirby x Steady](https://github.com/soerenengels/kirby-steady/blob/main/assets/kirby-steady-feature-preview.png?raw=true)

# Kirby 🤝 Steady

**[Kirby](https://getkirby.com/) meets [Steady](https://steadyhq.com/).** A plugin for Kirby `Version 5 and later` and `php >= 8.2`. Connect your Kirby site to your Steady publication. Request data for your publication, newsletter subscribers, members and plans from the Steady [API](https://developers.steadyhq.com/#rest).

## Features

- **Access the API:**  
Use the `steady()` helper or `$site->steady()` site method to **request publication, plans, subscriptions and newsletter subscribers** for use in templates, snippets or everywhere else
- **Steady Stats:**  
Stay up to date with Steady Reports in a Panel section
- **Display your Plans:**  
Add your Steady Plans as `$block` or `$snippet` to your Website.
- **Let your Content be worth it:**  
Add a Paywall `$block` to your articles or pages.
- **Adblock detection:**  
Activate Adblocker detection and display the Steady Adblock Overlay.
- **Member Login:**  
Let your members login to your Steady publication, e.g. to deactivate the paywall for them or for a special member section.
- **Steady Area:**  
Your Steady dashboard for stats, plugin configuration, subscribers and members.

## Get Started

### Intallation with Composer

Simply install the plugin with [composer](https://github.com/composer/composer).

```bash
composer require soerenengels/kirby-steady
```

### Quick setup

For basic usage, add your required [REST API-Key](https://steadyhq.com/backend/publications/default/integrations/api/edit) in your `config.php`. [Keep the key secure.](https://kirby-steady.soerenengels.de/get-started/install-setup.html#:~:text=%5D%0A%5D%3B-,%E2%98%9D%EF%B8%8F%20Tip,-You%20can%20use)

```php
return [
  'options' => [
    'soerenengels.steady.token' => '...',
  ]
];
```

## Documentation

Jump to the [documentation](https://kirby-steady.soerenengels.de/).

## More

### Available Translations

- English
- German

### Develop

Use PHPStan via `vendor/bin/phpstan` to check the code.

### License

MIT

### Credits

Sören Engels

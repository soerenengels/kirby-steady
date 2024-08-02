![Kirby x Steady](https://github.com/soerenengels/kirby-steady/blob/main/assets/kirby-steady-feature-preview.png?raw=true)

# Kirby 🤝 Steady

**[Kirby](https://getkirby.com/) meets [Steady](https://steadyhq.com/).** A plugin for Kirby `Version 4 and later` and `php >= 8.2`. Connect your Kirby site to your Steady publication. Request data for your publication, newsletter subscribers, members ans plans from the Steady [API](https://developers.steadyhq.com/#rest).

## Features

1. **Access the API:** Site method `$site->steady()` or `steady()`-helper to **request publication, plans, subscriptions and newsletter subscribers** for use in templates, snippets or everywhere else
2. **Steady reports:** Stay up to date with Steady Reports in a Panel section
3. **Display your Plans:** Add your Steady Plans as `$block` or `$snippet` to your Website.
4. **Let your Content be worth it:** Add a Paywall `$block` to your articles or pages.
5. **Adblock detection:** Activate Adblocker detection and display the Steady Adblock Overlay.
6. **Member Login:** Let your members login to your Steady publication, to deactivate the paywall for them.
7. **Hidden Steady Area:** Simple overview of Steady stats, Plugin configuration status and Plugin docs.

## Get Started

### Intallation with Composer

Simply install the plugin with [composer](https://github.com/composer/composer).

```bash
composer require soerenengels/kirby-steady
```

### Quick setup

For basic usage, add your required [REST API-Key](https://steadyhq.com/backend/publications/default/integrations/api/edit) in your `config.php`. Keep the key secure.

```php
return [
  'options' => [
    'soerenengels.steady' => [
      'token' => '...',
    ]
  ]
];
```

## Documentation

Jump to the [documentation](https://kirby-steady.soerenengels.de/).

## Features

<!-- TODO: ADD IMAGE: GIF -->

- **Stay on top of the things** with the Steady area: Look into your Publication stats, keep track of your Widgets and get an overview of the data from the `$steady`-API.
- **Mind the Numbers** with Steady reports: Add Steady reports to your panel via a the stats section or as individual reports. 
- **Display your plans** in your templates: Add a **Steady: Plans** `$block` by adding it to your fieldsets.
- **Steady paywall** as `$block`
- **Adblock detection**

## Outlook

- feature-checkout Integrate Steady Checkout with checkout_url, checkout_snippet and checkout_thanks

## Available Translations

- English
- German

## License

MIT

## Credits

Sören Engels

![Kirby x Steady](https://github.com/soerenengels/kirby-steady/blob/main/assets/kirby-steady-feature-preview.png?raw=true)

# Kirby x Steady

**[Kirby](https://getkirby.com/) meets [Steady](https://steadyhq.com/).** A plugin for Kirby `Version 4 and later` and `php >= 8.2`. Connect your Kirby site to your Steady publication. Request data for your publication from the [Steady API](https://developers.steadyhq.com/#rest).

## Features

1. **Access the API:** Site method `$site->steady()` or `steady()`-helper to **request publication, plans, subscriptions and newsletter subscribers** for use in templates, snippets or everywhere else
2. **Steady reports:** Stay up to date with Steady Reports in a Panel section
3. **Display your Plans:** Add your Steady Plans as `$block` or `$snippet` to your Website.
4. **Let your Content be worth it:** Add a Paywall `$block` to your articles or pages.
5. **Adblock detection:** Activate Adblocker detection and display the Steady Adblock Overlay.
6. **Member Login:** Let your members login to your Steady publication, to deactivate the paywall for them.
7. **Hidden Steady Area:** Simple overview of Steady stats, Plugin configuration status and Plugin docs.

## Installation

### Composer

Simply install the plugin with [composer](https://github.com/composer/composer).  
Follow the instructions in the [setup section](#setup).

```bash
composer require soerenengels/kirby-steady
```

### Download

[Download](https://github.com/soerenengels/kirby-steady/archive/refs/heads/main.zip), unzip and copy this repository to `/site/plugins/kirby-steady`. Follow the instructions in the [setup section](#setup).

### Git

```bash
git submodule add https://github.com/soerenengels/kirby-steady.git site/plugins/kirby-steady
```

### Setup

To use this plugin, add your required [REST API-Key](https://steadyhq.com/backend/publications/default/integrations/api/edit) in your `config.php`. Keep the key secure.

```php
return [
  // ...
  'options' => [
    'soerenengels.steady' => [
      'token' => '...', // Instead of ... use your API key
    ]
  ]
]
```

You also have following options in your `config.php`:

```php
return [
  'options' => [
    'soerenengels.steady' => [
      // OPTIONAL
      // Change to true, if you want to use login button, paywall, floating button or adblock detection.
      // If set to true, you also need to add the snippet('components/steady/widget') to your websites head
      'widget' => false
    ]
  ]
]
```

## Documentation

### Site method: $site->steady()

The site method `$site->steady()` or the Steady helper function `steady()` give you access to the [`Steady` Class](https://github.com/soerenengels/kirby-steady/blob/main/classes/Steady.php) and its following methods:

- `->publication()`
- `->plans()`
- `->subscriptions()`
- `->newsletter_subscribers()`
- `->report($id)`
- `->widgets()`

#### `$steady->widgets()`

The `widgets()` method gives you access to an Widgets object. By calling `->adblock()`, `->floatingButton()` or `paywall()` you have further access to the `isActive()` method. It returns a boolean, if the plugins 'widget' option is set to true AND the widget is activated in the Steady backend.

### Panel: Steady-Area

<!-- ADD IMAGE: GIF -->

Behind the panel route `/panel/steady` you'll find the hidden Steady panel area. In the tabs you find the available stats about you Steady publication, the status of the widgets in Steady backend aswell as the plugins config setting and an overview over the `$steady`-API and its available methods.

### Panel: Steady-Reports

Simply add Steady reports to your panel via a the stats section or as individual reports.  
Following reports are available:

- Members
- Newsletter Subscriptions
- Monthly Revenue

#### Example: Usage of the Steady Reports in a custom stats section

```yml
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

### $block Plans

You can simply add a **Steady: Plans** `$block` by adding it to your fieldsets.

You can use the predefined Snippets in `/snippets/components/steady/*.php` to render following components:

- All public Plans
- A single plan by `$id`

#### Example: Add Plans $block to your fieldsets

```yml
sections:
  content:
    fields:
      text:
        # ...
        type: blocks
        fieldsets:
          - steady_plans
          - ...
```

#### Example: Display all plans or a single plan via a `$snippet`

```php
// Display all plans
snippet('components/steady/plans', [
  'plans' => $steady->plans()->plans // array of Plan objects
]);
```

```php
// Display a single plan by id
snippet('components/steady/plan', [
  'plan' => $steady->plans()->find($id) // Plan object
]);
```

#### Style your plans

The styling of the plans is up to you. For the HTML markup structure and classes see [`/snippets/components/steady/plans.php`](https://github.com/soerenengels/kirby-steady/blob/main/snippets/components/steady/plans.php) and [`/snippets/components/steady/plan.php`](https://github.com/soerenengels/kirby-steady/blob/main/snippets/components/steady/plan.php). If you want to change the markup of the plans, you can overwrite those components by creating a new file in `/site/snippets/components/steady/{name-of-file-you-want-to-overwrite}.php`.

### Steady-Paywall

To use the Steady Paywall block, you need to **activate it** (1) in your Publications Steady settings (Integrations > Steady Paywall), **add it** (2) to your blocks fieldsets **and integrate** (3) the Steady Javascript Widget in your websites `<head>...</head>`.

#### Example: Add Paywall to your blocks fieldsets

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

### Adblock detection

If you have set the `widget` option to `true` in the `config.php` options, you can configure the Steady Adblock detection at your publication settings on the Steady website.

### Snippet: Login button

If you have set the `widget` option to `true` in the `config.php` options, you can integrate the Steady login button on your website via a snippet.

Visitors, who have not logged in on your website already, see this button. After they click it, a login page opens. Visitors, who are logged in already, can logout themselves with the same button.

#### Example: Add the Steady Login button to your Website

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

## Outlook

- fix-cache: Privacy by Design: Try to prevent saving unnecessary data in cache.
- feature-checkout Integrate Steady Checkout with checkout_url, checkout_snippet and checkout_thanks
- feature-oauth: see <https://github.com/oliverschloebe/oauth2-steadyhq>, connect steady users to kirby users
- feature-webhook: Webhook for new Steady Subscriptions

## Available Translations

- English
- German

## License

MIT

## Credits

Sören Engels

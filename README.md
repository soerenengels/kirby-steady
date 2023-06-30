![Kirby x Steady](https://github.com/soerenengels/kirby-steady/blob/main/assets/kirby-steady-feature-preview.png?raw=true)

# Kirby x Steady

A Steady plugin for Kirby `Version 4 and later` to connect your Kirby instance to your Steady publication. Request data for your publication from the [Steady API](https://developers.steadyhq.com/#rest).

## Features

1. Display Steady reports in a Panel section
2. Site method `$site->steady()` or helper `steady()` to request publication, plans, subscriptions and newsletter subscribers for use in templates, snippets or everywhere else
3. $block: Steady Paywall
4. $snippet: Login Button
5. $snippet: Adblock detection

## Todo

- fix-cache: Privacy by Design: do not save emails in cache if not necessary
- feature-block-plans: Add plans via Block
- fix-paywall: add link to steady edit page
- feature-oauth: see <https://github.com/oliverschloebe/oauth2-steadyhq>, connect steady users to kirby users
- feature-webhook: Webhook for new Steady Subscriptions

## Installation

- composer
- git
- zip

### Setup

You need to add your API-KEY.
You have following options in your `config.php`:

```php
return [
  'options' => [
    'soerenengels.steady' => [
      'api-token' => '...',
      'widget' => false, // change to true, if you want to use login, paywall, sticky button or adblock detection
      'cache-name' => 'steady-api', // optional: change cache name
    ]
  ]
]
```

## Documentation

### Site-Method: $site->steady()

The site method `$site->steady()` or the Steady helper function `steady()` give you access to the Steady Class and its following methods:

- `->publication()`
- `->plans()`
- `->subscriptions()`
- `->newsletter_subscribers()`
- `->report($id)`

### Panel: Steady-Reports

Simply add Steady reports to your panel via a the stats section or as individual reports.

Following reports are available:

- Members
- Newsletter Subscriptions
- Monthly Revenue

```yml
section:
  steady_section:
    type: stats
    label: My Steady reports
    size: huge
    reports:
      - site.steady.report('members') # For total members report
      - site.steady.report('newsletter_subscribers') # For newsletter subscribers report
      - site.steady.report('revenue') # For monthly revenue report
```

### Snippet: Plans

You can use the predefined Snippets in `/snippets/components/steady/...` to render following components:

- All Plans
- A single plan

Example:

```php
snippet('components/steady/plans', [
  'plans' => $steady->plans() // Plans object
]);
```

```php
snippet('components/steady/plan', [
  'plan' => $steady->plans()->find($id) // Plan object
]);
```

Or you just take some inspiration.

### Steady-Paywall

To use the Steady Paywall block, you need to activate it in you Steady settings (Integrations > Steady Paywall) and add it to your blocks fieldsets.

Example:

```yml
sections:
  content:
    fields:
      text:
        # ...
        type: blocks
        fieldsets:
          - steady/paywall
          - ...
```

### Adblock detection

If you have set the `widget` option to `true` in the `config.php` options, you can configure the Steady Adblock detection at your publication settings on the Steady website.

### Snippet: Login button

If you have set the `widget` option to `true` in the `config.php` options, you can integrate the Steady login button on your website via a snippet.

Visitors, who have not logged in on your website already, see this button. After they click it, a login page opens. Visitors, who are logged in already, can logout themselves with the same button.

Example

```php
// Default (size: medium, language: en)
snippet('components/steady/login');

// Custom
snippet('components/steady/login', [
  'size' => 'small',
  'language' => 'fr'
]);
```

**Achtung:** Benutze den Button nicht, wenn du den OAuth2-Flow manuell implementierst. Der Button ist so programmiert, dass er automatisch mit unserem Javascript-Widget arbeitet und somit nicht für den manuellen Flow verwendet werden kann.

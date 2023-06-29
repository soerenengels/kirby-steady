# Kirby meets Steady

A Steady plugin for Kirby `Version 4 and later` to request data for your publication from the Steady API.

What ist Steady?
What is Kirby?

## Features

- Display Steady Reports in your Panel
- Site method `->steady()` or helper `steady()` to request publication, plans, subscriptions and newsletter subscribers for use in templates, snippets or everywhere else
- Steady Paywall Block

## Todo

- OAUTH, see <https://github.com/oliverschloebe/oauth2-steadyhq>
- Opiniated Steady Panel Area
- connect steady users to kirby users
- do not save emails in cache if not necessary
- Webhook for new Steady Subscriptions

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

		]
	]
]
```

## Documentation

### Site-Method: $site->steady()

The site method `$site->steady()` gives you access to the SteadyApi Class and its following methods:

- `->publication()`
- `->plans()`
- `->subscriptions()`
- `->newsletter_subscribers()`

### Panel: Steady-Reports

Simply add Steady reports to your panel via a the stats section or as individual reports.

Following reports are available:

- Newsletter Subscriptions
- Total Members

```yml
section:
  steady_section:
    type: stats
    label: My Steady reports
    size: huge
    reports:
      - reportname
      - reportname 2

```

### Snippets

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

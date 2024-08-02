---
outline: deep
---

# Installation & Setup

## Installation

### Composer

The recommended method to install this plugin is with [Composer](https://github.com/composer/composer):

```bash
composer require soerenengels/kirby-steady
```

#### Alternative: Zip

If you prefer to install your plugins via a zip file, simply [download](https://github.com/soerenengels/kirby-steady/archive/refs/heads/main.zip) the plugin and unzip its content to `/site/plugins/steady`.

#### Alternative: Git

You can also add this plugin as git submodule:

```bash
git submodule add https://github.com/soerenengels/kirby-steady.git site/plugins/steady
```

## Setup

To use this plugin, you need to add your Steady [REST API-Key](https://steadyhq.com/backend/publications/default/integrations/api/edit) in your `config.php`. Keep the key secure.

::: code-group
```php{5} [config.php]
<?php
return [
  // ...
  'options' => [
    'soerenengels.steady.token' => '...', // Insert your API key
  ]
];
```
:::

:::tip ☝️ Tip
You can use the [kirby3-dotenv](https://github.com/bnomei/kirby3-dotenv) Plugin by [bnomei](https://github.com/bnomei/) and an `.env` file to keep secrets, such as your REST API-Key, out of your `config.php` and version control.
:::

When you sucessfully finished the setup you should see a new **Steady** link in your [panel](/panel/area) navigation.

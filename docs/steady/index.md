# API Wrapper

The *Kirby way* to do things in PHP is to chain methods. We embrace this approach and enable you, to access your Steady data as easy as possible.

```php
<?php
// Choose the way you prefer
$steady = $site->steady();
$steady = steady();
```

The **site method** `$site->steady()` or **helper function** `steady()` give you access to the [`Steady`](https://github.com/soerenengels/kirby-steady/blob/main/classes/Steady/Steady.php) Class and its following methods:

- [`$steady->publication()`](/steady/publication)
- [`$steady->plans()`](/steady/plans)
- [`$steady->subscriptions()`](/steady/subscriptions)
- [`$steady->newsletter_subscribers()`](/steady/newsletter-subscribers)
- [`$steady->report($id)`](/steady/reports)
- [`$steady->widgets()`](/steady/widgets)
- [`$steady->oauth()`](/oauth)

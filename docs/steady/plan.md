# Plans

The [Plan](https://github.com/soerenengels/kirby-steady/blob/main/classes/Steady/Plan.php) class represents a single Steady Plan as requested via the [Steady REST API](https://developers.steadyhq.com/#plans).

Attribute names are transformed from hyphen to underscore and can be accessed via method calls.

```php
$plan = steady()->plans()->first();
```

### Example

```php [Snippet]
$plan = steady()->plans()->find('');

snippet('components/steady/plan', ['plan' => $plan]);
```

## Methods

- Default methods
- high_res_image_url(): string

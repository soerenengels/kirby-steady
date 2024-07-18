# Plans

The Plans class represents your Steady plans as requested via the [Steady REST API](https://developers.steadyhq.com/#plans).

```php
$plans = steady()->plans();
```

Returns the [\Soerenengels\Steady\Plans]() Class.

## Methods

- count(): int
- filter(\Closure $filter): Plans
- find(string $id): ?Plan
- list(): Plan[]

### Examples

:::code-group
```php [count()]
$plans = steady()->plans();

$totalPlans = $plans->count();
```

```php [list()]
$plans = steady()->plans();

foreach($plans->list() as $plan):
  echo $plan->name;
endforeach;
```
:::

## Plan

...

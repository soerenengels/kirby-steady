# Plans

The [Plans](https://github.com/soerenengels/kirby-steady/blob/main/classes/Steady/Plans.php) class represents your Steady Plans as requested via the [Steady REST API](https://developers.steadyhq.com/#plans).

```php
$plans = steady()->plans();
```

## Methods

- count(): int
- filter(\Closure $filter): Plans
- find(string $id): ?Plan
- list(): Plan[]

### Examples

:::code-group
```php [count()]
$plans = steady()->plans();

$totalPlans = $plans->count(); // e.g. 3
```

```php [filter()]
$plans = steady()->plans();
$plans = $plans->filter(fn($plan) {
  return $plan->state() === 'published';
});

foreach($plans as $plan):
  echo $plan->name();
endforeach;
```

```php [find()]
$plans = steady()->plans();

$plan = $plans->find('b9d7574f-5246-4c94-ade5-1d4e9b169afc');
```
:::

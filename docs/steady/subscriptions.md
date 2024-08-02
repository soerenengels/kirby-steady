# Subscriptions

The [Subscriptions](https://github.com/soerenengels/kirby-steady/blob/main/classes/Steady/Subscriptions.php) class represents your Steady Subscriptions from members.

```php
$subscriptions = steady()->subcriptions();
```
  
## Methods

- <a href="https://github.com/soerenengels/kirby-steady/blob/main/classes/Steady/CountTrait.php">count()</a>: int
- <a href="https://github.com/soerenengels/kirby-steady/blob/main/classes/Steady/FilterTrait.php">filter(\Closure $filter)</a>: Subscriptions
- <a href="https://github.com/soerenengels/kirby-steady/blob/main/classes/Steady/FindTrait.php">find(string $id)</a>: ?Subscription
- <a href="https://github.com/soerenengels/kirby-steady/blob/main/classes/Steady/hasItems.php">list()</a>: Subscription[]

### Examples

:::code-group
```php [count()]
$total = steady()->subscriptions()->count();
```

```php [filter()]
$filteredSubscriptions = steady()->subscriptions()->filter(function ($subscription) {
  return $subscription->monthly_amount < 500;
});
```

```php [find()]
$subscription = steady()->subscriptions()->find('5e74bbc1-bf88-47a6-a357-f635dbd3f948');
```

```php [foreach]
foreach (steady()->subscriptions() as $subscription) {
  echo $subscription->subscriber()->fullname();
}
```
:::

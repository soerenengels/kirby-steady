# Subscription

The [Subscription](https://github.com/soerenengels/kirby-steady/blob/main/classes/Steady/Subscription.php) class represents a single Steady Subscription from a member.

```php
$subscription = steady()->subcriptions()->first();
```
  
## Methods

- cancel(): void
- subscriber(): array|null
- gifter(): array|null
- plan(): array|null
<!-- - subscriber(): \Soerenengels\Steady\User|null
- gifter(): \Soerenengels\Steady\User|null
- plan(): \Soerenengels\Steady\Plan|null -->

### Examples

:::code-group
```php [subscriber()]
$subscription = steady()->subscriptions()->find('8ef509c7-b8fe-4a56-a366-fadf030bfc64');
$subscriber = $subcription->subscriber();
```

```php [gifter()]
$subscription = steady()->subscriptions()->find('8ef509c7-b8fe-4a56-a366-fadf030bfc64');
$subscriptionGifter = $subcription->gifter();
```

```php [plan()]
$subscription = steady()->subscriptions()->find('8ef509c7-b8fe-4a56-a366-fadf030bfc64');
$subscribedPlan = $subcription->plan();
```

```php [cancel()]
$subscription = steady()->subscriptions()->find('8ef509c7-b8fe-4a56-a366-fadf030bfc64');
$subcription->cancel();
```
:::

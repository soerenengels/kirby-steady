# Users

The [Users](https://github.com/soerenengels/kirby-steady/blob/main/classes/Steady/Users.php) class represents your Steady newsletter subscibers.

```php
$newsletterSubscribers = steady()->newsletterSubscribers();
```

## Methods

- count(): int
- find(): \Soerenengels\Steady\User|null
- filter(\Closure $filter): \Soerenengels\Steady\Users

### Examples

:::code-group
```php [test]
test
```
:::

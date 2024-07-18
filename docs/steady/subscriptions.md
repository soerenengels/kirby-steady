# Subscriptions

The Subscriptions class represents your Steady subscriptions from members.

Returns the <a href="https://github.com/soerenengels/kirby-steady/blob/main/classes/Steady/Subscriptions.php">\Soerenengels\Steady\Subscriptions</a>
	Class.
  
## Methods

- <a href="https://github.com/soerenengels/kirby-steady/blob/main/classes/Steady/CountTrait.php">count()</a>: int
-<a href="https://github.com/soerenengels/kirby-steady/blob/main/classes/Steady/FilterTrait.php">filter(\Closure $filter)</a>: Subscriptions
- <a href="https://github.com/soerenengels/kirby-steady/blob/main/classes/Steady/FindTrait.php">find(string $id)</a>: ?Subscription
- <a href="https://github.com/soerenengels/kirby-steady/blob/main/classes/Steady/hasItems.php">list()</a>: Subscription[]

Example: $steady->subscriptions->count()
Example: $steady->subscriptions->filter(fn($sub) => $sub->monthly_amount < 500)
Example: $steady->subscriptions->find('5e74bbc1-bf88-47a6-a357-f635dbd3f948')
Example: $steady->subscriptions->list()

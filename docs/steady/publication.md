# Publication

The [Publication](https://github.com/soerenengels/kirby-steady/blob/main/classes/Steady/Publication.php) class represents your Steady Publication as requested via the [Steady REST API](https://developers.steadyhq.com/#publication).

Attribute names are transformed from hyphen to underscore and can be accessed via method calls.

## Examples

:::code-group
```php [template.php]
$publication = steady()->publication();

$title = $publication->title();
$editor = $publication->editor_name();
$members = $publication->members_count();
$monthlyRevenue = $publication->monthly_amount() / 100;
```
:::

:::tip ☝️ Tip
For all available methods, look into the [source file](https://github.com/soerenengels/kirby-steady/blob/main/classes/Steady/Publication.php) or use the type hints on the Publication object.
:::

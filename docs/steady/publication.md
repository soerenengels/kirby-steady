# Publication

The Publication class represents your Steady publication as requested via the [Steady REST API](https://developers.steadyhq.com/#publication).

## Examples

:::code-group
```php [template.php]
$publication = $site->steady()->publication();

$title = $publication->title;
$members = $publication->members_count;
$monthlyRevenue = $publication->monthly_amount / 100;
```
:::

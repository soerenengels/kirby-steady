# Widgets

The `$steady->widgets()` method gives you access to the [Widgets](https://github.com/soerenengels/kirby-steady/blob/main/classes/Steady/Widgets.php) object.

## Methods

- adblock(): Widget
- enabled(): bool
- static factory(): static
- filter(): static
- floatingButton(): Widget
- getWidgetLoaderContent(): string
- list(): array
- paywall(): Widget

### Examples

```php
$widgets = steady()->widgets();
```

By calling `->adblock()`, `->floatingButton()` or `paywall()` you have further access to the `isActive()` method. It returns a boolean, if the plugins 'widget' option is set to true AND the widget is activated in the Steady backend.

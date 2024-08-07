# Widgets

The `$steady->widgets()` method gives you access to the [Widgets](https://github.com/soerenengels/kirby-steady/blob/main/classes/Steady/Widgets.php) object. It is mostly used internally for the logic of the widgets panel tab.

## Methods

- adblock(): Widget
- floatingButton(): Widget
- paywall(): Widget
- static factory(): static
- enabled(): bool
- filter(): static
- getWidgetLoaderContent(): string
- list(): array

### Examples

```php
$widgets = steady()->widgets();
```

By calling `->adblock()`, `->floatingButton()` or `paywall()` you have further access to the `isActive()` method. It returns a boolean, if the plugins 'widget' option is set to true AND the widget is activated in the Steady backend.

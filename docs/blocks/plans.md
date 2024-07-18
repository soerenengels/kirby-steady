# Plans

You can simply add a **Steady: Plans** `$block` by adding it to your fieldsets.

You can use the predefined Snippets in `/snippets/components/steady/*.php` to render following components:

- All public Plans
- A single plan by `$id`

## Setup

### Example: Add Plans $block to your fieldsets

```yml
sections:
  content:
    fields:
      text:
        # ...
        type: blocks
        fieldsets:
          - steady_plans
          - ...
```

#### Example: Display all plans or a single plan via a `$snippet`

```php
// Display all plans
snippet('components/steady/plans', [
  'plans' => $steady->plans()->plans // array of Plan objects
]);
```

```php
// Display a single plan by id
snippet('components/steady/plan', [
  'plan' => $steady->plans()->find($id) // Plan object
]);
```

## Style your plans

The styling of the plans is up to you. For the HTML markup structure and classes see [`/snippets/components/steady/plans.php`](https://github.com/soerenengels/kirby-steady/blob/main/snippets/components/steady/plans.php) and [`/snippets/components/steady/plan.php`](https://github.com/soerenengels/kirby-steady/blob/main/snippets/components/steady/plan.php). If you want to change the markup of the plans, you can overwrite those components by creating a new file in `/site/snippets/components/steady/{name-of-file-you-want-to-overwrite}.php`.

### Styling

The styling of the plans is up to you. For the HTML markup structure and classes see [`/snippets/components/steady/plans.php`](https://github.com/soerenengels/kirby-steady/blob/main/snippets/components/steady/plans.php) and [`/snippets/components/steady/plan.php`](https://github.com/soerenengels/kirby-steady/blob/main/snippets/components/steady/plan.php). If you want to change the markup of the plans, you can overwrite those components by creating a new file in `/site/snippets/components/steady/{name-of-file-you-want-to-overwrite}.php`.

```css
.steady__plans {
	display: flex;
	gap: 1em;
	font-size: 0.8em;
}
.steady__plan.container {
	flex-basis: 14em;
	flex-grow: 1;
	flex-shrink: 0;
	display: grid;
	grid-template-areas:
		'name'
		'image'
		'benefits';
	grid-template-rows: auto auto 1fr auto;
}
.steady__plan.plan__benefits {
	margin-block: 2em;
	grid-area: benefits;
}
.steady__plan.plan__header {
	grid-area: name;
	font-weight: 900;
	font-size: calc(1em / 0.8);
}
.steady__plan.plan__image {
	grid-area: image;
}
```

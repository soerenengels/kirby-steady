# Changelog

All notable changes to this project will be documented in this file.  
Please consider this plugin as instable until 1.0 release.

## [0.4] OAuth & Docs – 2024-08-06

This plugin introduces OAuth functionality and docs.

### Add

- Create docs with VitePress
- Add OAuth functionality (see docs), including new options, routes, user blueprint
- Add permissions for individual tabs
- Enable `Subscription` `cancel()` method to unsubscribe
- Add `fullname()` method to `User`
- Add basic `sort()` method to `Plans`
- Add `high_res_image_url()` method to `Plan`
- Add `snippet()` method to `Widgets`
- Add `first()` and `last()` method to `CountTrait`
- Add `toArray()` method via `toArrayTrait`
- Create Changelog

### Refactor

- Use magic methods instead of public properties to mock kirby style for api requests
- Inline Steady JavaScript Widget Loader in `$snippet`
- Split up `SteadyView` into individual tab components
- Use KirbyTags `kt()` function for rendering of plan benefits
- Make classes `Plans`, `Subscriptions`, `Users` iterable
- Simplify colors and comparison of reports
- Hide debug tab and docs and github links in header when debug config is false
- Refactor `AccessToken` class for improved token handling
- Remove default styles for plans (see docs for an example)
- Add `toReports()` method to `Widgets`
- Use `isWarning()` method from `Widgets`

### Chore / Fix

- Enable Plans `$block` to be customized
- Add default values where translations seem not working on single language installations
- Fix display of correct annual amount of plans
- Add Github workflow for docs
- Improve `.gitattributes` to ignore unnecessary files when downloading zip
- Improve code quality, DocBlocks, comments and Readme
- Add Prettier config

## [0.3] Housekeeping - 2024-06-26

- move default panel area to /panel/steady
- change installer name to steady
- add composer autoloader for zip installs
- fix display of monthly revenue
- add $steady->reports() method
- fix deprecated `<k-inside>`
- improve examples in panel documentation
- update translations
- DRY: add traits

### [0.2] Introduce Panel Area - 2023-11-23

- add hidden /panel/steady/stats area
- introduce Widgets and Widget class
- refactor `$steady->widget()` to `$steady->widgets()`
- introduce `$steady->widgets()->adblock()`, `$steady->widgets()->checkout()`, `$steady->widgets()->floatingButtons()` and `$steady->widgets()->paywall()` methods
- remove NewsletterSubscriber and NewsletterSubscribers in favor of generic User and Users class

### [0.1.1] Initial Release - 2023-10-31

Kirby meets Steady: Work in Progress and Proof of Concept

**Features**

- Request Steady-API via $site->steady() or steady()-helper.
- Show Reports in Panel
- Add Steady Plans to your website as $block or $snippet
- Add Paywall via $block
- Add Widget via $snippet
- Add Steady Login via $snippet

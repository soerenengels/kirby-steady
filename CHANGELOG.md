# Changelog

All notable changes to this project will be documented in this file.

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

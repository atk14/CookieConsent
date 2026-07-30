# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## What this is

A Composer library (`atk14/cookie-consent`) that adds GDPR cookie consent management to ATK14 PHP applications. It is installed as a dependency into consuming ATK14 projects — it is **not** a standalone application. Development and testing must be done from inside a parent ATK14 app that has this package installed.

## Installation into an ATK14 project

```bash
composer require atk14/cookie-consent
```

Then symlink controllers, forms, views, models, helpers, router, migrations, and the JS file as detailed in README.md, then run `./scripts/migrate`.

## Running tests

Tests live in `src/test/models/tc_cookie_consent.php` and must be run from the consuming ATK14 project (after symlinking the test file into `test/models/`). ATK14's test runner is invoked from the project root:

```bash
./scripts/run_tests test/models/tc_cookie_consent.php   # single test file
```

The test fixture `src/test/fixtures/cookie_consent_categories.yml` must also be linked into the consuming project's `test/fixtures/`.

## Architecture

### Core data flow

1. **`CookieConsent`** (model, `src/app/models/cookie_consent.php`) — singleton DB row (id=1 enforced by CHECK constraint). Stores banner/dialog text. Translatable via the `Translatable` interface.

2. **`CookieConsentCategory`** (model, `src/app/models/cookie_consent_category.php`) — each category (e.g. `necessary`, `analytics`, `advertising`) has a `code`, `version`, `necessary` flag, and optional `cookies_regexp` for auto-deleting rejected cookies. Cached statically; use `GetActiveInstances()` for runtime logic.

3. **`CookieConsentSettings`** (lib, `src/lib/cookie_consent_settings.php`) — the core consent engine. Reads/writes a browser cookie named `consent` (base64-encoded JSON). Tracks per-category acceptance state. Entry point: `CookieConsent::GetSettings($request)`.

### Cookie format

The `consent` cookie stores base64(JSON) with compact keys:

| Key | Meaning |
|-----|---------|
| `c_v` | CookieConsent::VERSION |
| `c_t` | save timestamp |
| `all_a` | `"a"`=all accepted, `"r"`=all rejected, `""`=undecided |
| `all_t` | timestamp for all-accept/reject |
| `h` | HTTP host where saved |
| `cs` | map of `{code: {a, t, v}}` per category |

Per-category `a` values: `"a"` = accepted, `"r"` = rejected, `""` = undecided.

### Checking consent

**PHP:**
```php
if (CookieConsent::Accepted("advertising")) { ... }
```

**JavaScript:**
```js
if (window.UTILS.cookieConsent.accepted("advertising")) { ... }
```

The JS implementation (`src/public/scripts/utils/cookie_consent.js`) reads the same `consent` cookie directly from `document.cookie` and decodes it client-side.

### GTM / Google Consent Mode integration

`{cookie_consent_datalayer_command}` (Smarty helper, placed in `<head>`) outputs `gtag('consent', 'default', ...)` and `gtag('consent', 'update', ...)` calls. Category codes map to GTM Consent Mode API names via `CookieConsentSettings::$gtm_consent_equivalents` (e.g. `advertising` → `ad_storage`, `analytics` → `analytics_storage`). The `necessary` category has no GTM equivalent and is always treated as granted.

### Consent re-confirmation logic

`needsToBeConfirmed()` returns `true` if any non-necessary category has never been answered, or if the category's `version` in the DB has changed since the user last saved their preference. Increment a category's `version` in the admin when its purpose changes materially.

### Statistics export

`src/local_scripts/export_cookie_consent_statistics` parses `application.log` (and rotated `.log.N` / `.log.N.gz` files) for `cookie_consent_saved:` log entries and outputs CSV. Usage from the consuming project:

```bash
./local_scripts/export_cookie_consent_statistics [days] > export.csv
```

Default is 10 days of logs.

### Styles

Three stylesheet variants are provided — link the appropriate one into the consuming project:
- `_cookie_consent.bs5.scss` — Bootstrap 5
- `_cookie_consent.scss` — Bootstrap 4
- `cookie_consent.less` — Bootstrap 3

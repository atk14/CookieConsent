Cookie Consent
==============

Make your ATK14 app comply with the crazy EU cookie law.

Installation
------------

    cd path/to/your/project/
    composer require atk14/cookie-consent

    ln -s ../../../vendor/atk14/cookie-consent/src/app/controllers/admin/cookie_consents_controller.php app/controllers/admin/
    ln -s ../../../vendor/atk14/cookie-consent/src/app/forms/admin/cookie_consents app/forms/admin/
    ln -s ../../../vendor/atk14/cookie-consent/src/app/views/admin/cookie_consents app/views/admin/
    ln -s ../../../vendor/atk14/cookie-consent/src/app/controllers/admin/cookie_consent_categories_controller.php app/controllers/admin/
    ln -s ../../../vendor/atk14/cookie-consent/src/app/forms/admin/cookie_consent_categories app/forms/admin/
    ln -s ../../../vendor/atk14/cookie-consent/src/app/views/admin/cookie_consent_categories app/views/admin/
    ln -s ../../../vendor/atk14/cookie-consent/src/app/controllers/admin/cookie_consent_statistics_controller.php app/controllers/admin/
    ln -s ../../../vendor/atk14/cookie-consent/src/app/forms/admin/cookie_consent_statistics app/forms/admin/
    ln -s ../../../vendor/atk14/cookie-consent/src/app/views/admin/cookie_consent_statistics app/views/admin/
    ln -s ../../vendor/atk14/cookie-consent/src/app/controllers/cookie_consents_controller.php app/controllers/
    ln -s ../../vendor/atk14/cookie-consent/src/app/forms/cookie_consents app/forms/
    ln -s ../../vendor/atk14/cookie-consent/src/app/views/cookie_consents app/views/
    ln -s ../../vendor/atk14/cookie-consent/src/app/models/cookie_consent.php app/models/
    ln -s ../../vendor/atk14/cookie-consent/src/app/models/cookie_consent_category.php app/models/
    ln -s ../../../vendor/atk14/cookie-consent/src/app/views/shared/cookie_consent app/views/shared
    ln -s ../../vendor/atk14/cookie-consent/src/app/helpers/function.cookie_consent_datalayer_command.php app/helpers/
    ln -s ../../vendor/atk14/cookie-consent/src/test/models/tc_cookie_consent.php test/models/
    ln -s ../../vendor/atk14/cookie-consent/src/test/fixtures/cookie_consent_categories.yml test/fixtures/
    mkdir -p public/scripts/utils
    ln -s ../../../vendor/atk14/cookie-consent/src/public/scripts/utils/cookie_consent.js public/scripts/utils
    ln -s ../vendor/atk14/cookie-consent/src/local_scripts/export_cookie_consent_statistics local_scripts/
    ln -s ../../vendor/atk14/cookie-consent/src/config/routers/cookie_consents_router.php config/routers/

Symlink or copy migration files into your project and perform the migration script:

    ln -s ../../vendor/atk14/cookie-consent/src/db/migrations/0020_cookie_consents.sql db/migrations
    ln -s ../../vendor/atk14/cookie-consent/src/db/migrations/0021_cookie_consents_data_migration.php db/migrations
    ln -s ../../vendor/atk14/cookie-consent/src/db/migrations/0021_zz01_cookie_consents_data_migration.php db/migrations
    ln -s ../../vendor/atk14/cookie-consent/src/db/migrations/0021_zz02_cookie_consents_data_migration.php db/migrations
    ln -s ../../vendor/atk14/cookie-consent/src/db/migrations/0021_zz03_cookie_consents_datalayer_events.sql db/migrations

    #or
    cp vendor/atk14/cookie-consent/src/db/migrations/0020_cookie_consents.sql db/migrations/
    cp vendor/atk14/cookie-consent/src/db/migrations/0021_cookie_consents_data_migration.php db/migrations/
    cp vendor/atk14/cookie-consent/src/db/migrations/0021_zz01_cookie_consents_data_migration.php db/migrations/
    cp vendor/atk14/cookie-consent/src/db/migrations/0021_zz02_cookie_consents_data_migration.php db/migrations/
    cp vendor/atk14/cookie-consent/src/db/migrations/0021_zz03_cookie_consents_datalayer_events.sql db/migrations/

    ./scripts/migrate

Linking a proper style file either for Bootstrap 5, Bootstrap 4 (scss) or Bootstrap 3 (less).

    # Bootstrap 5
    ln -s ../../vendor/atk14/cookie-consent/src/public/styles/_cookie_consent.bs5.scss public/styles/_cookie_consent.scss

    # Bootstrap 4
    ln -s ../../vendor/atk14/cookie-consent/src/public/styles/_cookie_consent.scss public/styles/

    # or Bootstrap 3
    ln -s ../../vendor/atk14/cookie-consent/src/public/styles/cookie_consent.less public/styles/

Now include the selected style to your application style.

Add shared template into layout (app/layouts/default.tpl). Somewhere close to the end of the element <body>.

    <body>
      ...
      {render partial="shared/cookie_consent/banner"}
    </body>

If you are using Google, place helper {cookie_consent_datalayer_command} into your layout just after your GTM initialization script.
This will create a push command with granted consent groups.

    <head>
      {cookie_consent_datalayer_command}
      ...
    </head>

Add new section into your administration in app/controllers/admin/admin.php.

    ...
    [_("Cookie consent"),    "cookie_consents,cookie_consent_categories,cookie_consent_statistics"],
    ...

Include public/scripts/utils/cookie_consent.js in gulpfile.js into applicationScripts.

    var applicationScripts = [
      // ...
      "public/scripts/utils/cookie_consent.js",
      "public/scripts/application.js"
    ];

Include CookieConsentsRouter with some nice URIs.

    <?php
    // file: config/routers/load.php

    ...

    Atk14Url::AddRouter("CookieConsentsRouter");

    // Keep the DefaultRouter at the end of the list
    Atk14Url::AddRouter("DefaultRouter");

Usage
-----

Checking whether a category of the cookie consent is accepted or not in JavaScript and PHP:

#### Javascript

    if ( window.UTILS.cookieConsent.accepted( "advertising" ) ) {
      // accepted
    }

#### PHP

    if(CookieConsent::Accepted("advertising")){
      // accepted
    }

Google Consent Mode
-------------------

The `{cookie_consent_datalayer_command}` helper outputs the necessary `gtag()` calls for [Google Consent Mode v2](https://developers.google.com/tag-platform/security/guides/consent?hl=en). Place it in `<head>` **before** your GTM snippet.

Category codes map to GTM consent types as follows:

| Category code      | GTM consent type          |
|--------------------|---------------------------|
| `advertising`      | `ad_storage`              |
| `analytics`        | `analytics_storage`       |
| `functional`       | `functionality_storage`   |
| `personalization`  | `personalization_storage` |
| `security`         | `security_storage`        |
| `ad_personalization` | `ad_personalization`    |
| `ad_user_data`     | `ad_user_data`            |

The `necessary` category has no GTM equivalent and is always treated as granted.

Two boolean columns on the `cookie_consents` record control the helper's behavior:

- `send_consent_default_command` — when enabled, outputs `gtag('consent', 'default', ...)` with all categories denied (for visitors who haven't decided yet).
- `send_consent_update_command` — when enabled, outputs `gtag('consent', 'update', ...)` for returning visitors whose consent is already saved.

Both can be toggled in the admin under Cookie consent settings.

Category management
-------------------

Categories are managed in the admin under **Cookie consent → Categories**. Each category has:

- **Code** — identifier used in PHP and JavaScript (e.g. `advertising`, `analytics`).
- **Version** — integer starting at 1. Increment it whenever the category's purpose or scope changes in a way that requires fresh consent from existing users. On the next page load, the banner will reappear for anyone who previously accepted or rejected this category.
- **Cookies regexp** — optional regular expression (e.g. `/^(_ga.*|_gid.*)$/`). When a user rejects a category, all cookies whose names match this pattern are automatically deleted from their browser.
- **Necessary** — if checked, the category is always accepted and cannot be rejected by the user.

Statistics
----------

Consent events are logged to `application.log` as `cookie_consent_saved:` entries. To export them as CSV:

    ./local_scripts/export_cookie_consent_statistics        # last 10 days
    ./local_scripts/export_cookie_consent_statistics 30     # last 30 days

Or download directly from the admin under **Cookie consent → Statistics**.

Cookie format
-------------

The consent is stored in a browser cookie named `consent` as Base64-encoded JSON.

    base64_decode($cookie_value) // → JSON string
    json_decode(..., true)       // → array

The JSON structure uses compact keys to keep the cookie small:

    {
      "c_v": "1.0.11",      // package version (used as cookie schema indicator)
      "c_t": "1732012345",  // timestamp of the last save
      "all_a": "a",         // "a" = all accepted, "r" = all rejected, "" = not decided
      "all_t": "1732012345",
      "h": "www.example.com",
      "cs": {
        "analytics":     { "a": "a", "t": "1732012345", "v": "2" },
        "advertising":   { "a": "r", "t": "1732012345", "v": "3" },
        "functional":    { "a": "a", "t": "1732012345", "v": "1" }
      }
    }

Per-category `a` values: `"a"` = accepted, `"r"` = rejected, `""` = not yet decided. The `v` field is the category version at the time of consent — if the version in the database is later incremented, the user is asked to confirm again.

The cookie is only written when the user explicitly interacts with the banner or dialog. New visitors receive no cookie until they make a choice.

Requirements
------------

You must have the [atk14/drink-markdown](https://packagist.org/packages/atk14/drink-markdown) package installed in your project.

Helper [link_to_page](https://github.com/atk14/Atk14Skelet/blob/master/app/helpers/modifier.link_to_page.php) is used in template `src/app/views/cookie_consents/_edit.tpl`. Create a local copy of the template, if you want to make local changes in it.

License
-------

CookieConsent is free software distributed [under the terms of the MIT license](http://www.opensource.org/licenses/mit-license)

[//]: # ( vim: set ts=2 et: )

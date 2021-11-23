Cookie Consent
==============

Make your ATK14 app comply with the crazy EU cookie law.

Installation
------------

    cd path/to/your/project/
    composer require atk14/cookie-consent

    ln -s ../../../vendor/atk14/cookie-consent/src/app/controllers/admin/cookie_consent_categories_controller.php app/controllers/admin/
    ln -s ../../../vendor/atk14/cookie-consent/src/app/forms/admin/cookie_consent_categories app/forms/admin/
    ln -s ../../../vendor/atk14/cookie-consent/src/app/views/admin/cookie_consent_categories app/views/admin/
    ln -s ../../vendor/atk14/cookie-consent/src/app/controllers/cookie_consents_controller.php app/controllers/
    ln -s ../../vendor/atk14/cookie-consent/src/app/forms/cookie_consents app/forms/
    ln -s ../../vendor/atk14/cookie-consent/src/app/views/cookie_consents app/views/
    ln -s ../../vendor/atk14/cookie-consent/src/app/models/cookie_consent_category.php app/models/
    ln -s ../../../vendor/atk14/cookie-consent/src/app/views/shared/cookie_consent app/views/shared
    ln -s ../../vendor/atk14/cookie-consent/src/test/models/tc_cookie_consent.php test/models/
    ln -s ../../vendor/atk14/cookie-consent/src/test/fixtures/cookie_consent_categories.yml test/fixtures/
    ln -s ../../../vendor/atk14/cookie-consent/src/public/scripts/utils/cookie_consent.js public/scripts/utils

Symlink or copy migration files into your project and perform the migration script:

    ln -s ../../vendor/atk14/cookie-consent/src/db/migrations/0020_cookie_consent_migration.sql db/migrations
    ln -s ../../vendor/atk14/cookie-consent/src/db/migrations/0021_cookie_consent_categories_data_migration.php db/migrations

    #or
    cp vendor/atk14/cookie-consent/src/db/migrations/0020_cookie_consent_migration.sql db/migrations/
    cp vendor/atk14/cookie-consent/src/db/migrations/0021_cookie_consent_categories_data_migration.php db/migrations/

    ./scripts/migrate

Linking a proper style form either for  or Bootstrap 4 (scss) or Bootstrap 3 (less).

    # Bootstrap 4
    ln -s ../../vendor/atk14/cookie-consent/src/public/styles/_cookie_consent.scss public/styles/

    # or Bootstrap 3
    ln -s ../../vendor/atk14/cookie-consent/src/public/styles/cookie_consent.less public/styles/

Now include the selected style to your application style.

Add shared template into layout (app/layouts/default.tpl). Somewhere close to the end of page.

    {render partial="shared/cookie_consent/banner"}

Add new section into your administration in app/controllers/admin/admin.php.

    ...
    array(_("Cookie consent"),    "cookie_consent_categories"),
    ...

Include public/scripts/utils/cookie_consent.js in gulpfile.js into applicationScripts.

    var applicationScripts = [
      // ...
      "public/scripts/utils/cookie_consent.js",
      "public/scripts/application.js"
    ];

Usage
-----

Checking whether a category of the cookie consent is accepted or not in Javascript:

    if ( window.UTILS.cookieConsent.accepted( "advertising" ) ) {
      // accepted
    }

Requirements
------------

You must have the [atk14/drink-markdown](https://packagist.org/packages/atk14/drink-markdown) package installed in your project.

Helper [link_to_page](https://github.com/atk14/Atk14Skelet/blob/master/app/helpers/modifier.link_to_page.php) is used in template `src/app/views/cookie_consents/_edit.tpl`. Create a local copy of the template, if you want to make local changes in it.

License
-------

CookieConsent is free software distributed [under the terms of the MIT license](http://www.opensource.org/licenses/mit-license)

[//]: # ( vim: set ts=2 et: )

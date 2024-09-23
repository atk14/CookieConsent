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

    #or
    cp vendor/atk14/cookie-consent/src/db/migrations/0020_cookie_consents.sql db/migrations/
    cp vendor/atk14/cookie-consent/src/db/migrations/0021_cookie_consents_data_migration.php db/migrations/
    cp vendor/atk14/cookie-consent/src/db/migrations/0021_zz01_cookie_consents_data_migration.php db/migrations/
    cp vendor/atk14/cookie-consent/src/db/migrations/0021_zz02_cookie_consents_data_migration.php db/migrations/

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

Checking whether a category of the cookie consent is accepted or not in Javascrip and PHP:

#### Javascript

    if ( window.UTILS.cookieConsent.accepted( "advertising" ) ) {
      // accepted
    }

#### PHP

    if(CookieConsent::Accepted("advertising")){
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
